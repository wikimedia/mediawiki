<?php

class WebInstallerDBSettings extends WebInstallerPage {

	/**
	 * @return string|null
	 */
	public function execute() {
		$installer = $this->parent->getDBInstaller( $this->getVar( 'wgDBtype' ) );

		$r = $this->parent->request;
		if ( $r->wasPosted() ) {
			$status = $installer->submitSettingsForm();
			if ( $status === false ) {
				return 'skip';
			} elseif ( $status->isGood() ) {
				return 'continue';
			} else {
				$this->parent->showStatusBox( $status );
			}
		}

		$form = $installer->getSettingsForm();
		if ( $form === false ) {
			return 'skip';
		}

		$this->startForm();
		$this->addHTML( $form );
		$this->endForm();

		return null;
	}

}


