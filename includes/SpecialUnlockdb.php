<?php

function wfSpecialUnlockdb()
{
	global $wgUser, $wgOut, $action;

	if ( ! $wgUser->isDeveloper() ) {
		$wgOut->developerRequired();
		return;
	}
	$f = new DBUnlockForm();

	if ( "success" == $action ) { $f->showSuccess(); }
	else if ( "submit" == $action ) { $f->doSubmit(); }
	else { $f->showForm( "" ); }
}

class DBUnlockForm {

	function showForm( $err )
	{
		global $wgOut, $wgUser, $wgLang;
		global $wpLockConfirm;

		$wgOut->setPagetitle( wfMsg( "unlockdb" ) );
		$wgOut->addWikiText( wfMsg( "unlockdbtext" ) );

		if ( "" != $err ) {
			$wgOut->setSubtitle( wfMsg( "formerror" ) );
			$wgOut->addHTML( "<p><font color='red' size='+1'>{$err}</font>\n" );
		}
		$lc = wfMsg( "unlockconfirm" );
		$lb = wfMsg( "unlockbtn" );
		$action = wfLocalUrlE( $wgLang->specialPage( "Unlockdb" ),
		  "action=submit" );

		$wgOut->addHTML( "<p>
<form id=\"unlockdb\" method=\"post\" action=\"{$action}\">
<table border=0><tr>
<td align=right>
<input type=checkbox name=\"wpLockConfirm\">
</td>
<td align=\"left\">{$lc}<td>
</tr><tr>
<td>&nbsp;</td><td align=left>
<input type=submit name=\"wpLock\" value=\"{$lb}\">
</td></tr></table>
</form>\n" );

	}

	function doSubmit()
	{
		global $wgOut, $wgUser, $wgLang;
		global $wpLockConfirm, $wgReadOnlyFile;

		if ( ! $wpLockConfirm ) {
			$this->showForm( wfMsg( "locknoconfirm" ) );
			return;
		}
		if ( ! unlink( $wgReadOnlyFile ) ) {
			$wgOut->fileDeleteError( $wgReadOnlyFile );
			return;
		}
		$success = wfLocalUrl( $wgLang->specialPage( "Unlockdb" ),
		  "action=success" );
		$wgOut->redirect( $success );
	}

	function showSuccess()
	{
		global $wgOut, $wgUser;
		global $ip;

		$wgOut->setPagetitle( wfMsg( "unlockdb" ) );
		$wgOut->setSubtitle( wfMsg( "unlockdbsuccesssub" ) );
		$wgOut->addWikiText( wfMsg( "unlockdbsuccesstext", $ip ) );
	}
}

?>
