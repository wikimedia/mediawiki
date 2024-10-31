<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\MainConfigNames;
use MediaWiki\Status\Status;

/**
 * Create the PostgreSQL database
 *
 * @internal For use by the installer
 */
class PostgresCreateDatabaseTask extends Task {
	public function getName() {
		return 'database';
	}

	public function execute(): Status {
		$status = $this->getConnection( ITaskContext::CONN_CREATE_DATABASE );
		if ( !$status->isOK() ) {
			return $status;
		}
		$conn = $status->value;

		$dbName = $this->getConfigVar( MainConfigNames::DBname );

		$exists = (bool)$conn->selectField( '"pg_catalog"."pg_database"', '1',
			[ 'datname' => $dbName ], __METHOD__ );
		if ( !$exists ) {
			$safedb = $conn->addIdentifierQuotes( $dbName );
			$conn->query( "CREATE DATABASE $safedb", __METHOD__ );
		}

		return Status::newGood();
	}

}
