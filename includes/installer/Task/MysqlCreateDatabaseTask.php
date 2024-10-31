<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\MainConfigNames;
use MediaWiki\Status\Status;

/**
 * Create the MySQL/MariaDB database
 *
 * @internal For use by the installer
 */
class MysqlCreateDatabaseTask extends Task {
	public function getName() {
		return 'database';
	}

	public function getAliases() {
		return 'schema';
	}

	public function execute(): Status {
		$status = $this->getConnection( ITaskContext::CONN_CREATE_DATABASE );
		if ( !$status->isOK() ) {
			return $status;
		}
		$conn = $status->getDB();
		$dbName = $this->getConfigVar( MainConfigNames::DBname );
		if ( !$this->databaseExists( $dbName ) ) {
			$conn->query(
				"CREATE DATABASE " . $conn->addIdentifierQuotes( $dbName ) . "CHARACTER SET utf8",
				__METHOD__
			);
		}
		return Status::newGood();
	}

	/**
	 * Try to see if a given database exists
	 * @param string $dbName Database name to check
	 * @return bool
	 */
	private function databaseExists( $dbName ) {
		return (bool)$this->definitelyGetConnection( ITaskContext::CONN_CREATE_DATABASE )
			->newSelectQueryBuilder()
			->select( '1' )
			->from( 'information_schema.schemata' )
			->where( [ 'schema_name' => $dbName ] )
			->fetchRow();
	}

}
