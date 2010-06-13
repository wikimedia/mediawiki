<?php

/**
 * Implements Special:Unlockdb
 *
 * @ingroup SpecialPage
 */
class SpecialUnlockdb extends SpecialPage {

	public function __construct() {
		parent::__construct( 'Unlockdb', 'siteadmin' );
	}

	public function execute( $par ) {
		global $wgUser, $wgOut, $wgRequest;

		$this->setHeaders();

		if( !$wgUser->isAllowed( 'siteadmin' ) ) {
			$wgOut->permissionRequired( 'siteadmin' );
			return;
		}

		$this->outputHeader();

		$action = $wgRequest->getVal( 'action' );

		if ( $action == 'success' ) {
			$this->showSuccess();
		} else if ( $action == 'submit' && $wgRequest->wasPosted() &&
			$wgUser->matchEditToken( $wgRequest->getVal( 'wpEditToken' ) ) ) {
			$this->doSubmit();
		} else {
			$this->showForm();
		}
	}

	private function showForm( $err = '' ) {
		global $wgOut, $wgUser;

		global $wgReadOnlyFile;
		if( !file_exists( $wgReadOnlyFile ) ) {
			$wgOut->addWikiMsg( 'databasenotlocked' );
			return;
		}

		$wgOut->addWikiMsg( 'unlockdbtext' );

		if ( $err != '' ) {
			$wgOut->setSubtitle( wfMsg( 'formerror' ) );
			$wgOut->addHTML( '<p class="error">' . htmlspecialchars( $err ) . "</p>\n" );
		}

		$wgOut->addHTML(
			Html::openElement( 'form', array( 'id' => 'unlockdb', 'method' => 'POST',
				'action' => $this->getTitle()->getLocalURL( 'action=submit' ) ) ) . "
<table>
	<tr>
		" . Html::openElement( 'td', array( 'style' => 'text-align:right' ) ) . "
			" . Html::input( 'wpLockConfirm', null, 'checkbox' ) . "
		</td>
		" . Html::openElement( 'td', array( 'style' => 'text-align:left' ) ) .
			wfMsgHtml( 'unlockconfirm' ) . "</td>
	</tr>
	<tr>
		<td>&#160;</td>
		" . Html::openElement( 'td', array( 'style' => 'text-align:left' ) ) . "
			" . Html::input( 'wpLock', wfMsg( 'unlockbtn' ), 'submit' ) . "
		</td>
	</tr>
</table>\n" .
			Html::hidden( 'wpEditToken', $wgUser->editToken() ) . "\n" .
			Html::closeElement( 'form' )
		);

	}

	private function doSubmit() {
		global $wgOut, $wgRequest, $wgReadOnlyFile;

		$wpLockConfirm = $wgRequest->getCheck( 'wpLockConfirm' );
		if ( !$wpLockConfirm ) {
			$this->showForm( wfMsg( 'locknoconfirm' ) );
			return;
		}
		if ( @!unlink( $wgReadOnlyFile ) ) {
			$wgOut->showFileDeleteError( $wgReadOnlyFile );
			return;
		}

		$wgOut->redirect( $this->getTitle()->getFullURL( 'action=success' ) );
	}

	private function showSuccess() {
		global $wgOut;

		$wgOut->setSubtitle( wfMsg( 'unlockdbsuccesssub' ) );
		$wgOut->addWikiMsg( 'unlockdbsuccesstext' );
	}
}
