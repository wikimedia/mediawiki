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
		$wgOut->errorpage( "nosuchspecialpage", "nospecialpagetext" );
		return;
	}
	
	if ( 0 == $wgUser->getID() ||
		( !$wgUser->isValidEmailAddr( $wgUser->getEmail() ) ) ) {
		$wgOut->errorpage( "mailnologin", "mailnologintext" );
		return;
	}
	
	$action = $wgRequest->getVal( 'action' );
	if( empty( $par ) ) {
		$target = $wgRequest->getVal( 'target' );
	} else {
		$target = $par;
	}
	if ( "" == $target ) {
		$wgOut->errorpage( "notargettitle", "notargettext" );
		return;
	}
	$nt = Title::newFromURL( $target );
	if ( is_null( $nt ) ) {
		$wgOut->errorpage( "notargettitle", "notargettext" );
		return;
	}
	$nu = User::newFromName( $nt->getText() );

	if ( 0 == $nu->getID() ) {
		$wgOut->errorpage( "noemailtitle", "noemailtext" );
		return;
	}

	$address = $nu->getEmail();

	if ( ( !$nu->isValidEmailAddr( $address ) ) ||
	     ( 1 == $nu->getOption( "disablemail" ) ) ||
	     ( 0 == $nu->getEmailauthenticationtimestamp() ) ) {
		$wgOut->errorpage( "noemailtitle", "noemailtext" );
		return;
	}

	$f = new EmailUserForm( $nu->getName() . " <{$address}>", $target );

	if ( "success" == $action ) { $f->showSuccess(); }
	else if ( "submit" == $action && $wgRequest->wasPosted() ) { $f->doSubmit(); }
	else { $f->showForm( "" ); }
}

/**
 * @todo document
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class EmailUserForm {

	var $mAddress;
	var $target;
	var $text, $subject;

	function EmailUserForm( $addr, $target ) {
		global $wgRequest;
		$this->mAddress = $addr;
		$this->target = $target;
		$this->text = $wgRequest->getText( 'wpText' );
		$this->subject = $wgRequest->getText( 'wpSubject' );
	}

	function showForm( $err ) {
		global $wgOut, $wgUser, $wgLang;

		$wgOut->setPagetitle( wfMsg( "emailpage" ) );
		$wgOut->addWikiText( wfMsg( "emailpagetext" ) );

		if ( $this->subject === "" ) { 
			$this->subject = wfMsg( "defemailsubject" ); 
		}

		$emf = wfMsg( "emailfrom" );
		$sender = $wgUser->getName();
		$emt = wfMsg( "emailto" );
		$rcpt = str_replace( "_", " ", $this->target );
		$emr = wfMsg( "emailsubject" );
		$emm = wfMsg( "emailmessage" );
		$ems = wfMsg( "emailsend" );
		$encSubject = htmlspecialchars( $this->subject );
		
		$titleObj = Title::makeTitle( NS_SPECIAL, "Emailuser" );
		$action = $titleObj->escapeLocalURL( "target={$this->target}&action=submit" );

		if ( "" != $err ) {
			$wgOut->setSubtitle( wfMsg( "formerror" ) );
			$wgOut->addHTML( "<p><font color='red' size='+1'>{$err}</font></p>\n" );
		}
		$wgOut->addHTML( "
<form id=\"emailuser\" method=\"post\" action=\"{$action}\">
<table border='0'><tr>
<td align='right'>{$emf}:</td>
<td align='left'><strong>{$sender}</strong></td>
</tr><tr>
<td align='right'>{$emt}:</td>
<td align='left'><strong>{$rcpt}</strong></td>
</tr><tr>
<td align='right'>{$emr}:</td>
<td align='left'>
<input type='text' name=\"wpSubject\" value=\"{$encSubject}\" />
</td>
</tr><tr>
<td align='right'>{$emm}:</td>
<td align='left'>
<textarea name=\"wpText\" rows='10' cols='60' wrap='virtual'>" . htmlspecialchars( $this->text ) .
"</textarea>
</td></tr><tr>
<td>&nbsp;</td><td align='left'>
<input type='submit' name=\"wpSend\" value=\"{$ems}\" />
</td></tr></table>
</form>\n" );

	}

	function doSubmit() {
		global $wgOut, $wgUser, $wgLang, $wgOutputEncoding;
	    
		$from = wfQuotedPrintable( $wgUser->getName() ) . " <" . $wgUser->getEmail() . ">";
		$subject = wfQuotedPrintable( $this->subject );
		
		if (wfRunHooks('EmailUser', $this->mAddress, $from, $subject, $this->text)) {
			
			$mailResult = userMailer( $this->mAddress, $from, $subject, $this->text );
			
			if (!$mailResult) {
				$titleObj = Title::makeTitle( NS_SPECIAL, "Emailuser" );
				$encTarget = wfUrlencode( $this->target );
				$wgOut->redirect( $titleObj->getFullURL( "target={$encTarget}&action=success" ) );
				wfRunHooks('EmailUserComplete', $this->mAddress, $from, $subject, $this->text);
			} else {
			  $wgOut->addHTML( wfMsg( "usermailererror" ) . $mailResult);
			}
		}
	}

	function showSuccess() {
		global $wgOut, $wgUser;

		$wgOut->setPagetitle( wfMsg( "emailsent" ) );
		$wgOut->addHTML( wfMsg( "emailsenttext" ) );

		$wgOut->returnToMain( false );
	}
}
?>
