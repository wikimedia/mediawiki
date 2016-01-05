<?php

class WebInstallerInstall extends WebInstallerPage {

	/**
	 * @return bool Always true.
	 */
	public function isSlow() {
		return true;
	}

	/**
	 * @return string|bool
	 */
	public function execute() {
		if ( $this->getVar( '_UpgradeDone' ) ) {
			return 'skip';
		} elseif ( $this->getVar( '_InstallDone' ) ) {
			return 'continue';
		} elseif ( $this->parent->request->wasPosted() ) {
			$this->startForm();
			$this->addHTML( "<ul>" );
			$results = $this->parent->performInstallation(
				array( $this, 'startStage' ),
				array( $this, 'endStage' )
			);
			$this->addHTML( "</ul>" );
			// PerformInstallation bails on a fatal, so make sure the last item
			// completed before giving 'next.' Likewise, only provide back on failure
			$lastStep = end( $results );
			$continue = $lastStep->isOK() ? 'continue' : false;
			$back = $lastStep->isOK() ? false : 'back';
			$this->endForm( $continue, $back );
		} else {
			$this->startForm();
			$this->addHTML( $this->parent->getInfoBox( wfMessage( 'config-install-begin' )->plain() ) );
			$this->endForm();
		}

		return true;
	}

	/**
	 * @param string $step
	 */
	public function startStage( $step ) {
		// Messages: config-install-database, config-install-tables, config-install-interwiki,
		// config-install-stats, config-install-keys, config-install-sysop, config-install-mainpage
		$this->addHTML( "<li>" . wfMessage( "config-install-$step" )->escaped() .
			wfMessage( 'ellipsis' )->escaped() );

		if ( $step == 'extension-tables' ) {
			$this->startLiveBox();
		}
	}

	/**
	 * @param string $step
	 * @param Status $status
	 */
	public function endStage( $step, $status ) {
		if ( $step == 'extension-tables' ) {
			$this->endLiveBox();
		}
		$msg = $status->isOk() ? 'config-install-step-done' : 'config-install-step-failed';
		$html = wfMessage( 'word-separator' )->escaped() . wfMessage( $msg )->escaped();
		if ( !$status->isOk() ) {
			$html = "<span class=\"error\">$html</span>";
		}
		$this->addHTML( $html . "</li>\n" );
		if ( !$status->isGood() ) {
			$this->parent->showStatusBox( $status );
		}
	}

}

