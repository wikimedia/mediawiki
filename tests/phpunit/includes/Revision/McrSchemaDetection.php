<?php
namespace MediaWiki\Tests\Revision;

use Wikimedia\Rdbms\IDatabase;

/**
 * Trait providing methods for detecting which MCR schema migration phase the current schema
 * is compatible with.
 */
trait McrSchemaDetection {

	/**
	 * Returns true if MCR-related tables exist in the database.
	 * If yes, the database is compatible with with MIGRATION_NEW.
	 * If hasPreMcrFields() also returns true, the database supports MIGRATION_WRITE_BOTH mode.
	 *
	 * @param IDatabase $db
	 * @return bool
	 */
	protected function hasMcrTables( IDatabase $db ) {
		return $db->tableExists( 'slots', __METHOD__ );
	}

	/**
	 * Returns true if pre-MCR fields still exist in the database.
	 * If yes, the database is compatible with with MIGRATION_OLD mode.
	 * If hasMcrTables() also returns true, the database supports MIGRATION_WRITE_BOTH mode.
	 *
	 * Note that if the database has been updated in MIGRATION_NEW mode,
	 * the rev_text_id field will be 0 for new revisions. This means that
	 * in MIGRATION_OLD mode, reading such revisions will fail, even though
	 * all the necessary fields exist.
	 * This is not relevant for unit tests, since unit tests reset the database content anyway.
	 *
	 * @param IDatabase $db
	 * @return bool
	 */
	protected function hasPreMcrFields( IDatabase $db ) {
		return $db->fieldExists( 'revision', 'rev_content_model', __METHOD__ );
	}

}
