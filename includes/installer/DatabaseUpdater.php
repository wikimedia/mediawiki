<?php

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

	protected function __construct( $db, $shared ) {
		$this->db = $db;
		$this->shared = $shared;
	}

	public static function newForDB( $db, $shared ) {
		switch( $db->getType() ) {
			case 'mysql':
			case 'sqlite':
			case 'oracle':
				$class = ucfirst( $db->getType() ) . 'Updater';
				return new $class( $db, $shared );
			default:
				throw new MWException( __METHOD__ . ' called for unsupported $wgDBtype' );
		}
	}

	public function doUpdates( $doUser = false ) {
		global $IP, $wgVersion;
		require_once( "$IP/maintenance/updaters.inc" );
		$this->updates = array_merge( $this->getCoreUpdateList(),
			$this->getOldGlobalUpdates( $doUser ) );
		foreach ( $this->updates as $params ) {
			$func = array_shift( $params );
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
	private function getOldGlobalUpdates( $douser ) {
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
				'add_table', $tableRecord[0], $tableRecord[1], true
			);
		}

		foreach ( $wgExtNewFields as $fieldRecord ) {
			if ( $fieldRecord[0] != 'user' || $doUser ) {
				$updates[] = array(
					'add_field', $fieldRecord[0], $fieldRecord[1],
						$fieldRecord[2], true
				);
			}
		}

		foreach ( $wgExtNewIndexes as $fieldRecord ) {
			$updates[] = array(
				'add_index', $fieldRecord[0], $fieldRecord[1],
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
}

class OracleUpdater extends DatabaseUpdater {
	protected function getCoreUpdateList() {
		return array();
	}
}
