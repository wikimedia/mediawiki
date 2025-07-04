<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\Status\Status;
use Wikimedia\Rdbms\DatabaseSqlite;

/**
 * Create the SQLite search index
 *
 * @internal For use by the installer
 */
class SqliteCreateSearchIndexTask extends Task {
	/** @inheritDoc */
	public function getName() {
		return 'search';
	}

	public function execute(): Status {
		$status = Status::newGood();
		$db = $this->definitelyGetConnection( ITaskContext::CONN_CREATE_TABLES );
		$module = DatabaseSqlite::getFulltextSearchModule();
		$searchIndexSql = (string)$db->newSelectQueryBuilder()
			->select( 'sql' )
			->from( 'sqlite_master' )
			->where( [ 'tbl_name' => $db->tableName( 'searchindex', 'raw' ) ] )
			->caller( __METHOD__ )->fetchField();
		$fts3tTable = ( stristr( $searchIndexSql, 'fts' ) !== false );

		if ( $fts3tTable && !$module ) {
			$status->warning( 'config-sqlite-fts3-downgrade' );
			$this->applySourceFile( $db, 'searchindex-no-fts.sql' );
		} elseif ( !$fts3tTable && $module == 'FTS3' ) {
			$this->applySourceFile( $db, 'searchindex-fts3.sql' );
		}

		return $status;
	}

}
