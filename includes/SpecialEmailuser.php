<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
require_once('UserMailer.php');

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

	$f = new EmailUserForm( $nu );

	if ( "success" == $action ) {
		$f->showSuccess();
	} else if ( "submit" == $action && $wgRequest->wasPosted() &&
		$wgUser->matchEditToken( $wgRequest->getVal( 'wpEditToken' ) ) ) {
		$f->doSubmit();
	} else {
		$f->showForm();
	}
}

/**
 * @todo document
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class EmailUserForm {

	var $target;
	var $text, $subject;

	/**
	 * @param User $target
	 */
	function EmailUserForm( $target ) {
		global $wgRequest;
		$this->target = $target;
		$this->text = $wgRequest->getText( 'wpText' );
		$this->subject = $wgRequest->getText( 'wpSubject' );
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
		$encSubject = htmlspecialchars( $this->subject );

		$titleObj = Title::makeTitle( NS_SPECIAL, "Emailuser" );
		$action = $titleObj->escapeLocalURL( "target=" .
			urlencode( $this->target->getName() ) . "&action=submit" );
		$token = $wgUser->editToken();

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
<textarea name=\"wpText\" rows='20' cols='80' wrap='virtual' style=\"width: 100%;\">" . htmlspecialchars( $this->text ) .
"</textarea>
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
				$titleObj = Title::makeTitle( NS_SPECIAL, "Emailuser" );
				$encTarget = wfUrlencode( $this->target->getName() );
				$wgOut->redirect( $titleObj->getFullURL( "target={$encTarget}&action=success" ) );
				wfRunHooks( 'EmailUserComplete', array( $to, $from, $subject, $this->text ) );
			}
		}
	}

	function showSuccess() {
		global $wgOut;

		$wgOut->setPagetitle( wfMsg( "emailsent" ) );
		$wgOut->addHTML( wfMsg( "emailsenttext" ) );

		$wgOut->returnToMain( false );
	}
}
?>
