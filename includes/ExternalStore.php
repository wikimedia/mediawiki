<?php
/**
 * @defgroup ExternalStorage ExternalStorage
 */

/**
 * Constructor class for data kept in external repositories
 *
 * External repositories might be populated by maintenance/async
 * scripts, thus partial moving of data may be possible, as well
 * as possibility to have any storage format (i.e. for archives)
 *
 * @ingroup ExternalStorage
 */
class ExternalStore {
	/* Fetch data from given URL */
	static function fetchFromURL( $url ) {
		global $wgExternalStores;

		if( !$wgExternalStores )
			return false;

		@list( $proto, $path ) = explode( '://', $url, 2 );
		/* Bad URL */
		if( $path == '' )
			return false;

		$store = self::getStoreObject( $proto );
		if ( $store === false )
			return false;
		return $store->fetchFromURL( $url );
	}

	/**
	 * Get an external store object of the given type
	 */
	static function getStoreObject( $proto ) {
		global $wgExternalStores;
		if( !$wgExternalStores )
			return false;
		/* Protocol not enabled */
		if( !in_array( $proto, $wgExternalStores ) )
			return false;

		$class = 'ExternalStore' . ucfirst( $proto );
		/* Any custom modules should be added to $wgAutoLoadClasses for on-demand loading */
		if( !class_exists( $class ) ) {
			return false;
		}

		return new $class();
	}

	/**
	 * Store a data item to an external store, identified by a partial URL
	 * The protocol part is used to identify the class, the rest is passed to the
	 * class itself as a parameter.
	 * Returns the URL of the stored data item, or false on error
	 */
	static function insert( $url, $data ) {
		list( $proto, $params ) = explode( '://', $url, 2 );
		$store = self::getStoreObject( $proto );
		if ( $store === false ) {
			return false;
		} else {
			return $store->store( $params, $data );
		}
	}
	
	/**
	 * Like insert() above, but does more of the work for us.
	 * This function does not need a url param, it builds it by
	 * itself. It also fails-over to the next possible clusters.
	 *
	 * @param string $data
	 * Returns the URL of the stored data item, or false on error
	 */
	public static function randomInsert( $data ) {
		global $wgDefaultExternalStore;
		$tryStorages = (array)$wgDefaultExternalStore;
		// Do not wait and do second retry per master if we
		// have other active cluster masters to try instead.
		$retry = count($tryStorages) > 1 ? false : true;
		while ( count($tryStorages) > 0 ) {
			$index = mt_rand(0, count( $tryStorages ) - 1);
			$storeUrl = $tryStorages[$index];
			list( $proto, $params ) = explode( '://', $storeUrl, 2 );
			$store = self::getStoreObject( $proto );
			if ( $store === false ) {
				throw new MWException( "Invalid external storage protocol - $storeUrl" );
				return false; 
			} else {
				$url = $store->store( $params, $data, $retry ); // Try to save the object
				if ( $url ) {
					return $url; // Done!
				} else {
					unset( $tryStorages[$index] ); // Don't try this one again!
					sort( $tryStorages ); // Must have consecutive keys
					wfDebugLog( 'ExternalStorage', "Unable to store text to external storage $storeUrl" );
				}
			}
		}
		throw new MWException( "Unable to store text to external storage" );
		return false; // All cluster masters dead :(
	}
}
