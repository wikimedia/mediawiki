<?php

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * @author Addshore
 *
 * @ingroup Cache
 *
 * @since 1.27
 */
class WANObjectPsrCache implements CacheItemPoolInterface {

	/**
	 * List of invalid (or reserved) key characters.
	 *
	 * @var string
	 */
	const INVALID_KEY_CHARACTERS = '{}()/\@:';

	/**
	 * @var WANObjectCache
	 */
	private $objectCache;

	public function __construct( WANObjectCache $objectCache ) {
		$this->objectCache = $objectCache;
	}

	/**
	 * @return string The cache key used to store a list of all cached keys
	 */
	private function getKeyListKey() {
		return $this->objectCache->makeKey( __CLASS__, 'keylist' );
	}

	/**
	 * @return array Keys stored in this cache
	 */
	private function getKeyList() {
		$value = $this->objectCache->get( $this->getKeyListKey() );
		if ( !$value ) {
			return [];
		}

		return $value;
	}

	/**
	 * @param string $key Adds a key to the list of stored keys
	 */
	private function addToKeyList( $key ) {
		$storedKeys = $this->getKeyList();
		$storedKeys[$key] = $key;
		$this->objectCache->set( $this->getKeyListKey(), $storedKeys );
	}

	/**
	 * Clears all stored keys from the list
	 */
	private function clearKeyList() {
		$this->objectCache->delete( $this->getKeyListKey() );
	}

	/**
	 * @param mixed $key
	 *
	 * @throws MediaWikiPsrCacheInvalidArgumentException
	 */
	private function throwExceptionOnBadKey( $key ) {
		if ( !is_string( $key ) ) {
			throw new MediaWikiPsrCacheInvalidArgumentException( '$key must be a string' );
		}
		// Make sure nothing uses the same key as our key list
		if ( $key === $this->getKeyListKey() ) {
			throw new MediaWikiPsrCacheInvalidArgumentException( '$key value is reserved' );
		}
		// Valid key according to PSR-6 rules
		$invalid = preg_quote( static::INVALID_KEY_CHARACTERS, '/' );
		if ( preg_match( '/[' . $invalid . ']/', $key ) ) {
			throw new MediaWikiPsrCacheInvalidArgumentException(
				'Invalid key: ' . $key . '. Contains (a) character(s) reserved ' .
				'for future extension: ' . static::INVALID_KEY_CHARACTERS
			);
		}
	}

	/**
	 * Returns a Cache Item representing the specified key.
	 *
	 * This method must always return a CacheItemInterface object, even in case of
	 * a cache miss. It MUST NOT return null.
	 *
	 * @param string $key
	 *   The key for which to return the corresponding Cache Item.
	 *
	 * @throws \Psr\Cache\InvalidArgumentException
	 *   If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
	 *   MUST be thrown.
	 *
	 * @return CacheItemInterface
	 *   The corresponding Cache Item.
	 */
	public function getItem( $key ) {
		$this->throwExceptionOnBadKey( $key );

		$item = $this->objectCache->get( $key );
		if ( $item === false ) {
			return new MediaWikiPsrCacheItem( $key, null, false );
		}

		return new MediaWikiPsrCacheItem( $key, $item, true );
	}

	/**
	 * Returns a traversable set of cache items.
	 *
	 * @param array $keys
	 * An indexed array of keys of items to retrieve.
	 *
	 * @throws \Psr\Cache\InvalidArgumentException
	 *   If any of the keys in $keys are not a legal value a \Psr\Cache\InvalidArgumentException
	 *   MUST be thrown.
	 *
	 * @return array|\Traversable
	 *   A traversable collection of Cache Items keyed by the cache keys of
	 *   each item. A Cache item will be returned for each key, even if that
	 *   key is not found. However, if no keys are specified then an empty
	 *   traversable MUST be returned instead.
	 */
	public function getItems( array $keys = [] ) {
		$items = [];
		foreach ( $keys as $key ) {
			$items[$key] = $this->getItem( $key );
		}

		return $items;
	}

	/**
	 * Confirms if the cache contains specified cache item.
	 *
	 * Note: This method MAY avoid retrieving the cached value for performance reasons.
	 * This could result in a race condition with CacheItemInterface::get(). To avoid
	 * such situation use CacheItemInterface::isHit() instead.
	 *
	 * @param string $key
	 *    The key for which to check existence.
	 *
	 * @throws \Psr\Cache\InvalidArgumentException
	 *   If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
	 *   MUST be thrown.
	 *
	 * @return bool
	 *  True if item exists in the cache, false otherwise.
	 */
	public function hasItem( $key ) {
		$this->throwExceptionOnBadKey( $key );

		return $this->objectCache->get( $key ) !== false;
	}

	/**
	 * Deletes all items in the pool.
	 *
	 * @return bool
	 *   True if the pool was successfully cleared. False if there was an error.
	 */
	public function clear() {
		$this->deleteItems( $this->getKeyList() );
		$this->clearKeyList();

		return true;
	}

	/**
	 * Removes the item from the pool.
	 *
	 * @param string $key
	 *   The key for which to delete
	 *
	 * @throws \Psr\Cache\InvalidArgumentException
	 *   If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
	 *   MUST be thrown.
	 *
	 * @return bool
	 *   True if the item was successfully removed. False if there was an error.
	 */
	public function deleteItem( $key ) {
		$this->throwExceptionOnBadKey( $key );

		return $this->objectCache->delete( $key );
	}

	/**
	 * Removes multiple items from the pool.
	 *
	 * @param array $keys
	 *   An array of keys that should be removed from the pool.
	 *
	 * @throws \Psr\Cache\InvalidArgumentException
	 *   If any of the keys in $keys are not a legal value a \Psr\Cache\InvalidArgumentException
	 *   MUST be thrown.
	 *
	 * @return bool
	 *   True if the items were successfully removed. False if there was an error.
	 */
	public function deleteItems( array $keys ) {
		$totalReturn = true;
		foreach ( $keys as $key ) {
			$innerReturn = $this->deleteItem( $key );
			if ( !$innerReturn ) {
				$totalReturn = false;
			}
		}

		return $totalReturn;
	}

	/**
	 * Persists a cache item immediately.
	 *
	 * @param CacheItemInterface $item
	 *   The cache item to save.
	 *
	 * @return bool True if the item was successfully persisted. False if there was an error.
	 * True if the item was successfully persisted. False if there was an error.
	 * @throws MediaWikiPsrCacheException as BagOStuffs can not differentiate between a false
	 * value and an un-cached value
	 */
	public function save( CacheItemInterface $item ) {
		if ( !$item instanceof MediaWikiPsrCacheItem ) {
			throw new MediaWikiPsrCacheInvalidArgumentException(
				__CLASS__ . ' can only save BagOStuffPsrCacheItem objects'
			);
		}
		if ( $item->get() === false ) {
			throw new MediaWikiPsrCacheException( __CLASS__ . ' can not cache false' );
		}

		$exptime = 0;
		$itemExpiration = $item->getExpiration();
		if ( $itemExpiration instanceof DateTimeInterface ) {
			$exptime = date_timestamp_get( $itemExpiration );
		}

		$storeSuccess = $this->objectCache->set(
			$item->getKey(),
			$item->get(),
			$exptime
		);

		if ( $storeSuccess ) {
			$this->addToKeyList( $item->getKey() );
		}

		return $storeSuccess;
	}

	/**
	 * Sets a cache item to be persisted later.
	 *
	 * @param CacheItemInterface $item
	 *   The cache item to save.
	 *
	 * @return bool
	 *   False if the item could not be queued or if a commit was attempted and failed. True
	 *     otherwise.
	 */
	public function saveDeferred( CacheItemInterface $item ) {
		return $this->save( $item );
	}

	/**
	 * Persists any deferred cache items.
	 *
	 * @return bool
	 *   True if all not-yet-saved items were successfully saved or there were none. False
	 *     otherwise.
	 */
	public function commit() {
		return true;
	}

}
