<?php

namespace MediaWiki\Installer;

use MediaWiki\Status\Status;
use Wikimedia\Rdbms\IDatabase;

class PostgresDatabaseCreator extends NetworkedDatabaseCreator {
	/** @inheritDoc */
	protected function existsInConnection( IDatabase $conn, $database ) {
		return (bool)$conn->selectField( 'pg_catalog.pg_database', '1',
			[ 'datname' => $database ], __METHOD__ );
	}

	/** @inheritDoc */
	protected function createInConnection( IDatabase $conn, $database ): Status {
		$safedb = $conn->addIdentifierQuotes( $database );
		$conn->query( "CREATE DATABASE $safedb", __METHOD__ );
		return Status::newGood();
	}
}
