<?php

class SqlDataStore extends DataStore {
	private $batchSize = 500;
	public function __construct( $config ) {

	}

	public function get( $key, $latest = false ) {
		wfProfileIn( __METHOD__ );
		$db = $this->getDB( $latest );
		$res = $db->selectField( 'store', 'store_value', array( 'store_key' => $key ), __METHOD__ );
		wfProfileOut( __METHOD__ );

		return $res === false ? null : $res;
	}

	public function set( $key, $value ) {
		wfProfileIn( __METHOD__ );
		$dbw = $this->getDB( true );
		$dbw->insert( 'store', array( 'store_key' => $key, 'store_value' => $value ), __METHOD__, array( 'UPDATE' ) );
	}

	public function getByPrefix( $prefix, $callback, $latest = false ) {
		wfProfileIn( __METHOD__ );

		$db = $this->getDB( $latest );
		do {
			$res = $db->select( 'store',
				array( 'store_key', 'store_value' ),
				array( "store_key >= {$db->addQuotes( $prefix )}" ),
				__METHOD__,
				array( 'ORDER BY' => 'store_key', 'LIMIT' => $this->batchSize + 1 )
			);
		} while ( true );
		wfProfileOut( __METHOD__ );
	}

	protected function getDB( $master ) {
		if ( $master ) {
			return wfGetDB( DB_MASTER );
		}
		return wfGetDB( DB_SLAVE );
	}
};
