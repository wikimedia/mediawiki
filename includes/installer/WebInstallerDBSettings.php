<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Installer
 */

namespace MediaWiki\Installer;

class WebInstallerDBSettings extends WebInstallerPage {

	/**
	 * @return string|null
	 */
	public function execute() {
		$installer = $this->parent->getDBInstaller( $this->getVar( 'wgDBtype' ) );
		$form = $installer->getSettingsForm( $this->parent );

		$r = $this->parent->request;
		if ( $r->wasPosted() ) {
			$status = $form->submit();
			if ( $status === false ) {
				return 'skip';
			} elseif ( $status->isGood() ) {
				return 'continue';
			} else {
				$this->parent->showStatusBox( $status );
			}
		}

		$formHtml = $form->getHtml();
		if ( $formHtml === false ) {
			return 'skip';
		}

		$this->startForm();
		$this->addHTML( $formHtml );
		$this->endForm();

		return null;
	}

}
