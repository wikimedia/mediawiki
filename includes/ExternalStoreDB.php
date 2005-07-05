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
	/**
	 * Fetch data from given URL
	 * @param string $url An url
	 */
	function fetchFromURL($url) {
		global $wgExternalServers;
		#
		# URLs have the form DB://cluster/id, e.g.
		# DB://cluster1/3298247
		#
		$path = explode( '/', $url );
		$cluster  = $path[2];
		$id	  = $path[3];

		$lb = LoadBalancer::NewFromParams( $wgExternalServers[$cluster] );
		$db = $lb->getConnection( DB_SLAVE );

		$ret = $db->selectField( 'blobs', 'blob_text', array( 'blob_id' => $id ) ); 
		
		return $ret;
	}

	/* @fixme XXX: may require other methods, for store, delete, 
	 * whatever, for initial ext storage  
	 */
}
?>
