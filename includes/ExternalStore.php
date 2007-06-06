<?php
/**
 *
 *
 * Constructor class for data kept in external repositories
 *
 * External repositories might be populated by maintenance/async
 * scripts, thus partial moving of data may be possible, as well
 * as possibility to have any storage format (i.e. for archives)
 *
 */

class ExternalStore {
	/* Fetch data from given URL */
	function fetchFromURL($url) {
		global $wgExternalStores;

		if (!$wgExternalStores)
			return false;

		@list($proto,$path)=explode('://',$url,2);
		/* Bad URL */
		if ($path=="")
			return false;

		$store =& ExternalStore::getStoreObject( $proto );
		if ( $store === false )
			return false;
		return $store->fetchFromURL($url);
	}

	/**
	 * Get an external store object of the given type
	 */
	function &getStoreObject( $proto ) {
		global $wgExternalStores;
		if (!$wgExternalStores)
			return false;
		/* Protocol not enabled */
		if (!in_array( $proto, $wgExternalStores ))
			return false;

		$class='ExternalStore'.ucfirst($proto);
		/* Any custom modules should be added to $wgAutoLoadClasses for on-demand loading */
		if (!class_exists($class)) {
			return false;
		}
		$store=new $class();
		return $store;
	}

	/**
	 * Store a data item to an external store, identified by a partial URL
	 * The protocol part is used to identify the class, the rest is passed to the
	 * class itself as a parameter.
	 * Returns the URL of the stored data item, or false on error
	 */
	function insert( $url, $data ) {
		list( $proto, $params ) = explode( '://', $url, 2 );
		$store =& ExternalStore::getStoreObject( $proto );
		if ( $store === false ) {
			return false;
		} else {
			return $store->store( $params, $data );
		}
	}
}
?>
