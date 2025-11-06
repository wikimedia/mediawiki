<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Installer
 */

namespace MediaWiki\Installer;

class WebInstallerComplete extends WebInstallerPage {

	/** @inheritDoc */
	public function execute() {
		// Pop up a dialog box, to make it difficult for the user to forget
		// to download the file
		$lsPath = $this->parent->getUrl( [ 'localsettings' => 1 ] );
		$lsUrl = $this->parent->getDefaultServer() . $lsPath;
		$this->parent->request->response()->header( "Refresh: 0;url=$lsPath" );
		$this->startForm();
		$this->parent->disableLinkPopups();
		$location = $this->parent->getLocalSettingsLocation();
		$msg = 'config-install-done';
		if ( $location !== false ) {
			$msg = 'config-install-done-path';
		}
		$this->parent->showSuccess( $msg,
			$lsUrl,
			$this->getVar( 'wgServer' ) .
				$this->getVar( 'wgScriptPath' ) . '/index.php',
			"[$lsUrl " . wfMessage( 'config-download-localsettings' )->plain() . ']',
			$location ?: ''
		);
		$this->addHTML( $this->parent->getInfoBox(
			wfMessage( 'config-extension-link' )->plain() ) );

		$this->parent->restoreLinkPopups();
		$this->endForm( false, false );
		return '';
	}

}
