<?php
/**
 *
 * @addtogroup SpecialPage
 */

require_once('UserMailer.php');

/**
 * @todo document
 */
function wfSpecialEmailuser( $par ) {
	global $wgUser, $wgOut, $wgRequest, $wgEnableEmail, $wgEnableUserEmail;

	if( !( $wgEnableEmail && $wgEnableUserEmail ) ) {
		$wgOut->showErrorPage( "nosuchspecialpage", "nospecialpagetext" );
		return;
	}

	if( !$wgUser->canSendEmail() ) {
		wfDebug( "User can't send.\n" );
		$wgOut->showErrorPage( "mailnologin", "mailnologintext" );
		return;
	}

	$action = $wgRequest->getVal( 'action' );
	$target = isset($par) ? $par : $wgRequest->getVal( 'target' );
	if ( "" == $target ) {
		wfDebug( "Target is empty.\n" );
		$wgOut->showErrorPage( "notargettitle", "notargettext" );
		return;
	}

	$nt = Title::newFromURL( $target );
	if ( is_null( $nt ) ) {
		wfDebug( "Target is invalid title.\n" );
		$wgOut->showErrorPage( "notargettitle", "notargettext" );
		return;
	}

	$nu = User::newFromName( $nt->getText() );
	if( is_null( $nu ) || !$nu->canReceiveEmail() ) {
		wfDebug( "Target is invalid user or can't receive.\n" );
		$wgOut->showErrorPage( "noemailtitle", "noemailtext" );
		return;
	}

	if ( $wgUser->isBlockedFromEmailUser() ) {
		// User has been blocked from sending e-mail. Show the std blocked form.
		wfDebug( "User is blocked from sending e-mail.\n" );
		$wgOut->blockedPage();
		return;
	}

	$f = new EmailUserForm( $nu );

	if ( "success" == $action ) {
		$f->showSuccess( $nu );
	} else if ( "submit" == $action && $wgRequest->wasPosted() &&
				$wgUser->matchEditToken( $wgRequest->getVal( 'wpEditToken' ) ) ) 
	{
		# Check against the rate limiter
		if( $wgUser->pingLimiter( 'emailuser' ) ) {
			$wgOut->rateLimited();
			return;
		}

		$f->doSubmit();
	} else {
		$f->showForm();
	}
}

/**
 * Implements the Special:Emailuser web interface, and invokes userMailer for sending the email message.
 * @addtogroup SpecialPage
 */
class EmailUserForm {

	var $target;
	var $text, $subject;
	var $cc_me;     // Whether user requested to be sent a separate copy of their email.

	/**
	 * @param User $target
	 */
	function EmailUserForm( $target ) {
		global $wgRequest;
		$this->target = $target;
		$this->text = $wgRequest->getText( 'wpText' );
		$this->subject = $wgRequest->getText( 'wpSubject' );
		$this->cc_me = $wgRequest->getBool( 'wpCCMe' );
	}

	function showForm() {
		global $wgOut, $wgUser;

		$wgOut->setPagetitle( wfMsg( "emailpage" ) );
		$wgOut->addWikiText( wfMsg( "emailpagetext" ) );

		if ( $this->subject === "" ) {
			$this->subject = wfMsg( "defemailsubject" );
		}

		$emf = wfMsg( "emailfrom" );
		$sender = $wgUser->getName();
		$emt = wfMsg( "emailto" );
		$rcpt = $this->target->getName();
		$emr = wfMsg( "emailsubject" );
		$emm = wfMsg( "emailmessage" );
		$ems = wfMsg( "emailsend" );
		$emc = wfMsg( "emailccme" );
		$encSubject = htmlspecialchars( $this->subject );

		$titleObj = SpecialPage::getTitleFor( "Emailuser" );
		$action = $titleObj->escapeLocalURL( "target=" .
			urlencode( $this->target->getName() ) . "&action=submit" );
		$token = htmlspecialchars( $wgUser->editToken() );

		$wgOut->addHTML( "
<form id=\"emailuser\" method=\"post\" action=\"{$action}\">
<table border='0' id='mailheader'><tr>
<td align='right'>{$emf}:</td>
<td align='left'><strong>" . htmlspecialchars( $sender ) . "</strong></td>
</tr><tr>
<td align='right'>{$emt}:</td>
<td align='left'><strong>" . htmlspecialchars( $rcpt ) . "</strong></td>
</tr><tr>
<td align='right'>{$emr}:</td>
<td align='left'>
<input type='text' size='60' maxlength='200' name=\"wpSubject\" value=\"{$encSubject}\" />
</td>
</tr>
</table>
<span id='wpTextLabel'><label for=\"wpText\">{$emm}:</label><br /></span>
<textarea id=\"wpText\" name=\"wpText\" rows='20' cols='80' style=\"width: 100%;\">" . htmlspecialchars( $this->text ) .
"</textarea>
" . wfCheckLabel( $emc, 'wpCCMe', 'wpCCMe', $wgUser->getBoolOption( 'ccmeonemails' ) ) . "<br />
<input type='submit' name=\"wpSend\" value=\"{$ems}\" />
<input type='hidden' name='wpEditToken' value=\"$token\" />
</form>\n" );

	}

	function doSubmit() {
		global $wgOut, $wgUser;

		$to = new MailAddress( $this->target );
		$from = new MailAddress( $wgUser );
		$subject = $this->subject;

		if( wfRunHooks( 'EmailUser', array( &$to, &$from, &$subject, &$this->text ) ) ) {

			$mailResult = userMailer( $to, $from, $subject, $this->text );

			if( WikiError::isError( $mailResult ) ) {
				$wgOut->addHTML( wfMsg( "usermailererror" ) . $mailResult);
			} else {
				
				// if the user requested a copy of this mail, do this now,
				// unless they are emailing themselves, in which case one copy of the message is sufficient.
				if ($this->cc_me && $to != $from) {
					$cc_subject = wfMsg('emailccsubject', $this->target->getName(), $subject);
					if( wfRunHooks( 'EmailUser', array( &$from, &$from, &$cc_subject, &$this->text ) ) ) {
						$ccResult = userMailer( $from, $from, $cc_subject, $this->text );
						if( WikiError::isError( $ccResult ) ) {
							// At this stage, the user's CC mail has failed, but their 
							// original mail has succeeded. It's unlikely, but still, what to do?
							// We can either show them an error, or we can say everything was fine,
							// or we can say we sort of failed AND sort of succeeded. Of these options, 
							// simply saying there was an error is probably best.
							$wgOut->addHTML( wfMsg( "usermailererror" ) . $ccResult);
							return;
						}
					}
				}
				
				$titleObj = SpecialPage::getTitleFor( "Emailuser" );
				$encTarget = wfUrlencode( $this->target->getName() );
				$wgOut->redirect( $titleObj->getFullURL( "target={$encTarget}&action=success" ) );
				wfRunHooks( 'EmailUserComplete', array( $to, $from, $subject, $this->text ) );
			}
		}
	}

	function showSuccess( &$user ) {
		global $wgOut;

		$wgOut->setPagetitle( wfMsg( "emailsent" ) );
		$wgOut->addHTML( wfMsg( "emailsenttext" ) );

		$wgOut->returnToMain( false, $user->getUserPage() );
	}
}
