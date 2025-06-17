<?php
/**
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

use MediaWiki\Exception\ReadOnlyError;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Wikimedia\RequestTimeout\TimeoutException;

/**
 * @defgroup ExternalStorage ExternalStorage
 *
 * Object storage outside the main database, see also [ExternalStore Architecture](@ref externalstorearch).
 */

/**
 * This is the main interface for fetching or inserting objects with [ExternalStore](@ref externalstorearch).
 *
 * This interface is meant to mimic the ExternalStoreMedium base class (which
 * represents a single external store protocol), and transparently uses the
 * right instance of that class when fetching by URL.
 *
 * @see [ExternalStore Architecture](@ref externalstorearch).
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
	public function __construct( ExternalStoreFactory $factory, ?LoggerInterface $logger = null ) {
		$this->storeFactory = $factory;
		$this->logger = $logger ?: new NullLogger();
	}

	public function setLogger( LoggerInterface $logger ): void {
		$this->logger = $logger;
	}

	/**
	 * Fetch data from given URL
	 *
	 * @see ExternalStoreFactory::getStore()
	 *
	 * @param string $url The URL of the text to get
	 * @param array $params Map of context parameters; same as ExternalStoreFactory::getStore()
	 * @return string|false The text stored or false on error
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
	 * @return string|false The URL of the stored data item, or false on error
	 * @throws ExternalStoreException
	 */
	public function insert( $data, array $params = [], ?array $tryStores = null ) {
		$tryStores ??= $this->storeFactory->getWriteBaseUrls();
		if ( !$tryStores ) {
			throw new ExternalStoreException( "List of external stores provided is empty." );
		}

		$error = false;      // track the last exception thrown
		$readOnlyCount = 0;  // track if a store was read-only
		while ( count( $tryStores ) > 0 ) {
			$index = mt_rand( 0, count( $tryStores ) - 1 );
			$storeUrl = $tryStores[$index];

			$this->logger->debug( __METHOD__ . ": trying $storeUrl" );

			$store = $this->storeFactory->getStoreForUrl( $storeUrl, $params );
			if ( $store === false ) {
				throw new ExternalStoreException( "Invalid external storage protocol - $storeUrl" );
			}

			$location = $this->storeFactory->getStoreLocationFromUrl( $storeUrl );
			try {
				if ( $store->isReadOnly( $location ) ) {
					$readOnlyCount++;
					$msg = 'read only';
				} else {
					$url = $store->store( $location, $data );
					if ( $url !== false && $url !== '' ) {
						// A store accepted the write; done!
						return $url;
					}
					throw new ExternalStoreException(
						"No URL returned by storage medium ($storeUrl)"
					);
				}
			} catch ( TimeoutException $e ) {
				throw $e;
			} catch ( Exception $ex ) {
				$error = $ex;
				$msg = 'caught ' . get_class( $error ) . ' exception: ' . $error->getMessage();
			}

			unset( $tryStores[$index] ); // Don't try this one again!
			$tryStores = array_values( $tryStores ); // Must have consecutive keys
			$this->logger->error(
				"Unable to store text to external storage {store_path} ({failure})",
				[ 'store_path' => $storeUrl, 'failure' => $msg ]
			);
		}

		// We only get here when all stores failed.
		if ( $error ) {
			// At least one store threw an exception. Re-throw the most recent one.
			throw $error;
		} elseif ( $readOnlyCount ) {
			// If no exceptions where thrown and we get here,
			// this should mean that all stores were in read-only mode.
			throw new ReadOnlyError();
		} else {
			// We shouldn't get here. If there were no failures, this method should have returned
			// from inside the body of the loop.
			throw new LogicException( "Unexpected failure to store text to external store" );
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
