<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\MainConfigNames;
use MediaWiki\Status\Status;
use Wikimedia\Rdbms\DBQueryError;

/**
 * Enable PL/pgSQL in the wiki's database if necessary
 *
 * @internal For use by the installer
 */
class PostgresPlTask extends Task {
	public function getName() {
		return 'pg-plpgsql';
	}

	public function execute(): Status {
		// Connect as the install user, since it owns the database and so is
		// the user that needs to run "CREATE EXTENSION"
		$status = $this->getConnection( ITaskContext::CONN_CREATE_SCHEMA );
		if ( !$status->isOK() ) {
			return $status;
		}
		$conn = $status->getDB();
		try {
			$conn->query( 'CREATE EXTENSION IF NOT EXISTS plpgsql', __METHOD__ );
		} catch ( DBQueryError ) {
			return Status::newFatal( 'config-pg-no-plpgsql', $this->getConfigVar( MainConfigNames::DBname ) );
		}
		return Status::newGood();
	}
}
