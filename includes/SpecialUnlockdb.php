<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
function wfSpecialUnlockdb() {
	global $wgUser, $wgOut, $wgRequest;

	if ( ! $wgUser->isAllowed('siteadmin') ) {
		$wgOut->developerRequired();
		return;
	}
	$action = $wgRequest->getVal( 'action' );
	$f = new DBUnlockForm();

	if ( "success" == $action ) { $f->showSuccess(); }
	else if ( "submit" == $action && $wgRequest->wasPosted() ) { $f->doSubmit(); }
	else { $f->showForm( "" ); }
}

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class DBUnlockForm {
	function showForm( $err )
	{
		global $wgOut, $wgUser, $wgLang;

		$wgOut->setPagetitle( wfMsg( "unlockdb" ) );
		$wgOut->addWikiText( wfMsg( "unlockdbtext" ) );

		if ( "" != $err ) {
			$wgOut->setSubtitle( wfMsg( "formerror" ) );
			$wgOut->addHTML( "<p><font color='red' size='+1'>{$err}</font>\n" );
		}
		$lc = wfMsg( "unlockconfirm" );
		$lb = wfMsg( "unlockbtn" );
		$titleObj = Title::makeTitle( NS_SPECIAL, "Unlockdb" );
		$action = $titleObj->escapeLocalURL( "action=submit" );

		$wgOut->addHTML( <<<END

<form id="unlockdb" method="post" action="{$action}">
<table border="0">
	<tr>
		<td align="right">
			<input type="checkbox" name="wpLockConfirm" />
		</td>
		<td align="left">{$lc}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td align="left">
			<input type="submit" name="wpLock" value="{$lb}" />
		</td>
	</tr>
</table>
</form>
END
);

	}

	function doSubmit() {
		global $wgOut, $wgUser, $wgLang;
		global $wgRequest, $wgReadOnlyFile;

		$wpLockConfirm = $wgRequest->getCheck( 'wpLockConfirm' );
		if ( ! $wpLockConfirm ) {
			$this->showForm( wfMsg( "locknoconfirm" ) );
			return;
		}
		if ( @! unlink( $wgReadOnlyFile ) ) {
			$wgOut->fileDeleteError( $wgReadOnlyFile );
			return;
		}
		$titleObj = Title::makeTitle( NS_SPECIAL, "Unlockdb" );
		$success = $titleObj->getFullURL( "action=success" );
		$wgOut->redirect( $success );
	}

	function showSuccess() {
		global $wgOut, $wgUser;
		global $ip;

		$wgOut->setPagetitle( wfMsg( "unlockdb" ) );
		$wgOut->setSubtitle( wfMsg( "unlockdbsuccesssub" ) );
		$wgOut->addWikiText( wfMsg( "unlockdbsuccesstext", $ip ) );
	}
}

?>
