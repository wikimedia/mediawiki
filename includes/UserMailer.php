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
 * Stores a single person's name and email address.
 * These are passed in via the constructor, and will be returned in SMTP
 * header format when requested.
 */
class MailAddress {
	/**
	 * @param string|User $address string with an email address, or a User object
	 * @param string $name human-readable name if a string address is given
	 * @param string $realName human-readable real name if a string address is given
	 */
	function __construct( $address, $name = null, $realName = null ) {
		if ( is_object( $address ) && $address instanceof User ) {
			$this->address = $address->getEmail();
			$this->name = $address->getName();
			$this->realName = $address->getRealName();
		} else {
			$this->address = strval( $address );
			$this->name = strval( $name );
			$this->realName = strval( $realName );
		}
	}

	/**
	 * Return formatted and quoted address to insert into SMTP headers
	 * @return string
	 */
	function toString() {
		# PHP's mail() implementation under Windows is somewhat shite, and
		# can't handle "Joe Bloggs <joe@bloggs.com>" format email addresses,
		# so don't bother generating them
		if ( $this->address ) {
			if ( $this->name != '' && !wfIsWindows() ) {
				global $wgEnotifUseRealName;
				$name = ( $wgEnotifUseRealName && $this->realName ) ? $this->realName : $this->name;
				$quoted = UserMailer::quotedPrintable( $name );
				if ( strpos( $quoted, '.' ) !== false || strpos( $quoted, ',' ) !== false ) {
					$quoted = '"' . $quoted . '"';
				}
				return "$quoted <{$this->address}>";
			} else {
				return $this->address;
			}
		} else {
			return "";
		}
	}

	function __toString() {
		return $this->toString();
	}
}

/**
 * Collection of static functions for sending mail
 */
class UserMailer {
	static $mErrorString;

	/**
	 * Send mail using a PEAR mailer
	 *
	 * @param $mailer
	 * @param $dest
	 * @param $headers
	 * @param $body
	 *
	 * @return Status
	 */
	protected static function sendWithPear( $mailer, $dest, $headers, $body ) {
		$mailResult = $mailer->send( $dest, $headers, $body );

		# Based on the result return an error string,
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
	 * @return String
	 */
	static function arrayToHeaderString( $headers, $endl = "\n" ) {
		$strings = array();
		foreach ( $headers as $name => $value ) {
			$strings[] = "$name: $value";
		}
		return implode( $endl, $strings );
	}

	/**
	 * Create a value suitable for the MessageId Header
	 *
	 * @return String
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
	 * @param $to MailAddress: recipient's email (or an array of them)
	 * @param $from MailAddress: sender's email
	 * @param string $subject email's subject.
	 * @param string $body email's text or Array of two strings to be the text and html bodies
	 * @param $replyto MailAddress: optional reply-to email (default: null).
	 * @param string $contentType optional custom Content-Type (default: text/plain; charset=UTF-8)
	 * @throws MWException
	 * @return Status object
	 */
	public static function send( $to, $from, $subject, $body, $replyto = null, $contentType = 'text/plain; charset=UTF-8' ) {
		global $wgSMTP, $wgEnotifMaxRecips, $wgAdditionalMailParams, $wgAllowHTMLEmail;
		$mime = null;
		if ( !is_array( $to ) ) {
			$to = array( $to );
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

		# Make sure we have at least one address
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

		# Forge email headers
		# -------------------
		#
		# WARNING
		#
		# DO NOT add To: or Subject: headers at this step. They need to be
		# handled differently depending upon the mailer we are going to use.
		#
		# To:
		#  PHP mail() first argument is the mail receiver. The argument is
		#  used as a recipient destination and as a To header.
		#
		#  PEAR mailer has a recipient argument which is only used to
		#  send the mail. If no To header is given, PEAR will set it to
		#  to 'undisclosed-recipients:'.
		#
		#  NOTE: To: is for presentation, the actual recipient is specified
		#  by the mailer using the Rcpt-To: header.
		#
		# Subject:
		#  PHP mail() second argument to pass the subject, passing a Subject
		#  as an additional header will result in a duplicate header.
		#
		#  PEAR mailer should be passed a Subject header.
		#
		# -- hashar 20120218

		$headers['From'] = $from->toString();
		$headers['Return-Path'] = $from->address;

		if ( $replyto ) {
			$headers['Reply-To'] = $replyto->toString();
		}

		$headers['Date'] = MWTimestamp::getLocalInstance()->format( 'r' );
		$headers['Message-ID'] = self::makeMsgId();
		$headers['X-Mailer'] = 'MediaWiki mailer';

		# Line endings need to be different on Unix and Windows due to
		# the bug described at http://trac.wordpress.org/ticket/2603
		if ( wfIsWindows() ) {
			$endl = "\r\n";
		} else {
			$endl = "\n";
		}

		if ( is_array( $body ) ) {
			// we are sending a multipart message
			wfDebug( "Assembling multipart mime email\n" );
			if ( !stream_resolve_include_path( 'Mail/mime.php' ) ) {
				wfDebug( "PEAR Mail_Mime package is not installed. Falling back to text email.\n" );
				// remove the html body for text email fall back
				$body = $body['text'];
			}
			else {
				require_once 'Mail/mime.php';
				if ( wfIsWindows() ) {
					$body['text'] = str_replace( "\n", "\r\n", $body['text'] );
					$body['html'] = str_replace( "\n", "\r\n", $body['html'] );
				}
				$mime = new Mail_mime( array( 'eol' => $endl, 'text_charset' => 'UTF-8', 'html_charset' => 'UTF-8' ) );
				$mime->setTXTBody( $body['text'] );
				$mime->setHTMLBody( $body['html'] );
				$body = $mime->get(); // must call get() before headers()
				$headers = $mime->headers( $headers );
			}
		}
		if ( !isset( $mime ) ) {
			// sending text only, either deliberately or as a fallback
			if ( wfIsWindows() ) {
				$body = str_replace( "\n", "\r\n", $body );
			}
			$headers['MIME-Version'] = '1.0';
			$headers['Content-type'] = ( is_null( $contentType ) ?
				'text/plain; charset=UTF-8' : $contentType );
			$headers['Content-transfer-encoding'] = '8bit';
		}

		$ret = wfRunHooks( 'AlternateUserMailer', array( $headers, $to, $from, $subject, $body ) );
		if ( $ret === false ) {
			// the hook implementation will return false to skip regular mail sending
			return Status::newGood();
		} elseif ( $ret !== true ) {
			// the hook implementation will return a string to pass an error message
			return Status::newFatal( 'php-mail-error', $ret );
		}

		if ( is_array( $wgSMTP ) ) {
			#
			# PEAR MAILER
			#

			if ( !stream_resolve_include_path( 'Mail.php' ) ) {
				throw new MWException( 'PEAR mail package is not installed' );
			}
			require_once 'Mail.php';

			wfSuppressWarnings();

			// Create the mail object using the Mail::factory method
			$mail_object =& Mail::factory( 'smtp', $wgSMTP );
			if ( PEAR::isError( $mail_object ) ) {
				wfDebug( "PEAR::Mail factory failed: " . $mail_object->getMessage() . "\n" );
				wfRestoreWarnings();
				return Status::newFatal( 'pear-mail-error', $mail_object->getMessage() );
			}

			wfDebug( "Sending mail via PEAR::Mail\n" );

			$headers['Subject'] = self::quotedPrintable( $subject );

			# When sending only to one recipient, shows it its email using To:
			if ( count( $to ) == 1 ) {
				$headers['To'] = $to[0]->toString();
			}

			# Split jobs since SMTP servers tends to limit the maximum
			# number of possible recipients.
			$chunks = array_chunk( $to, $wgEnotifMaxRecips );
			foreach ( $chunks as $chunk ) {
				$status = self::sendWithPear( $mail_object, $chunk, $headers, $body );
				# FIXME : some chunks might be sent while others are not!
				if ( !$status->isOK() ) {
					wfRestoreWarnings();
					return $status;
				}
			}
			wfRestoreWarnings();
			return Status::newGood();
		} else {
			#
			# PHP mail()
			#
			if ( count( $to ) > 1 ) {
				$headers['To'] = 'undisclosed-recipients:;';
			}
			$headers = self::arrayToHeaderString( $headers, $endl );

			wfDebug( "Sending mail via internal mail() function\n" );

			self::$mErrorString = '';
			$html_errors = ini_get( 'html_errors' );
			ini_set( 'html_errors', '0' );
			set_error_handler( 'UserMailer::errorHandler' );

			$safeMode = wfIniGetBool( 'safe_mode' );

			foreach ( $to as $recip ) {
				if ( $safeMode ) {
					$sent = mail( $recip, self::quotedPrintable( $subject ), $body, $headers );
				} else {
					$sent = mail( $recip, self::quotedPrintable( $subject ), $body, $headers, $wgAdditionalMailParams );
				}
			}

			restore_error_handler();
			ini_set( 'html_errors', $html_errors );

			if ( self::$mErrorString ) {
				wfDebug( "Error sending mail: " . self::$mErrorString . "\n" );
				return Status::newFatal( 'php-mail-error', self::$mErrorString );
			} elseif ( ! $sent ) {
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
	 * @param $code Integer: error number
	 * @param string $string error message
	 */
	static function errorHandler( $code, $string ) {
		self::$mErrorString = preg_replace( '/^mail\(\)(\s*\[.*?\])?: /', '', $string );
	}

	/**
	 * Converts a string into a valid RFC 822 "phrase", such as is used for the sender name
	 * @param $phrase string
	 * @return string
	 */
	public static function rfc822Phrase( $phrase ) {
		$phrase = strtr( $phrase, array( "\r" => '', "\n" => '', '"' => '' ) );
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
	 * @return string
	 */
	public static function quotedPrintable( $string, $charset = '' ) {
		# Probably incomplete; see RFC 2045
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
			array( __CLASS__, 'quotedPrintableCallback' ), $string );
		$out .= '?=';
		return $out;
	}

	protected static function quotedPrintableCallback( $matches ) {
		return sprintf( "=%02X", ord( $matches[1] ) );
	}
}

/**
 * This module processes the email notifications when the current page is
 * changed. It looks up the table watchlist to find out which users are watching
 * that page.
 *
 * The current implementation sends independent emails to each watching user for
 * the following reason:
 *
 * - Each watching user will be notified about the page edit time expressed in
 * his/her local time (UTC is shown additionally). To achieve this, we need to
 * find the individual timeoffset of each watching user from the preferences..
 *
 * Suggested improvement to slack down the number of sent emails: We could think
 * of sending out bulk mails (bcc:user1,user2...) for all these users having the
 * same timeoffset in their preferences.
 *
 * Visit the documentation pages under http://meta.wikipedia.com/Enotif
 *
 *
 */
class EmailNotification {
	protected $subject, $body, $replyto, $from;
	protected $timestamp, $summary, $minorEdit, $oldid, $composed_common, $pageStatus;
	protected $mailTargets = array();

	/**
	 * @var Title
	 */
	protected $title;

	/**
	 * @var User
	 */
	protected $editor;

	/**
	 * Send emails corresponding to the user $editor editing the page $title.
	 * Also updates wl_notificationtimestamp.
	 *
	 * May be deferred via the job queue.
	 *
	 * @param $editor User object
	 * @param $title Title object
	 * @param $timestamp
	 * @param $summary
	 * @param $minorEdit
	 * @param $oldid (default: false)
	 * @param $pageStatus (default: 'changed')
	 */
	public function notifyOnPageChange( $editor, $title, $timestamp, $summary, $minorEdit, $oldid = false, $pageStatus = 'changed' ) {
		global $wgEnotifUseJobQ, $wgEnotifWatchlist, $wgShowUpdatedMarker, $wgEnotifMinorEdits,
			$wgUsersNotifiedOnAllChanges, $wgEnotifUserTalk;

		if ( $title->getNamespace() < 0 ) {
			return;
		}

		// Build a list of users to notify
		$watchers = array();
		if ( $wgEnotifWatchlist || $wgShowUpdatedMarker ) {
			$dbw = wfGetDB( DB_MASTER );
			$res = $dbw->select( array( 'watchlist' ),
				array( 'wl_user' ),
				array(
					'wl_user != ' . intval( $editor->getID() ),
					'wl_namespace' => $title->getNamespace(),
					'wl_title' => $title->getDBkey(),
					'wl_notificationtimestamp IS NULL',
				), __METHOD__
			);
			foreach ( $res as $row ) {
				$watchers[] = intval( $row->wl_user );
			}
			if ( $watchers ) {
				// Update wl_notificationtimestamp for all watching users except the editor
				$fname = __METHOD__;
				$dbw->onTransactionIdle(
					function() use ( $dbw, $timestamp, $watchers, $title, $fname ) {
						$dbw->begin( $fname );
						$dbw->update( 'watchlist',
							array( /* SET */
								'wl_notificationtimestamp' => $dbw->timestamp( $timestamp )
							), array( /* WHERE */
								'wl_user' => $watchers,
								'wl_namespace' => $title->getNamespace(),
								'wl_title' => $title->getDBkey(),
							), $fname
						);
						$dbw->commit( $fname );
					}
				);
			}
		}

		$sendEmail = true;
		// If nobody is watching the page, and there are no users notified on all changes
		// don't bother creating a job/trying to send emails
		// $watchers deals with $wgEnotifWatchlist
		if ( !count( $watchers ) && !count( $wgUsersNotifiedOnAllChanges ) ) {
			$sendEmail = false;
			// Only send notification for non minor edits, unless $wgEnotifMinorEdits
			if ( !$minorEdit || ( $wgEnotifMinorEdits && !$editor->isAllowed( 'nominornewtalk' ) ) ) {
				$isUserTalkPage = ( $title->getNamespace() == NS_USER_TALK );
				if ( $wgEnotifUserTalk && $isUserTalkPage && $this->canSendUserTalkEmail( $editor, $title, $minorEdit ) ) {
					$sendEmail = true;
				}
			}
		}

		if ( !$sendEmail ) {
			return;
		}
		if ( $wgEnotifUseJobQ ) {
			$params = array(
				'editor' => $editor->getName(),
				'editorID' => $editor->getID(),
				'timestamp' => $timestamp,
				'summary' => $summary,
				'minorEdit' => $minorEdit,
				'oldid' => $oldid,
				'watchers' => $watchers,
				'pageStatus' => $pageStatus
			);
			$job = new EnotifNotifyJob( $title, $params );
			JobQueueGroup::singleton()->push( $job );
		} else {
			$this->actuallyNotifyOnPageChange( $editor, $title, $timestamp, $summary, $minorEdit, $oldid, $watchers, $pageStatus );
		}
	}

	/**
	 * Immediate version of notifyOnPageChange().
	 *
	 * Send emails corresponding to the user $editor editing the page $title.
	 * Also updates wl_notificationtimestamp.
	 *
	 * @param $editor User object
	 * @param $title Title object
	 * @param string $timestamp Edit timestamp
	 * @param string $summary Edit summary
	 * @param $minorEdit bool
	 * @param int $oldid Revision ID
	 * @param array $watchers of user IDs
	 * @param string $pageStatus
	 * @throws MWException
	 */
	public function actuallyNotifyOnPageChange( $editor, $title, $timestamp, $summary, $minorEdit,
		$oldid, $watchers, $pageStatus = 'changed' ) {
		# we use $wgPasswordSender as sender's address
		global $wgEnotifWatchlist;
		global $wgEnotifMinorEdits, $wgEnotifUserTalk;

		wfProfileIn( __METHOD__ );

		# The following code is only run, if several conditions are met:
		# 1. EmailNotification for pages (other than user_talk pages) must be enabled
		# 2. minor edits (changes) are only regarded if the global flag indicates so

		$isUserTalkPage = ( $title->getNamespace() == NS_USER_TALK );

		$this->title = $title;
		$this->timestamp = $timestamp;
		$this->summary = $summary;
		$this->minorEdit = $minorEdit;
		$this->oldid = $oldid;
		$this->editor = $editor;
		$this->composed_common = false;
		$this->pageStatus = $pageStatus;

		$formattedPageStatus = array( 'deleted', 'created', 'moved', 'restored', 'changed' );

		wfRunHooks( 'UpdateUserMailerFormattedPageStatus', array( &$formattedPageStatus ) );
		if ( !in_array( $this->pageStatus, $formattedPageStatus ) ) {
			wfProfileOut( __METHOD__ );
			throw new MWException( 'Not a valid page status!' );
		}

		$userTalkId = false;

		if ( !$minorEdit || ( $wgEnotifMinorEdits && !$editor->isAllowed( 'nominornewtalk' ) ) ) {

			if ( $wgEnotifUserTalk && $isUserTalkPage && $this->canSendUserTalkEmail( $editor, $title, $minorEdit ) ) {
				$targetUser = User::newFromName( $title->getText() );
				$this->compose( $targetUser );
				$userTalkId = $targetUser->getId();
			}

			if ( $wgEnotifWatchlist ) {
				// Send updates to watchers other than the current editor
				$userArray = UserArray::newFromIDs( $watchers );
				foreach ( $userArray as $watchingUser ) {
					if ( $watchingUser->getOption( 'enotifwatchlistpages' ) &&
						( !$minorEdit || $watchingUser->getOption( 'enotifminoredits' ) ) &&
						$watchingUser->isEmailConfirmed() &&
						$watchingUser->getID() != $userTalkId )
					{
						$this->compose( $watchingUser );
					}
				}
			}
		}

		global $wgUsersNotifiedOnAllChanges;
		foreach ( $wgUsersNotifiedOnAllChanges as $name ) {
			if ( $editor->getName() == $name ) {
				// No point notifying the user that actually made the change!
				continue;
			}
			$user = User::newFromName( $name );
			$this->compose( $user );
		}

		$this->sendMails();
		wfProfileOut( __METHOD__ );
	}

	/**
	 * @param $editor User
	 * @param $title Title bool
	 * @param $minorEdit
	 * @return bool
	 */
	private function canSendUserTalkEmail( $editor, $title, $minorEdit ) {
		global $wgEnotifUserTalk;
		$isUserTalkPage = ( $title->getNamespace() == NS_USER_TALK );

		if ( $wgEnotifUserTalk && $isUserTalkPage ) {
			$targetUser = User::newFromName( $title->getText() );

			if ( !$targetUser || $targetUser->isAnon() ) {
				wfDebug( __METHOD__ . ": user talk page edited, but user does not exist\n" );
			} elseif ( $targetUser->getId() == $editor->getId() ) {
				wfDebug( __METHOD__ . ": user edited their own talk page, no notification sent\n" );
			} elseif ( $targetUser->getOption( 'enotifusertalkpages' ) &&
				( !$minorEdit || $targetUser->getOption( 'enotifminoredits' ) ) )
			{
				if ( !$targetUser->isEmailConfirmed() ) {
					wfDebug( __METHOD__ . ": talk page owner doesn't have validated email\n" );
				} elseif ( !wfRunHooks( 'AbortTalkPageEmailNotification', array( $targetUser, $title ) ) ) {
					wfDebug( __METHOD__ . ": talk page update notification is aborted for this user\n" );
				} else {
					wfDebug( __METHOD__ . ": sending talk page update notification\n" );
					return true;
				}
			} else {
				wfDebug( __METHOD__ . ": talk page owner doesn't want notifications\n" );
			}
		}
		return false;
	}

	/**
	 * Generate the generic "this page has been changed" e-mail text.
	 */
	private function composeCommonMailtext() {
		global $wgPasswordSender, $wgPasswordSenderName, $wgNoReplyAddress;
		global $wgEnotifFromEditor, $wgEnotifRevealEditorAddress;
		global $wgEnotifImpersonal, $wgEnotifUseRealName;

		$this->composed_common = true;

		# You as the WikiAdmin and Sysops can make use of plenty of
		# named variables when composing your notification emails while
		# simply editing the Meta pages

		$keys = array();
		$postTransformKeys = array();
		$pageTitleUrl = $this->title->getCanonicalURL();
		$pageTitle = $this->title->getPrefixedText();

		if ( $this->oldid ) {
			// Always show a link to the diff which triggered the mail. See bug 32210.
			$keys['$NEWPAGE'] = "\n\n" . wfMessage( 'enotif_lastdiff',
				$this->title->getCanonicalURL( array( 'diff' => 'next', 'oldid' => $this->oldid ) ) )
				->inContentLanguage()->text();

			if ( !$wgEnotifImpersonal ) {
				// For personal mail, also show a link to the diff of all changes
				// since last visited.
				$keys['$NEWPAGE'] .= "\n\n" . wfMessage( 'enotif_lastvisited',
					$this->title->getCanonicalURL( array( 'diff' => '0', 'oldid' => $this->oldid ) ) )
					->inContentLanguage()->text();
			}
			$keys['$OLDID'] = $this->oldid;
			// @deprecated Remove in MediaWiki 1.23.
			$keys['$CHANGEDORCREATED'] = wfMessage( 'changed' )->inContentLanguage()->text();
		} else {
			# clear $OLDID placeholder in the message template
			$keys['$OLDID'] = '';
			$keys['$NEWPAGE'] = '';
			// @deprecated Remove in MediaWiki 1.23.
			$keys['$CHANGEDORCREATED'] = wfMessage( 'created' )->inContentLanguage()->text();
		}

		$keys['$PAGETITLE'] = $this->title->getPrefixedText();
		$keys['$PAGETITLE_URL'] = $this->title->getCanonicalURL();
		$keys['$PAGEMINOREDIT'] = $this->minorEdit ?
			wfMessage( 'minoredit' )->inContentLanguage()->text() : '';
		$keys['$UNWATCHURL'] = $this->title->getCanonicalURL( 'action=unwatch' );

		if ( $this->editor->isAnon() ) {
			# real anon (user:xxx.xxx.xxx.xxx)
			$keys['$PAGEEDITOR'] = wfMessage( 'enotif_anon_editor', $this->editor->getName() )
				->inContentLanguage()->text();
			$keys['$PAGEEDITOR_EMAIL'] = wfMessage( 'noemailtitle' )->inContentLanguage()->text();

		} else {
			$keys['$PAGEEDITOR'] = $wgEnotifUseRealName ? $this->editor->getRealName() : $this->editor->getName();
			$emailPage = SpecialPage::getSafeTitleFor( 'Emailuser', $this->editor->getName() );
			$keys['$PAGEEDITOR_EMAIL'] = $emailPage->getCanonicalURL();
		}

		$keys['$PAGEEDITOR_WIKI'] = $this->editor->getUserPage()->getCanonicalURL();

		# Replace this after transforming the message, bug 35019
		$postTransformKeys['$PAGESUMMARY'] = $this->summary == '' ? ' - ' : $this->summary;

		// Now build message's subject and body

		// Messages:
		// enotif_subject_deleted, enotif_subject_created, enotif_subject_moved,
		// enotif_subject_restored, enotif_subject_changed
		$this->subject = wfMessage( 'enotif_subject_' . $this->pageStatus )->inContentLanguage()
			->params( $pageTitle, $keys['$PAGEEDITOR'] )->text();

		// Messages:
		// enotif_body_intro_deleted, enotif_body_intro_created, enotif_body_intro_moved,
		// enotif_body_intro_restored, enotif_body_intro_changed
		$keys['$PAGEINTRO'] = wfMessage( 'enotif_body_intro_' . $this->pageStatus )
			->inContentLanguage()->params( $pageTitle, $keys['$PAGEEDITOR'], $pageTitleUrl )
			->text();

		$body = wfMessage( 'enotif_body' )->inContentLanguage()->plain();
		$body = strtr( $body, $keys );
		$body = MessageCache::singleton()->transform( $body, false, null, $this->title );
		$this->body = wordwrap( strtr( $body, $postTransformKeys ), 72 );

		# Reveal the page editor's address as REPLY-TO address only if
		# the user has not opted-out and the option is enabled at the
		# global configuration level.
		$adminAddress = new MailAddress( $wgPasswordSender, $wgPasswordSenderName );
		if ( $wgEnotifRevealEditorAddress
			&& ( $this->editor->getEmail() != '' )
			&& $this->editor->getOption( 'enotifrevealaddr' ) )
		{
			$editorAddress = new MailAddress( $this->editor );
			if ( $wgEnotifFromEditor ) {
				$this->from = $editorAddress;
			} else {
				$this->from = $adminAddress;
				$this->replyto = $editorAddress;
			}
		} else {
			$this->from = $adminAddress;
			$this->replyto = new MailAddress( $wgNoReplyAddress );
		}
	}

	/**
	 * Compose a mail to a given user and either queue it for sending, or send it now,
	 * depending on settings.
	 *
	 * Call sendMails() to send any mails that were queued.
	 * @param $user User
	 */
	function compose( $user ) {
		global $wgEnotifImpersonal;

		if ( !$this->composed_common ) {
			$this->composeCommonMailtext();
		}

		if ( $wgEnotifImpersonal ) {
			$this->mailTargets[] = new MailAddress( $user );
		} else {
			$this->sendPersonalised( $user );
		}
	}

	/**
	 * Send any queued mails
	 */
	function sendMails() {
		global $wgEnotifImpersonal;
		if ( $wgEnotifImpersonal ) {
			$this->sendImpersonal( $this->mailTargets );
		}
	}

	/**
	 * Does the per-user customizations to a notification e-mail (name,
	 * timestamp in proper timezone, etc) and sends it out.
	 * Returns true if the mail was sent successfully.
	 *
	 * @param $watchingUser User object
	 * @return Boolean
	 * @private
	 */
	function sendPersonalised( $watchingUser ) {
		global $wgContLang, $wgEnotifUseRealName;
		// From the PHP manual:
		//     Note:  The to parameter cannot be an address in the form of "Something <someone@example.com>".
		//     The mail command will not parse this properly while talking with the MTA.
		$to = new MailAddress( $watchingUser );

		# $PAGEEDITDATE is the time and date of the page change
		# expressed in terms of individual local time of the notification
		# recipient, i.e. watching user
		$body = str_replace(
			array( '$WATCHINGUSERNAME',
				'$PAGEEDITDATE',
				'$PAGEEDITTIME' ),
			array( $wgEnotifUseRealName ? $watchingUser->getRealName() : $watchingUser->getName(),
				$wgContLang->userDate( $this->timestamp, $watchingUser ),
				$wgContLang->userTime( $this->timestamp, $watchingUser ) ),
			$this->body );

		return UserMailer::send( $to, $this->from, $this->subject, $body, $this->replyto );
	}

	/**
	 * Same as sendPersonalised but does impersonal mail suitable for bulk
	 * mailing.  Takes an array of MailAddress objects.
	 * @param $addresses array
	 * @return Status|null
	 */
	function sendImpersonal( $addresses ) {
		global $wgContLang;

		if ( empty( $addresses ) ) {
			return null;
		}

		$body = str_replace(
				array( '$WATCHINGUSERNAME',
					'$PAGEEDITDATE',
					'$PAGEEDITTIME' ),
				array( wfMessage( 'enotif_impersonal_salutation' )->inContentLanguage()->text(),
					$wgContLang->date( $this->timestamp, false, false ),
					$wgContLang->time( $this->timestamp, false, false ) ),
				$this->body );

		return UserMailer::send( $addresses, $this->from, $this->subject, $body, $this->replyto );
	}

} # end of class EmailNotification
