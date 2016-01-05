<?php

class WebInstallerRestart extends WebInstallerPage {

	/**
	 * @return string|null
	 */
	public function execute() {
		$r = $this->parent->request;
		if ( $r->wasPosted() ) {
			$really = $r->getVal( 'submit-restart' );
			if ( $really ) {
				$this->parent->reset();
			}

			return 'continue';
		}

		$this->startForm();
		$s = $this->parent->getWarningBox( wfMessage( 'config-help-restart' )->plain() );
		$this->addHTML( $s );
		$this->endForm( 'restart' );

		return null;
	}

}

