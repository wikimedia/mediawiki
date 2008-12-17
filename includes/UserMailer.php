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
 * @author <brion@pobox.com>
 * @author <mail@tgries.de>
 * @author Tim Starling
 *
 */


/**
 * Stores a single person's name and email address.
 * These are passed in via the constructor, and will be returned in SMTP
 * header format when requested.
 */
class MailAddress {
	/**
	 * @param $address Mixed: string with an email address, or a User object
	 * @param $name String: human-readable name if a string address is given
	 */
	function __construct( $address, $name = null, $realName = null ) {
		if( is_object( $address ) && $address instanceof User ) {
			$this->address = $address->getEmail();
			$this->name = $address->getName();
			$this->realName = $address->getRealName();
		} else {
			$this->address = strval( $address );
			$this->name = strval( $name );
			$this->reaName = strval( $realName );
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
		if( $this->name != '' && !wfIsWindows() ) {
			global $wgEnotifUseRealName;
			$name = ( $wgEnotifUseRealName && $this->realName ) ? $this->realName : $this->name;
			$quoted = wfQuotedPrintable( $name );
			if( strpos( $quoted, '.' ) !== false || strpos( $quoted, ',' ) !== false ) {
				$quoted = '"' . $quoted . '"';
			}
			return "$quoted <{$this->address}>";
		} else {
			return $this->address;
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
	/**
	 * Send mail using a PEAR mailer
	 */
	protected static function sendWithPear($mailer, $dest, $headers, $body)
	{
		$mailResult = $mailer->send($dest, $headers, $body);

		# Based on the result return an error string,
		if( PEAR::isError( $mailResult ) ) {
			wfDebug( "PEAR::Mail failed: " . $mailResult->getMessage() . "\n" );
			return new WikiError( $mailResult->getMessage() );
		} else {
			return true;
		}
	}

	/**
	 * This function will perform a direct (authenticated) login to
	 * a SMTP Server to use for mail relaying if 'wgSMTP' specifies an
	 * array of parameters. It requires PEAR:Mail to do that.
	 * Otherwise it just uses the standard PHP 'mail' function.
	 *
	 * @param $to MailAddress: recipient's email
	 * @param $from MailAddress: sender's email
	 * @param $subject String: email's subject.
	 * @param $body String: email's text.
	 * @param $replyto String: optional reply-to email (default: null).
	 * @param $contentType String: optional custom Content-Type
	 * @return mixed True on success, a WikiError object on failure.
	 */
	static function send( $to, $from, $subject, $body, $replyto=null, $contentType=null ) {
		global $wgSMTP, $wgOutputEncoding, $wgErrorString, $wgEnotifImpersonal;
		global $wgEnotifMaxRecips;

		if ( is_array( $to ) ) {
			wfDebug( __METHOD__.': sending mail to ' . implode( ',', $to ) . "\n" );
		} else {
			wfDebug( __METHOD__.': sending mail to ' . implode( ',', array( $to->toString() ) ) . "\n" );
		}

		if (is_array( $wgSMTP )) {
			require_once( 'Mail.php' );

			$msgid = str_replace(" ", "_", microtime());
			if (function_exists('posix_getpid'))
				$msgid .= '.' . posix_getpid();

			if (is_array($to)) {
				$dest = array();
				foreach ($to as $u)
					$dest[] = $u->address;
			} else
				$dest = $to->address;

			$headers['From'] = $from->toString();

			if ($wgEnotifImpersonal) {
				$headers['To'] = 'undisclosed-recipients:;';
			}
			else {
				$headers['To'] = implode( ", ", (array )$dest );
			}

			if ( $replyto ) {
				$headers['Reply-To'] = $replyto->toString();
			}
			$headers['Subject'] = wfQuotedPrintable( $subject );
			$headers['Date'] = date( 'r' );
			$headers['MIME-Version'] = '1.0';
			$headers['Content-type'] = (is_null($contentType) ?
					'text/plain; charset='.$wgOutputEncoding : $contentType);
			$headers['Content-transfer-encoding'] = '8bit';
			$headers['Message-ID'] = "<$msgid@" . $wgSMTP['IDHost'] . '>'; // FIXME
			$headers['X-Mailer'] = 'MediaWiki mailer';

			// Create the mail object using the Mail::factory method
			$mail_object =& Mail::factory('smtp', $wgSMTP);
			if( PEAR::isError( $mail_object ) ) {
				wfDebug( "PEAR::Mail factory failed: " . $mail_object->getMessage() . "\n" );
				return new WikiError( $mail_object->getMessage() );
			}

			wfDebug( "Sending mail via PEAR::Mail to $dest\n" );
			$chunks = array_chunk( (array)$dest, $wgEnotifMaxRecips );
			foreach ($chunks as $chunk) {
				$e = self::sendWithPear($mail_object, $chunk, $headers, $body);
				if( WikiError::isError( $e ) )
					return $e;
			}
		} else	{
			# In the following $headers = expression we removed "Reply-To: {$from}\r\n" , because it is treated differently
			# (fifth parameter of the PHP mail function, see some lines below)

			# Line endings need to be different on Unix and Windows due to
			# the bug described at http://trac.wordpress.org/ticket/2603
			if ( wfIsWindows() ) {
				$body = str_replace( "\n", "\r\n", $body );
				$endl = "\r\n";
			} else {
				$endl = "\n";
			}
			$ctype = (is_null($contentType) ? 
					'text/plain; charset='.$wgOutputEncoding : $contentType);
			$headers =
				"MIME-Version: 1.0$endl" .
				"Content-type: $ctype$endl" .
				"Content-Transfer-Encoding: 8bit$endl" .
				"X-Mailer: MediaWiki mailer$endl".
				'From: ' . $from->toString();
			if ($replyto) {
				$headers .= "{$endl}Reply-To: " . $replyto->toString();
			}

			$wgErrorString = '';
			$html_errors = ini_get( 'html_errors' );
			ini_set( 'html_errors', '0' );
			set_error_handler( array( 'UserMailer', 'errorHandler' ) );
			wfDebug( "Sending mail via internal mail() function\n" );

			if (function_exists('mail')) {
				if (is_array($to)) {
					foreach ($to as $recip) {
						$sent = mail( $recip->toString(), wfQuotedPrintable( $subject ), $body, $headers );
					}
				} else {
					$sent = mail( $to->toString(), wfQuotedPrintable( $subject ), $body, $headers );
				}
			} else {
				$wgErrorString = 'PHP is not configured to send mail';
			}

			restore_error_handler();
			ini_set( 'html_errors', $html_errors );

			if ( $wgErrorString ) {
				wfDebug( "Error sending mail: $wgErrorString\n" );
				return new WikiError( $wgErrorString );
			} elseif (! $sent) {
				//mail function only tells if there's an error
				wfDebug( "Error sending mail\n" );
				return new WikiError( 'mailer error' );
			} else {
				return true;
			}
		}
	}

	/**
	 * Get the mail error message in global $wgErrorString
	 *
	 * @param $code Integer: error number
	 * @param $string String: error message
	 */
	static function errorHandler( $code, $string ) {
		global $wgErrorString;
		$wgErrorString = preg_replace( '/^mail\(\)(\s*\[.*?\])?: /', '', $string );
	}

	/**
	 * Converts a string into a valid RFC 822 "phrase", such as is used for the sender name
	 */
	static function rfc822Phrase( $phrase ) {
		$phrase = strtr( $phrase, array( "\r" => '', "\n" => '', '"' => '' ) );
		return '"' . $phrase . '"';
	}
}


class EmailNotification {
	
	/*
	 * Send users an email.
	 *
	 * @param $editor User whose action precipitated the notification.
	 * @param $timestamp of the event.
	 * @param Callback that returns an an array of Users who will recieve the notification.
	 * @param Callback that returns an array($keys, $body, $subject) where
	 *        * $keys is a dictionary whose keys will be replaced with the corresponding
	 *          values within the subject and body of the message.
	 *        * $body is the name of the message that will be used for the email body.
	 *        * $subject is the name of the message that will be used for the subject.
	 * Both messages are resolved using the content language.
	 * The messageCompositionFunction is invoked for each recipient user;
	 * The keys returned are merged with those given by EmailNotification::commonMessageKeys().
	 * The recipient is appended to the arguments given to messageCompositionFunction.
	 * Both callbacks are to be given in the same formats accepted by the hook system.
	 */
	static function notify( $editor, $timestamp, $userListFunction, $messageCompositionFunction ) {
		global $wgEnotifUseRealName, $wgEnotifImpersonal;
		global $wgLang;

		$users = wfInvoke( 'userList', $userListFunction );
		if( !count( $users ) )
			return;

		$common_keys = self::commonMessageKeys( $editor );
		foreach( $users as $u ) {
			list( $user_keys, $body_msg_name, $subj_msg_name ) =
				wfInvoke( 'message', $messageCompositionFunction, array( $u ) ); 
			$keys = array_merge( $common_keys, $user_keys );

			if( $wgEnotifImpersonal ) {
				$keys['$WATCHINGUSERNAME'] = wfMsgForContent( 'enotif_impersonal_salutation' );
				$keys['$PAGEEDITDATE'] = $wgLang->timeanddate( $timestamp, true, false, false );
			} else {
				$keys['$WATCHINGUSERNAME'] = $wgEnotifUseRealName ? $u->getRealName() : $u->getName();
				$keys['$PAGEEDITDATE'] = $wgLang->timeAndDate( $timestamp, true, false,
					$u->getOption( 'timecorrection' ) );
			}

			$subject = strtr( wfMsgForContent( $subj_msg_name ), $keys );
			$body = wordwrap( strtr( wfMsgForContent( $body_msg_name ), $keys ), 72 );

			$to = new MailAddress( $u );
			$from = $keys['$FROM_HEADER'];
			$replyto = $keys['$REPLYTO_HEADER'];
			UserMailer::send( $to, $from, $subject, $body, $replyto );
		}
	}


	static function commonMessageKeys( $editor ) {
		global $wgEnotifUseRealName, $wgEnotifRevealEditorAddress;
		global $wgNoReplyAddress, $wgPasswordSender;

		$keys = array();

		$name = $wgEnotifUseRealName ? $editor->getRealName() : $editor->getName();

		$adminAddress = new MailAddress( $wgPasswordSender, 'WikiAdmin' );
		$editorAddress = new MailAddress( $editor );
		if( $wgEnotifRevealEditorAddress
		    	&& $editor->getEmail() != ''
		    	&& $editor->getOption( 'enotifrevealaddr' ) ) {
			if( $wgEnotifFromEditor ) {
				$from    = $editorAddress;
			} else {
				$from    = $adminAddress;
				$replyto = $editorAddress;
			}
		} else {
			$from    = $adminAddress;
			$replyto = new MailAddress( $wgNoReplyAddress );
		}
		$keys['$FROM_HEADER'] = $from;
		$keys['$REPLYTO_HEADER'] = $replyto;

		if( $editor->isAnon() ) {
			$keys['$PAGEEDITOR'] = wfMsgForContent( 'enotif_anon_editor', $name );
			$keys['$PAGEEDITOR_EMAIL'] = wfMsgForContent( 'noemailtitle' );
		} else{
			$keys['$PAGEEDITOR'] = $name;
			$keys['$PAGEEDITOR_EMAIL'] = SpecialPage::getSafeTitleFor( 'Emailuser', $name )->getFullUrl();
		}
		$keys['$PAGEEDITOR_WIKI'] = $editor->getUserPage()->getFullUrl();

		return $keys;
	}
	
	

	/*
	 * @deprecated
	 * Use PageChangeNotification::notifyOnPageChange instead.
	 */
	function notifyOnPageChange($editor, $title, $timestamp, $summary, $minorEdit, $oldid = false) {                          
		PageChangeNotification::notifyOnPageChange($editor, $title, $timestamp, $summary, $minorEdit, $oldid);
	}
}

class PageChangeNotification {
	
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
	 */
	static function notifyOnPageChange( $editor, $title, $timestamp, $summary, $minorEdit, $oldid = false ) {                          
		global $wgEnotifUseJobQ;

		if ( $title->getNamespace() < 0 )
			return;

		if ( $wgEnotifUseJobQ ) {
			$params = array(
				"editor" => $editor->getName(),
				"editorID" => $editor->getID(),
				"timestamp" => $timestamp,
				"summary" => $summary,
				"minorEdit" => $minorEdit,
				"oldid" => $oldid);
			$job = new EnotifNotifyJob( $title, $params );
			$job->insert();
		} else {
			self::actuallyNotifyOnPageChange( $editor, $title, $timestamp, $summary, $minorEdit, $oldid );
		}

	}

	/*
	 * Immediate version of notifyOnPageChange().
	 *
	 * Send emails corresponding to the user $editor editing the page $title.
	 * Also updates wl_notificationtimestamp.
	 *
	 * @param $editor User object
	 * @param $title Title object
	 * @param $timestamp
	 * @param $summary
	 * @param $minorEdit
	 * @param $oldid (default: false)
	 */
	static function actuallyNotifyOnPageChange( $editor, $title, $timestamp,
			$summary, $minorEdit, $oldid = false ) {
		global $wgShowUpdatedMarker, $wgEnotifWatchlist;
		
		wfProfileIn( __METHOD__ );
		
		EmailNotification::notify( $editor, $timestamp,
			array( 'PageChangeNotification::usersList', array( $editor, $title, $minorEdit ) ),
			array( 'PageChangeNotification::message', array( $oldid, $minorEdit, $summary, $title, $editor ) ) );
	
		$latestTimestamp = Revision::getTimestampFromId( $title, $title->getLatestRevID() );
		// Do not update watchlists if something else already did.
		if ( $timestamp >= $latestTimestamp && ($wgShowUpdatedMarker || $wgEnotifWatchlist) ) {
			# Mark the changed watch-listed page with a timestamp, so that the page is
			# listed with an "updated since your last visit" icon in the watch list. Do
			# not do this to users for their own edits.
			$dbw = wfGetDB( DB_MASTER );
			$dbw->update( 'watchlist',
				array( /* SET */
					'wl_notificationtimestamp' => $dbw->timestamp($timestamp)
				), array( /* WHERE */
					'wl_title' => $title->getDBkey(),
					'wl_namespace' => $title->getNamespace(),
					'wl_notificationtimestamp IS NULL',
					'wl_user != ' . $editor->getID()
				), __METHOD__
			);
		}

		wfProfileOut( __METHOD__ );
	}
	
	
	static function message( $stuff ) {
		global $wgEnotifImpersonal;

		list( $oldid, $medit, $summary, $title, $user ) = $stuff;
		$keys = array();

		# regarding the use of oldid as an indicator for the last visited version, see also
		# http://bugzilla.wikipeda.org/show_bug.cgi?id=603 "Delete + undelete cycle doesn't preserve old_id"
		# However, in the case of a new page which is already watched, we have no previous version to compare
		if( $oldid ) {
			$difflink = $title->getFullUrl( 'diff=0&oldid=' . $oldid );
			$keys['$NEWPAGE'] = wfMsgForContent( 'enotif_lastvisited', $difflink );
			$keys['$OLDID']   = $oldid;
			$keys['$CHANGEDORCREATED'] = wfMsgForContent( 'changed' );
		} else {
			$keys['$NEWPAGE'] = wfMsgForContent( 'enotif_newpagetext' );
			# clear $OLDID placeholder in the message template
			$keys['$OLDID']   = '';
			$keys['$CHANGEDORCREATED'] = wfMsgForContent( 'created' );
		}

		if ($wgEnotifImpersonal && $oldid) {
			# For impersonal mail, show a diff link to the last revision.
			$keys['$NEWPAGE'] = wfMsgForContent( 'enotif_lastdiff',
					$title->getFullURL( "oldid={$oldid}&diff=prev" ) );
		}

		$keys['$PAGETITLE'] = $title->getPrefixedText();
		$keys['$PAGETITLE_URL'] = $title->getFullUrl();
		$keys['$PAGEMINOREDIT'] = $medit ? wfMsg( 'minoredit' ) : '';
		$keys['$PAGESUMMARY'] = ( $summary == '' ) ? ' - ' : $summary;

		return array( $keys, 'enotif_body', 'enotif_subject' );
	}

	static function usersList( $stuff ) {
		global $wgEnotifWatchlist, $wgEnotifMinorEdits, $wgUsersNotifiedOnAllChanges;

		list( $editor, $title, $minorEdit ) = $stuff;
		$recipients = array();

		# User talk pages:
		$userTalkId = false;
		if( $title->getNamespace() == NS_USER_TALK && ( !$minorEdit || $wgEnotifMinorEdits ) ) {
		   $targetUser = User::newFromName( $title->getText() );

		   if ( !$targetUser || $targetUser->isAnon() )
		      $msg = "user talk page edited, but user does not exist";

		   else if ( $targetUser->getId() == $editor->getId() )
		      $msg = "user edited their own talk page, no notification sent";

		   else if ( !$targetUser->getOption( 'enotifusertalkpages' ) )
		      $msg = "talk page owner doesn't want notifications";

		   else if ( !$targetUser->isEmailConfirmed() )
		      $msg = "talk page owner doesn't have validated email";

		   else {
		      $msg = "sending talk page update notification";
		      $recipients[] = $targetUser;
		      $userTalkId = $targetUser->getId(); # won't be included in watchlist, below.
		   }
		   wfDebug( __METHOD__ . ': ' . $msg . "\n" );
		}
		wfDebug( "Did not send a user-talk notification.\n" );

		if( $wgEnotifWatchlist && ( !$minorEdit || $wgEnotifMinorEdits ) ) {
			// Send updates to watchers other than the current editor
			$userCondition = 'wl_user != ' . $editor->getID();

			if ( $userTalkId !== false ) {
				// Already sent an email to this person
				$userCondition .= ' AND wl_user != ' . intval( $userTalkId );
			}
			$dbr = wfGetDB( DB_SLAVE );

			list( $user ) = $dbr->tableNamesN( 'user' );

			$res = $dbr->select( array( 'watchlist', 'user' ),
				array( "$user.*" ),
				array(
					'wl_user=user_id',
					'wl_title' => $title->getDBkey(),
					'wl_namespace' => $title->getNamespace(),
					$userCondition,
					'wl_notificationtimestamp IS NULL',
				), __METHOD__ );
			$userArray = UserArray::newFromResult( $res );

			foreach ( $userArray as $watchingUser ) {
				if ( $watchingUser->getOption( 'enotifwatchlistpages' ) &&
						( !$minorEdit || $watchingUser->getOption('enotifminoredits') ) &&
						$watchingUser->isEmailConfirmed() ) {
					$recipients[] = $watchingUser;
				}
			}
		}

		foreach ( $wgUsersNotifiedOnAllChanges as $name ) {
			$recipients[] = User::newFromName( $name );
		}

		return $recipients;
	}
}

/**
 * Backwards compatibility functions
 */
function wfRFC822Phrase( $s ) {
	return UserMailer::rfc822Phrase( $s );
}

function userMailer( $to, $from, $subject, $body, $replyto=null ) {
	return UserMailer::send( $to, $from, $subject, $body, $replyto );
}
