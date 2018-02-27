<?php
/**
 * @defgroup ExternalStorage ExternalStorage
 */

use MediaWiki\MediaWikiServices;
use \Psr\Log\LoggerAwareInterface;
use \Psr\Log\LoggerInterface;
use \Psr\Log\NullLogger;

/**
 * @ingroup ExternalStorage
 */
class ExternalStoreFactory implements LoggerAwareInterface {
	/** @var string[] List of external store protocols */
	private $stores;
	/** @var string[] List of external store base URLs for insertToDefault() */
	private $defaultStores;
	/** @var string Default database domain to store content under */
	private $localDomainId;
	/** @var LoggerInterface */
	private $logger;

	/**
	 * @param string[] $externalStores See $wgExternalStores
	 * @param string[] $defaultStores See $wgDefaultExternalStore
	 * @param string $localDomainId Local database/wiki ID
	 * @param LoggerInterface|null $logger
	 */
	public function __construct(
		array $externalStores,
		array $defaultStores,
		$localDomainId,
		LoggerInterface $logger = null
	) {
		$this->stores = array_map( 'strtolower', $externalStores );
		$this->defaultStores = $defaultStores;
		$this->localDomainId = $localDomainId;
		$this->logger = $logger ?: new NullLogger();
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Get an external store object of the given type, with the given parameters
	 *
	 * @param string $proto Type of external storage, should be a value in $wgExternalStores
	 * @param array $params Map of ExternalStoreMedium::__construct context parameters
	 * @return ExternalStoreMedium|bool The store class or false on error
	 */
	public function getStoreObject( $proto, array $params = [] ) {
		$protoLowercase = strtolower( $proto ); // normalize
		if ( !$this->stores || !in_array( $protoLowercase, $this->stores ) ) {
			// Protocol not enabled
			return false;
		}

		$class = 'ExternalStore' . ucfirst( $proto );
		$params += [ 'wiki' => $this->localDomainId ]; // default "wiki"
		$params['writableLocations'] = [];
		// Determine the locations for this protocol/store still receiving writes
		foreach ( $this->defaultStores as $storeUrl ) {
			list( $storeProto, $storePath ) = self::splitStoreUrl( $storeUrl );
			if ( $protoLowercase === strtolower( $storeProto ) ) {
				$params['writableLocations'][] = $storePath;
			}
		}
		// @TODO: ideally, this class should not hardcode what classes need what backend factory
		// objects. For now, inject the factory instances into __construct() for those that do.
		if ( $class === 'ExternalStoreDB' ) {
			$params['lbFactory'] = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		} elseif ( $class === 'ExternalStoreMwstore' ) {
			$params['fbGroup'] = FileBackendGroup::singleton();
		}
		$params['logger'] = $this->logger;

		// Any custom modules should be added to $wgAutoLoadClasses for on-demand loading
		return class_exists( $class ) ? new $class( $params ) : false;
	}

	/**
	 * Fetch data from given URL
	 *
	 * @param string $url The URL of the text to get
	 * @param array $params Map of ExternalStoreMedium::__construct context parameters
	 * @return string|bool The text stored or false on error
	 * @throws MWException
	 * @since 1.31
	 */
	public function fetchFromURL( $url, array $params = [] ) {
		$parts = self::splitStoreUrl( $url, 'no_validate' );
		if ( count( $parts ) != 2 ) {
			return false; // invalid URL
		}

		list( $proto, $path ) = $parts;
		if ( $path == '' ) { // bad URL
			return false;
		}

		$store = $this->getStoreObject( $proto, $params );
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
	 * @throws MWException
	 * @since 1.31
	 */
	public function batchFetchFromURLs( array $urls ) {
		$batches = [];
		foreach ( $urls as $url ) {
			$scheme = parse_url( $url, PHP_URL_SCHEME );
			if ( $scheme ) {
				$batches[$scheme][] = $url;
			}
		}
		$retval = [];
		foreach ( $batches as $proto => $batchedUrls ) {
			$store = $this->getStoreObject( $proto );
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
	 * @param array $params Map of ExternalStoreMedium::__construct context parameters
	 * @return string|bool The URL of the stored data item, or false on error
	 * @throws MWException
	 * @since 1.31
	 */
	public function insert( $url, $data, array $params = [] ) {
		$parts = self::splitStoreUrl( $url, 'no_validate' );
		if ( count( $parts ) != 2 ) {
			return false; // invalid URL
		}

		list( $proto, $path ) = $parts;
		if ( $path == '' ) { // bad URL
			return false;
		}

		$store = $this->getStoreObject( $proto, $params );
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
	 * @param array $params Map of ExternalStoreMedium::__construct context parameters
	 * @return string|bool The URL of the stored data item, or false on error
	 * @throws MWException
	 */
	public function insertToDefault( $data, array $params = [] ) {
		return $this->insertWithFallback( $this->defaultStores, $data, $params );
	}

	/**
	 * Like insert() above, but does more of the work for us.
	 * This function does not need a url param, it builds it by
	 * itself. It also fails-over to the next possible clusters
	 * as provided in the first parameter.
	 *
	 * @param array $tryStores Refer to $wgDefaultExternalStore
	 * @param string $data
	 * @param array $params Map of ExternalStoreMedium::__construct context parameters
	 * @return string|bool The URL of the stored data item, or false on error
	 * @throws MWException
	 * @since 1.31
	 */
	public function insertWithFallback( array $tryStores, $data, array $params = [] ) {
		if ( !$tryStores ) {
			throw new MWException( "List of external stores provided is empty." );
		}

		$error = false;
		while ( count( $tryStores ) > 0 ) {
			$index = mt_rand( 0, count( $tryStores ) - 1 );
			$storeUrl = $tryStores[$index];

			$this->logger->debug( __METHOD__ . ": trying $storeUrl\n" );

			list( $proto, $path ) = self::splitStoreUrl( $storeUrl );
			$store = $this->getStoreObject( $proto, $params );
			if ( $store === false ) {
				throw new MWException( "Invalid external storage protocol - $storeUrl" );
			}

			try {
				if ( $store->isReadOnly( $path ) ) {
					$msg = 'read only';
				} else {
					$url = $store->store( $path, $data );
					if ( strlen( $url ) ) {
						return $url; // a store accepted the write; done!
					}
					$msg = 'operation failed';
				}
			} catch ( Exception $error ) {
				$msg = 'caught exception';
			}

			unset( $tryStores[$index] ); // Don't try this one again!
			$tryStores = array_values( $tryStores ); // Must have consecutive keys
			$this->logger->error(
				"Unable to store text to external storage {store_path} ({failure})",
				[ 'store_path' => $storeUrl, 'failure' => $msg ]
			);
		}
		// All stores failed
		if ( $error ) {
			throw $error; // rethrow the last error
		} else {
			throw new MWException( "Unable to store text to external storage" );
		}
	}

	/**
	 * @return bool Whether all the default insertion stores are marked as read-only
	 * @since 1.31
	 */
	public function defaultStoresAreReadOnly() {
		if ( !$this->defaultStores ) {
			return false; // no stores exists which can be "read only"
		}

		foreach ( $this->defaultStores as $storeUrl ) {
			list( $proto, $path ) = self::splitStoreUrl( $storeUrl );
			$store = $this->getStoreObject( $proto );
			if ( !$store->isReadOnly( $path ) ) {
				return false; // at least one store is not read-only
			}
		}

		return true; // all stores are read-only
	}

	/**
	 * @param string $storeUrl
	 * @param string|null $validate
	 * @return string[] (protocol, store location or location-qualified path)
	 */
	private static function splitStoreUrl( $storeUrl, $validate = 'validate' ) {
		$parts = explode( '://', $storeUrl, 2 );
		if ( $validate === 'validate' && count( $parts ) != 2 ) {
			throw new InvalidArgumentException( "Invalid store URL '$storeUrl'" );
		}

		return $parts;
	}
}
