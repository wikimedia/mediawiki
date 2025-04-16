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

class WebInstallerUpgrade extends WebInstallerPage {

	/**
	 * @return bool Always true.
	 */
	public function isSlow() {
		return true;
	}

	/**
	 * @return string|null
	 */
	public function execute() {
		if ( $this->getVar( '_UpgradeDone' ) ) {
			// Allow regeneration of LocalSettings.php, unless we are working
			// from a pre-existing LocalSettings.php file and we want to avoid
			// leaking its contents
			if ( $this->parent->request->wasPosted() && !$this->getVar( '_ExistingDBSettings' ) ) {
				// Done message acknowledged
				return 'continue';
			}
			// Back button click
			// Show the done message again
			// Make them click back again if they want to do the upgrade again
			$this->showDoneMessage();

			return 'output';
		}

		if ( !$this->parent->needsUpgrade() ) {
			return 'skip';
		}

		if ( $this->parent->request->wasPosted() ) {
			$this->startLiveBox();
			$result = $this->parent->doUpgrade();
			$this->endLiveBox();

			if ( $result ) {
				$this->showDoneMessage();
			} else {
				$this->startForm();
				$this->parent->showError( 'config-upgrade-error' );
				$this->endForm();
			}

			return 'output';
		}

		$this->startForm();
		$this->addHTML( $this->parent->getInfoBox(
			wfMessage( 'config-can-upgrade', MW_VERSION )->plain() ) );
		$this->endForm();

		return null;
	}

	public function showDoneMessage() {
		$this->startForm();
		$regenerate = !$this->getVar( '_ExistingDBSettings' );
		if ( $regenerate ) {
			$msg = 'config-upgrade-done';
		} else {
			$msg = 'config-upgrade-done-no-regenerate';
		}
		$this->parent->disableLinkPopups();
		$this->parent->showSuccess(
			$msg,
			$this->getVar( 'wgServer' ) . $this->getVar( 'wgScriptPath' ) . '/index.php'
		);
		$this->parent->restoreLinkPopups();
		$this->endForm( $regenerate ? 'regenerate' : false, false );
	}

}
