<?php
/**
 * 
 * @package MediaWiki
 *
 * DB accessable external objects
 *
 */
require_once( 'LoadBalancer.php' ); 


/** @package MediaWiki */

/**
 * External database storage will use one (or more) separate connection pools
 * from what the main wiki uses. If we load many revisions, such as when doing
 * bulk backups or maintenance, we want to keep them around over the lifetime
 * of the script.
 *
 * Associative array of LoadBalancer objects, indexed by cluster name.
 */
global $wgExternalLoadBalancers;
$wgExternalLoadBalancers = array();

/**
 * One-step cache variable to hold base blobs; operations that
 * pull multiple revisions may often pull multiple times from
 * the same blob. By keeping the last-used one open, we avoid
 * redundant unserialization and decompression overhead.
 */
global $wgExternalBlobCache;
$wgExternalBlobCache = array();

class ExternalStoreDB {
	/**
	 * Fetch data from given URL
	 * @param string $url An url
	 */

	function &getLoadBalancer( $cluster ) {
		global $wgExternalServers, $wgExternalLoadBalancers;
		if ( !array_key_exists( $cluster, $wgExternalLoadBalancers ) ) {
			$wgExternalLoadBalancers[$cluster] = LoadBalancer::newFromParams( $wgExternalServers[$cluster] );
		}
		return $wgExternalLoadBalancers[$cluster];
	}
	
	function &getSlave( $cluster ) {
		$lb =& $this->getLoadBalancer( $cluster );
		return $lb->getConnection( DB_SLAVE );
	}

	function &getMaster( $cluster ) {
		$lb =& $this->getLoadBalancer( $cluster );
		return $lb->getConnection( DB_MASTER );
	}		
	
	function fetchFromURL($url) {
		global $wgExternalServers;
		#
		# URLs have the form DB://cluster/id or DB://cluster/id/itemid for concatenated storage
		#
		$path = explode( '/', $url );
		$cluster  = $path[2];
		$id	  = $path[3];
		if ( isset( $path[4] ) ) {
			$itemID = $path[4];
		} else {
			$itemID = false;
		}

		$ret =& $this->fetchBlob( $cluster, $id, $itemID );

		if ( $itemID !== false ) {
			return $ret->getItem( $itemID );
		}
		return $ret;
	}
	
	/**
	 * Fetch a blob item out of the database; a cache of the last-loaded
	 * blob will be kept so that multiple loads out of a multi-item blob
	 * can avoid redundant database access and decompression.
	 * @return mixed
	 * @access private
	 */
	function &fetchBlob( $cluster, $id, $itemID ) {
		global $wgExternalBlobCache;
		$cacheID = ( $itemID === false ) ? "$cluster/$id" : "$cluster/$id/";
		if( isset( $wgExternalBlobCache[$cacheID] ) ) {
			wfDebug( "ExternalStoreDB::fetchBlob cache hit on $cacheID\n" );
			return $wgExternalBlobCache[$cacheID];
		}
		
		wfDebug( "ExternalStoreDB::fetchBlob cache miss on $cacheID\n" );
		
		$dbr =& $this->getSlave( $cluster );
		$ret = $dbr->selectField( 'blobs', 'blob_text', array( 'blob_id' => $id ) );
		if( $itemID !== false ) {
			// Unserialise object; caller extracts item
			$ret = unserialize( $ret );
		}
		
		$wgExternalBlobCache = array( $cacheID => &$ret );
		return $ret;
	}

	/**
	 * Insert a data item into a given cluster
	 *
	 * @param string $cluster The cluster name
	 * @param string $data The data item
	 * @return string URL
	 */
	function store( $cluster, $data ) {
		global $wgExternalServers;
		$fname = 'ExternalStoreDB::store';

		$dbw =& $this->getMaster( $cluster );

		$id = $dbw->nextSequenceValue( 'blob_blob_id_seq' );
		$dbw->insert( 'blobs', array( 'blob_id' => $id, 'blob_text' => $data ), $fname );
		return "DB://$cluster/" . $dbw->insertId();
	}
}
?>
