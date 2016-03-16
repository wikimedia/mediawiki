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
class BagOStuffPsrCache implements CacheItemPoolInterface {

	/**
	 * @var BagOStuff
	 */
	private $bagOStuff;

	/**
	 * @param BagOStuff $bagOStuff
	 */
	public function __construct( BagOStuff $bagOStuff ) {
		$this->bagOStuff = $bagOStuff;
	}

	private function getKeyListKey() {
		return $this->bagOStuff->makeKey( __CLASS__, 'keylist' );
	}

	private function getKeyList() {
		$value = $this->bagOStuff->get( $this->getKeyListKey() );
		if ( !$value ) {
			return [];
		}
		return $value;
	}

	private function addToKeyList( $key ) {
		$storedKeys = $this->getKeyList();
		$storedKeys[$key] = $key;
		$this->bagOStuff->set( $this->getKeyListKey(), $storedKeys );
	}

	private function clearKeyList() {
		$this->bagOStuff->delete( $this->getKeyListKey() );
	}

	/**
	 * @param mixed $key
	 *
	 * @throws BagOStuffPsrCacheInvalidArgumentException
	 */
	private function throwExceptionOnBadKey( $key ) {
		if ( !is_string( $key ) ) {
			throw new BagOStuffPsrCacheInvalidArgumentException( '$key must be a string' );
		}
		if ( $key === $this->getKeyListKey() ) {
			throw new BagOStuffPsrCacheInvalidArgumentException( '$key value is reserved' );
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

		$item = $this->bagOStuff->get( $key );
		if ( $item === false ) {
			return new BagOStuffPsrCacheItem( $key, null, false );
		}
		return new BagOStuffPsrCacheItem( $key, $item, true );
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

		return $this->bagOStuff->get( $key ) !== false;
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

		return $this->bagOStuff->delete( $key );
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
	 * @throws BagOStuffPsrCacheException as BagOStuffs can not differentiate between a false
	 * value and an un-cached value
	 */
	public function save( CacheItemInterface $item ) {
		if ( $item->get() === false ) {
			throw new BagOStuffPsrCacheException( __CLASS__ . ' can not cache false' );
		}
		$storeSuccess = $this->bagOStuff->set(
			$item->getKey(),
			$item->get()
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
