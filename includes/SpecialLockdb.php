<?php

function wfSpecialLockdb()
{
	global $wgUser, $wgOut, $action;

	if ( ! $wgUser->isDeveloper() ) {
		$wgOut->developerRequired();
		return;
	}
	$fields = array( "wpLockReason" );
	wfCleanFormFields( $fields );

	$f = new DBLockForm();

	if ( "success" == $action ) { $f->showSuccess(); }
	else if ( "submit" == $action ) { $f->doSubmit(); }
	else { $f->showForm( "" ); }
}

class DBLockForm {

	function showForm( $err )
	{
		global $wgOut, $wgUser, $wgLang;
		global $wpLockConfirm;

		$wgOut->setPagetitle( wfMsg( "lockdb" ) );
		$wgOut->addWikiText( wfMsg( "lockdbtext" ) );

		if ( "" != $err ) {
			$wgOut->setSubtitle( wfMsg( "formerror" ) );
			$wgOut->addHTML( "<p><font color='red' size='+1'>{$err}</font>\n" );
		}
		$lc = wfMsg( "lockconfirm" );
		$lb = wfMsg( "lockbtn" );
		$elr = wfMsg( "enterlockreason" );
		$action = wfLocalUrlE( $wgLang->specialPage( "Lockdb" ),
		  "action=submit" );

		$wgOut->addHTML( "<p>
<form id=\"lockdb\" method=\"post\" action=\"{$action}\">
{$elr}:
<textarea name=\"wpLockReason\" rows=10 cols=60 wrap=virtual>
</textarea>
<table border=0><tr>
<td align=right>
<input type=checkbox name=\"wpLockConfirm\">
</td>
<td align=left>{$lc}<td>
</tr><tr>
<td>&nbsp;</td><td align=left>
<input type=submit name=\"wpLock\" value=\"{$lb}\">
</td></tr></table>
</form>\n" );

	}

	function doSubmit()
	{
		global $wgOut, $wgUser, $wgLang;
		global $wpLockConfirm, $wpLockReason, $wgReadOnlyFile;

		if ( ! $wpLockConfirm ) {
			$this->showForm( wfMsg( "locknoconfirm" ) );
			return;
		}
		$fp = fopen( $wgReadOnlyFile, "w" );

		if ( false === $fp ) {
			$wgOut->fileNotFoundError( $wgReadOnlyFile );
			return;
		}
		fwrite( $fp, $wpLockReason );
		fwrite( $fp, "\n<p>(by " . $wgUser->getName() . " at " .
		  $wgLang->timeanddate( wfTimestampNow() ) . ")\n" );
		fclose( $fp );

		$success = wfLocalUrl( $wgLang->specialPage( "Lockdb" ),
		  "action=success" );
		$wgOut->redirect( $success );
	}

	function showSuccess()
	{
		global $wgOut, $wgUser;
		global $ip;

		$wgOut->setPagetitle( wfMsg( "lockdb" ) );
		$wgOut->setSubtitle( wfMsg( "lockdbsuccesssub" ) );
		$wgOut->addWikiText( wfMsg( "lockdbsuccesstext", $ip ) );
	}
}

?>
