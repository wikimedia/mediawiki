<?php
/**
 * Created by PhpStorm.
 * User: daki
 * Date: 10.04.18
 * Time: 20:48
 */

namespace MediaWiki\Tests\Storage;

use Wikimedia\Rdbms\IDatabase;

/**
 * Trait providing methods for detecting which MCR schema migration phase the current schema
 * is compatible with.
 */
trait McrSchemaDetection {

	/**
	 * Returns true if MCR-related tables exist in the database.
	 * If yes, the database is compatible with with MIGRATION_NEW and MIGRATION_WRITE_BOTH mode.
	 *
	 * @param IDatabase $db
	 * @return bool
	 */
	protected function hasMcrTables( IDatabase $db ) {
		return $db->tableExists( 'slots', __METHOD__ );
	}

	/**
	 * Returns true if pre-MCR fields still exist in the database.
	 * If yes, the database is compatible with with MIGRATION_OLD and MIGRATION_WRITE_BOTH mode.
	 *
	 * @param IDatabase $db
	 * @return bool
	 */
	protected function hasPreMcrFields( IDatabase $db ) {
		return $db->fieldExists( 'revision', 'rev_content_model', __METHOD__ );
	}

}
