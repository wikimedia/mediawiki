<?php
/**
 * @defgroup ExternalStorage ExternalStorage
 */
use MediaWiki\Storage\BlobStore;
use MediaWiki\Storage\NotFoundException;
use MediaWiki\Storage\StorageException;
use Wikimedia\Assert\Assert;

/**
 * Interface for data storage in external repositories.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * Constructor class for key/value blob data kept in external repositories.
 *
 * Objects in external stores are defined by a special URL. The URL is of
 * the form "<store protocol>://<location>/<object name>". The protocol is used
 * to determine what ExternalStoreMedium class is used. The location identifies
 * particular storage instances or database clusters for store class to use.
 *
 * When an object is inserted into a store, the calling code uses a partial URL of
 * the form "<store protocol>://<location>" and receives the full object URL on success.
 * This is useful since object names can be sequential IDs, UUIDs, or hashes.
 * Callers are not responsible for unique name generation.
 *
 * External repositories might be populated by maintenance/async
 * scripts, thus partial moving of data may be possible, as well
 * as the possibility to have any storage format (i.e. for archives).
 *
 * @ingroup ExternalStorage
 */
class ExternalStore implements BlobStore {

	/**
	 * @var string[] location urls
	 */
	private $storeLocations;

	/**
	 * @var array associative array of ExternalStoreMedium parameters
	 */
	private $mediumParameters;

	/**
	 * ExternalStore constructor.
	 *
	 * @param string[] $storeLocations List of storage location URLs of the form "medium://path",
	 *                 e.g. [ 'DB://cluster1', 'DB://cluster2' ]. See $wgDefaultExternalStore.
	 * @param array $mediumParameters associative array of ExternalStoreMedium parameters
	 */
	public function __construct( array $storeLocations, array $mediumParameters = [] ) {
		Assert::parameterElementType( 'string', $storeLocations, '$storeLocations' );

		$this->storeLocations = $storeLocations;
		$this->mediumParameters = $mediumParameters;
	}

	/**
	 * @see BlobLookup::loadData
	 *
	 * @param string $address An ExternalStore data URL
	 * @param int $queryFlags
	 *
	 * @throws NotFoundException if the requested data blob was not found
	 * @return string the data loaded from the given address
	 */
	public function loadData( $address, $queryFlags = 0 ) {
		$data = self::fetchFromURL( $address );

		if ( $data === false ) {
			throw new NotFoundException( 'Failed to load blob from address ' . $address );
		}

		return $data;
	}

	/**
	 * @see BlobLookup::getHints
	 *
	 * The current implementation always returns an empty array.
	 *
	 * @param string $address
	 * @param string $fetchBlob
	 *
	 * @return array
	 */
	public function getHints( $address, $fetchBlob = 'no' ) {
		return [];
	}

	/**
	 * @see BlobStore::storeData
	 *
	 * @param string $data
	 * @param array $hints Currently unused.
	 *
	 * @throws StorageException if a storage level error occurred
	 * @return string The address of the stored data, given as the canonical decimal
	 *         representation of the value of the old_id field in the text table.
	 */
	public function storeData( $data, $hints = [] ) {
		$url = self::insertWithFallback(
			$this->storeLocations,
			$data,
			$this->mediumParameters
		);

		if ( $url === false ) {
			throw new StorageException( 'Failed to store blob!' );
		}

		return $url;
	}

	/**
	 * Get an external store object of the given type, with the given parameters
	 *
	 * @param string $proto Type of external storage, should be a value in $wgExternalStores
	 * @param array $params Associative array of ExternalStoreMedium parameters
	 * @return ExternalStoreMedium|bool The store class or false on error
	 */
	public static function getStoreObject( $proto, array $params = [] ) {
		global $wgExternalStores;

		// TODO: use a ServiceContainer instead of $wgExternalStores
		if ( !$wgExternalStores || !in_array( $proto, $wgExternalStores ) ) {
			return false; // protocol not enabled
		}

		$class = 'ExternalStore' . ucfirst( $proto );

		// Any custom modules should be added to $wgAutoLoadClasses for on-demand loading
		return class_exists( $class ) ? new $class( $params ) : false;
	}

	/**
	 * Fetch data from given URL
	 *
	 * @param string $url The URL of the text to get
	 * @param array $params Associative array of ExternalStoreMedium parameters
	 * @return string|bool The text stored or false on error
	 * @throws MWException
	 */
	public static function fetchFromURL( $url, array $params = [] ) {
		$parts = explode( '://', $url, 2 );
		if ( count( $parts ) != 2 ) {
			return false; // invalid URL
		}

		list( $proto, $path ) = $parts;
		if ( $path == '' ) { // bad URL
			return false;
		}

		$store = self::getStoreObject( $proto, $params );
		if ( $store === false ) {
			return false;
		}

		return $store->fetchFromURL( $url );
	}

	/**
	 * Fetch data from multiple URLs with a minimum of round trips
	 *
	 * @param array $urls The URLs of the text to get
	 * @return array Map from url to its data.  Data is either string when found
	 *     or false on failure.
	 */
	public static function batchFetchFromURLs( array $urls ) {
		$batches = [];
		foreach ( $urls as $url ) {
			$scheme = parse_url( $url, PHP_URL_SCHEME );
			if ( $scheme ) {
				$batches[$scheme][] = $url;
			}
		}
		$retval = [];
		foreach ( $batches as $proto => $batchedUrls ) {
			$store = self::getStoreObject( $proto );
			if ( $store === false ) {
				continue;
			}
			$retval += $store->batchFetchFromURLs( $batchedUrls );
		}
		// invalid, not found, db dead, etc.
		$missing = array_diff( $urls, array_keys( $retval ) );
		if ( $missing ) {
			foreach ( $missing as $url ) {
				$retval[$url] = false;
			}
		}

		return $retval;
	}

	/**
	 * Store a data item to an external store, identified by a partial URL
	 * The protocol part is used to identify the class, the rest is passed to the
	 * class itself as a parameter.
	 *
	 * @param string $url A partial external store URL ("<store type>://<location>")
	 * @param string $data
	 * @param array $params Associative array of ExternalStoreMedium parameters
	 * @return string|bool The URL of the stored data item, or false on error
	 * @throws MWException
	 */
	public static function insert( $url, $data, array $params = [] ) {
		$parts = explode( '://', $url, 2 );
		if ( count( $parts ) != 2 ) {
			return false; // invalid URL
		}

		list( $proto, $path ) = $parts;
		if ( $path == '' ) { // bad URL
			return false;
		}

		$store = self::getStoreObject( $proto, $params );
		if ( $store === false ) {
			return false;
		} else {
			return $store->store( $path, $data );
		}
	}

	/**
	 * Like insert() above, but does more of the work for us.
	 * This function does not need a url param, it builds it by
	 * itself. It also fails-over to the next possible clusters
	 * provided by $wgDefaultExternalStore.
	 *
	 * @param string $data
	 * @param array $params Associative array of ExternalStoreMedium parameters
	 * @return string|bool The URL of the stored data item, or false on error
	 * @throws MWException
	 */
	public static function insertToDefault( $data, array $params = [] ) {
		global $wgDefaultExternalStore;

		return self::insertWithFallback( (array)$wgDefaultExternalStore, $data, $params );
	}

	/**
	 * Like insert() above, but does more of the work for us.
	 * This function does not need a url param, it builds it by
	 * itself. It also fails-over to the next possible clusters
	 * as provided in the first parameter.
	 *
	 * @param array $tryStores Refer to $wgDefaultExternalStore
	 * @param string $data
	 * @param array $params Associative array of ExternalStoreMedium parameters
	 * @return string|bool The URL of the stored data item, or false on error
	 * @throws MWException
	 */
	public static function insertWithFallback( array $tryStores, $data, array $params = [] ) {
		$error = false;
		while ( count( $tryStores ) > 0 ) {
			$index = mt_rand( 0, count( $tryStores ) - 1 );
			$storeUrl = $tryStores[$index];
			wfDebug( __METHOD__ . ": trying $storeUrl\n" );
			list( $proto, $path ) = explode( '://', $storeUrl, 2 );
			$store = self::getStoreObject( $proto, $params );
			if ( $store === false ) {
				throw new MWException( "Invalid external storage protocol - $storeUrl" );
			}
			try {
				$url = $store->store( $path, $data ); // Try to save the object
			} catch ( Exception $error ) {
				$url = false;
			}
			if ( strlen( $url ) ) {
				return $url; // Done!
			} else {
				unset( $tryStores[$index] ); // Don't try this one again!
				$tryStores = array_values( $tryStores ); // Must have consecutive keys
				wfDebugLog( 'ExternalStorage',
					"Unable to store text to external storage $storeUrl" );
			}
		}
		// All stores failed
		if ( $error ) {
			throw $error; // rethrow the last error
		} else {
			throw new MWException( "Unable to store text to external storage" );
		}
	}

	/**
	 * @param string $data
	 * @param string $wiki
	 * @return string|bool The URL of the stored data item, or false on error
	 * @throws MWException
	 */
	public static function insertToForeignDefault( $data, $wiki ) {
		return self::insertToDefault( $data, [ 'wiki' => $wiki ] );
	}

}
