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
	protected $backend;
	/** @var HashBagOStuff */
	protected $procCache;

	/**
	 * @stable to call
	 * @param BagOStuff $backend Permanent backend to use
	 * @param array $params Parameters for HashBagOStuff
	 */
	public function __construct( BagOStuff $backend, $params = [] ) {
		parent::__construct( $params );

		$this->backend = $backend;
		$this->procCache = new HashBagOStuff( $params );
		$this->attrMap = $backend->attrMap;
	}

	public function setDebug( $enabled ) {
		parent::setDebug( $enabled );
		$this->backend->setDebug( $enabled );
	}

	public function get( $key, $flags = 0 ) {
		$value = $this->procCache->get( $key, $flags );
		if ( $value === false && !$this->procCache->hasKey( $key ) ) {
			$value = $this->backend->get( $key, $flags );
			$this->set( $key, $value, self::TTL_INDEFINITE, self::WRITE_CACHE_ONLY );
		}

		return $value;
	}

	public function getMulti( array $keys, $flags = 0 ) {
		$valuesByKeyCached = [];

		$keysMissing = [];
		foreach ( $keys as $key ) {
			$value = $this->procCache->get( $key, $flags );
			if ( $value === false && !$this->procCache->hasKey( $key ) ) {
				$keysMissing[] = $key;
			} else {
				$valuesByKeyCached[$key] = $value;
			}
		}

		$valuesByKeyFetched = $this->backend->getMulti( $keysMissing, $flags );
		$this->setMulti( $valuesByKeyFetched, self::TTL_INDEFINITE, self::WRITE_CACHE_ONLY );

		return $valuesByKeyCached + $valuesByKeyFetched;
	}

	public function set( $key, $value, $exptime = 0, $flags = 0 ) {
		$this->procCache->set( $key, $value, $exptime, $flags );

		if ( !$this->fieldHasFlags( $flags, self::WRITE_CACHE_ONLY ) ) {
			$this->backend->set( $key, $value, $exptime, $flags );
		}

		return true;
	}

	public function delete( $key, $flags = 0 ) {
		$this->procCache->delete( $key, $flags );

		if ( !$this->fieldHasFlags( $flags, self::WRITE_CACHE_ONLY ) ) {
			$this->backend->delete( $key, $flags );
		}

		return true;
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

		return $this->backend->merge( $key, $callback, $exptime, $attempts, $flags );
	}

	public function changeTTL( $key, $exptime = 0, $flags = 0 ) {
		$this->procCache->delete( $key );

		return $this->backend->changeTTL( $key, $exptime, $flags );
	}

	public function lock( $key, $timeout = 6, $expiry = 6, $rclass = '' ) {
		return $this->backend->lock( $key, $timeout, $expiry, $rclass );
	}

	public function unlock( $key ) {
		return $this->backend->unlock( $key );
	}

	public function deleteObjectsExpiringBefore(
		$timestamp,
		callable $progress = null,
		$limit = INF
	) {
		$this->procCache->deleteObjectsExpiringBefore( $timestamp, $progress, $limit );

		return $this->backend->deleteObjectsExpiringBefore( $timestamp, $progress, $limit );
	}

	public function makeKeyInternal( $keyspace, $args ) {
		return $this->backend->makeKeyInternal( $keyspace, $args );
	}

	public function makeKey( $class, ...$components ) {
		return $this->backend->makeKey( $class, ...$components );
	}

	public function makeGlobalKey( $class, ...$components ) {
		return $this->backend->makeGlobalKey( $class, ...$components );
	}

	public function getLastError() {
		return $this->backend->getLastError();
	}

	public function clearLastError() {
		return $this->backend->clearLastError();
	}

	public function setMulti( array $data, $exptime = 0, $flags = 0 ) {
		$this->procCache->setMulti( $data, $exptime, $flags );

		if ( !$this->fieldHasFlags( $flags, self::WRITE_CACHE_ONLY ) ) {
			return $this->backend->setMulti( $data, $exptime, $flags );
		}

		return true;
	}

	public function deleteMulti( array $keys, $flags = 0 ) {
		$this->procCache->deleteMulti( $keys, $flags );

		if ( !$this->fieldHasFlags( $flags, self::WRITE_CACHE_ONLY ) ) {
			return $this->backend->deleteMulti( $keys, $flags );
		}

		return true;
	}

	public function changeTTLMulti( array $keys, $exptime, $flags = 0 ) {
		$this->procCache->changeTTLMulti( $keys, $exptime, $flags );

		if ( !$this->fieldHasFlags( $flags, self::WRITE_CACHE_ONLY ) ) {
			return $this->backend->changeTTLMulti( $keys, $exptime, $flags );
		}

		return true;
	}

	public function incr( $key, $value = 1, $flags = 0 ) {
		$this->procCache->delete( $key );

		return $this->backend->incr( $key, $value, $flags );
	}

	public function decr( $key, $value = 1, $flags = 0 ) {
		$this->procCache->delete( $key );

		return $this->backend->decr( $key, $value, $flags );
	}

	public function incrWithInit( $key, $exptime, $value = 1, $init = null, $flags = 0 ) {
		$this->procCache->delete( $key );

		return $this->backend->incrWithInit( $key, $exptime, $value, $init, $flags );
	}

	public function addBusyCallback( callable $workCallback ) {
		$this->backend->addBusyCallback( $workCallback );
	}

	public function setNewPreparedValues( array $valueByKey ) {
		return $this->backend->setNewPreparedValues( $valueByKey );
	}

	public function setMockTime( &$time ) {
		parent::setMockTime( $time );
		$this->procCache->setMockTime( $time );
		$this->backend->setMockTime( $time );
	}

	// @codeCoverageIgnoreEnd
}
