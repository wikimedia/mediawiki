<?php

/**
 * DB accessable external objects
 * @ingroup ExternalStorage
 */
class ExternalStoreDB {

	function __construct( $params = array() ) {
		$this->mParams = $params;
	}

	/**
	 * Get a LoadBalancer for the specified cluster
	 *
	 * @param $cluster String: cluster name
	 * @return LoadBalancer object
	 */
	function &getLoadBalancer( $cluster ) {
		$wiki = isset($this->mParams['wiki']) ? $this->mParams['wiki'] : false;
		
		return wfGetLBFactory()->getExternalLB( $cluster, $wiki );
	}

	/**
	 * Get a slave database connection for the specified cluster
	 *
	 * @param $cluster String: cluster name
	 * @return DatabaseBase object
	 */
	function &getSlave( $cluster ) {
		$wiki = isset($this->mParams['wiki']) ? $this->mParams['wiki'] : false;
		$lb =& $this->getLoadBalancer( $cluster );
		return $lb->getConnection( DB_SLAVE, array(), $wiki );
	}

	/**
	 * Get a master database connection for the specified cluster
	 *
	 * @param $cluster String: cluster name
	 * @return DatabaseBase object
	 */
	function &getMaster( $cluster ) {
		$wiki = isset($this->mParams['wiki']) ? $this->mParams['wiki'] : false;
		$lb =& $this->getLoadBalancer( $cluster );
		return $lb->getConnection( DB_MASTER, array(), $wiki );
	}

	/**
	 * Get the 'blobs' table name for this database
	 *
	 * @param $db DatabaseBase
	 * @return String: table name ('blobs' by default)
	 */
	function getTable( &$db ) {
		$table = $db->getLBInfo( 'blobs table' );
		if ( is_null( $table ) ) {
			$table = 'blobs';
		}
		return $table;
	}

	/**
	 * Fetch data from given URL
	 * @param $url String: an url of the form DB://cluster/id or DB://cluster/id/itemid for concatened storage.
	 */
	function fetchFromURL( $url ) {
		$path = explode( '/', $url );
		$cluster  = $path[2];
		$id	  = $path[3];
		if ( isset( $path[4] ) ) {
			$itemID = $path[4];
		} else {
			$itemID = false;
		}

		$ret =& $this->fetchBlob( $cluster, $id, $itemID );

		if ( $itemID !== false && $ret !== false ) {
			return $ret->getItem( $itemID );
		}
		return $ret;
	}

	/**
	 * Fetch a blob item out of the database; a cache of the last-loaded
	 * blob will be kept so that multiple loads out of a multi-item blob
	 * can avoid redundant database access and decompression.
	 * @param $cluster
	 * @param $id
	 * @param $itemID
	 * @return mixed
	 * @private
	 */
	function &fetchBlob( $cluster, $id, $itemID ) {
		/**
		 * One-step cache variable to hold base blobs; operations that
		 * pull multiple revisions may often pull multiple times from
		 * the same blob. By keeping the last-used one open, we avoid
		 * redundant unserialization and decompression overhead.
		 */
		static $externalBlobCache = array();

		$cacheID = ( $itemID === false ) ? "$cluster/$id" : "$cluster/$id/";
		if( isset( $externalBlobCache[$cacheID] ) ) {
			wfDebug( "ExternalStoreDB::fetchBlob cache hit on $cacheID\n" );
			return $externalBlobCache[$cacheID];
		}

		wfDebug( "ExternalStoreDB::fetchBlob cache miss on $cacheID\n" );

		$dbr =& $this->getSlave( $cluster );
		$ret = $dbr->selectField( $this->getTable( $dbr ), 'blob_text', array( 'blob_id' => $id ) );
		if ( $ret === false ) {
			wfDebugLog( 'ExternalStoreDB', "ExternalStoreDB::fetchBlob master fallback on $cacheID\n" );
			// Try the master
			$dbw =& $this->getMaster( $cluster );
			$ret = $dbw->selectField( $this->getTable( $dbw ), 'blob_text', array( 'blob_id' => $id ) );
			if( $ret === false) {
				wfDebugLog( 'ExternalStoreDB', "ExternalStoreDB::fetchBlob master failed to find $cacheID\n" );
			}
		}
		if( $itemID !== false && $ret !== false ) {
			// Unserialise object; caller extracts item
			$ret = unserialize( $ret );
		}

		$externalBlobCache = array( $cacheID => &$ret );
		return $ret;
	}

	/**
	 * Insert a data item into a given cluster
	 *
	 * @param $cluster String: the cluster name
	 * @param $data String: the data item
	 * @return string URL
	 */
	function store( $cluster, $data ) {
		$dbw = $this->getMaster( $cluster );
		$id = $dbw->nextSequenceValue( 'blob_blob_id_seq' );
		$dbw->insert( $this->getTable( $dbw ), 
			array( 'blob_id' => $id, 'blob_text' => $data ), 
			__METHOD__ );
		$id = $dbw->insertId();
		if ( !$id ) {
			throw new MWException( __METHOD__.': no insert ID' );
		}
		if ( $dbw->getFlag( DBO_TRX ) ) {
			$dbw->commit();
		}
		return "DB://$cluster/$id";
	}
}
