<?php

use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IMaintainableDatabase;

/**
 * @deprecated Used by MathLaTeXMLDatabaseTest and ParserTestTopLevelSuite.
 *
 * @since 1.30
 */
class MediaWikiLegacyTestDBHelper {

	private static $useTemporaryTables = true;
	private static $reuseDB = false;
	private static $dbSetup = false;

	/**
	 * Creates an empty skeleton of the wiki database by cloning its structure
	 * to equivalent tables using the given $prefix. Then sets MediaWiki to
	 * use the new set of tables (aka schema) instead of the original set.
	 *
	 * This is used to generate a dummy table set, typically consisting of temporary
	 * tables, that will be used by tests instead of the original wiki database tables.
	 *
	 * @since 1.30 (moved from MediaWikiTestCase)
	 *
	 * @note the original table prefix is stored in self::$oldTablePrefix. This is used
	 * by teardownTestDB() to return the wiki to using the original table set.
	 *
	 * @note this method only works when first called. Subsequent calls have no effect,
	 * even if using different parameters.
	 *
	 * @param Database $db The database connection
	 * @param string $prefix The prefix to use for the new table set (aka schema).
	 *
	 * @throws MWException If the database table prefix is already $prefix
	 */
	public static function setupTestDB( Database $db, $prefix ) {
		if ( self::$dbSetup ) {
			return;
		}
		if ( $db->tablePrefix() === $prefix ) {
			throw new MWException(
				'Cannot run unit tests, the database prefix is already "' . $prefix . '"' );
		}
		// TODO: the below should be re-written as soon as LBFactory, LoadBalancer,
		// and Database no longer use global state.
		self::$dbSetup = true;
		if ( !self::setupDatabaseWithTestPrefix( $db, $prefix ) ) {
			return;
		}
		// Assuming this isn't needed for External Store database, and not sure if the procedure
		// would be available there.
		if ( $db->getType() == 'oracle' ) {
			$db->query( 'BEGIN FILL_WIKI_INFO; END;' );
		}
	}

	/**
	 * Setups a database with the given prefix.
	 *
	 * If reuseDB is true and certain conditions apply, it will just change the prefix.
	 * Otherwise, it will clone the tables and change the prefix.
	 *
	 * Clones all tables in the given database (whatever database that connection has
	 * open), to versions with the test prefix.
	 *
	 * @param IMaintainableDatabase $db Database to use
	 * @param string $prefix Prefix to use for test tables
	 * @return bool True if tables were cloned, false if only the prefix was changed
	 */
	private static function setupDatabaseWithTestPrefix( IMaintainableDatabase $db, $prefix ) {
		$tablesCloned = self::listTables( $db );
		$dbClone = new CloneDatabase( $db, $tablesCloned, $prefix );
		$dbClone->useTemporaryTables( self::$useTemporaryTables );
		if ( ( $db->getType() == 'oracle' || !self::$useTemporaryTables ) && self::$reuseDB ) {
			CloneDatabase::changePrefix( $prefix );
			return false;
		} else {
			$dbClone->cloneTableStructure();
			return true;
		}
	}

	/**
	 * @param IMaintainableDatabase $db
	 *
	 * @return array
	 */
	private static function listTables( IMaintainableDatabase $db ) {
		$prefix = $db->tablePrefix();
		$tables = $db->listTables( $prefix, __METHOD__ );
		if ( $db->getType() === 'mysql' ) {
			static $viewListCache = null;
			if ( $viewListCache === null ) {
				$viewListCache = $db->listViews( null, __METHOD__ );
			}
			// T45571: cannot clone VIEWs under MySQL
			$tables = array_diff( $tables, $viewListCache );
		}
		array_walk( $tables, [ __CLASS__, 'unprefixTable' ], $prefix );
		// Don't duplicate test tables from the previous fataled run
		$tables = array_filter( $tables, [ __CLASS__, 'isNotUnittest' ] );
		if ( $db->getType() == 'sqlite' ) {
			$tables = array_flip( $tables );
			// these are subtables of searchindex and don't need to be duped/dropped separately
			unset( $tables['searchindex_content'] );
			unset( $tables['searchindex_segdir'] );
			unset( $tables['searchindex_segments'] );
			$tables = array_flip( $tables );
		}
		return $tables;
	}

}
