<?php

class WebInstallerWelcome extends WebInstallerPage {

	/**
	 * @return string
	 */
	public function execute() {
		if ( $this->parent->request->wasPosted() ) {
			if ( $this->getVar( '_Environment' ) ) {
				return 'continue';
			}
		}
		$this->parent->output->addWikiText( wfMessage( 'config-welcome' )->plain() );
		$status = $this->parent->doEnvironmentChecks();
		if ( $status->isGood() ) {
			$this->parent->output->addHTML( '<span class="success-message">' .
				wfMessage( 'config-env-good' )->escaped() . '</span>' );
			$this->parent->output->addWikiText( wfMessage( 'config-copyright',
				SpecialVersion::getCopyrightAndAuthorList() )->plain() );
			$this->startForm();
			$this->endForm();
		} else {
			$this->parent->showStatusMessage( $status );
		}

		return '';
	}

}


