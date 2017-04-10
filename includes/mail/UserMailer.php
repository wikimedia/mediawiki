<?php
/**
 * Classes used to send e-mails
 *
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
 * @author <brion@pobox.com>
 * @author <mail@tgries.de>
 * @author Tim Starling
 * @author Luke Welling lwelling@wikimedia.org
 */

/**
 * Collection of static functions for sending mail
 */
class UserMailer {
	private static $mErrorString;

	/**
	 * Send mail using a PEAR mailer
	 *
	 * @param Mail_smtp $mailer
	 * @param string $dest
	 * @param string $headers
	 * @param string $body
	 *
	 * @return Status
	 */
	protected static function sendWithPear( $mailer, $dest, $headers, $body ) {
		$mailResult = $mailer->send( $dest, $headers, $body );

		// Based on the result return an error string,
		if ( PEAR::isError( $mailResult ) ) {
			wfDebug( "PEAR::Mail failed: " . $mailResult->getMessage() . "\n" );
			return Status::newFatal( 'pear-mail-error', $mailResult->getMessage() );
		} else {
			return Status::newGood();
		}
	}

	/**
	 * Creates a single string from an associative array
	 *
	 * @param array $headers Associative Array: keys are header field names,
	 *                 values are ... values.
	 * @param string $endl The end of line character.  Defaults to "\n"
	 *
	 * Note RFC2822 says newlines must be CRLF (\r\n)
	 * but php mail naively "corrects" it and requires \n for the "correction" to work
	 *
	 * @return string
	 */
	static function arrayToHeaderString( $headers, $endl = PHP_EOL ) {
		$strings = [];
		foreach ( $headers as $name => $value ) {
			// Prevent header injection by stripping newlines from value
			$value = self::sanitizeHeaderValue( $value );
			$strings[] = "$name: $value";
		}
		return implode( $endl, $strings );
	}

	/**
	 * Create a value suitable for the MessageId Header
	 *
	 * @return string
	 */
	static function makeMsgId() {
		global $wgSMTP, $wgServer;

		$msgid = uniqid( wfWikiID() . ".", true ); /* true required for cygwin */
		if ( is_array( $wgSMTP ) && isset( $wgSMTP['IDHost'] ) && $wgSMTP['IDHost'] ) {
			$domain = $wgSMTP['IDHost'];
		} else {
			$url = wfParseUrl( $wgServer );
			$domain = $url['host'];
		}
		return "<$msgid@$domain>";
	}

	/**
	 * This function will perform a direct (authenticated) login to
	 * a SMTP Server to use for mail relaying if 'wgSMTP' specifies an
	 * array of parameters. It requires PEAR:Mail to do that.
	 * Otherwise it just uses the standard PHP 'mail' function.
	 *
	 * @param MailAddress|MailAddress[] $to Recipient's email (or an array of them)
	 * @param MailAddress $from Sender's email
	 * @param string $subject Email's subject.
	 * @param string $body Email's text or Array of two strings to be the text and html bodies
	 * @param array $options:
	 * 		'replyTo' MailAddress
	 * 		'contentType' string default 'text/plain; charset=UTF-8'
	 * 		'headers' array Extra headers to set
	 *
	 * @throws MWException
	 * @throws Exception
	 * @return Status
	 */
	public static function send( $to, $from, $subject, $body, $options = [] ) {
		global $wgAllowHTMLEmail;

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

		if ( !$wgAllowHTMLEmail && is_array( $body ) ) {
			// HTML not wanted.  Dump it.
			$body = $body['text'];
		}

		wfDebug( __METHOD__ . ': sending mail to ' . implode( ', ', $to ) . "\n" );

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
			Hooks::run( 'UserMailerSplitTo', [ &$to ] );
			if ( $oldTo != $to ) {
				$splitTo = array_diff( $oldTo, $to );
				$to = array_diff( $oldTo, $splitTo ); // ignore new addresses added in the hook
				// first send to non-split address list, then to split addresses one by one
				$status = Status::newGood();
				if ( $to ) {
					$status->merge( UserMailer::sendInternal(
						$to, $from, $subject, $body, $options ) );
				}
				foreach ( $splitTo as $newTo ) {
					$status->merge( UserMailer::sendInternal(
						[ $newTo ], $from, $subject, $body, $options ) );
				}
				return $status;
			}
		}

		return UserMailer::sendInternal( $to, $from, $subject, $body, $options );
	}

	/**
	 * Helper function fo UserMailer::send() which does the actual sending. It expects a $to
	 * list which the UserMailerSplitTo hook would not split further.
	 * @param MailAddress[] $to Array of recipients' email addresses
	 * @param MailAddress $from Sender's email
	 * @param string $subject Email's subject.
	 * @param string $body Email's text or Array of two strings to be the text and html bodies
	 * @param array $options:
	 * 		'replyTo' MailAddress
	 * 		'contentType' string default 'text/plain; charset=UTF-8'
	 * 		'headers' array Extra headers to set
	 *
	 * @throws MWException
	 * @throws Exception
	 * @return Status
	 */
	protected static function sendInternal(
		array $to,
		MailAddress $from,
		$subject,
		$body,
		$options = []
	) {
		global $wgSMTP, $wgEnotifMaxRecips, $wgAdditionalMailParams;
		$mime = null;

		$replyto = isset( $options['replyTo'] ) ? $options['replyTo'] : null;
		$contentType = isset( $options['contentType'] ) ?
			$options['contentType'] : 'text/plain; charset=UTF-8';
		$headers = isset( $options['headers'] ) ? $options['headers'] : [];

		// Allow transformation of content, such as encrypting/signing
		$error = false;
		if ( !Hooks::run( 'UserMailerTransformContent', [ $to, $from, &$body, &$error ] ) ) {
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
		$extraParams = $wgAdditionalMailParams;

		// Hook to generate custom VERP address for 'Return-Path'
		Hooks::run( 'UserMailerChangeReturnPath', [ $to, &$returnPath ] );
		// Add the envelope sender address using the -f command line option when PHP mail() is used.
		// Will default to the $from->address when the UserMailerChangeReturnPath hook fails and the
		// generated VERP address when the hook runs effectively.
		$extraParams .= ' -f ' . $returnPath;

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
		// the bug described at http://trac.wordpress.org/ticket/2603
		$endl = PHP_EOL;

		if ( is_array( $body ) ) {
			// we are sending a multipart message
			wfDebug( "Assembling multipart mime email\n" );
			if ( !stream_resolve_include_path( 'Mail/mime.php' ) ) {
				wfDebug( "PEAR Mail_Mime package is not installed. Falling back to text email.\n" );
				// remove the html body for text email fall back
				$body = $body['text'];
			} else {
				// Check if pear/mail_mime is already loaded (via composer)
				if ( !class_exists( 'Mail_mime' ) ) {
					require_once 'Mail/mime.php';
				}
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
			}
		}
		if ( $mime === null ) {
			// sending text only, either deliberately or as a fallback
			if ( wfIsWindows() ) {
				$body = str_replace( "\n", "\r\n", $body );
			}
			$headers['MIME-Version'] = '1.0';
			$headers['Content-type'] = $contentType;
			$headers['Content-transfer-encoding'] = '8bit';
		}

		// allow transformation of MIME-encoded message
		if ( !Hooks::run( 'UserMailerTransformMessage',
			[ $to, $from, &$subject, &$headers, &$body, &$error ] )
		) {
			if ( $error ) {
				return Status::newFatal( 'php-mail-error', $error );
			} else {
				return Status::newFatal( 'php-mail-error-unknown' );
			}
		}

		$ret = Hooks::run( 'AlternateUserMailer', [ $headers, $to, $from, $subject, $body ] );
		if ( $ret === false ) {
			// the hook implementation will return false to skip regular mail sending
			return Status::newGood();
		} elseif ( $ret !== true ) {
			// the hook implementation will return a string to pass an error message
			return Status::newFatal( 'php-mail-error', $ret );
		}

		if ( is_array( $wgSMTP ) ) {
			// Check if pear/mail is already loaded (via composer)
			if ( !class_exists( 'Mail' ) ) {
				// PEAR MAILER
				if ( !stream_resolve_include_path( 'Mail.php' ) ) {
					throw new MWException( 'PEAR mail package is not installed' );
				}
				require_once 'Mail.php';
			}

			MediaWiki\suppressWarnings();

			// Create the mail object using the Mail::factory method
			$mail_object =& Mail::factory( 'smtp', $wgSMTP );
			if ( PEAR::isError( $mail_object ) ) {
				wfDebug( "PEAR::Mail factory failed: " . $mail_object->getMessage() . "\n" );
				MediaWiki\restoreWarnings();
				return Status::newFatal( 'pear-mail-error', $mail_object->getMessage() );
			}

			wfDebug( "Sending mail via PEAR::Mail\n" );

			$headers['Subject'] = self::quotedPrintable( $subject );

			// When sending only to one recipient, shows it its email using To:
			if ( count( $to ) == 1 ) {
				$headers['To'] = $to[0]->toString();
			}

			// Split jobs since SMTP servers tends to limit the maximum
			// number of possible recipients.
			$chunks = array_chunk( $to, $wgEnotifMaxRecips );
			foreach ( $chunks as $chunk ) {
				$status = self::sendWithPear( $mail_object, $chunk, $headers, $body );
				// FIXME : some chunks might be sent while others are not!
				if ( !$status->isOK() ) {
					MediaWiki\restoreWarnings();
					return $status;
				}
			}
			MediaWiki\restoreWarnings();
			return Status::newGood();
		} else {
			// PHP mail()
			if ( count( $to ) > 1 ) {
				$headers['To'] = 'undisclosed-recipients:;';
			}
			$headers = self::arrayToHeaderString( $headers, $endl );

			wfDebug( "Sending mail via internal mail() function\n" );

			self::$mErrorString = '';
			$html_errors = ini_get( 'html_errors' );
			ini_set( 'html_errors', '0' );
			set_error_handler( 'UserMailer::errorHandler' );

			try {
				foreach ( $to as $recip ) {
					$sent = mail(
						$recip,
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
				wfDebug( "Error sending mail: " . self::$mErrorString . "\n" );
				return Status::newFatal( 'php-mail-error', self::$mErrorString );
			} elseif ( !$sent ) {
				// mail function only tells if there's an error
				wfDebug( "Unknown error sending mail\n" );
				return Status::newFatal( 'php-mail-error-unknown' );
			} else {
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
	static function errorHandler( $code, $string ) {
		self::$mErrorString = preg_replace( '/^mail\(\)(\s*\[.*?\])?: /', '', $string );
	}

	/**
	 * Strips bad characters from a header value to prevent PHP mail header injection attacks
	 * @param string $val String to be santizied
	 * @return string
	 */
	public static function sanitizeHeaderValue( $val ) {
		return strtr( $val, [ "\r" => '', "\n" => '' ] );
	}

	/**
	 * Converts a string into a valid RFC 822 "phrase", such as is used for the sender name
	 * @param string $phrase
	 * @return string
	 */
	public static function rfc822Phrase( $phrase ) {
		// Remove line breaks
		$phrase = self::sanitizeHeaderValue( $phrase );
		// Remove quotes
		$phrase = str_replace( '"', '', $phrase );
		return '"' . $phrase . '"';
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
		if ( empty( $charset ) ) {
			$charset = 'UTF-8';
		}
		$charset = strtoupper( $charset );
		$charset = str_replace( 'ISO-8859', 'ISO8859', $charset ); // ?

		$illegal = '\x00-\x08\x0b\x0c\x0e-\x1f\x7f-\xff=';
		$replace = $illegal . '\t ?_';
		if ( !preg_match( "/[$illegal]/", $string ) ) {
			return $string;
		}
		$out = "=?$charset?Q?";
		$out .= preg_replace_callback( "/([$replace])/",
			function ( $matches ) {
				return sprintf( "=%02X", ord( $matches[1] ) );
			},
			$string
		);
		$out .= '?=';
		return $out;
	}
}
