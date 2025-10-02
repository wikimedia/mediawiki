<?php

/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Installer
 */

namespace MediaWiki\Installer;

use MediaWiki\Status\Status;

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
			$this->setVar( '_UpgradeKeySupplied', true );
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
				'value' => '',
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

		$this->setVar( '_InstallUser', $vars['wgDBadminuser'] ?? $vars['wgDBuser'] );
		$this->setVar( '_InstallPassword', $vars['wgDBadminpassword'] ?? $vars['wgDBpassword'] );

		// Test the database connection
		$status = $installer->getConnection( DatabaseInstaller::CONN_CREATE_DATABASE );
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
			$vars['wgAuthenticationTokenVersion'] ?? null
		);

		return $status;
	}

}
