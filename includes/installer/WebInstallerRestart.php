<?php

/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Installer
 */

namespace MediaWiki\Installer;

use MediaWiki\Html\Html;

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
		$s = Html::warningBox( wfMessage( 'config-help-restart' )->parse(), 'config-warning-box' );
		$this->addHTML( $s );
		$this->endForm( 'restart' );

		return null;
	}

}
