<?php

/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Installer
 */

namespace MediaWiki\Installer;

use MediaWiki\Installer\Task\Task;
use MediaWiki\Status\Status;

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
			$status = $this->parent->performInstallation(
				$this->startStage( ... ),
				$this->endStage( ... )
			);
			$this->addHTML( "</ul>" );
			$continue = $status->isOK() ? 'continue' : false;
			$back = $status->isOK() ? false : 'back';
			$this->endForm( $continue, $back );
		} else {
			$this->startForm();
			$this->addHTML( $this->parent->getInfoBox( wfMessage( 'config-install-begin' )->plain() ) );
			$this->endForm();
		}

		return true;
	}

	/**
	 * @param Task $task
	 */
	public function startStage( $task ) {
		$this->addHTML( "<li>" . $task->getDescriptionMessage()->escaped() .
			wfMessage( 'ellipsis' )->escaped() );

		if ( $task->getName() == 'extension-tables' ) {
			$this->startLiveBox();
		}
	}

	/**
	 * @param Task $task
	 * @param Status $status
	 */
	public function endStage( $task, $status ) {
		if ( $task->getName() == 'extension-tables' ) {
			$this->endLiveBox();
		}
		$msg = $status->isOK() ? 'config-install-step-done' : 'config-install-step-failed';
		$html = wfMessage( 'word-separator' )->escaped() . wfMessage( $msg )->escaped();
		if ( !$status->isOK() ) {
			$html = "<span class=\"error\">$html</span>";
		}
		$this->addHTML( $html . "</li>\n" );
		if ( !$status->isGood() ) {
			$this->parent->showStatusBox( $status );
		}
	}

}
