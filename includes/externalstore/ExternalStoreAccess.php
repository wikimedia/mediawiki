<?php
/**
 * @defgroup ExternalStorage ExternalStorage
 */

use \Psr\Log\LoggerAwareInterface;
use \Psr\Log\LoggerInterface;
use \Psr\Log\NullLogger;

/**
 * Key/value blob storage for a collection of storage medium types (e.g. RDBMs, files)
 *
 * Multiple medium types can be active and each one can have multiple "locations" available.
 * Blobs are stored under URLs of the form "<protocol>://<location>/<path>". Each type of storage
 * medium has an associated protocol. Insertions will randomly pick mediums and locations from
 * the provided list of writable medium-qualified locations. Insertions will also fail-over to
 * other writable locations or mediums if one or more are not available.
 *
 * @ingroup ExternalStorage
 * @since 1.34
 */
class ExternalStoreAccess implements LoggerAwareInterface {
	/** @var ExternalStoreFactory */
	private $storeFactory;
	/** @var LoggerInterface */
	private $logger;

	/**
	 * @param ExternalStoreFactory $factory
	 * @param LoggerInterface|null $logger
	 */
	public function __construct( ExternalStoreFactory $factory, LoggerInterface $logger = null ) {
		$this->storeFactory = $factory;
		$this->logger = $logger ?: new NullLogger();
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Fetch data from given URL
	 *
	 * @see ExternalStoreFactory::getStore()
	 *
	 * @param string $url The URL of the text to get
	 * @param array $params Map of context parameters; same as ExternalStoreFactory::getStore()
	 * @return string|bool The text stored or false on error
	 * @throws ExternalStoreException
	 */
	public function fetchFromURL( $url, array $params = [] ) {
		return $this->storeFactory->getStoreForUrl( $url, $params )->fetchFromURL( $url );
	}

	/**
	 * Fetch data from multiple URLs with a minimum of round trips
	 *
	 * @see ExternalStoreFactory::getStore()
	 *
	 * @param array $urls The URLs of the text to get
	 * @param array $params Map of context parameters; same as ExternalStoreFactory::getStore()
	 * @return array Map of (url => string or false if not found)
	 * @throws ExternalStoreException
	 */
	public function fetchFromURLs( array $urls, array $params = [] ) {
		$batches = $this->storeFactory->getUrlsByProtocol( $urls );
		$retval = [];
		foreach ( $batches as $proto => $batchedUrls ) {
			$store = $this->storeFactory->getStore( $proto, $params );
			$retval += $store->batchFetchFromURLs( $batchedUrls );
		}
		// invalid, not found, db dead, etc.
		$missing = array_diff( $urls, array_keys( $retval ) );
		foreach ( $missing as $url ) {
			$retval[$url] = false;
		}

		return $retval;
	}

	/**
	 * Insert data into storage and return the assigned URL
	 *
	 * This will randomly pick one of the available write storage locations to put the data.
	 * It will keep failing-over to any untried storage locations whenever one location is
	 * not usable.
	 *
	 * @see ExternalStoreFactory::getStore()
	 *
	 * @param string $data
	 * @param array $params Map of context parameters; same as ExternalStoreFactory::getStore()
	 * @param string[]|null $tryStores Base URLs to try, e.g. [ "DB://cluster1" ]
	 * @return string|bool The URL of the stored data item, or false on error
	 * @throws ExternalStoreException
	 */
	public function insert( $data, array $params = [], array $tryStores = null ) {
		$tryStores = $tryStores ?? $this->storeFactory->getWriteBaseUrls();
		if ( !$tryStores ) {
			throw new ExternalStoreException( "List of external stores provided is empty." );
		}

		$error = false;
		while ( count( $tryStores ) > 0 ) {
			$index = mt_rand( 0, count( $tryStores ) - 1 );
			$storeUrl = $tryStores[$index];

			$this->logger->debug( __METHOD__ . ": trying $storeUrl\n" );

			$store = $this->storeFactory->getStoreForUrl( $storeUrl, $params );
			if ( $store === false ) {
				throw new ExternalStoreException( "Invalid external storage protocol - $storeUrl" );
			}

			$location = $this->storeFactory->getStoreLocationFromUrl( $storeUrl );
			try {
				if ( $store->isReadOnly( $location ) ) {
					$msg = 'read only';
				} else {
					$url = $store->store( $location, $data );
					if ( strlen( $url ) ) {
						return $url; // a store accepted the write; done!
					}
					$msg = 'operation failed';
				}
			} catch ( Exception $error ) {
				$msg = 'caught ' . get_class( $error ) . ' exception: ' . $error->getMessage();
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
			throw new ExternalStoreException( "Unable to store text to external storage" );
		}
	}

	/**
	 * @param string[]|string|null $storeUrls Base URL(s) to check, e.g. [ "DB://cluster1" ]
	 * @return bool Whether all the default insertion stores are marked as read-only
	 * @throws ExternalStoreException
	 */
	public function isReadOnly( $storeUrls = null ) {
		if ( $storeUrls === null ) {
			$storeUrls = $this->storeFactory->getWriteBaseUrls();
		} else {
			$storeUrls = is_array( $storeUrls ) ? $storeUrls : [ $storeUrls ];
		}

		if ( !$storeUrls ) {
			return false; // no stores exists which can be "read only"
		}

		foreach ( $storeUrls as $storeUrl ) {
			$store = $this->storeFactory->getStoreForUrl( $storeUrl );
			$location = $this->storeFactory->getStoreLocationFromUrl( $storeUrl );
			if ( $store !== false && !$store->isReadOnly( $location ) ) {
				return false; // at least one store is not read-only
			}
		}

		return true; // all stores are read-only
	}
}
