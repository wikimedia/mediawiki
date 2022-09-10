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
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * A cache class that replicates all writes to multiple child caches. Reads
 * are implemented by reading from the caches in the order they are given in
 * the configuration until a cache gives a positive result.
 *
 * Note that cache key construction will use the first cache backend in the list,
 * so make sure that the other backends can handle such keys (e.g. via encoding).
 *
 * @newable
 * @ingroup Cache
 */
class MultiWriteBagOStuff extends BagOStuff {
	/** @var BagOStuff[] Backing cache stores in order of highest to lowest tier */
	protected $caches;

	/** @var bool Use async secondary writes */
	protected $asyncWrites = false;
	/** @var int[] List of all backing cache indexes */
	protected $cacheIndexes = [];

	/** @var int TTL when a key is copied to a higher cache tier */
	private static $UPGRADE_TTL = 3600;

	/**
	 * @stable to call
	 * @param array $params
	 *   - caches: A numbered array of either ObjectFactory::getObjectFromSpec
	 *      arrays yielding BagOStuff objects or direct BagOStuff objects.
	 *      If using the former, the 'args' field *must* be set.
	 *      The first cache is the primary one, being the first to
	 *      be read in the fallback chain. Writes happen to all stores
	 *      in the order they are defined. However, lock()/unlock() calls
	 *      only use the primary store.
	 *   - replication: Either 'sync' or 'async'. This controls whether writes
	 *      to secondary stores are deferred when possible. To use 'async' writes
	 *      requires the 'asyncHandler' option to be set as well.
	 *      Async writes can increase the chance of some race conditions
	 *      or cause keys to expire seconds later than expected. It is
	 *      safe to use for modules when cached values: are immutable,
	 *      invalidation uses logical TTLs, invalidation uses etag/timestamp
	 *      validation against the DB, or merge() is used to handle races.
	 * @phan-param array{caches:array<int,array|BagOStuff>,replication:string} $params
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
					// Callers intending this to be for ObjectFactory::getObjectFromSpec
					// should have set "args" per the docs above. Doings so avoids extra
					// (likely harmless) params (factory/class/calls) ending up in "args".
					$cacheInfo['args'] = [ $cacheInfo ];
				}

				// ObjectFactory::getObjectFromSpec accepts an array, not just a callable (phan bug)
				// @phan-suppress-next-line PhanTypeInvalidCallableArraySize
				$this->caches[] = ObjectFactory::getObjectFromSpec( $cacheInfo );
			}
		}

		$this->attrMap = $this->mergeFlagMaps( $this->caches );

		$this->asyncWrites = (
			isset( $params['replication'] ) &&
			$params['replication'] === 'async' &&
			is_callable( $this->asyncHandler )
		);

		$this->cacheIndexes = array_keys( $this->caches );
	}

	public function get( $key, $flags = 0 ) {
		$args = func_get_args();

		if ( $this->fieldHasFlags( $flags, self::READ_LATEST ) ) {
			// If the latest write was a delete(), we do NOT want to fallback
			// to the other tiers and possibly see the old value. Also, this
			// is used by merge(), which only needs to hit the primary.
			return $this->callKeyMethodOnTierCache(
				0,
				__FUNCTION__,
				self::ARG0_KEY,
				self::RES_NONKEY,
				$args
			);
		}

		$value = false;
		// backends checked
		$missIndexes = [];
		foreach ( $this->cacheIndexes as $i ) {
			$value = $this->callKeyMethodOnTierCache(
				$i,
				__FUNCTION__,
				self::ARG0_KEY,
				self::RES_NONKEY,
				$args
			);
			if ( $value !== false ) {
				break;
			}
			$missIndexes[] = $i;
		}

		if (
			$value !== false &&
			$this->fieldHasFlags( $flags, self::READ_VERIFIED ) &&
			$missIndexes
		) {
			// Backfill the value to the higher (and often faster/smaller) cache tiers
			$this->callKeyWriteMethodOnTierCaches(
				$missIndexes,
				'set',
				self::ARG0_KEY,
				self::RES_NONKEY,
				[ $key, $value, self::$UPGRADE_TTL ]
			);
		}

		return $value;
	}

	public function set( $key, $value, $exptime = 0, $flags = 0 ) {
		return $this->callKeyWriteMethodOnTierCaches(
			$this->cacheIndexes,
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args()
		);
	}

	public function delete( $key, $flags = 0 ) {
		return $this->callKeyWriteMethodOnTierCaches(
			$this->cacheIndexes,
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args()
		);
	}

	public function add( $key, $value, $exptime = 0, $flags = 0 ) {
		// Try the write to the top-tier cache
		$ok = $this->callKeyMethodOnTierCache(
			0,
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args()
		);

		if ( $ok ) {
			// Relay the add() using set() if it succeeded. This is meant to handle certain
			// migration scenarios where the same store might get written to twice for certain
			// keys. In that case, it makes no sense to return false due to "self-conflicts".
			$okSecondaries = $this->callKeyWriteMethodOnTierCaches(
				array_slice( $this->cacheIndexes, 1 ),
				'set',
				self::ARG0_KEY,
				self::RES_NONKEY,
				[ $key, $value, $exptime, $flags ]
			);
			if ( $okSecondaries === false ) {
				$ok = false;
			}
		}

		return $ok;
	}

	public function merge( $key, callable $callback, $exptime = 0, $attempts = 10, $flags = 0 ) {
		return $this->callKeyWriteMethodOnTierCaches(
			$this->cacheIndexes,
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args()
		);
	}

	public function changeTTL( $key, $exptime = 0, $flags = 0 ) {
		return $this->callKeyWriteMethodOnTierCaches(
			$this->cacheIndexes,
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args()
		);
	}

	public function lock( $key, $timeout = 6, $exptime = 6, $rclass = '' ) {
		// Only need to lock the first cache; also avoids deadlocks
		return $this->callKeyMethodOnTierCache(
			0,
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args()
		);
	}

	public function unlock( $key ) {
		// Only the first cache is locked
		return $this->callKeyMethodOnTierCache(
			0,
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args()
		);
	}

	public function deleteObjectsExpiringBefore(
		$timestamp,
		callable $progress = null,
		$limit = INF,
		string $tag = null
	) {
		$ret = false;
		foreach ( $this->caches as $cache ) {
			if ( $cache->deleteObjectsExpiringBefore( $timestamp, $progress, $limit, $tag ) ) {
				$ret = true;
			}
		}

		return $ret;
	}

	public function getMulti( array $keys, $flags = 0 ) {
		// Just iterate over each key in order to handle all the backfill logic
		$res = [];
		foreach ( $keys as $key ) {
			$val = $this->get( $key, $flags );
			if ( $val !== false ) {
				$res[$key] = $val;
			}
		}

		return $res;
	}

	public function setMulti( array $valueByKey, $exptime = 0, $flags = 0 ) {
		return $this->callKeyWriteMethodOnTierCaches(
			$this->cacheIndexes,
			__FUNCTION__,
			self::ARG0_KEYMAP,
			self::RES_NONKEY,
			func_get_args()
		);
	}

	public function deleteMulti( array $keys, $flags = 0 ) {
		return $this->callKeyWriteMethodOnTierCaches(
			$this->cacheIndexes,
			__FUNCTION__,
			self::ARG0_KEYARR,
			self::RES_NONKEY,
			func_get_args()
		);
	}

	public function changeTTLMulti( array $keys, $exptime, $flags = 0 ) {
		return $this->callKeyWriteMethodOnTierCaches(
			$this->cacheIndexes,
			__FUNCTION__,
			self::ARG0_KEYARR,
			self::RES_NONKEY,
			func_get_args()
		);
	}

	public function incr( $key, $value = 1, $flags = 0 ) {
		return $this->callKeyWriteMethodOnTierCaches(
			$this->cacheIndexes,
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args()
		);
	}

	public function decr( $key, $value = 1, $flags = 0 ) {
		return $this->callKeyWriteMethodOnTierCaches(
			$this->cacheIndexes,
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args()
		);
	}

	public function incrWithInit( $key, $exptime, $step = 1, $init = null, $flags = 0 ) {
		return $this->callKeyWriteMethodOnTierCaches(
			$this->cacheIndexes,
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args()
		);
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
		// short-circuit; already uses "generic" keys
		return $key;
	}

	public function addBusyCallback( callable $workCallback ) {
		$this->caches[0]->addBusyCallback( $workCallback );
	}

	public function setNewPreparedValues( array $valueByKey ) {
		return $this->callKeyMethodOnTierCache(
			0,
			__FUNCTION__,
			self::ARG0_KEYMAP,
			self::RES_NONKEY,
			func_get_args()
		);
	}

	public function setMockTime( &$time ) {
		parent::setMockTime( $time );
		foreach ( $this->caches as $cache ) {
			$cache->setMockTime( $time );
		}
	}

	/**
	 * Call a method on the cache instance for the given cache tier (index)
	 *
	 * @param int $index Cache tier
	 * @param string $method Method name
	 * @param int $arg0Sig BagOStuff::A0_* constant describing argument 0
	 * @param int $rvSig BagOStuff::RV_* constant describing the return value
	 * @param array $args Method arguments
	 * @return mixed The result of calling the given method
	 */
	private function callKeyMethodOnTierCache( $index, $method, $arg0Sig, $rvSig, array $args ) {
		return $this->caches[$index]->proxyCall( $method, $arg0Sig, $rvSig, $args, $this );
	}

	/**
	 * Call a write method on the cache instances, in order, for the given tiers (indexes)
	 *
	 * @param int[] $indexes List of cache tiers
	 * @param string $method Method name
	 * @param int $arg0Sig BagOStuff::ARG0_* constant describing argument 0
	 * @param int $resSig BagOStuff::RES_* constant describing the return value
	 * @param array $args Method arguments
	 * @return mixed First synchronous result or false if any failed; null if all asynchronous
	 */
	private function callKeyWriteMethodOnTierCaches(
		array $indexes,
		$method,
		$arg0Sig,
		$resSig,
		array $args
	) {
		$res = null;

		if ( $this->asyncWrites && array_diff( $indexes, [ 0 ] ) && $method !== 'merge' ) {
			// Deep-clone $args to prevent misbehavior when something writes an
			// object to the BagOStuff then modifies it afterwards, e.g. T168040.
			$args = unserialize( serialize( $args ) );
		}

		foreach ( $indexes as $i ) {
			$cache = $this->caches[$i];

			if ( $i == 0 || !$this->asyncWrites ) {
				// Tier 0 store or in sync mode: write synchronously and get result
				$storeRes = $cache->proxyCall( $method, $arg0Sig, $resSig, $args, $this );
				if ( $storeRes === false ) {
					$res = false;
				} elseif ( $res === null ) {
					// first synchronous result
					$res = $storeRes;
				}
			} else {
				// Secondary write in async mode: do not block this HTTP request
				( $this->asyncHandler )(
					function () use ( $cache, $method, $arg0Sig, $resSig, $args ) {
						$cache->proxyCall( $method, $arg0Sig, $resSig, $args, $this );
					}
				);
			}
		}

		return $res;
	}
}
