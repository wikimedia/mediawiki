<?php

namespace MediaWiki\Installer;

use MediaWiki\Status\Status;
use Wikimedia\Rdbms\IDatabase;

class MysqlDatabaseCreator extends NetworkedDatabaseCreator {
	/** @inheritDoc */
	protected function existsInConnection( IDatabase $conn, $database ) {
		return (bool)$conn->newSelectQueryBuilder()
			->select( '1' )
			->from( 'information_schema.schemata' )
			->where( [ 'schema_name' => $database ] )
			->caller( __METHOD__ )
			->fetchRow();
	}

	/** @inheritDoc */
	protected function createInConnection( IDatabase $conn, $database ): Status {
		$encDatabase = $conn->addIdentifierQuotes( $database );
		$conn->query( "CREATE DATABASE IF NOT EXISTS $encDatabase", __METHOD__ );
		return Status::newGood();
	}
}
