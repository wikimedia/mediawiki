<?php

namespace MediaWiki\Installer;

use MediaWiki\Status\Status;
use Wikimedia\Rdbms\DatabasePostgres;

/**
 * @internal
 */
class PostgresSettingsForm extends DatabaseSettingsForm {

	public function getHtml() {
		if ( $this->getPostgresInstaller()->canCreateAccounts() ) {
			$noCreateMsg = false;
		} else {
			$noCreateMsg = 'config-db-web-no-create-privs';
		}
		$s = $this->getWebUserBox( $noCreateMsg );

		return $s;
	}

	public function submit() {
		$status = $this->submitWebUserBox();
		if ( !$status->isOK() ) {
			return $status;
		}

		$same = $this->getVar( 'wgDBuser' ) === $this->getVar( '_InstallUser' );

		if ( $same ) {
			$exists = true;
		} else {
			// Check if the web user exists
			// Connect to the database with the install user
			$status = $this->getPostgresInstaller()->getConnection( DatabaseInstaller::CONN_CREATE_DATABASE );
			if ( !$status->isOK() ) {
				return $status;
			}
			$conn = $status->getDB();
			'@phan-var DatabasePostgres $conn'; /** @var DatabasePostgres $conn */
			$exists = $conn->roleExists( $this->getVar( 'wgDBuser' ) );
		}

		// Validate the create checkbox
		if ( $this->getPostgresInstaller()->canCreateAccounts() && !$same && !$exists ) {
			$create = $this->getVar( '_CreateDBAccount' );
		} else {
			$this->setVar( '_CreateDBAccount', false );
			$create = false;
		}

		if ( !$create && !$exists ) {
			if ( $this->getPostgresInstaller()->canCreateAccounts() ) {
				$msg = 'config-install-user-missing-create';
			} else {
				$msg = 'config-install-user-missing';
			}

			return Status::newFatal( $msg, $this->getVar( 'wgDBuser' ) );
		}

		if ( !$exists ) {
			// No more checks to do
			return Status::newGood();
		}

		// Existing web account. Test the connection.
		$status = $this->getPostgresInstaller()->openConnectionToAnyDB(
			$this->getVar( 'wgDBuser' ),
			$this->getVar( 'wgDBpassword' ) );
		if ( !$status->isOK() ) {
			return $status;
		}

		// The web user is conventionally the table owner in PostgreSQL
		// installations. Make sure the install user is able to create
		// objects on behalf of the web user.
		if ( $same || $this->getPostgresInstaller()->canCreateObjectsForWebUser() ) {
			return Status::newGood();
		} else {
			return Status::newFatal( 'config-pg-not-in-role' );
		}
	}

	/**
	 * Downcast the DatabaseInstaller
	 * @return PostgresInstaller
	 */
	private function getPostgresInstaller(): PostgresInstaller {
		// @phan-suppress-next-line PhanTypeMismatchReturnSuperType
		return $this->dbInstaller;
	}

}
