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
 * @ingroup Deployment
 */

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

		// wgDBtype is generally valid here because otherwise the previous page
		// (connect) wouldn't have declared its happiness
		$type = $this->getVar( 'wgDBtype' );
		$installer = $this->parent->getDBInstaller( $type );

		if ( !$installer->needsUpgrade() ) {
			return 'skip';
		}

		if ( $this->parent->request->wasPosted() ) {
			$installer->preUpgrade();

			$this->startLiveBox();
			$result = $installer->doUpgrade();
			$this->endLiveBox();

			if ( $result ) {
				// If they're going to possibly regenerate LocalSettings, we
				// need to create the upgrade/secret keys. T28481
				if ( !$this->getVar( '_ExistingDBSettings' ) ) {
					$this->parent->generateKeys();
				}
				$this->setVar( '_UpgradeDone', true );
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
			wfMessage( 'config-can-upgrade', $GLOBALS['wgVersion'] )->plain() ) );
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
		$this->addHTML(
			$this->parent->getInfoBox(
				wfMessage( $msg,
					$this->getVar( 'wgServer' ) .
					$this->getVar( 'wgScriptPath' ) . '/index.php'
				)->plain(), 'tick-32.png'
			)
		);
		$this->parent->restoreLinkPopups();
		$this->endForm( $regenerate ? 'regenerate' : false, false );
	}

}
