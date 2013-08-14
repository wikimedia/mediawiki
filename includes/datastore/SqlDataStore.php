<?php

class SqlDataStore extends DataStore {
	/* Store parameters */

	/**
	 * @var int: Batch size for certain operations like getByPrefix
	 */
	protected $batchSize = 500;

	/* End store parameters */

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
		$dbw->replace( 'store',
			array( 'store_key' ),
			array( 'store_key' => $key, 'store_value' => $value ),
			__METHOD__
		);
		wfProfileOut( __METHOD__ );
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
			$count = 0;
			foreach( $res as $row ) {
				$prefix = $row->store_key;
				if( ++$count > $this->batchSize ) {
					break;
				}
				call_user_func( $callback, $row->store_key, $row->store_value );
			}
		} while ( $count > $this->batchSize );

		wfProfileOut( __METHOD__ );
	}

	public function delete( $key ) {
		wfProfileIn( __METHOD__ );
		$dbw = $this->getDB( true );
		$dbw->delete( 'store', array( 'store_key' => $key ), __METHOD__ );
		wfProfileOut( __METHOD__ );
	}

	protected function deleteByPrefixInternal( $prefix ) {
		wfProfileIn( __METHOD__ );
		$dbw = $this->getDB( true );
		$dbw->delete( 'store', array( 'store_key' . $dbw->buildLike( $prefix, $dbw->anyString() ) ), __METHOD__ );
		wfProfileOut( __METHOD__ );
	}

	protected function getDB( $master ) {
		if ( $master ) {
			return wfGetDB( DB_MASTER );
		}
		return wfGetDB( DB_SLAVE );
	}
}
