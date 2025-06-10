<?php

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
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
