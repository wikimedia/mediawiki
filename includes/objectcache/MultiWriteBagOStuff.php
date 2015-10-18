<?php
/**
 * Wrapper for object caching in different caches.
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
 * A cache class that replicates all writes to multiple child caches. Reads
 * are implemented by reading from the caches in the order they are given in
 * the configuration until a cache gives a positive result.
 *
 * @ingroup Cache
 */
class MultiWriteBagOStuff extends BagOStuff {
	/** @var BagOStuff[] */
	protected $caches;
	/** @var bool Use async secondary writes */
	protected $asyncWrites = false;

	/** Idiom for "write to all backends" */
	const ALL = INF;

	const UPGRADE_TTL = 3600; // TTL when a key is copied to a higher cache tier

	/**
	 * $params include:
	 *   - caches:      This should have a numbered array of cache parameter
	 *                  structures, in the style required by $wgObjectCaches. See
	 *                  the documentation of $wgObjectCaches for more detail.
	 *                  BagOStuff objects can also be used as values.
	 *                  The first cache is the primary one, being the first to
	 *                  be read in the fallback chain. Writes happen to all stores
	 *                  in the order they are defined. However, lock()/unlock() calls
	 *                  only use the primary store.
	 *   - replication: Either 'sync' or 'async'. This controls whether writes to
	 *                  secondary stores are deferred when possible. Async writes
	 *                  require the HHVM register_postsend_function() function.
	 *                  Async writes can increase the chance of some race conditions
	 *                  or cause keys to expire seconds later than expected. It is
	 *                  safe to use for modules when cached values: are immutable,
	 *                  invalidation uses logical TTLs, invalidation uses etag/timestamp
	 *                  validation against the DB, or merge() is used to handle races.
	 *
	 * @param array $params
	 * @throws InvalidArgumentException
	 */
	public function __construct( $params ) {
		parent::__construct( $params );

		if ( empty( $params['caches'] ) || !is_array( $params['caches'] ) ) {
			throw new InvalidArgumentException(
				__METHOD__ . ': "caches" parameter must be an array of caches'
			);
		}

		$this->caches = array();
		foreach ( $params['caches'] as $cacheInfo ) {
			$this->caches[] = ( $cacheInfo instanceof BagOStuff )
				? $cacheInfo
				: ObjectCache::newFromParams( $cacheInfo );
		}

		$this->asyncWrites = isset( $params['replication'] ) && $params['replication'] === 'async';
	}

	public function setDebug( $debug ) {
		foreach ( $this->caches as $cache ) {
			$cache->setDebug( $debug );
		}
	}

	protected function doGet( $key, $flags = 0 ) {
		$misses = 0; // number backends checked
		$value = false;
		foreach ( $this->caches as $cache ) {
			$value = $cache->get( $key, $flags );
			if ( $value !== false ) {
				break;
			}
			++$misses;
		}

		if ( $value !== false
			&& $misses > 0
			&& ( $flags & self::READ_VERIFIED ) == self::READ_VERIFIED
		) {
			$this->doWrite( $misses, $this->asyncWrites, 'set', $key, $value, self::UPGRADE_TTL );
		}

		return $value;
	}

	public function set( $key, $value, $exptime = 0 ) {
		return $this->doWrite( self::ALL, $this->asyncWrites, 'set', $key, $value, $exptime );
	}

	public function delete( $key ) {
		return $this->doWrite( self::ALL, $this->asyncWrites, 'delete', $key );
	}

	public function add( $key, $value, $exptime = 0 ) {
		return $this->doWrite( self::ALL, $this->asyncWrites, 'add', $key, $value, $exptime );
	}

	public function incr( $key, $value = 1 ) {
		return $this->doWrite( self::ALL, $this->asyncWrites, 'incr', $key, $value );
	}

	public function decr( $key, $value = 1 ) {
		return $this->doWrite( self::ALL, $this->asyncWrites, 'decr', $key, $value );
	}

	public function lock( $key, $timeout = 6, $expiry = 6, $rclass = '' ) {
		// Only need to lock the first cache; also avoids deadlocks
		return $this->caches[0]->lock( $key, $timeout, $expiry, $rclass );
	}

	public function unlock( $key ) {
		// Only the first cache is locked
		return $this->caches[0]->unlock( $key );
	}

	public function merge( $key, $callback, $exptime = 0, $attempts = 10, $flags = 0 ) {
		$asyncWrites = ( ( $flags & self::WRITE_SYNC ) == self::WRITE_SYNC )
			? false
			: $this->asyncWrites;

		return $this->doWrite( self::ALL, $asyncWrites, 'merge', $key, $callback, $exptime );
	}

	public function getLastError() {
		return $this->caches[0]->getLastError();
	}

	public function clearLastError() {
		$this->caches[0]->clearLastError();
	}

	/**
	 * Apply a write method to the first $count backing caches
	 *
	 * @param integer $count
	 * @param bool $asyncWrites
	 * @param string $method
	 * @param mixed ...
	 * @return bool
	 */
	protected function doWrite( $count, $asyncWrites, $method /*, ... */ ) {
		$ret = true;
		$args = array_slice( func_get_args(), 3 );

		foreach ( $this->caches as $i => $cache ) {
			if ( $i >= $count ) {
				break; // ignore the lower tiers
			}

			if ( $i == 0 || !$asyncWrites ) {
				// First store or in sync mode: write now and get result
				if ( !call_user_func_array( array( $cache, $method ), $args ) ) {
					$ret = false;
				}
			} else {
				// Secondary write in async mode: do not block this HTTP request
				$logger = $this->logger;
				DeferredUpdates::addCallableUpdate(
					function () use ( $cache, $method, $args, $logger ) {
						if ( !call_user_func_array( array( $cache, $method ), $args ) ) {
							$logger->warning( "Async $method op failed" );
						}
					}
				);
			}
		}

		return $ret;
	}

	/**
	 * Delete objects expiring before a certain date.
	 *
	 * Succeed if any of the child caches succeed.
	 * @param string $date
	 * @param bool|callable $progressCallback
	 * @return bool
	 */
	public function deleteObjectsExpiringBefore( $date, $progressCallback = false ) {
		$ret = false;
		foreach ( $this->caches as $cache ) {
			if ( $cache->deleteObjectsExpiringBefore( $date, $progressCallback ) ) {
				$ret = true;
			}
		}

		return $ret;
	}
}
