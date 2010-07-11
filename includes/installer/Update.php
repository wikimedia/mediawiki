<?php
/*
 * Class for handling database updates. Roughly based off of updaters.inc, with
 * a few improvements :)
 */

class Update {

	// Array of updates to perform on the database
	protected $updates = array();

	// Things we'll need
	protected $db, $updater;

	public function __construct( $db ) {
		$this->db = $db;
		switch( $this->db->getType() ) {
			case 'mysql':
				$this->updater = new MysqlUpdater();
				break;
			case 'sqlite':
				$this->updater = new SqliteUpdater();
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
			$this->setAppliedUpdates( $version, $updates );
		}
	}

	protected function loadUpdates() {
		// If the updatelog table hasn't been upgraded, we can't use the new
		// style of recording our steps. Run all to be safe
		if( !$this->canUseNewUpdatelog() ) {
			$this->updates = $this->updater->getUpdates();
			return;
		}
		foreach( $this->updater->getUpdates() as $version => $updates ) {
			$appliedUpdates = $this->getAppliedUpdates( $version );
			if( !$appliedUpdates || $appliedUpdates != $updates ) {
				$this->updates[ $version ] = $updates;
			}
		}
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

	private function setAppliedUpdates( $version, $updates = array() ) {
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
}
