<?php
/*
 * Class for handling database updates. Roughly based off of updaters.inc, with
 * a few improvements :)
 */

class Update {

	// Array of updates to perform on the database
	protected $updates = array();

	// Thing we'll need
	protected $db, $updater;

	public function __construct( $db ) {
		$this->db = $db;
		switch( $this->db->getType() ) {
			case 'mysql':
				$this->updater = new MysqlUpdaters();
				break;
			case 'sqlite':
				$this->updater = new SqliteUpdaters();
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
		foreach( $this->updater->getUpdates() as $version => $updates ) {
			$appliedUpdates = $this->getAppliedUpdates( $version );
			if( !$appliedUpdates || count( $appliedUpdates ) != count( $updates ) ) {
				$this->updates[ $version ] = $updates;
			}
		}
	}

	private function getAppliedUpdates( $version ) {
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
		$key = "updatelist-$version";
		$this->db->delete( 'updatelog', array( 'ul_key' => $key ), __METHOD__ );
		$this->db->insert( 'updatelog',
			array( 'ul_key' => $key, 'ul_value' => serialize( $updates ) ),
			 __METHOD__ );
	}
}
