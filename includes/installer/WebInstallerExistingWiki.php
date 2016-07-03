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

class WebInstallerExistingWiki extends WebInstallerPage {

	/**
	 * @return string
	 */
	public function execute() {
		// If there is no LocalSettings.php, continue to the installer welcome page
		$vars = Installer::getExistingLocalSettings();
		if ( !$vars ) {
			return 'skip';
		}

		// Check if the upgrade key supplied to the user has appeared in LocalSettings.php
		if ( $vars['wgUpgradeKey'] !== false
			&& $this->getVar( '_UpgradeKeySupplied' )
			&& $this->getVar( 'wgUpgradeKey' ) === $vars['wgUpgradeKey']
		) {
			// It's there, so the user is authorized
			$status = $this->handleExistingUpgrade( $vars );
			if ( $status->isOK() ) {
				return 'skip';
			} else {
				$this->startForm();
				$this->parent->showStatusBox( $status );
				$this->endForm( 'continue' );

				return 'output';
			}
		}

		// If there is no $wgUpgradeKey, tell the user to add one to LocalSettings.php
		if ( $vars['wgUpgradeKey'] === false ) {
			if ( $this->getVar( 'wgUpgradeKey', false ) === false ) {
				$secretKey = $this->getVar( 'wgSecretKey' ); // preserve $wgSecretKey
				$this->parent->generateKeys();
				$this->setVar( 'wgSecretKey', $secretKey );
				$this->setVar( '_UpgradeKeySupplied', true );
			}
			$this->startForm();
			$this->addHTML( $this->parent->getInfoBox(
				wfMessage( 'config-upgrade-key-missing', "<pre dir=\"ltr\">\$wgUpgradeKey = '" .
					$this->getVar( 'wgUpgradeKey' ) . "';</pre>" )->plain()
			) );
			$this->endForm( 'continue' );

			return 'output';
		}

		// If there is an upgrade key, but it wasn't supplied, prompt the user to enter it

		$r = $this->parent->request;
		if ( $r->wasPosted() ) {
			$key = $r->getText( 'config_wgUpgradeKey' );
			if ( !$key || $key !== $vars['wgUpgradeKey'] ) {
				$this->parent->showError( 'config-localsettings-badkey' );
				$this->showKeyForm();

				return 'output';
			}
			// Key was OK
			$status = $this->handleExistingUpgrade( $vars );
			if ( $status->isOK() ) {
				return 'continue';
			} else {
				$this->parent->showStatusBox( $status );
				$this->showKeyForm();

				return 'output';
			}
		} else {
			$this->showKeyForm();

			return 'output';
		}
	}

	/**
	 * Show the "enter key" form
	 */
	protected function showKeyForm() {
		$this->startForm();
		$this->addHTML(
			$this->parent->getInfoBox( wfMessage( 'config-localsettings-upgrade' )->plain() ) .
			'<br />' .
			$this->parent->getTextBox( [
				'var' => 'wgUpgradeKey',
				'label' => 'config-localsettings-key',
				'attribs' => [ 'autocomplete' => 'off' ],
			] )
		);
		$this->endForm( 'continue' );
	}

	/**
	 * @param string[] $names
	 * @param mixed[] $vars
	 *
	 * @return Status
	 */
	protected function importVariables( $names, $vars ) {
		$status = Status::newGood();
		foreach ( $names as $name ) {
			if ( !isset( $vars[$name] ) ) {
				$status->fatal( 'config-localsettings-incomplete', $name );
			}
			$this->setVar( $name, $vars[$name] );
		}

		return $status;
	}

	/**
	 * Initiate an upgrade of the existing database
	 *
	 * @param mixed[] $vars Variables from LocalSettings.php
	 *
	 * @return Status
	 */
	protected function handleExistingUpgrade( $vars ) {
		// Check $wgDBtype
		if ( !isset( $vars['wgDBtype'] ) ||
			!in_array( $vars['wgDBtype'], Installer::getDBTypes() )
		) {
			return Status::newFatal( 'config-localsettings-connection-error', '' );
		}

		// Set the relevant variables from LocalSettings.php
		$requiredVars = [ 'wgDBtype' ];
		$status = $this->importVariables( $requiredVars, $vars );
		$installer = $this->parent->getDBInstaller();
		$status->merge( $this->importVariables( $installer->getGlobalNames(), $vars ) );
		if ( !$status->isOK() ) {
			return $status;
		}

		if ( isset( $vars['wgDBadminuser'] ) ) {
			$this->setVar( '_InstallUser', $vars['wgDBadminuser'] );
		} else {
			$this->setVar( '_InstallUser', $vars['wgDBuser'] );
		}
		if ( isset( $vars['wgDBadminpassword'] ) ) {
			$this->setVar( '_InstallPassword', $vars['wgDBadminpassword'] );
		} else {
			$this->setVar( '_InstallPassword', $vars['wgDBpassword'] );
		}

		// Test the database connection
		$status = $installer->getConnection();
		if ( !$status->isOK() ) {
			// Adjust the error message to explain things correctly
			$status->replaceMessage( 'config-connection-error',
				'config-localsettings-connection-error' );

			return $status;
		}

		// All good
		$this->setVar( '_ExistingDBSettings', true );

		// Copy $wgAuthenticationTokenVersion too, if it exists
		$this->setVar( 'wgAuthenticationTokenVersion',
			isset( $vars['wgAuthenticationTokenVersion'] )
				? $vars['wgAuthenticationTokenVersion']
				: null
		);

		return $status;
	}

}
