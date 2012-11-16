<?php
/**
 * DBMS-specific updater helper.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Deployment
 */

require_once( __DIR__ . '/../../maintenance/Maintenance.php' );

/**
 * Class for handling database updates. Roughly based off of updaters.inc, with
 * a few improvements :)
 *
 * @ingroup Deployment
 * @since 1.17
 */
abstract class DatabaseUpdater {

	/**
	 * Array of updates to perform on the database
	 *
	 * @var array
	 */
	protected $updates = array();

	/**
	 * List of extension-provided database updates
	 * @var array
	 */
	protected $extensionUpdates = array();

	/**
	 * Handle to the database subclass
	 *
	 * @var DatabaseBase
	 */
	protected $db;

	protected $shared = false;

	protected $postDatabaseUpdateMaintenance = array(
		'DeleteDefaultMessages',
		'PopulateRevisionLength',
		'PopulateRevisionSha1',
		'PopulateImageSha1',
		'FixExtLinksProtocolRelative',
	);

	/**
	 * Constructor
	 *
	 * @param $db DatabaseBase object to perform updates on
	 * @param $shared bool Whether to perform updates on shared tables
	 * @param $maintenance Maintenance Maintenance object which created us
	 */
	protected function __construct( DatabaseBase &$db, $shared, Maintenance $maintenance = null ) {
		$this->db = $db;
		$this->db->setFlag( DBO_DDLMODE ); // For Oracle's handling of schema files
		$this->shared = $shared;
		if ( $maintenance ) {
			$this->maintenance = $maintenance;
		} else {
			$this->maintenance = new FakeMaintenance;
		}
		$this->maintenance->setDB( $db );
		$this->initOldGlobals();
		$this->loadExtensions();
		wfRunHooks( 'LoadExtensionSchemaUpdates', array( $this ) );
	}

	/**
	 * Initialize all of the old globals. One day this should all become
	 * something much nicer
	 */
	private function initOldGlobals() {
		global $wgExtNewTables, $wgExtNewFields, $wgExtPGNewFields,
			$wgExtPGAlteredFields, $wgExtNewIndexes, $wgExtModifiedFields;

		# For extensions only, should be populated via hooks
		# $wgDBtype should be checked to specifiy the proper file
		$wgExtNewTables = array(); // table, dir
		$wgExtNewFields = array(); // table, column, dir
		$wgExtPGNewFields = array(); // table, column, column attributes; for PostgreSQL
		$wgExtPGAlteredFields = array(); // table, column, new type, conversion method; for PostgreSQL
		$wgExtNewIndexes = array(); // table, index, dir
		$wgExtModifiedFields = array(); // table, index, dir
	}

	/**
	 * Loads LocalSettings.php, if needed, and initialises everything needed for LoadExtensionSchemaUpdates hook
	 */
	private function loadExtensions() {
		if ( !defined( 'MEDIAWIKI_INSTALL' ) ) {
			return; // already loaded
		}
		$vars = Installer::getExistingLocalSettings();
		if ( !$vars ) {
			return; // no LocalSettings found
		}
		if ( !isset( $vars['wgHooks'] ) || !isset( $vars['wgHooks']['LoadExtensionSchemaUpdates'] ) ) {
			return;
		}
		global $wgHooks, $wgAutoloadClasses;
		$wgHooks['LoadExtensionSchemaUpdates'] = $vars['wgHooks']['LoadExtensionSchemaUpdates'];
		$wgAutoloadClasses = $wgAutoloadClasses + $vars['wgAutoloadClasses'];
	}

	/**
	 * @throws MWException
	 * @param DatabaseBase $db
	 * @param bool $shared
	 * @param null $maintenance
	 * @return DatabaseUpdater
	 */
	public static function newForDB( &$db, $shared = false, $maintenance = null ) {
		$type = $db->getType();
		if( in_array( $type, Installer::getDBTypes() ) ) {
			$class = ucfirst( $type ) . 'Updater';
			return new $class( $db, $shared, $maintenance );
		} else {
			throw new MWException( __METHOD__ . ' called for unsupported $wgDBtype' );
		}
	}

	/**
	 * Get a database connection to run updates
	 *
	 * @return DatabaseBase
	 */
	public function getDB() {
		return $this->db;
	}

	/**
	 * Output some text. If we're running from web, escape the text first.
	 *
	 * @param $str String: Text to output
	 */
	public function output( $str ) {
		if ( $this->maintenance->isQuiet() ) {
			return;
		}
		global $wgCommandLineMode;
		if( !$wgCommandLineMode ) {
			$str = htmlspecialchars( $str );
		}
		echo $str;
		flush();
	}

	/**
	 * Add a new update coming from an extension. This should be called by
	 * extensions while executing the LoadExtensionSchemaUpdates hook.
	 *
	 * @since 1.17
	 *
	 * @param $update Array: the update to run. Format is the following:
	 *                first item is the callback function, it also can be a
	 *                simple string with the name of a function in this class,
	 *                following elements are parameters to the function.
	 *                Note that callback functions will receive this object as
	 *                first parameter.
	 */
	public function addExtensionUpdate( array $update ) {
		$this->extensionUpdates[] = $update;
	}

	/**
	 * Convenience wrapper for addExtensionUpdate() when adding a new table (which
	 * is the most common usage of updaters in an extension)
	 *
	 * @since 1.18
	 *
	 * @param $tableName String Name of table to create
	 * @param $sqlPath String Full path to the schema file
	 */
	public function addExtensionTable( $tableName, $sqlPath ) {
		$this->extensionUpdates[] = array( 'addTable', $tableName, $sqlPath, true );
	}

	/**
	 * @since 1.19
	 *
	 * @param $tableName string
	 * @param $indexName string
	 * @param $sqlPath string
	 */
	public function addExtensionIndex( $tableName, $indexName, $sqlPath ) {
		$this->extensionUpdates[] = array( 'addIndex', $tableName, $indexName, $sqlPath, true );
	}

	/**
	 *
	 * @since 1.19
	 *
	 * @param $tableName string
	 * @param $columnName string
	 * @param $sqlPath string
	 */
	public function addExtensionField( $tableName, $columnName, $sqlPath ) {
		$this->extensionUpdates[] = array( 'addField', $tableName, $columnName, $sqlPath, true );
	}

	/**
	 *
	 * @since 1.20
	 *
	 * @param $tableName string
	 * @param $columnName string
	 * @param $sqlPath string
	 */
	public function dropExtensionField( $tableName, $columnName, $sqlPath ) {
		$this->extensionUpdates[] = array( 'dropField', $tableName, $columnName, $sqlPath, true );
	}

	/**
	 *
	 * @since 1.20
	 *
	 * @param $tableName string
	 * @param $sqlPath string
	 */
	public function dropExtensionTable( $tableName, $sqlPath ) {
		$this->extensionUpdates[] = array( 'dropTable', $tableName, $sqlPath, true );
	}

	/**
	 *
	 * @since 1.20
	 *
	 * @param $tableName string
	 * @return bool
	 */
	public function tableExists( $tableName ) {
		return ( $this->db->tableExists( $tableName, __METHOD__ ) );
	}

	/**
	 * Add a maintenance script to be run after the database updates are complete.
	 *
	 * @since 1.19
	 *
	 * @param $class string Name of a Maintenance subclass
	 */
	public function addPostDatabaseUpdateMaintenance( $class ) {
		$this->postDatabaseUpdateMaintenance[] = $class;
	}

	/**
	 * Get the list of extension-defined updates
	 *
	 * @return Array
	 */
	protected function getExtensionUpdates() {
		return $this->extensionUpdates;
	}

	/**
	 * @since 1.17
	 *
	 * @return array
	 */
	public function getPostDatabaseUpdateMaintenance() {
		return $this->postDatabaseUpdateMaintenance;
	}

	/**
	 * Do all the updates
	 *
	 * @param $what Array: what updates to perform
	 */
	public function doUpdates( $what = array( 'core', 'extensions', 'purge', 'stats' ) ) {
		global $wgLocalisationCacheConf, $wgVersion;

		$this->db->begin( __METHOD__ );
		$what = array_flip( $what );
		if ( isset( $what['core'] ) ) {
			$this->runUpdates( $this->getCoreUpdateList(), false );
		}
		if ( isset( $what['extensions'] ) ) {
			$this->runUpdates( $this->getOldGlobalUpdates(), false );
			$this->runUpdates( $this->getExtensionUpdates(), true );
		}

		$this->setAppliedUpdates( $wgVersion, $this->updates );

		if ( isset( $what['stats'] ) ) {
			$this->checkStats();
		}

		if ( isset( $what['purge'] ) ) {
			$this->purgeCache();

			if ( $wgLocalisationCacheConf['manualRecache'] ) {
				$this->rebuildLocalisationCache();
			}
		}
		$this->db->commit( __METHOD__ );
	}

	/**
	 * Helper function for doUpdates()
	 *
	 * @param $updates Array of updates to run
	 * @param $passSelf Boolean: whether to pass this object we calling external
	 *                  functions
	 */
	private function runUpdates( array $updates, $passSelf ) {
		foreach ( $updates as $params ) {
			$func = array_shift( $params );
			if( !is_array( $func ) && method_exists( $this, $func ) ) {
				$func = array( $this, $func );
			} elseif ( $passSelf ) {
				array_unshift( $params, $this );
			}
			call_user_func_array( $func, $params );
			flush();
		}
		$this->updates = array_merge( $this->updates, $updates );
	}

	/**
	 * @param $version
	 * @param $updates array
	 */
	protected function setAppliedUpdates( $version, $updates = array() ) {
		$this->db->clearFlag( DBO_DDLMODE );
		if( !$this->canUseNewUpdatelog() ) {
			return;
		}
		$key = "updatelist-$version-" . time();
		$this->db->insert( 'updatelog',
			array( 'ul_key' => $key, 'ul_value' => serialize( $updates ) ),
			 __METHOD__ );
		$this->db->setFlag( DBO_DDLMODE );
	}

	/**
	 * Helper function: check if the given key is present in the updatelog table.
	 * Obviously, only use this for updates that occur after the updatelog table was
	 * created!
	 * @param $key String Name of the key to check for
	 *
	 * @return bool
	 */
	public function updateRowExists( $key ) {
		$row = $this->db->selectRow(
			'updatelog',
			'1',
			array( 'ul_key' => $key ),
			__METHOD__
		);
		return (bool)$row;
	}

	/**
	 * Helper function: Add a key to the updatelog table
	 * Obviously, only use this for updates that occur after the updatelog table was
	 * created!
	 * @param $key String Name of key to insert
	 * @param $val String [optional] value to insert along with the key
	 */
	public function insertUpdateRow( $key, $val = null ) {
		$this->db->clearFlag( DBO_DDLMODE );
		$values = array( 'ul_key' => $key );
		if( $val && $this->canUseNewUpdatelog() ) {
			$values['ul_value'] = $val;
		}
		$this->db->insert( 'updatelog', $values, __METHOD__, 'IGNORE' );
		$this->db->setFlag( DBO_DDLMODE );
	}

	/**
	 * Updatelog was changed in 1.17 to have a ul_value column so we can record
	 * more information about what kind of updates we've done (that's what this
	 * class does). Pre-1.17 wikis won't have this column, and really old wikis
	 * might not even have updatelog at all
	 *
	 * @return boolean
	 */
	protected function canUseNewUpdatelog() {
		return $this->db->tableExists( 'updatelog', __METHOD__ ) &&
			$this->db->fieldExists( 'updatelog', 'ul_value', __METHOD__ );
	}

	/**
	 * Before 1.17, we used to handle updates via stuff like
	 * $wgExtNewTables/Fields/Indexes. This is nasty :) We refactored a lot
	 * of this in 1.17 but we want to remain back-compatible for a while. So
	 * load up these old global-based things into our update list.
	 *
	 * @return array
	 */
	protected function getOldGlobalUpdates() {
		global $wgExtNewFields, $wgExtNewTables, $wgExtModifiedFields,
			$wgExtNewIndexes, $wgSharedDB, $wgSharedTables;

		$doUser = $this->shared ?
			$wgSharedDB && in_array( 'user', $wgSharedTables ) :
			!$wgSharedDB || !in_array( 'user', $wgSharedTables );

		$updates = array();

		foreach ( $wgExtNewTables as $tableRecord ) {
			$updates[] = array(
				'addTable', $tableRecord[0], $tableRecord[1], true
			);
		}

		foreach ( $wgExtNewFields as $fieldRecord ) {
			if ( $fieldRecord[0] != 'user' || $doUser ) {
				$updates[] = array(
					'addField', $fieldRecord[0], $fieldRecord[1],
						$fieldRecord[2], true
				);
			}
		}

		foreach ( $wgExtNewIndexes as $fieldRecord ) {
			$updates[] = array(
				'addIndex', $fieldRecord[0], $fieldRecord[1],
					$fieldRecord[2], true
			);
		}

		foreach ( $wgExtModifiedFields as $fieldRecord ) {
			$updates[] = array(
				'modifyField', $fieldRecord[0], $fieldRecord[1],
					$fieldRecord[2], true
			);
		}

		return $updates;
	}

	/**
	 * Get an array of updates to perform on the database. Should return a
	 * multi-dimensional array. The main key is the MediaWiki version (1.12,
	 * 1.13...) with the values being arrays of updates, identical to how
	 * updaters.inc did it (for now)
	 *
	 * @return Array
	 */
	protected abstract function getCoreUpdateList();

	/**
	 * Applies a SQL patch
	 * @param $path String Path to the patch file
	 * @param $isFullPath Boolean Whether to treat $path as a relative or not
	 * @param $msg String Description of the patch
	 */
	protected function applyPatch( $path, $isFullPath = false, $msg = null ) {
		if ( $msg === null ) {
			$msg = "Applying $path patch";
		}

		if ( !$isFullPath ) {
			$path = $this->db->patchPath( $path );
		}

		$this->output( "$msg ..." );
		$this->db->sourceFile( $path );
		$this->output( "done.\n" );
	}

	/**
	 * Add a new table to the database
	 * @param $name String Name of the new table
	 * @param $patch String Path to the patch file
	 * @param $fullpath Boolean Whether to treat $patch path as a relative or not
	 */
	protected function addTable( $name, $patch, $fullpath = false ) {
		if ( $this->db->tableExists( $name, __METHOD__ ) ) {
			$this->output( "...$name table already exists.\n" );
		} else {
			$this->applyPatch( $patch, $fullpath, "Creating $name table" );
		}
	}

	/**
	 * Add a new field to an existing table
	 * @param $table String Name of the table to modify
	 * @param $field String Name of the new field
	 * @param $patch String Path to the patch file
	 * @param $fullpath Boolean Whether to treat $patch path as a relative or not
	 */
	protected function addField( $table, $field, $patch, $fullpath = false ) {
		if ( !$this->db->tableExists( $table, __METHOD__ ) ) {
			$this->output( "...$table table does not exist, skipping new field patch.\n" );
		} elseif ( $this->db->fieldExists( $table, $field, __METHOD__ ) ) {
			$this->output( "...have $field field in $table table.\n" );
		} else {
			$this->applyPatch( $patch, $fullpath, "Adding $field field to table $table" );
		}
	}

	/**
	 * Add a new index to an existing table
	 * @param $table String Name of the table to modify
	 * @param $index String Name of the new index
	 * @param $patch String Path to the patch file
	 * @param $fullpath Boolean Whether to treat $patch path as a relative or not
	 */
	protected function addIndex( $table, $index, $patch, $fullpath = false ) {
		if ( $this->db->indexExists( $table, $index, __METHOD__ ) ) {
			$this->output( "...index $index already set on $table table.\n" );
		} else {
			$this->applyPatch( $patch, $fullpath, "Adding index $index to table $table" );
		}
	}

	/**
	 * Drop a field from an existing table
	 *
	 * @param $table String Name of the table to modify
	 * @param $field String Name of the old field
	 * @param $patch String Path to the patch file
	 * @param $fullpath Boolean Whether to treat $patch path as a relative or not
	 */
	protected function dropField( $table, $field, $patch, $fullpath = false ) {
		if ( $this->db->fieldExists( $table, $field, __METHOD__ ) ) {
			$this->applyPatch( $patch, $fullpath, "Table $table contains $field field. Dropping" );
		} else {
			$this->output( "...$table table does not contain $field field.\n" );
		}
	}

	/**
	 * Drop an index from an existing table
	 *
	 * @param $table String: Name of the table to modify
	 * @param $index String: Name of the old index
	 * @param $patch String: Path to the patch file
	 * @param $fullpath Boolean: Whether to treat $patch path as a relative or not
	 */
	protected function dropIndex( $table, $index, $patch, $fullpath = false ) {
		if ( $this->db->indexExists( $table, $index, __METHOD__ ) ) {
			$this->applyPatch( $patch, $fullpath, "Dropping $index index from table $table" );
		} else {
			$this->output( "...$index key doesn't exist.\n" );
		}
	}

	/**
	 * If the specified table exists, drop it, or execute the
	 * patch if one is provided.
	 *
	 * Public @since 1.20
	 *
	 * @param $table string
	 * @param $patch string|false
	 * @param $fullpath bool
	 */
	public function dropTable( $table, $patch = false, $fullpath = false ) {
		if ( $this->db->tableExists( $table, __METHOD__ ) ) {
			$msg = "Dropping table $table";

			if ( $patch === false ) {
				$this->output( "$msg ..." );
				$this->db->dropTable( $table, __METHOD__ );
				$this->output( "done.\n" );
			}
			else {
				$this->applyPatch( $patch, $fullpath, $msg );
			}

		} else {
			$this->output( "...$table doesn't exist.\n" );
		}
	}

	/**
	 * Modify an existing field
	 *
	 * @param $table String: name of the table to which the field belongs
	 * @param $field String: name of the field to modify
	 * @param $patch String: path to the patch file
	 * @param $fullpath Boolean: whether to treat $patch path as a relative or not
	 */
	public function modifyField( $table, $field, $patch, $fullpath = false ) {
		$updateKey = "$table-$field-$patch";
		if ( !$this->db->tableExists( $table, __METHOD__ ) ) {
			$this->output( "...$table table does not exist, skipping modify field patch.\n" );
		} elseif ( !$this->db->fieldExists( $table, $field, __METHOD__ ) ) {
			$this->output( "...$field field does not exist in $table table, skipping modify field patch.\n" );
		} elseif( $this->updateRowExists( $updateKey ) ) {
			$this->output( "...$field in table $table already modified by patch $patch.\n" );
		} else {
			$this->applyPatch( $patch, $fullpath, "Modifying $field field of table $table" );
			$this->insertUpdateRow( $updateKey );
		}
	}

	/**
	 * Purge the objectcache table
	 */
	protected function purgeCache() {
		# We can't guarantee that the user will be able to use TRUNCATE,
		# but we know that DELETE is available to us
		$this->output( "Purging caches..." );
		$this->db->delete( 'objectcache', '*', __METHOD__ );
		$this->output( "done.\n" );
	}

	/**
	 * Check the site_stats table is not properly populated.
	 */
	protected function checkStats() {
		$this->output( "...site_stats is populated..." );
		$row = $this->db->selectRow( 'site_stats', '*', array( 'ss_row_id' => 1 ), __METHOD__ );
		if ( $row === false ) {
			$this->output( "data is missing! rebuilding...\n" );
		} elseif ( isset( $row->site_stats ) && $row->ss_total_pages == -1 ) {
			$this->output( "missing ss_total_pages, rebuilding...\n" );
		} else {
			$this->output( "done.\n" );
			return;
		}
		SiteStatsInit::doAllAndCommit( $this->db );
	}

	# Common updater functions

	/**
	 * Sets the number of active users in the site_stats table
	 */
	protected function doActiveUsersInit() {
		$activeUsers = $this->db->selectField( 'site_stats', 'ss_active_users', false, __METHOD__ );
		if ( $activeUsers == -1 ) {
			$activeUsers = $this->db->selectField( 'recentchanges',
				'COUNT( DISTINCT rc_user_text )',
				array( 'rc_user != 0', 'rc_bot' => 0, "rc_log_type != 'newusers'" ), __METHOD__
			);
			$this->db->update( 'site_stats',
				array( 'ss_active_users' => intval( $activeUsers ) ),
				array( 'ss_row_id' => 1 ), __METHOD__, array( 'LIMIT' => 1 )
			);
		}
		$this->output( "...ss_active_users user count set...\n" );
	}

	/**
	 * Populates the log_user_text field in the logging table
	 */
	protected function doLogUsertextPopulation() {
		if ( !$this->updateRowExists( 'populate log_usertext' ) ) {
			$this->output(
			"Populating log_user_text field, printing progress markers. For large\n" .
			"databases, you may want to hit Ctrl-C and do this manually with\n" .
			"maintenance/populateLogUsertext.php.\n" );

			$task = $this->maintenance->runChild( 'PopulateLogUsertext' );
			$task->execute();
			$this->output( "done.\n" );
		}
	}

	/**
	 * Migrate log params to new table and index for searching
	 */
	protected function doLogSearchPopulation() {
		if ( !$this->updateRowExists( 'populate log_search' ) ) {
			$this->output(
				"Populating log_search table, printing progress markers. For large\n" .
				"databases, you may want to hit Ctrl-C and do this manually with\n" .
				"maintenance/populateLogSearch.php.\n" );

			$task = $this->maintenance->runChild( 'PopulateLogSearch' );
			$task->execute();
			$this->output( "done.\n" );
		}
	}

	/**
	 * Updates the timestamps in the transcache table
	 */
	protected function doUpdateTranscacheField() {
		if ( $this->updateRowExists( 'convert transcache field' ) ) {
			$this->output( "...transcache tc_time already converted.\n" );
			return;
		}

		$this->applyPatch( 'patch-tc-timestamp.sql', false, "Converting tc_time from UNIX epoch to MediaWiki timestamp" );
	}

	/**
	 * Update CategoryLinks collation
	 */
	protected function doCollationUpdate() {
		global $wgCategoryCollations;

		$collations = $this->db->selectField( 'updatelog', 'ul_value', array( 'ul_key' => 'collations' ), __METHOD__ );
		if ( $collations !== false ) {
			$collations = unserialize( $collations );
			if ( $collations !== false ) {
				$currentCollations = $wgCategoryCollations;
				sort( $currentCollations );
				sort( $collations );
				if ( $collations == $currentCollations ) {
					$this->output( "...collations up-to-date.\n" );
					return;
				}
			}
		}

		$this->output( "Updating category collations..." );
		$task = $this->maintenance->runChild( 'UpdateCollation' );
		$task->execute();
		$this->output( "...done.\n" );
	}

	/**
	 * Migrates user options from the user table blob to user_properties
	 */
	protected function doMigrateUserOptions() {
		$cl = $this->maintenance->runChild( 'ConvertUserOptions', 'convertUserOptions.php' );
		$cl->execute();
		$this->output( "done.\n" );
	}

	/**
	 * Rebuilds the localisation cache
	 */
	protected function rebuildLocalisationCache() {
		/**
		 * @var $cl RebuildLocalisationCache
		 */
		$cl = $this->maintenance->runChild( 'RebuildLocalisationCache', 'rebuildLocalisationCache.php' );
		$this->output( "Rebuilding localisation cache...\n" );
		$cl->setForce();
		$cl->execute();
		$this->output( "done.\n" );
	}
}
