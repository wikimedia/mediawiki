<?php

/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Installer
 */

namespace MediaWiki\Installer;

use MediaWiki\Specials\SpecialVersion;

class WebInstallerWelcome extends WebInstallerPage {

	/**
	 * @return string
	 */
	public function execute() {
		if ( $this->parent->request->wasPosted() && $this->getVar( '_Environment' ) ) {
			return 'continue';
		}
		$this->parent->output->addWikiTextAsInterface( wfMessage( 'config-welcome' )->plain() );
		$status = $this->parent->doEnvironmentChecks();
		if ( $status->isGood() ) {
			$this->parent->showSuccess( 'config-env-good' );
			$this->parent->output->addWikiTextAsInterface(
				wfMessage( 'config-welcome-section-copyright',
					SpecialVersion::getCopyrightAndAuthorList(),
					$this->parent->getVar( 'wgServer' ) .
						$this->parent->getDocUrl( 'Copying' )
				)->plain()
			);
			$this->startForm();
			$this->endForm();
		} else {
			$this->parent->showStatusMessage( $status );
		}

		return '';
	}

}
