<?php
/**
 * 
 * @package MediaWiki
 *
 * DB accessable external objects
 *
 */
require_once( 'LoadBalancer.php' ); 



class ExternalStoreDB {
	/* Fetch data from given URL */
	function fetchFromURL($url) {
		global $wgExternalServers;
		#
		# URLs have the form db://cluster/id, e.g.
		# mysql://cluster1/3298247
		#
		$path = explode( '/', $url );
		$cluster  = $path[2];
		$id	  = $path[3];

		$lb = LoadBalancer::NewFromParams( $wgExternalServers[$cluster] );
		$db = $lb->getConnection( DB_SLAVE );

		$ret = $db->selectField( 'text', 'text_text', array( 'text_id' => $id ) ); 
		
		return $ret;
	}

	/* XXX: may require other methods, for store, delete, 
	 * whatever, for initial ext storage  
	 */
}
?>
