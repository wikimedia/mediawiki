<?php
/**
 *
 */

/**
 * Constructor
 */
function wfSpecialLockdb()
{
	global $wgUser, $wgOut, $wgRequest;

	if ( ! $wgUser->isDeveloper() ) {
		$wgOut->developerRequired();
		return;
	}
	$action = $wgRequest->getText( 'action' );
	$f = new DBLockForm();

	if ( "success" == $action ) { $f->showSuccess(); }
	else if ( "submit" == $action && $wgRequest->wasPosted() ) { $f->doSubmit(); }
	else { $f->showForm( "" ); }
}

/**
 *
 */
class DBLockForm {
	var $reason = '';
	
	function DBLockForm() {
		global $wgRequest;
		$this->reason = $wgRequest->getText( 'wpLockReason' );
	}
	
	function showForm( $err )
	{
		global $wgOut, $wgUser, $wgLang;

		$wgOut->setPagetitle( wfMsg( "lockdb" ) );
		$wgOut->addWikiText( wfMsg( "lockdbtext" ) );

		if ( "" != $err ) {
			$wgOut->setSubtitle( wfMsg( "formerror" ) );
			$wgOut->addHTML( "<p><font color='red' size='+1'>{$err}</font>\n" );
		}
		$lc = wfMsg( "lockconfirm" );
		$lb = wfMsg( "lockbtn" );
		$elr = wfMsg( "enterlockreason" );
		$titleObj = Title::makeTitle( NS_SPECIAL, "Lockdb" );
		$action = $titleObj->escapeLocalURL( "action=submit" );

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

	function doSubmit() {
		global $wgOut, $wgUser, $wgLang, $wgRequest;
		global $wgReadOnlyFile;

		if ( ! $wgRequest->getCheck( 'wpLockConfirm' ) ) {
			$this->showForm( wfMsg( "locknoconfirm" ) );
			return;
		}
		$fp = fopen( $wgReadOnlyFile, "w" );

		if ( false === $fp ) {
			$wgOut->fileNotFoundError( $wgReadOnlyFile );
			return;
		}
		fwrite( $fp, $this->reason );
		fwrite( $fp, "\n<p>(by " . $wgUser->getName() . " at " .
		  $wgLang->timeanddate( wfTimestampNow() ) . ")\n" );
		fclose( $fp );

		$titleObj = Title::makeTitle( NS_SPECIAL, "Lockdb" );
		$wgOut->redirect( $titleObj->getFullURL( "action=success" ) );
	}

	function showSuccess() {
		global $wgOut, $wgUser;

		$wgOut->setPagetitle( wfMsg( "lockdb" ) );
		$wgOut->setSubtitle( wfMsg( "lockdbsuccesssub" ) );
		$wgOut->addWikiText( wfMsg( "lockdbsuccesstext" ) );
	}
}

?>
