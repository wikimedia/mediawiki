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
	var $mParams;
	
	function __construct( $params = array() ) {
		$this->mParams = $params;
	}
	
	/**
	 * Fetch data from given URL
	 *
	 * @param $url String: The URL of the text to get
	 * @param $params Array: associative array of parameters for the ExternalStore object.
	 * @return The text stored or false on error
	 */
	static function fetchFromURL( $url, $params = array() ) {
		global $wgExternalStores;

		if( !$wgExternalStores )
			return false;

		$parts = explode( '://', $url, 2 );

		if ( count( $parts ) != 2 ) {
			return false;
		}

		list( $proto, $path ) = $parts;

		if ( $path == '' ) { // Bad URL
			return false;
		}

		$store = self::getStoreObject( $proto, $params );
		if ( $store === false )
			return false;
		return $store->fetchFromURL( $url );
	}

	/**
	 * Get an external store object of the given type, with the given parameters
	 *
	 * @param $proto String: type of external storage, should be a value in $wgExternalStores
	 * @param $params Array: associative array of parameters for the ExternalStore object.
	 * @return ExternalStore subclass or false on error
	 */
	static function getStoreObject( $proto, $params = array() ) {
		global $wgExternalStores;
		if( !$wgExternalStores )
			return false;
		/* Protocol not enabled */
		if( !in_array( $proto, $wgExternalStores ) )
			return false;

		$class = 'ExternalStore' . ucfirst( $proto );
		/* Any custom modules should be added to $wgAutoLoadClasses for on-demand loading */
		if( !MWInit::classExists( $class ) ) {
			return false;
		}

		return new $class($params);
	}

	/**
	 * Store a data item to an external store, identified by a partial URL
	 * The protocol part is used to identify the class, the rest is passed to the
	 * class itself as a parameter.
	 * @return The URL of the stored data item, or false on error
	 */
	static function insert( $url, $data, $params = array() ) {
		list( $proto, $params ) = explode( '://', $url, 2 );
		$store = self::getStoreObject( $proto, $params );
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
	 * @param $data String
	 * @param $storageParams Array: associative array of parameters for the ExternalStore object.
	 * @return The URL of the stored data item, or false on error
	 */
	public static function insertToDefault( $data, $storageParams = array() ) {
		global $wgDefaultExternalStore;
		$tryStores = (array)$wgDefaultExternalStore;
		$error = false;
		while ( count( $tryStores ) > 0 ) {
			$index = mt_rand(0, count( $tryStores ) - 1);
			$storeUrl = $tryStores[$index];
			wfDebug( __METHOD__.": trying $storeUrl\n" );
			list( $proto, $params ) = explode( '://', $storeUrl, 2 );
			$store = self::getStoreObject( $proto, $storageParams );
			if ( $store === false ) {
				throw new MWException( "Invalid external storage protocol - $storeUrl" );
			}
			try {
				$url = $store->store( $params, $data ); // Try to save the object
			} catch ( DBConnectionError $error ) {
				$url = false;
			} catch( DBQueryError $error ) {
				$url = false;
			}
			if ( $url ) {
				return $url; // Done!
			} else {
				unset( $tryStores[$index] ); // Don't try this one again!
				$tryStores = array_values( $tryStores ); // Must have consecutive keys
				wfDebugLog( 'ExternalStorage', "Unable to store text to external storage $storeUrl" );
			}
		}
		// All stores failed
		if ( $error ) {
			// Rethrow the last connection error
			throw $error;
		} else {
			throw new MWException( "Unable to store text to external storage" );
		}
	}
	
	/** Like insertToDefault, but inserts on another wiki */
	public static function insertToForeignDefault( $data, $wiki ) {
		return self::insertToDefault( $data, array( 'wiki' => $wiki ) );
	}
}
