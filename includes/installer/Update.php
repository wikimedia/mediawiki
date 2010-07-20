<?php

/*
 * Class for handling database updates. Roughly based off of updaters.inc, with
 * a few improvements :)
 */
class Update {

	/**
	 * Array of updates to perform on the database
	 * 
	 * @var array
	 */
	protected $updates = array();

	protected $db;
	
	protected $updater;

	public function __construct( $db ) {
		$this->db = $db;
		switch( $this->db->getType() ) {
			case 'mysql':
				$this->updater = new MysqlUpdater();
				break;
			case 'sqlite':
				$this->updater = new SqliteUpdater();
				break;
			case 'oracle':
				$this->updater = new OracleUpdater();
				break;
			default:
				throw new MWException( __METHOD__ . ' called for unsupported $wgDBtype' );
		}
	}

	public function doUpdates() {
		global $IP;
		require_once( "$IP/maintenance/updaters.inc" );
		$this->loadUpdates();
		foreach ( $this->updates as $version => $updates ) {
			foreach( $updates as $params ) {
				$func = array_shift( $params );
				call_user_func_array( $func, $params );
				flush();
			}
			// some updates don't get recorded :(
			if( $version !== 'always' ) {
				$this->setAppliedUpdates( $version, $updates );
			}
		}
	}

	protected function loadUpdates() {
		// If the updatelog table hasn't been upgraded, we can't use the new
		// style of recording our steps. Run all to be safe
		if( !$this->canUseNewUpdatelog() ) {
			$this->updates = $this->updater->getUpdates();
		} else {
			foreach( $this->updater->getUpdates() as $version => $updates ) {
				$appliedUpdates = $this->getAppliedUpdates( $version );
				if( !$appliedUpdates || $appliedUpdates != $updates ) {
					$this->updates[ $version ] = $updates;
				}
			}
		}
		$this->getOldGlobalUpdates();
	}

	protected function getAppliedUpdates( $version ) {
		$key = "updatelist-$version";
		$val = $this->db->selectField( 'updatelog', 'ul_value',
			array( 'ul_key' => $key ), __METHOD__ );
		if( !$val ) {
			return null;
		} else {
			return unserialize( $val );
		}
	}

	protected function setAppliedUpdates( $version, $updates = array() ) {
		if( !$this->canUseNewUpdatelog() ) {
			return;
		}
		$key = "updatelist-$version";
		$this->db->delete( 'updatelog', array( 'ul_key' => $key ), __METHOD__ );
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
	private function getOldGlobalUpdates() {
		global $wgUpdates, $wgExtNewFields, $wgExtNewTables,
			$wgExtModifiedFields, $wgExtNewIndexes;

		if( isset( $wgUpdates[ $this->db->getType() ] ) ) {
			foreach( $wgUpdates[ $this->db->getType() ] as $upd ) {
				$this->updates['always'][] = $upd;
			}
		}

		foreach ( $wgExtNewTables as $tableRecord ) {
			$this->updates['always'][] = array(
				'add_table', $tableRecord[0], $tableRecord[1], true
			);
		}

		foreach ( $wgExtNewFields as $fieldRecord ) {
			if ( $fieldRecord[0] != 'user' || $doUser ) {
				$this->updates['always'][] = array(
					'add_field', $fieldRecord[0], $fieldRecord[1],
						$fieldRecord[2], true
				);
			}
		}

		foreach ( $wgExtNewIndexes as $fieldRecord ) {
			$this->updates['always'][] = array(
				'add_index', $fieldRecord[0], $fieldRecord[1],
					$fieldRecord[2], true
			);
		}

		foreach ( $wgExtModifiedFields as $fieldRecord ) {
			$this->updates['always'][] = array(
				'modify_field', $fieldRecord[0], $fieldRecord[1],
					$fieldRecord[2], true
			);
		}
	}
	
}