<?php
/**
 * DBMS-specific updater helper.
 *
 * @file
 * @ingroup Deployment
 */
 
/*
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

	protected $extensionUpdates = array();

	protected $db;

	protected $shared = false;

	protected $postDatabaseUpdateMaintenance = array(
		'DeleteDefaultMessages'
	);

	/**
	 * Constructor
	 *
	 * @param $db DatabaseBase object to perform updates on
	 * @param $shared bool Whether to perform updates on shared tables
	 *
	 * @TODO @FIXME Make $wgDatabase go away.
	 */
	protected function __construct( DatabaseBase &$db, $shared ) {
		global $wgDatabase;
		$wgDatabase = $db;
		$this->db = $db;
		$this->shared = $shared;
		$this->initOldGlobals();
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

	public static function newForDB( &$db, $shared = false ) {
		$type = $db->getType();
		if( in_array( $type, Installer::getDBTypes() ) ) {
			$class = ucfirst( $type ) . 'Updater';
			return new $class( $db, $shared );
		} else {
			throw new MWException( __METHOD__ . ' called for unsupported $wgDBtype' );
		}
	}

	/**
	 * Get a database connection to run updates
	 *
	 * @return DatabasBase object
	 */
	public function getDB() {
		return $this->db;
	}

	/**
	 * Add a new update coming from an extension. This should be called by
	 * extensions while executing the LoadExtensionSchemaUpdates hook.
	 *
	 * @param $update Array: the update to run. Format is the following:
	 *                first item is the callback function, it also can be a
	 *                simple string with the name of a function in this class,
	 *                following elements are parameters to the function.
	 *                Note that callback functions will recieve this object as
	 *                first parameter.
	 */
	public function addExtensionUpdate( $update ) {
		$this->extensionUpdates[] = $update;
	}

	/**
	 * Get the list of extension-defined updates
	 *
	 * @return Array
	 */
	protected function getExtensionUpdates() {
		return $this->extensionUpdates;
	}

	public function getPostDatabaseUpdateMaintenance() {
		return $this->postDatabaseUpdateMaintenance;
	}

	/**
	 * Do all the updates
	 *
	 * @param $purge Boolean: whether to clear the objectcache table after updates
	 */
	public function doUpdates( $purge = true ) {
		global $wgVersion;

		$this->runUpdates( $this->getCoreUpdateList(), false );
		$this->runUpdates( $this->getOldGlobalUpdates(), false );
		$this->runUpdates( $this->getExtensionUpdates(), true );

		$this->setAppliedUpdates( $wgVersion, $this->updates );

		if( $purge ) {
			$this->purgeCache();
		}
		$this->checkStats();
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

	protected function setAppliedUpdates( $version, $updates = array() ) {
		if( !$this->canUseNewUpdatelog() ) {
			return;
		}
		$key = "updatelist-$version-" . time();
		$this->db->insert( 'updatelog',
			array( 'ul_key' => $key, 'ul_value' => serialize( $updates ) ),
			 __METHOD__ );
	}

	/**
	 * Helper function: check if the given key is present in the updatelog table.
	 * Obviously, only use this for updates that occur after the updatelog table was
	 * created!
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
	 * Updatelog was changed in 1.17 to have a ul_value column so we can record
	 * more information about what kind of updates we've done (that's what this
	 * class does). Pre-1.17 wikis won't have this column, and really old wikis
	 * might not even have updatelog at all
	 *
	 * @return boolean
	 */
	protected function canUseNewUpdatelog() {
		return $this->db->tableExists( 'updatelog' ) &&
			$this->db->fieldExists( 'updatelog', 'ul_value' );
	}

	/**
	 * Before 1.17, we used to handle updates via stuff like
	 * $wgExtNewTables/Fields/Indexes. This is nasty :) We refactored a lot
	 * of this in 1.17 but we want to remain back-compatible for awhile. So
	 * load up these old global-based things into our update list.
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
	 */
	protected function applyPatch( $path, $isFullPath = false ) {
		if ( $isFullPath ) {
			$this->db->sourceFile( $path );
		} else {
			$this->db->sourceFile( DatabaseBase::patchPath( $path ) );
		}
	}

	/**
	 * Add a new table to the database
	 * @param $name String Name of the new table
	 * @param $patch String Path to the patch file
	 * @param $fullpath Boolean Whether to treat $patch path as a relative or not
	 */
	protected function addTable( $name, $patch, $fullpath = false ) {
		if ( $this->db->tableExists( $name ) ) {
			wfOut( "...$name table already exists.\n" );
		} else {
			wfOut( "Creating $name table..." );
			$this->applyPatch( $patch, $fullpath );
			wfOut( "ok\n" );
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
		if ( !$this->db->tableExists( $table ) ) {
			wfOut( "...$table table does not exist, skipping new field patch\n" );
		} elseif ( $this->db->fieldExists( $table, $field ) ) {
			wfOut( "...have $field field in $table table.\n" );
		} else {
			wfOut( "Adding $field field to table $table..." );
			$this->applyPatch( $patch, $fullpath );
			wfOut( "ok\n" );
		}
	}

	/**
	 * Add a new index to an existing table
	 * @param $table String Name of the table to modify
	 * @param $index String Name of the new index
	 * @param $patch String Path to the patch file
	 * @param $fullpath Boolean Whether to treat $patch path as a relative or not
	 */
	function addIndex( $table, $index, $patch, $fullpath = false ) {
		if ( $this->db->indexExists( $table, $index ) ) {
			wfOut( "...$index key already set on $table table.\n" );
		} else {
			wfOut( "Adding $index key to table $table... " );
			$this->applyPatch( $patch, $fullpath );
			wfOut( "ok\n" );
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
	function dropField( $table, $field, $patch, $fullpath = false ) {
		if ( $this->db->fieldExists( $table, $field ) ) {
			wfOut( "Table $table contains $field field. Dropping... " );
			$this->applyPatch( $patch, $fullpath );
			wfOut( "ok\n" );
		} else {
			wfOut( "...$table table does not contain $field field.\n" );
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
	function dropIndex( $table, $index, $patch, $fullpath = false ) {
		if ( $this->db->indexExists( $table, $index ) ) {
			wfOut( "Dropping $index from table $table... " );
			$this->applyPatch( $patch, $fullpath );
			wfOut( "ok\n" );
		} else {
			wfOut( "...$index key doesn't exist.\n" );
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
		if ( !$this->db->tableExists( $table ) ) {
			wfOut( "...$table table does not exist, skipping modify field patch\n" );
		} elseif ( !$this->db->fieldExists( $table, $field ) ) {
			wfOut( "...$field field does not exist in $table table, skipping modify field patch\n" );
		} else {
			wfOut( "Modifying $field field of table $table..." );
			$this->applyPatch( $patch, $fullpath );
			wfOut( "ok\n" );
		}
	}

	/**
	 * Purge the objectcache table
	 */
	protected function purgeCache() {
		# We can't guarantee that the user will be able to use TRUNCATE,
		# but we know that DELETE is available to us
		wfOut( "Purging caches..." );
		$this->db->delete( 'objectcache', '*', __METHOD__ );
		wfOut( "done.\n" );
	}

	/**
	 * Check the site_stats table is not properly populated.
	 */
	protected function checkStats() {
		wfOut( "Checking site_stats row..." );
		$row = $this->db->selectRow( 'site_stats', '*', array( 'ss_row_id' => 1 ), __METHOD__ );
		if ( $row === false ) {
			wfOut( "data is missing! rebuilding...\n" );
		} elseif ( isset( $row->site_stats ) && $row->ss_total_pages == -1 ) {
			wfOut( "missing ss_total_pages, rebuilding...\n" );
		} else {
			wfOut( "done.\n" );
			return;
		}
		SiteStatsInit::doAllAndCommit( false );
	}

	# Common updater functions

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
		wfOut( "...ss_active_users user count set...\n" );
	}

	protected function doLogSearchPopulation() {
		if ( $this->updateRowExists( 'populate log_search' ) ) {
			wfOut( "...log_search table already populated.\n" );
			return;
		}

		wfOut(
			"Populating log_search table, printing progress markers. For large\n" .
			"databases, you may want to hit Ctrl-C and do this manually with\n" .
			"maintenance/populateLogSearch.php.\n" );
		$task = new PopulateLogSearch();
		$task->execute();
		wfOut( "Done populating log_search table.\n" );
	}

	function doUpdateTranscacheField() {
		if ( $this->updateRowExists( 'convert transcache field' ) ) {
			wfOut( "...transcache tc_time already converted.\n" );
			return;
		}

		wfOut( "Converting tc_time from UNIX epoch to MediaWiki timestamp... " );
		$this->applyPatch( 'patch-tc-timestamp.sql' );
		wfOut( "ok\n" );
	}

	protected function doCollationUpdate() {
		global $wgCategoryCollation;
		if ( $this->db->selectField(
			'categorylinks',
			'COUNT(*)',
			'cl_collation != ' . $this->db->addQuotes( $wgCategoryCollation ),
			__METHOD__
		) == 0 ) {
			wfOut( "...collations up-to-date.\n" );
			return;
		}

		$task = new UpdateCollation();
		$task->execute();
	}
}
