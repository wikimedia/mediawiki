<?php
/**
 * Wrapper around a BagOStuff that caches data in memory
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
 * @ingroup Cache
 */

/**
 * Wrapper around a BagOStuff that caches data in memory
 *
 * The differences between CachedBagOStuff and MultiWriteBagOStuff are:
 * * CachedBagOStuff supports only one "backend".
 * * There's a flag for writes to only go to the in-memory cache.
 * * The in-memory cache is always updated.
 * * Locks go to the backend cache (with MultiWriteBagOStuff, it would wind
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
	 * @stable to call
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

	public function get( $key, $flags = 0 ) {
		$value = $this->procCache->get( $key, $flags );
		if ( $value !== false || $this->procCache->hasKey( $key ) ) {
			return $value;
		}

		$value = $this->store->proxyCall(
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args()
		);
		$this->set( $key, $value, self::TTL_INDEFINITE, self::WRITE_CACHE_ONLY );

		return $value;
	}

	public function getMulti( array $keys, $flags = 0 ) {
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
			[ $keysFetch, $flags ]
		);
		$this->setMulti( $valueByKeyFetched, self::TTL_INDEFINITE, self::WRITE_CACHE_ONLY );

		return $valueByKeyCached + $valueByKeyFetched;
	}

	public function set( $key, $value, $exptime = 0, $flags = 0 ) {
		$this->procCache->set( $key, $value, $exptime, $flags );

		if ( $this->fieldHasFlags( $flags, self::WRITE_CACHE_ONLY ) ) {
			return true;
		}

		return $this->store->proxyCall( __FUNCTION__, self::ARG0_KEY, self::RES_NONKEY, func_get_args() );
	}

	public function delete( $key, $flags = 0 ) {
		$this->procCache->delete( $key, $flags );

		if ( $this->fieldHasFlags( $flags, self::WRITE_CACHE_ONLY ) ) {
			return true;
		}

		return $this->store->proxyCall( __FUNCTION__, self::ARG0_KEY, self::RES_NONKEY, func_get_args() );
	}

	public function add( $key, $value, $exptime = 0, $flags = 0 ) {
		if ( $this->get( $key ) === false ) {
			return $this->set( $key, $value, $exptime, $flags );
		}

		return false; // key already set
	}

	// These just call the backend (tested elsewhere)
	// @codeCoverageIgnoreStart

	public function merge( $key, callable $callback, $exptime = 0, $attempts = 10, $flags = 0 ) {
		$this->procCache->delete( $key );

		return $this->store->proxyCall( __FUNCTION__, self::ARG0_KEY, self::RES_NONKEY, func_get_args() );
	}

	public function changeTTL( $key, $exptime = 0, $flags = 0 ) {
		$this->procCache->delete( $key );

		return $this->store->proxyCall( __FUNCTION__, self::ARG0_KEY, self::RES_NONKEY, func_get_args() );
	}

	public function lock( $key, $timeout = 6, $expiry = 6, $rclass = '' ) {
		return $this->store->proxyCall( __FUNCTION__, self::ARG0_KEY, self::RES_NONKEY, func_get_args() );
	}

	public function unlock( $key ) {
		return $this->store->proxyCall( __FUNCTION__, self::ARG0_KEY, self::RES_NONKEY, func_get_args() );
	}

	public function deleteObjectsExpiringBefore(
		$timestamp,
		callable $progress = null,
		$limit = INF
	) {
		$this->procCache->deleteObjectsExpiringBefore( $timestamp, $progress, $limit );

		return $this->store->proxyCall( __FUNCTION__, self::ARG0_NONKEY, self::RES_NONKEY, func_get_args() );
	}

	public function makeKeyInternal( $keyspace, $components ) {
		return $this->genericKeyFromComponents( $keyspace, ...$components );
	}

	public function makeKey( $collection, ...$components ) {
		return $this->genericKeyFromComponents( $this->keyspace, $collection, ...$components );
	}

	public function makeGlobalKey( $collection, ...$components ) {
		return $this->genericKeyFromComponents( self::GLOBAL_KEYSPACE, $collection, ...$components );
	}

	protected function convertGenericKey( $key ) {
		return $key; // short-circuit; already uses "generic" keys
	}

	public function getLastError() {
		return $this->store->getLastError();
	}

	public function clearLastError() {
		return $this->store->clearLastError();
	}

	public function setMulti( array $valueByKey, $exptime = 0, $flags = 0 ) {
		$this->procCache->setMulti( $valueByKey, $exptime, $flags );

		if ( $this->fieldHasFlags( $flags, self::WRITE_CACHE_ONLY ) ) {
			return true;
		}

		return $this->store->proxyCall( __FUNCTION__, self::ARG0_KEYMAP, self::RES_NONKEY, func_get_args() );
	}

	public function deleteMulti( array $keys, $flags = 0 ) {
		$this->procCache->deleteMulti( $keys, $flags );

		if ( $this->fieldHasFlags( $flags, self::WRITE_CACHE_ONLY ) ) {
			return true;
		}

		return $this->store->proxyCall( __FUNCTION__, self::ARG0_KEYARR, self::RES_NONKEY, func_get_args() );
	}

	public function changeTTLMulti( array $keys, $exptime, $flags = 0 ) {
		$this->procCache->changeTTLMulti( $keys, $exptime, $flags );

		if ( $this->fieldHasFlags( $flags, self::WRITE_CACHE_ONLY ) ) {
			return true;
		}

		return $this->store->proxyCall( __FUNCTION__, self::ARG0_KEYARR, self::RES_NONKEY, func_get_args() );
	}

	public function incr( $key, $value = 1, $flags = 0 ) {
		$this->procCache->delete( $key );

		return $this->store->proxyCall( __FUNCTION__, self::ARG0_KEY, self::RES_NONKEY, func_get_args() );
	}

	public function decr( $key, $value = 1, $flags = 0 ) {
		$this->procCache->delete( $key );

		return $this->store->proxyCall( __FUNCTION__, self::ARG0_KEY, self::RES_NONKEY, func_get_args() );
	}

	public function incrWithInit( $key, $exptime, $value = 1, $init = null, $flags = 0 ) {
		$this->procCache->delete( $key );

		return $this->store->proxyCall( __FUNCTION__, self::ARG0_KEY, self::RES_NONKEY, func_get_args() );
	}

	public function addBusyCallback( callable $workCallback ) {
		$this->store->addBusyCallback( $workCallback );
	}

	public function setNewPreparedValues( array $valueByKey ) {
		return $this->store->proxyCall( __FUNCTION__, self::ARG0_KEYMAP, self::RES_NONKEY, func_get_args() );
	}

	public function setMockTime( &$time ) {
		parent::setMockTime( $time );
		$this->procCache->setMockTime( $time );
		$this->store->setMockTime( $time );
	}

	// @codeCoverageIgnoreEnd
}
