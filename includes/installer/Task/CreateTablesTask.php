<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\Status\Status;

/**
 * Create core tables
 *
 * @internal For use by the installer
 */
class CreateTablesTask extends Task {
	/** @inheritDoc */
	public function getName() {
		return 'tables';
	}

	/** @inheritDoc */
	public function getDependencies() {
		return 'schema';
	}

	public function execute(): Status {
		$status = $this->getConnection( ITaskContext::CONN_CREATE_TABLES );
		if ( !$status->isOK() ) {
			return $status;
		}
		$conn = $status->getDB();
		if ( $conn->tableExists( 'archive', __METHOD__ ) ) {
			$status->warning( "config-install-tables-exist" );
			return $status;
		}
		$status = $this->applySourceFile( $conn, 'tables-generated.sql' );
		if ( !$status->isOK() ) {
			return $status;
		}
		// TODO Make this optional, not every DB type has a tables.sql file.
		$status->merge( $this->applySourceFile( $conn, 'tables.sql' ) );
		return $status;
	}
}
