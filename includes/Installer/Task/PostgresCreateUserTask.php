<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\MainConfigNames;
use MediaWiki\Status\Status;
use Wikimedia\Rdbms\DatabasePostgres;
use Wikimedia\Rdbms\DBQueryError;

/**
 * Create the PostgreSQL user
 *
 * @internal For use by the installer
 */
class PostgresCreateUserTask extends Task {
	/** @inheritDoc */
	public function getName() {
		return 'user';
	}

	/** @inheritDoc */
	public function getDependencies() {
		return 'database';
	}

	public function isSkipped(): bool {
		return !$this->getOption( 'CreateDBAccount' );
	}

	public function execute(): Status {
		$status = $this->getConnection( ITaskContext::CONN_CREATE_DATABASE );
		if ( !$status->isOK() ) {
			return $status;
		}
		$conn = $status->getDB();
		'@phan-var DatabasePostgres $conn'; /** @var DatabasePostgres $conn */

		$safeuser = $conn->addIdentifierQuotes( $this->getConfigVar( MainConfigNames::DBuser ) );
		$safepass = $conn->addQuotes( $this->getConfigVar( MainConfigNames::DBpassword ) );

		// Check if the user already exists
		$userExists = $conn->roleExists( $this->getConfigVar( MainConfigNames::DBuser ) );
		if ( !$userExists ) {
			// Create the user
			try {
				$sql = "CREATE ROLE $safeuser NOCREATEDB LOGIN PASSWORD $safepass";

				// If the install user is not a superuser, we need to make the install
				// user a member of the new user's group, so that the install user will
				// be able to create a schema and other objects on behalf of the new user.
				if ( !$this->getPostgresUtils()->isSuperUser() ) {
					$sql .= ' ROLE' . $conn->addIdentifierQuotes( $this->getOption( 'InstallUser' ) );
				}

				$conn->query( $sql, __METHOD__ );
			} catch ( DBQueryError $e ) {
				return Status::newFatal( 'config-install-user-create-failed',
					$this->getConfigVar( MainConfigNames::DBuser ), $e->getMessage() );
			}
		}

		return Status::newGood();
	}

	private function getPostgresUtils(): PostgresUtils {
		return new PostgresUtils( $this->getContext() );
	}
}
