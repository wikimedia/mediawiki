<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @author Brooke Vibber
 * @author <mail@tgries.de>
 * @author Tim Starling
 * @author Luke Welling lwelling@wikimedia.org
 */

use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Utils\MWTimestamp;
use MediaWiki\WikiMap\WikiMap;

/**
 * @defgroup Mail Mail
 */

/**
 * Collection of static functions for sending mail
 *
 * @since 1.12
 * @ingroup Mail
 */
class UserMailer {
	/** @var string */
	private static $mErrorString;

	/**
	 * Send mail using a PEAR mailer
	 *
	 * @param Mail_smtp $mailer
	 * @param string[]|string $dest
	 * @param array $headers
	 * @param string $body
	 *
	 * @return Status
	 */
	protected static function sendWithPear( $mailer, $dest, $headers, $body ) {
		$mailResult = $mailer->send( $dest, $headers, $body );

		// Based on the result return an error string,
		if ( PEAR::isError( $mailResult ) ) {
			wfDebug( "PEAR::Mail failed: " . $mailResult->getMessage() );
			return Status::newFatal( 'pear-mail-error', $mailResult->getMessage() );
		} else {
			return Status::newGood();
		}
	}

	/**
	 * Create a value suitable for the MessageId Header
	 *
	 * @return string
	 */
	private static function makeMsgId() {
		$services = MediaWikiServices::getInstance();

		$smtp = $services->getMainConfig()->get( MainConfigNames::SMTP );
		$server = $services->getMainConfig()->get( MainConfigNames::Server );
		$domainId = WikiMap::getCurrentWikiDbDomain()->getId();
		$msgid = uniqid( $domainId . ".", true /** for cygwin */ );

		if ( is_array( $smtp ) && isset( $smtp['IDHost'] ) && $smtp['IDHost'] ) {
			$domain = $smtp['IDHost'];
		} else {
			$domain = parse_url( $server, PHP_URL_HOST ) ?? '';
		}
		return "<$msgid@$domain>";
	}

	/**
	 * Send a raw email via SMTP (if $wgSMTP is set) or otherwise via PHP mail().
	 *
	 * This function perform a direct (authenticated) login to a SMTP server,
	 * to use for mail relaying, if 'wgSMTP' specifies an array of parameters.
	 * This uses the pear/mail package.
	 *
	 * Otherwise it uses the standard PHP 'mail' function, which in turn relies
	 * on the server's sendmail configuration.
	 *
	 * @since 1.12
	 * @param MailAddress|MailAddress[] $to Recipient's email (or an array of them)
	 * @param MailAddress $from Sender's email
	 * @param string $subject Email's subject.
	 * @param string|string[] $body Email's text or Array of two strings to be the text and html bodies
	 * @param array $options Keys:
	 *     'replyTo' MailAddress
	 *     'contentType' string default 'text/plain; charset=UTF-8'
	 *     'headers' array Extra headers to set
	 * @return Status
	 */
	public static function send( $to, $from, $subject, $body, $options = [] ) {
		$services = MediaWikiServices::getInstance();
		$allowHTMLEmail = $services->getMainConfig()->get(
			MainConfigNames::AllowHTMLEmail );

		if ( !isset( $options['contentType'] ) ) {
			$options['contentType'] = 'text/plain; charset=UTF-8';
		}

		if ( !is_array( $to ) ) {
			$to = [ $to ];
		}

		// mail body must have some content
		$minBodyLen = 10;
		// arbitrary but longer than Array or Object to detect casting error

		// body must either be a string or an array with text and body
		if (
			!(
				!is_array( $body ) &&
				strlen( $body ) >= $minBodyLen
			)
			&&
			!(
				is_array( $body ) &&
				isset( $body['text'] ) &&
				isset( $body['html'] ) &&
				strlen( $body['text'] ) >= $minBodyLen &&
				strlen( $body['html'] ) >= $minBodyLen
			)
		) {
			// if it is neither we have a problem
			return Status::newFatal( 'user-mail-no-body' );
		}

		if ( !$allowHTMLEmail && is_array( $body ) ) {
			// HTML not wanted.  Dump it.
			$body = $body['text'];
		}

		wfDebug( __METHOD__ . ': sending mail to ' . implode( ', ', $to ) );

		// Make sure we have at least one address
		$has_address = false;
		foreach ( $to as $u ) {
			if ( $u->address ) {
				$has_address = true;
				break;
			}
		}
		if ( !$has_address ) {
			return Status::newFatal( 'user-mail-no-addy' );
		}

		// give a chance to UserMailerTransformContents subscribers who need to deal with each
		// target differently to split up the address list
		if ( count( $to ) > 1 ) {
			$oldTo = $to;
			( new HookRunner( $services->getHookContainer() ) )->onUserMailerSplitTo( $to );
			if ( $oldTo != $to ) {
				$splitTo = array_diff( $oldTo, $to );
				$to = array_diff( $oldTo, $splitTo ); // ignore new addresses added in the hook
				// first send to non-split address list, then to split addresses one by one
				$status = Status::newGood();
				if ( $to ) {
					$status->merge( self::sendInternal(
						$to, $from, $subject, $body, $options ) );
				}
				foreach ( $splitTo as $newTo ) {
					$status->merge( self::sendInternal(
						[ $newTo ], $from, $subject, $body, $options ) );
				}
				return $status;
			}
		}

		return self::sendInternal( $to, $from, $subject, $body, $options );
	}

	/**
	 * Helper function fo UserMailer::send() which does the actual sending. It expects a $to
	 * list which the UserMailerSplitTo hook would not split further.
	 * @param MailAddress[] $to Array of recipients' email addresses
	 * @param MailAddress $from Sender's email
	 * @param string $subject Email's subject.
	 * @param string|string[] $body Email's text or Array of two strings to be the text and html bodies
	 * @param array $options Keys:
	 *     'replyTo' MailAddress
	 *     'contentType' string default 'text/plain; charset=UTF-8'
	 *     'headers' array Extra headers to set
	 * @return Status
	 */
	protected static function sendInternal(
		array $to,
		MailAddress $from,
		$subject,
		$body,
		$options = []
	) {
		$services = MediaWikiServices::getInstance();
		$mainConfig = $services->getMainConfig();
		$smtp = $mainConfig->get( MainConfigNames::SMTP );
		$enotifMaxRecips = $mainConfig->get( MainConfigNames::EnotifMaxRecips );
		$additionalMailParams = $mainConfig->get( MainConfigNames::AdditionalMailParams );

		$replyto = $options['replyTo'] ?? null;
		$contentType = $options['contentType'] ?? 'text/plain; charset=UTF-8';
		$headers = $options['headers'] ?? [];

		$hookRunner = new HookRunner( $services->getHookContainer() );
		// Allow transformation of content, such as encrypting/signing
		$error = false;
		// @phan-suppress-next-line PhanTypeMismatchArgument Type mismatch on pass-by-ref args
		if ( !$hookRunner->onUserMailerTransformContent( $to, $from, $body, $error ) ) {
			if ( $error ) {
				return Status::newFatal( 'php-mail-error', $error );
			} else {
				return Status::newFatal( 'php-mail-error-unknown' );
			}
		}

		/**
		 * Forge email headers
		 * -------------------
		 *
		 * WARNING
		 *
		 * DO NOT add To: or Subject: headers at this step. They need to be
		 * handled differently depending upon the mailer we are going to use.
		 *
		 * To:
		 *  PHP mail() first argument is the mail receiver. The argument is
		 *  used as a recipient destination and as a To header.
		 *
		 *  PEAR mailer has a recipient argument which is only used to
		 *  send the mail. If no To header is given, PEAR will set it to
		 *  to 'undisclosed-recipients:'.
		 *
		 *  NOTE: To: is for presentation, the actual recipient is specified
		 *  by the mailer using the Rcpt-To: header.
		 *
		 * Subject:
		 *  PHP mail() second argument to pass the subject, passing a Subject
		 *  as an additional header will result in a duplicate header.
		 *
		 *  PEAR mailer should be passed a Subject header.
		 *
		 * -- hashar 20120218
		 */

		$headers['From'] = $from->toString();
		$returnPath = $from->address;
		$extraParams = $additionalMailParams;

		// Hook to generate custom VERP address for 'Return-Path'
		$hookRunner->onUserMailerChangeReturnPath( $to, $returnPath );
		// Add the envelope sender address using the -f command line option when PHP mail() is used.
		// Will default to the $from->address when the UserMailerChangeReturnPath hook fails and the
		// generated VERP address when the hook runs effectively.

		// PHP runs this through escapeshellcmd(). However that's not sufficient
		// escaping (e.g. due to spaces). MediaWiki's email sanitizer should generally
		// be good enough, but just in case, put in double quotes, and remove any
		// double quotes present (" is not allowed in emails, so should have no
		// effect, although this might cause apostrophes to be double escaped)
		$returnPathCLI = '"' . str_replace( '"', '', $returnPath ) . '"';
		$extraParams .= ' -f ' . $returnPathCLI;

		$headers['Return-Path'] = $returnPath;

		if ( $replyto ) {
			$headers['Reply-To'] = $replyto->toString();
		}

		$headers['Date'] = MWTimestamp::getLocalInstance()->format( 'r' );
		$headers['Message-ID'] = self::makeMsgId();
		$headers['X-Mailer'] = 'MediaWiki mailer';
		$headers['List-Unsubscribe'] = '<' . SpecialPage::getTitleFor( 'Preferences' )
			->getFullURL( '', false, PROTO_CANONICAL ) . '>';

		// Line endings need to be different on Unix and Windows due to
		// the bug described at https://core.trac.wordpress.org/ticket/2603
		$endl = PHP_EOL;

		if ( is_array( $body ) ) {
			// we are sending a multipart message
			wfDebug( "Assembling multipart mime email" );
			if ( wfIsWindows() ) {
				$body['text'] = str_replace( "\n", "\r\n", $body['text'] );
				$body['html'] = str_replace( "\n", "\r\n", $body['html'] );
			}
			$mime = new Mail_mime( [
				'eol' => $endl,
				'text_charset' => 'UTF-8',
				'html_charset' => 'UTF-8'
			] );
			$mime->setTXTBody( $body['text'] );
			$mime->setHTMLBody( $body['html'] );
			$body = $mime->get(); // must call get() before headers()
			$headers = $mime->headers( $headers );
		} else {
			// sending text only
			if ( wfIsWindows() ) {
				$body = str_replace( "\n", "\r\n", $body );
			}
			$headers['MIME-Version'] = '1.0';
			$headers['Content-type'] = $contentType;
			$headers['Content-transfer-encoding'] = '8bit';
		}

		// allow transformation of MIME-encoded message
		if ( !$hookRunner->onUserMailerTransformMessage(
			$to, $from, $subject, $headers, $body, $error )
		) {
			if ( $error ) {
				return Status::newFatal( 'php-mail-error', $error );
			} else {
				return Status::newFatal( 'php-mail-error-unknown' );
			}
		}

		$ret = $hookRunner->onAlternateUserMailer( $headers, $to, $from, $subject, $body );
		if ( $ret === false ) {
			// the hook implementation will return false to skip regular mail sending
			LoggerFactory::getInstance( 'usermailer' )->info(
				"Email to {to} from {from} with subject {subject} handled by AlternateUserMailer",
				[
					'to' => $to[0]->toString(),
					'allto' => implode( ', ', array_map( 'strval', $to ) ),
					'from' => $from->toString(),
					'subject' => $subject,
				]
			);
			return Status::newGood();
		} elseif ( $ret !== true ) {
			// the hook implementation will return a string to pass an error message
			return Status::newFatal( 'php-mail-error', $ret );
		}

		if ( is_array( $smtp ) ) {
			$recips = array_map( 'strval', $to );

			// Create the mail object using the Mail::factory method
			$mail_object = Mail::factory( 'smtp', $smtp );
			if ( PEAR::isError( $mail_object ) ) {
				wfDebug( "PEAR::Mail factory failed: " . $mail_object->getMessage() );
				return Status::newFatal( 'pear-mail-error', $mail_object->getMessage() );
			}
			'@phan-var Mail_smtp $mail_object';

			wfDebug( "Sending mail via PEAR::Mail" );

			$headers['Subject'] = self::quotedPrintable( $subject );

			// When sending only to one recipient, shows it its email using To:
			if ( count( $recips ) == 1 ) {
				$headers['To'] = $recips[0];
			}

			// Split jobs since SMTP servers tends to limit the maximum
			// number of possible recipients.
			$chunks = array_chunk( $recips, $enotifMaxRecips );
			foreach ( $chunks as $chunk ) {
				$status = self::sendWithPear( $mail_object, $chunk, $headers, $body );
				// FIXME : some chunks might be sent while others are not!
				if ( !$status->isOK() ) {
					return $status;
				}
			}
			return Status::newGood();
		} else {
			// PHP mail()
			if ( count( $to ) > 1 ) {
				$headers['To'] = 'undisclosed-recipients:;';
			}

			wfDebug( "Sending mail via internal mail() function" );

			self::$mErrorString = '';
			$html_errors = ini_get( 'html_errors' );
			ini_set( 'html_errors', '0' );
			set_error_handler( [ self::class, 'errorHandler' ] );

			try {
				foreach ( $to as $recip ) {
					$sent = mail(
						$recip->toString(),
						self::quotedPrintable( $subject ),
						$body,
						$headers,
						$extraParams
					);
				}
			} catch ( Exception $e ) {
				restore_error_handler();
				throw $e;
			}

			restore_error_handler();
			ini_set( 'html_errors', $html_errors );

			if ( self::$mErrorString ) {
				wfDebug( "Error sending mail: " . self::$mErrorString );
				return Status::newFatal( 'php-mail-error', self::$mErrorString );
			} elseif ( !$sent ) {
				// @phan-suppress-previous-line PhanPossiblyUndeclaredVariable sent set on success
				// mail function only tells if there's an error
				wfDebug( "Unknown error sending mail" );
				return Status::newFatal( 'php-mail-error-unknown' );
			} else {
				LoggerFactory::getInstance( 'usermailer' )->info(
					"Email sent to {to} from {from} with subject {subject}",
					[
						'to' => $to[0]->toString(),
						'allto' => implode( ', ', array_map( 'strval', $to ) ),
						'from' => $from->toString(),
						'subject' => $subject,
					]
				);
				return Status::newGood();
			}
		}
	}

	/**
	 * Set the mail error message in self::$mErrorString
	 *
	 * @param int $code Error number
	 * @param string $string Error message
	 */
	private static function errorHandler( $code, $string ) {
		if ( self::$mErrorString !== '' ) {
			self::$mErrorString .= "\n";
		}
		self::$mErrorString .= preg_replace( '/^mail\(\)(\s*\[.*?\])?: /', '', $string );
	}

	/**
	 * Strips bad characters from a header value to prevent PHP mail header injection attacks
	 * @param string $val String to be sanitized
	 * @return string
	 * @deprecated in 1.44. No replacement is provided as this
	 * 	function is unused per codesearch.
	 */
	public static function sanitizeHeaderValue( $val ) {
		wfDeprecated( __METHOD__, '1.44' );
		return strtr( $val, [ "\r" => '', "\n" => '' ] );
	}

	/**
	 * Converts a string into quoted-printable format
	 * @since 1.17
	 *
	 * From PHP5.3 there is a built in function quoted_printable_encode()
	 * This method does not duplicate that.
	 * This method is doing Q encoding inside encoded-words as defined by RFC 2047
	 * This is for email headers.
	 * The built in quoted_printable_encode() is for email bodies
	 * @param string $string
	 * @param string $charset
	 * @return string
	 */
	public static function quotedPrintable( $string, $charset = '' ) {
		// Probably incomplete; see RFC 2045
		if ( !$charset ) {
			$charset = 'UTF-8';
		}
		$charset = strtoupper( $charset );
		$charset = str_replace( 'ISO-8859', 'ISO8859', $charset ); // ?

		$illegal = '\x00-\x08\x0b\x0c\x0e-\x1f\x7f-\xff=';
		if ( !preg_match( "/[$illegal]/", $string ) ) {
			return $string;
		}

		// T344912: Add period '.' char
		$replace = $illegal . '.\t ?_';

		$out = "=?$charset?Q?";
		$out .= preg_replace_callback( "/[$replace]/",
			static fn ( $m ) => sprintf( "=%02X", ord( $m[0] ) ),
			$string
		);
		$out .= '?=';
		return $out;
	}
}
