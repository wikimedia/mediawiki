<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\ObjectCache;

use InvalidArgumentException;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * Wrap multiple BagOStuff objects, to implement different caching tiers.
 *
 * The order of the caches is important. The first tier is considered the primary
 * and highest tier which must handle the majority of the load for reads,
 * and is generally less persistent, smaller, and faster (e.g. evicts data
 * regularly based on demand, keeping fewer keys at a given time).
 * The other caches are consider secondary and lower tiers, which should
 * hold more data and retain it for longer than the primary tier.
 *
 * Data writes ("set") go to all given BagOStuff caches.
 * If the `replication => async` option is set, then only the primary write
 * is blocking during the web request, with other writes deferred until
 * after the web response is sent.
 *
 * Data reads try each cache in the order they are given, until a value is found.
 * When a value is found at a secondary tier, it is automatically copied (back)
 * to the primary tier.
 *
 * **Example**: Keep popular data in memcached, with a fallback to a MySQL database.
 * This is how ParserCache is used at Wikimedia Foundation (as of 2024).
 *
 * ```
 * $wgObjectCaches['parsercache-multiwrite'] = [
 *    'class' => 'MultiWriteBagOStuff',
 *    'caches' => [
 *      0 => [
 *        'class' => 'MemcachedPeclBagOStuff',
 *        'servers' => [ '127.0.0.1:11212' ],
 *      ],
 *      1 => [
 *        'class' => 'SqlBagOStuff',
 *        'servers' => $parserCacheDbServers,
 *        'purgePeriod' => 0,
 *        'tableName' => 'pc',
 *        'shards' => 256,
 *        'reportDupes' => false
 *      ],
 *    ]
 * ];
 * ```
 *
 * If you configure a memcached server for MultiWriteBagOStuff that is the same
 * as the one used for MediaWiki more generally, it is recommended to specify
 * the tier via ObjectCache::getInstance() so that the same object and Memcached
 * connection can be re-used.
 *
 * ```
 * $wgObjectCaches['my-memcached'] = [ .. ];
 * $wgMainCacheType = 'my-memcached';
 *
 * $wgObjectCaches['parsercache-multiwrite'] = [
 *    'class' => 'MultiWriteBagOStuff',
 *    'caches' => [
 *      0 => [
 *        'factory' => [ 'ObjectCache', 'getInstance' ],
 *        'args' => [ 'my-memcached' ],
 *      ],
 *      1 => [
 *        'class' => 'SqlBagOStuff',
 *        'servers' => $parserCacheDbServers,
 *        'purgePeriod' => 0,
 *        'tableName' => 'pc',
 *        'shards' => 256,
 *        'reportDupes' => false
 *      ],
 *    ]
 * ];
 * ```
 *
 * The makeKey() method of this class uses an implementation-agnostic encoding.
 * When it forward gets and sets to the other BagOStuff objects, keys are
 * automatically re-encoded. For example, to satisfy the character and length
 * constraints of MemcachedBagOStuff.
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
	 *
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
	 *
	 * @phan-param array{caches:array<int,array|BagOStuff>,replication:string} $params
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

	/** @inheritDoc */
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

	/** @inheritDoc */
	public function set( $key, $value, $exptime = 0, $flags = 0 ) {
		return $this->callKeyWriteMethodOnTierCaches(
			$this->cacheIndexes,
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args()
		);
	}

	/** @inheritDoc */
	public function delete( $key, $flags = 0 ) {
		return $this->callKeyWriteMethodOnTierCaches(
			$this->cacheIndexes,
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args()
		);
	}

	/** @inheritDoc */
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

	/** @inheritDoc */
	public function merge( $key, callable $callback, $exptime = 0, $attempts = 10, $flags = 0 ) {
		return $this->callKeyWriteMethodOnTierCaches(
			$this->cacheIndexes,
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args()
		);
	}

	/** @inheritDoc */
	public function changeTTL( $key, $exptime = 0, $flags = 0 ) {
		return $this->callKeyWriteMethodOnTierCaches(
			$this->cacheIndexes,
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args()
		);
	}

	/** @inheritDoc */
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

	/** @inheritDoc */
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

	/** @inheritDoc */
	public function deleteObjectsExpiringBefore(
		$timestamp,
		?callable $progress = null,
		$limit = INF,
		?string $tag = null
	) {
		$ret = false;
		foreach ( $this->caches as $cache ) {
			if ( $cache->deleteObjectsExpiringBefore( $timestamp, $progress, $limit, $tag ) ) {
				$ret = true;
			}
		}

		return $ret;
	}

	/** @inheritDoc */
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

	/** @inheritDoc */
	public function setMulti( array $valueByKey, $exptime = 0, $flags = 0 ) {
		return $this->callKeyWriteMethodOnTierCaches(
			$this->cacheIndexes,
			__FUNCTION__,
			self::ARG0_KEYMAP,
			self::RES_NONKEY,
			func_get_args()
		);
	}

	/** @inheritDoc */
	public function deleteMulti( array $keys, $flags = 0 ) {
		return $this->callKeyWriteMethodOnTierCaches(
			$this->cacheIndexes,
			__FUNCTION__,
			self::ARG0_KEYARR,
			self::RES_NONKEY,
			func_get_args()
		);
	}

	/** @inheritDoc */
	public function changeTTLMulti( array $keys, $exptime, $flags = 0 ) {
		return $this->callKeyWriteMethodOnTierCaches(
			$this->cacheIndexes,
			__FUNCTION__,
			self::ARG0_KEYARR,
			self::RES_NONKEY,
			func_get_args()
		);
	}

	/** @inheritDoc */
	public function incrWithInit( $key, $exptime, $step = 1, $init = null, $flags = 0 ) {
		return $this->callKeyWriteMethodOnTierCaches(
			$this->cacheIndexes,
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args()
		);
	}

	/** @inheritDoc */
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
	 *
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
	 *
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

/** @deprecated class alias since 1.43 */
class_alias( MultiWriteBagOStuff::class, 'MultiWriteBagOStuff' );
