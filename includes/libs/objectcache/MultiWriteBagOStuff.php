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
use Wikimedia\ObjectFactory;

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
	/** @var int[] List of all backing cache indexes */
	protected $cacheIndexes = [];

	const UPGRADE_TTL = 3600; // TTL when a key is copied to a higher cache tier

	/**
	 * $params include:
	 *   - caches: A numbered array of either ObjectFactory::getObjectFromSpec
	 *      arrays yeilding BagOStuff objects or direct BagOStuff objects.
	 *      If using the former, the 'args' field *must* be set.
	 *      The first cache is the primary one, being the first to
	 *      be read in the fallback chain. Writes happen to all stores
	 *      in the order they are defined. However, lock()/unlock() calls
	 *      only use the primary store.
	 *   - replication: Either 'sync' or 'async'. This controls whether writes
	 *      to secondary stores are deferred when possible. Async writes
	 *      require setting 'asyncHandler'. HHVM register_postsend_function() function.
	 *      Async writes can increase the chance of some race conditions
	 *      or cause keys to expire seconds later than expected. It is
	 *      safe to use for modules when cached values: are immutable,
	 *      invalidation uses logical TTLs, invalidation uses etag/timestamp
	 *      validation against the DB, or merge() is used to handle races.
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

		$this->caches = [];
		foreach ( $params['caches'] as $cacheInfo ) {
			if ( $cacheInfo instanceof BagOStuff ) {
				$this->caches[] = $cacheInfo;
			} else {
				if ( !isset( $cacheInfo['args'] ) ) {
					// B/C for when $cacheInfo was for ObjectCache::newFromParams().
					// Callers intenting this to be for ObjectFactory::getObjectFromSpec
					// should have set "args" per the docs above. Doings so avoids extra
					// (likely harmless) params (factory/class/calls) ending up in "args".
					$cacheInfo['args'] = [ $cacheInfo ];
				}
				$this->caches[] = ObjectFactory::getObjectFromSpec( $cacheInfo );
			}
		}
		$this->mergeFlagMaps( $this->caches );

		$this->asyncWrites = (
			isset( $params['replication'] ) &&
			$params['replication'] === 'async' &&
			is_callable( $this->asyncHandler )
		);

		$this->cacheIndexes = array_keys( $this->caches );
	}

	public function setDebug( $debug ) {
		foreach ( $this->caches as $cache ) {
			$cache->setDebug( $debug );
		}
	}

	protected function doGet( $key, $flags = 0 ) {
		if ( ( $flags & self::READ_LATEST ) == self::READ_LATEST ) {
			// If the latest write was a delete(), we do NOT want to fallback
			// to the other tiers and possibly see the old value. Also, this
			// is used by mergeViaLock(), which only needs to hit the primary.
			return $this->caches[0]->get( $key, $flags );
		}

		$value = false;
		$missIndexes = []; // backends checked
		foreach ( $this->caches as $i => $cache ) {
			$value = $cache->get( $key, $flags );
			if ( $value !== false ) {
				break;
			}
			$missIndexes[] = $i;
		}

		if ( $value !== false
			&& $missIndexes
			&& ( $flags & self::READ_VERIFIED ) == self::READ_VERIFIED
		) {
			// Backfill the value to the higher (and often faster/smaller) cache tiers
			$this->doWrite(
				$missIndexes, $this->asyncWrites, 'set', $key, $value, self::UPGRADE_TTL
			);
		}

		return $value;
	}

	public function set( $key, $value, $exptime = 0, $flags = 0 ) {
		$asyncWrites = ( ( $flags & self::WRITE_SYNC ) == self::WRITE_SYNC )
			? false
			: $this->asyncWrites;

		return $this->doWrite( $this->cacheIndexes, $asyncWrites, 'set', $key, $value, $exptime );
	}

	public function delete( $key ) {
		return $this->doWrite( $this->cacheIndexes, $this->asyncWrites, 'delete', $key );
	}

	public function add( $key, $value, $exptime = 0 ) {
		// Try the write to the top-tier cache
		$ok = $this->doWrite( [ 0 ], $this->asyncWrites, 'add', $key, $value, $exptime );
		if ( $ok ) {
			// Relay the add() using set() if it succeeded. This is meant to handle certain
			// migration scenarios where the same store might get written to twice for certain
			// keys. In that case, it does not make sense to return false due to "self-conflicts".
			return $this->doWrite(
				array_slice( $this->cacheIndexes, 1 ),
				$this->asyncWrites,
				'set',
				$key,
				$value,
				$exptime
			);
		}

		return false;
	}

	public function incr( $key, $value = 1 ) {
		return $this->doWrite( $this->cacheIndexes, $this->asyncWrites, 'incr', $key, $value );
	}

	public function decr( $key, $value = 1 ) {
		return $this->doWrite( $this->cacheIndexes, $this->asyncWrites, 'decr', $key, $value );
	}

	public function merge( $key, callable $callback, $exptime = 0, $attempts = 10, $flags = 0 ) {
		$asyncWrites = ( ( $flags & self::WRITE_SYNC ) == self::WRITE_SYNC )
			? false
			: $this->asyncWrites;

		return $this->doWrite(
			$this->cacheIndexes,
			$asyncWrites,
			'merge',
			$key,
			$callback,
			$exptime,
			$attempts,
			$flags
		);
	}

	public function lock( $key, $timeout = 6, $expiry = 6, $rclass = '' ) {
		// Only need to lock the first cache; also avoids deadlocks
		return $this->caches[0]->lock( $key, $timeout, $expiry, $rclass );
	}

	public function unlock( $key ) {
		// Only the first cache is locked
		return $this->caches[0]->unlock( $key );
	}

	public function getLastError() {
		return $this->caches[0]->getLastError();
	}

	public function clearLastError() {
		$this->caches[0]->clearLastError();
	}

	/**
	 * Apply a write method to the backing caches specified by $indexes (in order)
	 *
	 * @param int[] $indexes List of backing cache indexes
	 * @param bool $asyncWrites
	 * @param string $method
	 * @param mixed $args,...
	 * @return bool
	 */
	protected function doWrite( $indexes, $asyncWrites, $method /*, ... */ ) {
		$ret = true;
		$args = array_slice( func_get_args(), 3 );

		if ( array_diff( $indexes, [ 0 ] ) && $asyncWrites && $method !== 'merge' ) {
			// Deep-clone $args to prevent misbehavior when something writes an
			// object to the BagOStuff then modifies it afterwards, e.g. T168040.
			$args = unserialize( serialize( $args ) );
		}

		foreach ( $indexes as $i ) {
			$cache = $this->caches[$i];
			if ( $i == 0 || !$asyncWrites ) {
				// First store or in sync mode: write now and get result
				if ( !$cache->$method( ...$args ) ) {
					$ret = false;
				}
			} else {
				// Secondary write in async mode: do not block this HTTP request
				$logger = $this->logger;
				( $this->asyncHandler )(
					function () use ( $cache, $method, $args, $logger ) {
						if ( !$cache->$method( ...$args ) ) {
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

	public function makeKeyInternal( $keyspace, $args ) {
		return $this->caches[0]->makeKeyInternal( ...func_get_args() );
	}

	public function makeKey( $class, $component = null ) {
		return $this->caches[0]->makeKey( ...func_get_args() );
	}

	public function makeGlobalKey( $class, $component = null ) {
		return $this->caches[0]->makeGlobalKey( ...func_get_args() );
	}
}
