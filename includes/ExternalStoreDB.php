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

class ExternalStoreDB {
	var $loadBalancers = array();
	
	/**
	 * Fetch data from given URL
	 * @param string $url An url
	 */

	function &getLoadBalancer( $cluster ) {
		global $wgExternalServers;
		if ( !array_key_exists( $cluster, $this->loadBalancers ) ) {
			$this->loadBalancers[$cluster] = LoadBalancer::newFromParams( $wgExternalServers[$cluster] );
		}
		return $this->loadBalancers[$cluster];
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

		$dbr =& $this->getSlave( $cluster );
		$ret = $dbr->selectField( 'blobs', 'blob_text', array( 'blob_id' => $id ) ); 

		if ( $itemID !== false ) {
			# Unserialise object and get item
			$obj = unserialize( $ret );
			$ret = $obj->getItem( $itemID );
		}
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
