<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\MainConfigNames;
use MediaWiki\Status\Status;
use Wikimedia\Rdbms\DatabasePostgres;
use Wikimedia\Rdbms\DBQueryError;

/**
 * Create the PostgreSQL schema
 *
 * @internal For use by the installer
 */
class PostgresCreateSchemaTask extends Task {
	/** @inheritDoc */
	public function getName() {
		return 'schema';
	}

	/** @inheritDoc */
	public function getDependencies() {
		return 'user';
	}

	public function execute(): Status {
		// Get a connection to the target database
		$status = $this->getConnection( ITaskContext::CONN_CREATE_SCHEMA );
		if ( !$status->isOK() ) {
			return $status;
		}
		$conn = $status->getDB();
		'@phan-var DatabasePostgres $conn'; /** @var DatabasePostgres $conn */

		// Create the schema if necessary
		$schema = $this->getConfigVar( MainConfigNames::DBmwschema );
		$safeschema = $conn->addIdentifierQuotes( $schema );
		$safeuser = $conn->addIdentifierQuotes( $this->getConfigVar( MainConfigNames::DBuser ) );
		if ( !$conn->schemaExists( $schema ) ) {
			try {
				$conn->query( "CREATE SCHEMA $safeschema AUTHORIZATION $safeuser", __METHOD__ );
			} catch ( DBQueryError ) {
				return Status::newFatal( 'config-install-pg-schema-failed',
					$this->getOption( 'InstallUser' ), $schema );
			}
		}

		return Status::newGood();
	}
}
