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
			throw new InvalidArgumentException( __METHOD__ . ': "caches" parameter must be an array of caches' );
		}

		$this->caches = array();
		foreach ( $params['caches'] as $cacheInfo ) {
			$this->caches[] = ( $cacheInfo instanceof BagOStuff )
				? $cacheInfo
				: ObjectCache::newFromParams( $cacheInfo );
		}

		$this->asyncWrites = isset( $params['replication'] ) && $params['replication'] === 'async';
	}

	/**
	 * @param bool $debug
	 */
	public function setDebug( $debug ) {
		$this->doWrite( 'setDebug', $debug );
	}

	public function get( $key, &$casToken = null, $flags = 0 ) {
		foreach ( $this->caches as $cache ) {
			$value = $cache->get( $key, $casToken, $flags );
			if ( $value !== false ) {
				return $value;
			}
		}
		return false;
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @param int $exptime
	 * @return bool
	 */
	public function set( $key, $value, $exptime = 0 ) {
		return $this->doWrite( 'set', $key, $value, $exptime );
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function delete( $key ) {
		return $this->doWrite( 'delete', $key );
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @param int $exptime
	 * @return bool
	 */
	public function add( $key, $value, $exptime = 0 ) {
		return $this->doWrite( 'add', $key, $value, $exptime );
	}

	/**
	 * @param string $key
	 * @param int $value
	 * @return bool|null
	 */
	public function incr( $key, $value = 1 ) {
		return $this->doWrite( 'incr', $key, $value );
	}

	/**
	 * @param string $key
	 * @param int $value
	 * @return bool
	 */
	public function decr( $key, $value = 1 ) {
		return $this->doWrite( 'decr', $key, $value );
	}

	/**
	 * @param string $key
	 * @param int $timeout
	 * @param int $expiry
	 * @param string $rclass
	 * @return bool
	 */
	public function lock( $key, $timeout = 6, $expiry = 6, $rclass = '' ) {
		// Lock only the first cache, to avoid deadlocks
		return $this->caches[0]->lock( $key, $timeout, $expiry, $rclass );
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function unlock( $key ) {
		return $this->caches[0]->unlock( $key );
	}

	/**
	 * @param string $key
	 * @param callable $callback Callback method to be executed
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @param int $attempts The amount of times to attempt a merge in case of failure
	 * @return bool Success
	 */
	public function merge( $key, $callback, $exptime = 0, $attempts = 10 ) {
		return $this->doWrite( 'merge', $key, $callback, $exptime );
	}

	public function getLastError() {
		return $this->caches[0]->getLastError();
	}

	public function clearLastError() {
		$this->caches[0]->clearLastError();
	}

	/**
	 * @param string $method
	 * @return bool
	 */
	protected function doWrite( $method /*, ... */ ) {
		$ret = true;
		$args = func_get_args();
		array_shift( $args );

		foreach ( $this->caches as $i => $cache ) {
			if ( $i == 0 || !$this->asyncWrites ) {
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
