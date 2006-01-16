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
	if( $wgUser->isAllowed( 'siteadmin' ) ) {
		$form = new DBUnlockForm();
		if( $action == 'success' ) {
			$form->showSuccess();
		} else if( $action == 'submit' && wgRequest->wasPosted() && $wgUser->matchEditToken( $wgRequest->getVal( 'wpEditToken' ) ) ) {
			$form->doSubmit();
		} else {
			$form->showForm();
	} else {
		$wgOut->permissionRequired( 'siteadmin' );
		return;
	}
}

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class DBUnlockForm {

	function showForm( $error = false ) {
		global $wgOut, $wgUser;
		$wgOut->setPagetitle( wfMsg( 'unlockdb' ) );
		$wgOut->addWikiText( wfMsg( 'unlockdbtext' ) );
		
		if( $error ) {
			$wgOut->setSubtitle( wfMsg( 'formerror' ) );
			$wgOut->addHTML( '<p class="error">' . htmlspecialchars( $error ) . "</p>\n" );
		}
	
		$lc = htmlspecialchars( wfMsg( "unlockconfirm" ) );
		$lb = htmlspecialchars( wfMsg( "unlockbtn" ) );
		$titleObj = Title::makeTitle( NS_SPECIAL, "Unlockdb" );
		$action = $titleObj->escapeLocalURL( "action=submit" );
		$token = htmlspecialchars( $wgUser->editToken() );

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
<input type="hidden" name="wpEditToken" value="{$token}" />
</form>
END
);

	}

	function doSubmit() {
		global $wgOut, $wgUser, $wgRequest, $wgReadOnlyFile;

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
