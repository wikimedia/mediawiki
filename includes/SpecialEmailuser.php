<?php

require_once('UserMailer.php');

function wfSpecialEmailuser( $par ) {
	global $wgUser, $wgOut, $action, $target;

	if ( 0 == $wgUser->getID() ||
		( false === strpos( $wgUser->getEmail(), "@" ) ) ) {
		$wgOut->errorpage( "mailnologin", "mailnologintext" );
		return;
	}
	if( empty( $par ) ) {
		$target = wfCleanQueryVar( $target );
	} else {
		$target = $par;
	}
	if ( "" == $target ) {
		$wgOut->errorpage( "notargettitle", "notargettext" );
		return;
	}
	$nt = Title::newFromURL( $target );
	$nu = User::newFromName( $nt->getText() );
	$id = $nu->idForName();

	if ( 0 == $id ) {
		$wgOut->errorpage( "noemailtitle", "noemailtext" );
		return;
	}
	$nu->setID( $id );
	$address = $nu->getEmail();

	if ( ( false === strpos( $address, "@" ) ) ||
	  ( 1 == $nu->getOption( "disablemail" ) ) ) {
		$wgOut->errorpage( "noemailtitle", "noemailtext" );
		return;
	}
	$fields = array( "wpSubject", "wpText" );
	wfCleanFormFields( $fields );

	$f = new EmailUserForm( $nu->getName() . " <{$address}>" );

	if ( "success" == $action ) { $f->showSuccess(); }
	else if ( "submit" == $action ) { $f->doSubmit(); }
	else { $f->showForm( "" ); }
}

class EmailUserForm {

	var $mAddress;

	function EmailUserForm( $addr )
	{
		$this->mAddress = $addr;
	}

	function showForm( $err )
	{
		global $wgOut, $wgUser, $wgLang;
		global $wpSubject, $wpText, $target;

		$wgOut->setPagetitle( wfMsg( "emailpage" ) );
		$wgOut->addWikiText( wfMsg( "emailpagetext" ) );

		if ( ! $wpSubject ) { $wpSubject = wfMsg( "defemailsubject" ); }

		$emf = wfMsg( "emailfrom" );
		$sender = $wgUser->getName();
		$emt = wfMsg( "emailto" );
		$rcpt = str_replace( "_", " ", urldecode( $target ) );
		$emr = wfMsg( "emailsubject" );
		$emm = wfMsg( "emailmessage" );
		$ems = wfMsg( "emailsend" );

		$action = wfLocalUrlE( $wgLang->specialPage( "Emailuser" ),
		  "target={$target}&action=submit" );

		if ( "" != $err ) {
			$wgOut->setSubtitle( wfMsg( "formerror" ) );
			$wgOut->addHTML( "<p><font color='red' size='+1'>{$err}</font>\n" );
		}
		$wgOut->addHTML( "<p>
<form id=\"emailuser\" method=\"post\" action=\"{$action}\">
<table border=0><tr>
<td align=right>{$emf}:</td>
<td align=left><strong>{$sender}</strong></td>
</tr><tr>
<td align=right>{$emt}:</td>
<td align=left><strong>{$rcpt}</strong></td>
</tr><tr>
<td align=right>{$emr}:</td>
<td align=left>
<input type=text name=\"wpSubject\" value=\"{$wpSubject}\">
</td>
</tr><tr>
<td align=right>{$emm}:</td>
<td align=left>
<textarea name=\"wpText\" rows=10 cols=60 wrap=virtual>
{$wpText}
</textarea>
</td></tr><tr>
<td>&nbsp;</td><td align=left>
<input type=submit name=\"wpSend\" value=\"{$ems}\">
</td></tr></table>
</form>\n" );

	}

	function doSubmit()
	{
		global $wgOut, $wgUser, $wgLang, $wgOutputEncoding;
		global $wpSubject, $wpText, $target;
	    
		$from = wfQuotedPrintable( $wgUser->getName() ) . " <" . $wgUser->getEmail() . ">";
		
		$mailResult = userMailer( $this->mAddress, $from, wfQuotedPrintable( $wpSubject ), $wpText );

		if (! $mailResult)
		{
			$success = wfLocalUrl( $wgLang->specialPage( "Emailuser" ),
		  		"target={$target}&action=success" );
			$wgOut->redirect( $success );
		}
		else
			$wgOut->addHTML( wfMsg( "usermailererror" ) . $mailResult);
	}

	function showSuccess()
	{
		global $wgOut, $wgUser;

		$wgOut->setPagetitle( wfMsg( "emailsent" ) );
		$wgOut->addHTML( wfMsg( "emailsenttext" ) );

		$wgOut->returnToMain( false );
	}
}
?>
