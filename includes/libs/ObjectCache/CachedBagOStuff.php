<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\ObjectCache;

use LogicException;

/**
 * Wrap any BagOStuff and add an in-process memory cache to it.
 *
 * The differences between CachedBagOStuff and MultiWriteBagOStuff are:
 * - CachedBagOStuff supports only one "backend".
 * - There's a flag for writes to only go to the in-memory cache.
 * - The in-memory cache is always updated.
 * - Locks go to the backend cache (with MultiWriteBagOStuff, it would wind
 *   up going to the HashBagOStuff used for the in-memory cache).
 *
 * @newable
 * @ingroup Cache
 */
class CachedBagOStuff extends BagOStuff {
	/** @var BagOStuff */
	protected $store;
	/** @var HashBagOStuff */
	protected $procCache;

	/**
	 * Whether the last get() call was read from cache ($procCache) or the actual store.
	 * Null means not applicable (e.g. there was no get() call yet).
	 * Should be called immediately after a get() call. getMulti() is not supported.
	 */
	protected ?bool $wasLastGetCached = null;

	/**
	 * @stable to call
	 *
	 * @param BagOStuff $backend Permanent backend to use
	 * @param array $params Parameters for HashBagOStuff
	 */
	public function __construct( BagOStuff $backend, $params = [] ) {
		$params['keyspace'] = $backend->keyspace;
		parent::__construct( $params );

		$this->store = $backend;
		$this->procCache = new HashBagOStuff( $params );

		$this->attrMap = $backend->attrMap;
	}

	/** @inheritDoc */
	public function get( $key, $flags = 0 ) {
		$value = $this->procCache->get( $key, $flags );
		if ( $value !== false || $this->procCache->hasKey( $key ) ) {
			$this->wasLastGetCached = true;
			return $value;
		}

		$value = $this->store->proxyCall(
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args(),
			$this
		);
		$this->set( $key, $value, self::TTL_INDEFINITE, self::WRITE_CACHE_ONLY );

		$this->wasLastGetCached = false;
		return $value;
	}

	/** @inheritDoc */
	public function getMulti( array $keys, $flags = 0 ) {
		$this->wasLastGetCached = null;
		$valueByKeyCached = [];

		$keysFetch = [];
		foreach ( $keys as $key ) {
			$value = $this->procCache->get( $key, $flags );
			if ( $value === false && !$this->procCache->hasKey( $key ) ) {
				$keysFetch[] = $key;
			} else {
				$valueByKeyCached[$key] = $value;
			}
		}

		$valueByKeyFetched = $this->store->proxyCall(
			__FUNCTION__,
			self::ARG0_KEYARR,
			self::RES_KEYMAP,
			[ $keysFetch, $flags ],
			$this
		);
		$this->setMulti( $valueByKeyFetched, self::TTL_INDEFINITE, self::WRITE_CACHE_ONLY );

		return $valueByKeyCached + $valueByKeyFetched;
	}

	/** @inheritDoc */
	public function set( $key, $value, $exptime = 0, $flags = 0 ) {
		$this->procCache->set( $key, $value, $exptime, $flags );

		if ( $this->fieldHasFlags( $flags, self::WRITE_CACHE_ONLY ) ) {
			return true;
		}

		return $this->store->proxyCall(
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args(),
			$this
		);
	}

	/** @inheritDoc */
	public function delete( $key, $flags = 0 ) {
		$this->procCache->delete( $key, $flags );

		if ( $this->fieldHasFlags( $flags, self::WRITE_CACHE_ONLY ) ) {
			return true;
		}

		return $this->store->proxyCall(
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args(),
			$this
		);
	}

	/** @inheritDoc */
	public function add( $key, $value, $exptime = 0, $flags = 0 ) {
		if ( $this->get( $key ) === false ) {
			return $this->set( $key, $value, $exptime, $flags );
		}

		// key already set
		return false;
	}

	// These just call the backend (tested elsewhere)
	// @codeCoverageIgnoreStart

	/** @inheritDoc */
	public function merge( $key, callable $callback, $exptime = 0, $attempts = 10, $flags = 0 ) {
		$this->procCache->delete( $key );

		return $this->store->proxyCall(
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args(),
			$this
		);
	}

	/** @inheritDoc */
	public function changeTTL( $key, $exptime = 0, $flags = 0 ) {
		$this->procCache->delete( $key );

		return $this->store->proxyCall(
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args(),
			$this
		);
	}

	/** @inheritDoc */
	public function lock( $key, $timeout = 6, $exptime = 6, $rclass = '' ) {
		return $this->store->proxyCall(
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args(),
			$this
		);
	}

	/** @inheritDoc */
	public function unlock( $key ) {
		return $this->store->proxyCall(
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args(),
			$this
		);
	}

	/** @inheritDoc */
	public function deleteObjectsExpiringBefore(
		$timestamp,
		?callable $progress = null,
		$limit = INF,
		?string $tag = null
	) {
		$this->procCache->deleteObjectsExpiringBefore( $timestamp, $progress, $limit, $tag );

		return $this->store->proxyCall(
			__FUNCTION__,
			self::ARG0_NONKEY,
			self::RES_NONKEY,
			func_get_args(),
			$this
		);
	}

	/** @inheritDoc */
	public function setMulti( array $valueByKey, $exptime = 0, $flags = 0 ) {
		$this->procCache->setMulti( $valueByKey, $exptime, $flags );

		if ( $this->fieldHasFlags( $flags, self::WRITE_CACHE_ONLY ) ) {
			return true;
		}

		return $this->store->proxyCall(
			__FUNCTION__,
			self::ARG0_KEYMAP,
			self::RES_NONKEY,
			func_get_args(),
			$this
		);
	}

	/** @inheritDoc */
	public function deleteMulti( array $keys, $flags = 0 ) {
		$this->procCache->deleteMulti( $keys, $flags );

		if ( $this->fieldHasFlags( $flags, self::WRITE_CACHE_ONLY ) ) {
			return true;
		}

		return $this->store->proxyCall(
			__FUNCTION__,
			self::ARG0_KEYARR,
			self::RES_NONKEY,
			func_get_args(),
			$this
		);
	}

	/** @inheritDoc */
	public function changeTTLMulti( array $keys, $exptime, $flags = 0 ) {
		$this->procCache->changeTTLMulti( $keys, $exptime, $flags );

		if ( $this->fieldHasFlags( $flags, self::WRITE_CACHE_ONLY ) ) {
			return true;
		}

		return $this->store->proxyCall(
			__FUNCTION__,
			self::ARG0_KEYARR,
			self::RES_NONKEY,
			func_get_args(),
			$this
		);
	}

	/** @inheritDoc */
	public function incrWithInit( $key, $exptime, $step = 1, $init = null, $flags = 0 ) {
		$this->procCache->delete( $key );

		return $this->store->proxyCall(
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args(),
			$this
		);
	}

	/** @inheritDoc */
	public function setMockTime( &$time ) {
		parent::setMockTime( $time );
		$this->procCache->setMockTime( $time );
		$this->store->setMockTime( $time );
	}

	/**
	 * True if the last get() call was read from cache, false if it was a cache miss.
	 * Should be called immediately after a get() call (might throw otherwise).
	 * getMulti() is not supported.
	 * @since 1.45
	 */
	public function wasLastGetCached(): bool {
		if ( $this->wasLastGetCached === null ) {
			throw new LogicException( __METHOD__ . ' must be called immediately after get()' );
		}

		$status = $this->wasLastGetCached;
		// Disallow multiple calls without a get() in between as we expect this method to
		// be used right after a get().
		$this->wasLastGetCached = null;

		return $status;
	}

	// @codeCoverageIgnoreEnd
}

/** @deprecated class alias since 1.43 */
class_alias( CachedBagOStuff::class, 'CachedBagOStuff' );
