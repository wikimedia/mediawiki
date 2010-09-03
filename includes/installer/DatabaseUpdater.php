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

	protected $db;

	protected $shared = false;

	protected $postDatabaseUpdateMaintenance = array(
		'DeleteDefaultMessages'
	);

	protected function __construct( $db, $shared ) {
		$this->db = $db;
		$this->shared = $shared;
	}

	public static function newForDB( $db, $shared ) {
		$type = $db->getType();
		if( in_array( $type, Installer::getDBTypes() ) ) {
			$class = ucfirst( $type ) . 'Updater';
			return new $class( $db, $shared );
		} else {
			throw new MWException( __METHOD__ . ' called for unsupported $wgDBtype' );
		}
	}

	public function getDB() { return $this->db; }

	public function getPostDatabaseUpdateMaintenance() {
		return $this->postDatabaseUpdateMaintenance;
	}

	public function doUpdates() {
		global $IP, $wgVersion;
		require_once( "$IP/maintenance/updaters.inc" );
		$this->updates = array_merge( $this->getCoreUpdateList(),
			$this->getOldGlobalUpdates() );
		foreach ( $this->updates as $params ) {
			$func = array_shift( $params );
			if( !is_array( $func ) && method_exists( $this, $func ) ) {
				$func = array( $this, $func );
			}
			call_user_func_array( $func, $params );
			flush();
		}
		$this->setAppliedUpdates( $wgVersion, $this->updates );
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
	 * Before 1.17, we used to handle updates via stuff like $wgUpdates,
	 * $wgExtNewTables/Fields/Indexes. This is nasty :) We refactored a lot
	 * of this in 1.17 but we want to remain back-compatible for awhile. So
	 * load up these old global-based things into our update list. We can't
	 * version these like we do with our core updates, so they have to go
	 * in 'always'
	 */
	protected function getOldGlobalUpdates() {
		global $wgUpdates, $wgExtNewFields, $wgExtNewTables,
			$wgExtModifiedFields, $wgExtNewIndexes, $wgSharedDB, $wgSharedTables;

		$doUser = $this->shared ?
			$wgSharedDB && in_array( 'user', $wgSharedTables ) :
			!$wgSharedDB || !in_array( 'user', $wgSharedTables );

		$updates = array();

		if( isset( $wgUpdates[ $this->db->getType() ] ) ) {
			foreach( $wgUpdates[ $this->db->getType() ] as $upd ) {
				$updates[] = $upd;
			}
		}

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
				'modify_field', $fieldRecord[0], $fieldRecord[1],
					$fieldRecord[2], true
			);
		}

		return $updates;
	}

	/**
	 * Get an array of updates to perform on the database. Should return a
	 * mutli-dimensional array. The main key is the MediaWiki version (1.12,
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
}
