<?php
/** Copyright (C) 2004 Thomas Gries <mail@tgries.de>
# http://www.mediawiki.org/
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
# http://www.gnu.org/copyleft/gpl.html
**/

/**
 * Provide mail capabilities
 *
 * @package MediaWiki
 */

function wfQuotedPrintable_name_and_emailaddr( $string ) {
	/*	it takes formats like "name <emailaddr>" into account,
		for which the partial string "name" will be quoted-printable converted
		but not "<emailaddr>".

		The php mail() function does not accept the email address partial string
		to be quotedprintable, it does not accept inputs as quotedprintable("name <emailaddr>") .

		case 1:
		input:	"name <emailaddr> rest"
		output:	"quoted-printable(name) <emailaddr>"

		case 2: (should not happen)
		input:	"<emailaddr> rest"
		output:	"<emailaddr>"

		case 3:	(should not happen)
		input:	"name"
		output:	"quoted-printable(name)"

		T. Gries 18.11.2004
	*/

	/* do not quote printable for email address string <emailaddr>, but only for the (leading) string, usually the name */
	preg_match( '/^([^<]*)?(<([A-z0-9_.-]+([A-z0-9_.-]+)*\@[A-z0-9_-]+([A-z0-9_.-]+)*([A-z.]{2,})+)>)?$/', $string, $part );
	if ( !isset($part[1]) ) return $part[2];
	if ( !isset($part[2]) ) return wfQuotedprintable($part[1]);
	return wfQuotedprintable($part[1]) . $part[2] ;
}

/**
 * This function will perform a direct (authenticated) login to
 * a SMTP Server to use for mail relaying if 'wgSMTP' specifies an
 * array of parameters. It requires PEAR:Mail to do that.
 * Otherwise it just uses the standard PHP 'mail' function.
 *
 * @param string $to recipient's email
 * @param string $from sender's email
 * @param string $subject email's subject
 * @param string $body email's text
 */
function userMailer( $to, $from, $subject, $body, $replyto=false ) {
	global $wgUser, $wgSMTP, $wgOutputEncoding, $wgErrorString, $wgEmergencyContact;
	
  	$qto = wfQuotedPrintable_name_and_emailaddr( $to );
	
	if (is_array( $wgSMTP )) {
		require_once( 'Mail.php' );
		
		$timestamp = time();
	
		$headers['From'] = $from;
/* removing to: field as it should be set by the send() function below
   UNTESTED - Hashar */
//		$headers['To'] = $qto;
/* Reply-To:
   UNTESTED - Tom Gries */
		$headers['Reply-To'] = $replyto;
		$headers['Subject'] = $subject;
		$headers['MIME-Version'] = '1.0';
		$headers['Content-type'] = 'text/plain; charset='.$wgOutputEncoding;
		$headers['Content-transfer-encoding'] = '8bit';
		$headers['Message-ID'] = "<{$timestamp}" . $wgUser->getName() . '@' . $wgSMTP['IDHost'] . '>';
		$headers['X-Mailer'] = 'MediaWiki mailer';
	
		// Create the mail object using the Mail::factory method
		$mail_object =& Mail::factory('smtp', $wgSMTP);
	
		$mailResult =& $mail_object->send($to, $headers, $body);
		
		# Based on the result return an error string, 
		if ($mailResult === true)
			return '';
		else if (is_object($mailResult))
			return $mailResult->getMessage();
		else
			return 'Mail object return unknown error.';
	} else	{
		# In the following $headers = expression we removed "Reply-To: {$from}\r\n" , because it is treated differently
		# (fifth parameter of the PHP mail function, see some lines below)
		$headers =
			"MIME-Version: 1.0\n" .
			"Content-type: text/plain; charset={$wgOutputEncoding}\n" .
			"Content-Transfer-Encoding: 8bit\n" .
			"X-Mailer: MediaWiki mailer\n".
			'From:' . wfQuotedPrintable_name_and_emailaddr($from) . "\n";
		if ($replyto) {
			$headers .= 'Reply-To: '.wfQuotedPrintable_name_and_emailaddr($replyto)."\n";
		}

		$wgErrorString = '';
		set_error_handler( 'mailErrorHandler' );
		# added -f parameter, see PHP manual for the fifth parameter when using the mail function
		mail( wfQuotedPrintable_name_and_emailaddr($to), $subject, $body, $headers, "-f{$wgEmergencyContact}\n");
		restore_error_handler();

		return $wgErrorString;
	}
}

/**
 *
 */
function mailErrorHandler( $code, $string ) {
	global $wgErrorString;
	$wgErrorString = preg_replace( "/^mail\(\): /", "", $string );
}


/**
 * Patch for email notification on page changes T.Gries/M.Arndt 11.09.2004
 *
 *	This new module processes the email notifications when the current page is changed.
 *	It looks up the table watchlist to find out which users are watching that page.
 *
 *	The current implementation sends independent emails to each watching user for the following reason:
 *
 * 	-	Each watching user will be notified about the page edit time expressed in his/her local time (UTC is shown additionally).
 *		To achieve this, we need to find the individual timeoffset of each watching user from the preferences..
 *
 *		Suggested improvement to slack down the number of sent emails:
 *		We could think of sending out bulk mails (bcc:user1,user2...) for all these users having the same timeoffset in their preferences.
 *
 *	-	Visit the documentation pages under http://meta.wikipedia.com/Enotif
 */
class EmailNotification {
	var $to, $subject, $body, $replyto, $from;
	
	function NotifyOnPageChange($currentUser, $currentPage, $currentNs, $timestamp, $currentSummary, $currentMinorEdit, $oldid=false) {
	
		# we use $wgEmergencyContact as sender's address
		global $wgUser, $wgLang, $wgEmergencyContact;
		global $wgEmailNotificationForWatchlistPages, $wgEmailNotificationForMinorEdits;
		global $wgEmailNotificationSystembeep, $wgEmailNotificationForUserTalkPages;
		global $wgEmailNotificationRevealPageEditorAddress;
		global $wgEmailNotificationMailsSentFromPageEditor;
		global $wgEmailAuthentication;
		global $beeped;
	
		# The following code is only run, if several conditions are met:
		# 1. EmailNotification for pages (other than user_talk pages) must be enabled
		# 2. minor edits (changes) are only regarded if the global flag indicates so
	
		$isUserTalkPage = ($currentNs == NS_USER_TALK);
		$enotifusertalkpage = ($isUserTalkPage && $wgEmailNotificationForUserTalkPages);
		$enotifwatchlistpage = (!$isUserTalkPage && $wgEmailNotificationForWatchlistPages);
	
		if ( ($enotifusertalkpage || $enotifwatchlistpage)
			&& (!$currentMinorEdit || $wgEmailNotificationForMinorEdits) ) {
	
			$dbr =& wfGetDB( DB_MASTER );
			extract( $dbr->tableNames( 'watchlist' ) );
			$sql = "SELECT wl_user FROM $watchlist
				WHERE wl_title='" . $dbr->strencode($currentPage)."'  AND wl_namespace = " . $currentNs .
				" AND wl_user <>" . $currentUser . " AND wl_notificationtimestamp <= 1";
			$res = $dbr->query( $sql,'UserMailer::NotifyOnChange');
	
			if ( $dbr->numRows( $res ) > 0 ) { # if anyone is watching ... set up the email message text which is common for all receipients ...
	
				# This is a switch for one beep on the server when sending notification mails
				$beeped = false;
	
				$article->mTimestamp = $timestamp;
				$article->mSummary = $currentSummary;
				$article->mMinorEdit = $currentMinorEdit;
				$article->mNamespace = $currentNs;
				$article->mTitle = $currentPage;
				$article->mOldid = $oldid;
	
				$mail = $this->composeCommonMailtext( $wgUser, $article );
				$watchingUser = new User();
	
				for ($i = 1; $i <= $dbr->numRows( $res ); $i++) { # ... now do for all watching users ... if the options fit
	
					$wuser = $dbr->fetchObject( $res );
					$watchingUser->setID($wuser->wl_user);
					if ( ( $enotifwatchlistpage && $watchingUser->getOption('enotifwatchlistpages') ) ||
						( $enotifusertalkpage && $watchingUser->getOption('enotifusertalkpages') )
					&& (!$currentMinorEdit || ($wgEmailNotificationForMinorEdits && $watchingUser->getOption('enotifminoredits') ) )
					&& ($watchingUser->getEmail() != '')
					&& (!$wgEmailAuthentication || ($watchingUser->getEmailAuthenticationtimestamp() != 0 ) ) ) {
						# ... adjust remaining text and page edit time placeholders
						# which needs to be personalized for each user
						$sent = $this->composeAndSendPersonalisedMail( $watchingUser, $mail, $article );
						/* the beep here beeps once when a watched-listed page is changed */
						if ($sent && !$beeped && ($wgEmailNotificationSystembeep != '') ) {
							$last_line = system($wgEmailNotificationSystembeep);
							$beeped=true;
						}
					} # if the watching user has an email address in the preferences
					# mark the changed watch-listed page with a timestamp, so that the page is listed with an "updated since your last visit" icon in the watch list, ...
					# ... no matter, if the watching user has or has not indicated an email address in his/her preferences.
					# We memorise the event of sending out a notification and use this as a flag to suppress further mails for changes on the same page for that watching user
					$dbw =& wfGetDB( DB_MASTER );
					$succes = $dbw->update( 'watchlist',
						array( /* SET */
							'wl_notificationtimestamp' => $article->mTimestamp
						), array( /* WHERE */
							'wl_title' => $currentPage,
							'wl_namespace' => $currentNs,
							'wl_user' => $wuser->wl_user
						), 'UserMailer::NotifyOnChange'
					);
				} # for every watching user
			} # if anyone is watching
		} # if $wgEmailNotificationForWatchlistPages = true
	} # function NotifyOnChange
	
	/**
	 * @param User $pageeditorUser
	 * @param Article $article
	 * @access private
	 */
	function composeCommonMailtext( $pageeditorUser, $article ) {
		global $wgLang, $wgEmergencyContact;
		global $wgEmailNotificationRevealPageEditorAddress;
		global $wgEmailNotificationMailsSentFromPageEditor;
		global $wgNoReplyAddress;
		
		$summary = ($article->mSummary == '') ? ' - ' : $article->mSummary;
		$medit   = ($article->mMinorEdit) ? wfMsg( 'minoredit' ) : '';
		
		# You as the WikiAdmin and Sysops can make use of plenty of
		# named variables when composing your notification emails while
		# simply editing the Meta pages
		
		$to      = wfMsg( 'email_notification_to' );
		$subject = wfMsg( 'email_notification_subject' );
		$body    = wfMsg( 'email_notification_body' );
		$from    = ''; /* fail safe */
		$replyto = ''; /* fail safe */
		$keys    = array();
		
		# regarding the use of oldid as an indicator for the last visited version, see also
		# http://bugzilla.wikipeda.org/show_bug.cgi?id=603 "Delete + undelete cycle doesn't preserve old_id"
		# However, in the case of a new page which is already watched, we have no previous version to compare
		if( $article->mOldid ) {
			$keys['$NEWPAGE'] = wfMsg( 'email_notification_lastvisitedrevisiontext' );
			$keys['$OLDID']   = $article->mOldid;
		} else {
			$keys['$NEWPAGE'] = wfMsg( 'email_notification_newpagetext' );
			# clear $OLDID placeholder in the message template
			$keys['$OLDID']   = '';
		}
	
		$pagetitle = $article->mTitle;
		if( $article->mNamespace != NS_MAIN ) {
			$pagetitle = $wgLang->getNsText( $article->mNamespace ) . ':' . $pagetitle;
		}
		$subject = str_replace( '$PAGETITLE_QP', wfQuotedPrintable( str_replace( '_', ' ', $pagetitle ) ), $subject);
		$keys['%24PAGETITLE_RAWURL'] = wfUrlencode( $pagetitle );
		$keys['$PAGETITLE_RAWURL']   = wfUrlencode( $pagetitle );
		$keys['%24PAGETITLE']        = $pagetitle; # needed for the {{localurl:$PAGETITLE}} in the messagetext, "$" appears here as "%24"
		$keys['$PAGETITLE']          = $pagetitle;
		$keys['$PAGETIMESTAMP']      = $article->mTimestamp;	# this is the raw internal timestamp - can be useful, too
		$keys['$PAGEEDITDATEUTC']    = $wgLang->timeanddate( $article->mTimestamp, false, false, false, true );
		$keys['$PAGEMINOREDIT']      = $medit;
		$keys['$PAGESUMMARY']        = $summary;
	

		# Reveal the page editor's address as REPLY-TO address only if
		# the user has not opted-out and the option is enabled at the
		# global configuration level.
		$pageeditor_qp = wfQuotedPrintable( $pageeditorUser->getName() );
		$adminAddress = 'WikiAdmin <' . $wgEmergencyContact . '>';
		$editorAddress = $pageeditorUser->getName() . ' <' . $pageeditorUser->getEmail() . '>';
		if( $wgEmailNotificationRevealPageEditorAddress
		    && ( $pageeditorUser->getEmail() != '' )
		    && $pageeditorUser->getOption( 'enotifrevealaddr' ) ) {
			if( $wgEmailNotificationMailsSentFromPageEditor ) {
				$from    = $editorAddress;
			} else {
				$from    = $adminAddress;
				$replyto = $editorAddress;
			}
			$keys['$PAGEEDITORNAMEANDEMAILADDR'] = $editorAddress;
		} else {
			$from    = $adminAddress;
			$replyto = $wgNoReplyAddress;
			$keys['$PAGEEDITORNAMEANDEMAILADDR'] = $replyto;
		}
	
		if( $pageeditorUser->isIP( $pageeditorUser->getName() ) ) {
			#real anon (user:xxx.xxx.xxx.xxx)
			$subject = str_replace('$PAGEEDITOR_QP', 'anonymous user ' . $pageeditorUser->getName(), $subject);
			$name    = $pageeditorUser->getName();
			$anon    = $name . ' (anonymous user)';
			$anonUrl = wfUrlencode( $name ) . ' (anonymous user)';
			
			$keys['$PAGEEDITOR_RAWURL']   = $anonUrl;
			$keys['%24PAGEEDITOR_RAWURL'] = $anonUrl;
			$keys['%24PAGEEDITORE']       = $anon;
			$keys['$PAGEEDITORE']         = $anon;
			$keys['$PAGEEDITOR']          = 'anonymous user ' . $name;
		} else {
			$name = $pageeditorUser->getName();
			$subject = str_replace('$PAGEEDITOR_QP', $pageeditor_qp, $subject);
			$keys['$PAGEEDITOR_RAWURL']   = wfUrlencode( $name );
			$keys['%24PAGEEDITOR_RAWURL'] = wfUrlencode( $name );
			$keys['%24PAGEEDITORE']       = $pageeditorUser->getTitleKey();
			$keys['$PAGEEDITORE']         = $pageeditorUser->getTitleKey();
			$keys['$PAGEEDITOR']          = $pageeditorUser->getName();
		}
		$body = strtr( $body, $keys );
	
		# now save this as the constant user-independent part of the message
		$this->to      = $to;
		$this->from    = $from;
		$this->replyto = $replyto;
		$this->subject = $subject;
		$this->body    = $body;
		return $this;
	}


	
	/**
	 * Does the per-user customizations to a notification e-mail (name,
	 * timestamp in proper timezone, etc) and sends it out.
	 * Returns true if the mail was sent successfully.
	 *
	 * @param User $watchingUser
	 * @param object $mail
	 * @param Article $article
	 * @return bool
	 * @access private
	 */
	function composeAndSendPersonalisedMail( $watchingUser, $mail, $article ) {
		global $wgLang;
	
		$to = $watchingUser->getName() . ' <' . $watchingUser->getEmail() . '>';
		$body = str_replace( '$WATCHINGUSERNAME', $watchingUser->getName() , $mail->body );
		$body = str_replace( '$WATCHINGUSEREMAILADDR', $watchingUser->getEmail(), $body );
	
		$timecorrection = $watchingUser->getOption( 'timecorrection' );
		if( !$timecorrection ) {
			# fail safe - I prefer it. TomGries
			$timecorrection = '00:00';
		}
		# $PAGEEDITDATE is the time and date of the page change
		# expressed in terms of individual local time of the notification
		# recipient, i.e. watching user
		$body = str_replace('$PAGEEDITDATE',
			$wgLang->timeanddate( $article->mTimestamp, true, false, $timecorrection, true),
			$body);
		
		$error = userMailer( $to, $mail->from, $mail->subject, $body, $mail->replyto );
		return ($error == '');
	}

} # end of class EmailNotification
?>
