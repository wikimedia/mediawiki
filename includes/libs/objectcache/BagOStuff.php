<?php
/**
 * Copyright © 2003-2004 Brion Vibber <brion@pobox.com>
 * https://www.mediawiki.org/
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
 * @defgroup Cache Cache
 */

use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Wikimedia\LightweightObjectStore\ExpirationAwareness;
use Wikimedia\LightweightObjectStore\StorageAwareness;
use Wikimedia\ScopedCallback;

/**
 * Class representing a cache/ephemeral data store
 *
 * This interface is intended to be more or less compatible with the PHP memcached client.
 *
 * Class instances should be created with an intended access scope for the dataset, such as:
 *   - a) A single PHP thread on a server (e.g. stored in a PHP variable)
 *   - b) A single application server (e.g. stored in APC or sqlite)
 *   - c) All application servers in datacenter (e.g. stored in memcached or mysql)
 *   - d) All application servers in all datacenters (e.g. stored via mcrouter or dynomite)
 *
 * Callers should use the proper factory methods that yield BagOStuff instances. Site admins
 * should make sure that the configuration for those factory methods match their access scope.
 * BagOStuff subclasses have widely varying levels of support replication features within and
 * among datacenters.
 *
 * Subclasses should override the default "segmentationSize" field with an appropriate value.
 * The value should not be larger than what the backing store (by default) supports. It also
 * should be roughly informed by common performance bottlenecks (e.g. values over a certain size
 * having poor scalability). The same goes for the "segmentedValueMaxSize" member, which limits
 * the maximum size and chunk count (indirectly) of values.
 *
 * A few notes about data consistency for BagOStuff instances:
 *  - Read operation methods, e.g. get(), should be synchronous in the local datacenter.
 *    When used with READ_LATEST, such operations should reflect any prior writes originating
 *    from the local datacenter (e.g. by avoiding replica DBs or invoking quorom reads).
 *  - Write operation methods, e.g. set(), should be synchronous in the local datacenter, with
 *    asynchronous cross-datacenter replication. This replication can be either "best effort"
 *    or eventually consistent. If the write succeeded, then any subsequent `get()` operations with
 *    READ_LATEST, regardless of datacenter, should reflect the changes.
 *  - Locking operation methods, e.g. lock(), unlock(), and getScopedLock(), should only apply
 *    to the local datacenter.
 *  - Any set of single-key write operation method calls originating from a single datacenter
 *    should observe "best effort" linearizability.
 *    In this context, "best effort" means that consistency holds as long as connectivity is
 *    strong, network latency is low, and there are no relevant storage server failures.
 *    Per https://en.wikipedia.org/wiki/PACELC_theorem, the store should act as a PA/EL
 *    distributed system for these operations.
 *
 * @stable to extend
 * @newable
 * @ingroup Cache
 */
abstract class BagOStuff implements
	ExpirationAwareness,
	StorageAwareness,
	IStoreKeyEncoder,
	LoggerAwareInterface
{
	/** @var StatsdDataFactoryInterface */
	protected $stats;
	/** @var LoggerInterface */
	protected $logger;
	/** @var callable|null */
	protected $asyncHandler;
	/** @var int[] Map of (BagOStuff:ATTR_* constant => BagOStuff:QOS_* constant) */
	protected $attrMap = [];
	/**
	 * @var array<string,array> Cache key processing callbacks and info for metrics
	 * @phan-var array<string,array{0:string,1:callable}>
	 */
	protected $wrapperInfoByPrefix = [];

	/** @var string Default keyspace; used by makeKey() */
	protected $keyspace;

	/** @var int BagOStuff:ERR_* constant of the last error that occurred */
	protected $lastError = self::ERR_NONE;
	/** @var int Error event sequence number of the last error that occurred */
	protected $lastErrorId = 0;

	/** @var int Next sequence number to use for watch/error events */
	protected static $nextErrorMonitorId = 1;

	/** @var float|null */
	private $wallClockOverride;

	/** Bitfield constants for get()/getMulti(); these are only advisory */
	/** If supported, avoid reading stale data due to replication */
	public const READ_LATEST = 1;
	/** Promise that the caller handles detection of staleness */
	public const READ_VERIFIED = 2;

	/** Bitfield constants for set()/merge(); these are only advisory */
	/** Only change state of the in-memory cache */
	public const WRITE_CACHE_ONLY = 8;
	/** Allow partitioning of the value if it is a large string */
	public const WRITE_ALLOW_SEGMENTS = 16;
	/** Delete all the segments if the value is partitioned */
	public const WRITE_PRUNE_SEGMENTS = 32;
	/**
	 * If supported, do not block on write operation completion; instead, treat writes as
	 * succesful based on whether they could be buffered. When using this flag with methods
	 * that yield item values, the boolean "true" will be used as a placeholder. The next
	 * blocking operation (e.g. typical read) will trigger a flush of the operation buffer.
	 */
	public const WRITE_BACKGROUND = 64;

	/** Abort after the first merge conflict */
	public const MAX_CONFLICTS_ONE = 1;

	/** @var string Global keyspace; used by makeGlobalKey() */
	protected const GLOBAL_KEYSPACE = 'global';
	/** @var string Precomputed global cache key prefix (needs no encoding) */
	protected const GLOBAL_PREFIX = 'global:';

	/** @var int Item is a single cache key */
	protected const ARG0_KEY = 0;
	/** @var int Item is an array of cache keys */
	protected const ARG0_KEYARR = 1;
	/** @var int Item is an array indexed by cache keys */
	protected const ARG0_KEYMAP = 2;
	/** @var int Item does not involve any keys */
	protected const ARG0_NONKEY = 3;

	/** @var int Item is an array indexed by cache keys */
	protected const RES_KEYMAP = 0;
	/** @var int Item does not involve any keys */
	protected const RES_NONKEY = 1;

	/** Key to the metric group to use for the relevant cache wrapper */
	private const WRAPPER_STATS_GROUP = 0;
	/** Key to the callback that extracts collection names from cache wrapper keys */
	private const WRAPPER_COLLECTION_CALLBACK = 1;

	/**
	 * @stable to call
	 * @param array $params Parameters include:
	 *   - keyspace: Keyspace to use for keys in makeKey(). [Default: "local"]
	 *   - asyncHandler: Callable to use for scheduling tasks after the web request ends.
	 *      In CLI mode, it should run the task immediately. [Default: null]
	 *   - stats: IStatsdDataFactory instance. [optional]
	 *   - logger: Psr\Log\LoggerInterface instance. [optional]
	 * @phan-param array{keyspace?:string,logger?:Psr\Log\LoggerInterface,asyncHandler?:callable} $params
	 */
	public function __construct( array $params = [] ) {
		$this->keyspace = $params['keyspace'] ?? 'local';
		$this->stats = $params['stats'] ?? new NullStatsdDataFactory();
		$this->setLogger( $params['logger'] ?? new NullLogger() );

		$asyncHandler = $params['asyncHandler'] ?? null;
		if ( is_callable( $asyncHandler ) ) {
			$this->asyncHandler = $asyncHandler;
		}
	}

	/**
	 * @param LoggerInterface $logger
	 * @return void
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * @since 1.35
	 * @return LoggerInterface
	 */
	public function getLogger(): LoggerInterface {
		return $this->logger;
	}

	/**
	 * Get an item, regenerating and setting it if not found
	 *
	 * The callback can take $exptime as argument by reference and modify it.
	 * Nothing is stored nor deleted if the callback returns false.
	 *
	 * @param string $key
	 * @param int $exptime Time-to-live (seconds)
	 * @param callable $callback Callback that derives the new value
	 * @param int $flags Bitfield of BagOStuff::READ_* or BagOStuff::WRITE_* constants [optional]
	 * @return mixed The cached value if found or the result of $callback otherwise
	 * @since 1.27
	 */
	final public function getWithSetCallback( $key, $exptime, $callback, $flags = 0 ) {
		$value = $this->get( $key, $flags );

		if ( $value === false ) {
			$value = $callback( $exptime );
			if ( $value !== false && $exptime >= 0 ) {
				$this->set( $key, $value, $exptime, $flags );
			}
		}

		return $value;
	}

	/**
	 * Get an item
	 *
	 * If the key includes a deterministic input hash (e.g. the key can only have
	 * the correct value) or complete staleness checks are handled by the caller
	 * (e.g. nothing relies on the TTL), then the READ_VERIFIED flag should be set.
	 * This lets tiered backends know they can safely upgrade a cached value to
	 * higher tiers using standard TTLs.
	 *
	 * @param string $key
	 * @param int $flags Bitfield of BagOStuff::READ_* constants [optional]
	 * @return mixed Returns false on failure or if the item does not exist
	 */
	abstract public function get( $key, $flags = 0 );

	/**
	 * Set an item
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants
	 * @return bool Success
	 */
	abstract public function set( $key, $value, $exptime = 0, $flags = 0 );

	/**
	 * Delete an item if it exists
	 *
	 * For large values written using WRITE_ALLOW_SEGMENTS, this only deletes the main
	 * segment list key unless WRITE_PRUNE_SEGMENTS is in the flags. While deleting the segment
	 * list key has the effect of functionally deleting the key, it leaves unused blobs in cache.
	 *
	 * @param string $key
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants
	 * @return bool Success (item deleted or not found)
	 */
	abstract public function delete( $key, $flags = 0 );

	/**
	 * Insert an item if it does not already exist
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param int $exptime
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants (since 1.33)
	 * @return bool Success (item created)
	 */
	abstract public function add( $key, $value, $exptime = 0, $flags = 0 );

	/**
	 * Merge changes into the existing cache value (possibly creating a new one)
	 *
	 * The callback function returns the new value given the current value
	 * (which will be false if not present), and takes the arguments:
	 * (this BagOStuff, cache key, current value, TTL).
	 * The TTL parameter is reference set to $exptime. It can be overridden in the callback.
	 * Nothing is stored nor deleted if the callback returns false.
	 *
	 * @param string $key
	 * @param callable $callback Callback method to be executed
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @param int $attempts The amount of times to attempt a merge in case of failure
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants
	 * @return bool Success
	 * @throws InvalidArgumentException
	 */
	abstract public function merge(
		$key,
		callable $callback,
		$exptime = 0,
		$attempts = 10,
		$flags = 0
	);

	/**
	 * Change the expiration on an item
	 *
	 * If an expiry in the past is given then the key will immediately be expired
	 *
	 * For large values written using WRITE_ALLOW_SEGMENTS, this only changes the TTL of the
	 * main segment list key. While lowering the TTL of the segment list key has the effect of
	 * functionally lowering the TTL of the key, it might leave unused blobs in cache for longer.
	 * Raising the TTL of such keys is not effective, since the expiration of a single segment
	 * key effectively expires the entire value.
	 *
	 * @param string $key
	 * @param int $exptime TTL or UNIX timestamp
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants (since 1.33)
	 * @return bool Success (item found and updated)
	 * @since 1.28
	 */
	abstract public function changeTTL( $key, $exptime = 0, $flags = 0 );

	/**
	 * Acquire an advisory lock on a key string, exclusive to the caller
	 *
	 * @param string $key
	 * @param int $timeout Lock wait timeout; 0 for non-blocking [optional]
	 * @param int $exptime Lock time-to-live in seconds; 1 day maximum [optional]
	 * @param string $rclass If this thread already holds the lock, and the lock was acquired
	 *  using the same value for this parameter, then return true and use reference counting so
	 *  that only the unlock() call from the outermost lock() caller actually releases the lock
	 *  (note that only the outermost time-to-live is used) [optional]
	 * @return bool Success
	 */
	abstract public function lock( $key, $timeout = 6, $exptime = 6, $rclass = '' );

	/**
	 * Release an advisory lock on a key string
	 *
	 * @param string $key
	 * @return bool Success
	 */
	abstract public function unlock( $key );

	/**
	 * Get a lightweight exclusive self-unlocking lock
	 *
	 * Note that the same lock cannot be acquired twice.
	 *
	 * This is useful for task de-duplication or to avoid obtrusive
	 * (though non-corrupting) DB errors like INSERT key conflicts
	 * or deadlocks when using LOCK IN SHARE MODE.
	 *
	 * @param string $key
	 * @param int $timeout Lock wait timeout; 0 for non-blocking [optional]
	 * @param int $expiry Lock expiry [optional]; 1 day maximum
	 * @param string $rclass Allow reentry if set and the current lock used this value
	 * @return ScopedCallback|null Returns null on failure
	 * @since 1.26
	 */
	final public function getScopedLock( $key, $timeout = 6, $expiry = 30, $rclass = '' ) {
		$expiry = min( $expiry ?: INF, self::TTL_DAY );

		if ( !$this->lock( $key, $timeout, $expiry, $rclass ) ) {
			return null;
		}

		return new ScopedCallback( function () use ( $key, $expiry ) {
			$this->unlock( $key );
		} );
	}

	/**
	 * Delete all objects expiring before a certain date
	 *
	 * @param string|int $timestamp The reference date in MW or TS_UNIX format
	 * @param callable|null $progress Optional, a function which will be called
	 *     regularly during long-running operations with the percentage progress
	 *     as the first parameter. [optional]
	 * @param int|float $limit Maximum number of keys to delete [default: INF]
	 * @param string|null $tag Tag to purge a single shard only.
	 *  This is only supported when server tags are used in configuration.
	 * @return bool Success; false if unimplemented
	 */
	abstract public function deleteObjectsExpiringBefore(
		$timestamp,
		callable $progress = null,
		$limit = INF,
		string $tag = null
	);

	/**
	 * Get a batch of items
	 *
	 * @param string[] $keys List of keys
	 * @param int $flags Bitfield; supports READ_LATEST [optional]
	 * @return mixed[] Map of (key => value) for existing keys
	 */
	abstract public function getMulti( array $keys, $flags = 0 );

	/**
	 * Set a batch of items
	 *
	 * This does not support WRITE_ALLOW_SEGMENTS to avoid excessive read I/O
	 *
	 * WRITE_BACKGROUND can be used for bulk insertion where the response is not vital
	 *
	 * @param mixed[] $valueByKey Map of (key => value)
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants (since 1.33)
	 * @return bool Success
	 * @since 1.24
	 */
	abstract public function setMulti( array $valueByKey, $exptime = 0, $flags = 0 );

	/**
	 * Delete a batch of items
	 *
	 * This does not support WRITE_ALLOW_SEGMENTS to avoid excessive read I/O
	 *
	 * WRITE_BACKGROUND can be used for bulk deletion where the response is not vital
	 *
	 * @param string[] $keys List of keys
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants
	 * @return bool Success (items deleted and/or not found)
	 * @since 1.33
	 */
	abstract public function deleteMulti( array $keys, $flags = 0 );

	/**
	 * Change the expiration of multiple items
	 *
	 * @see BagOStuff::changeTTL()
	 *
	 * @param string[] $keys List of keys
	 * @param int $exptime TTL or UNIX timestamp
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants (since 1.33)
	 * @return bool Success (all items found and updated)
	 * @since 1.34
	 */
	abstract public function changeTTLMulti( array $keys, $exptime, $flags = 0 );

	/**
	 * Increase stored value of $key by $value while preserving its TTL
	 *
	 * @param string $key Key to increase
	 * @param int $value Value to add to $key (default: 1) [optional]
	 * @param int $flags Bit field of class WRITE_* constants [optional]
	 * @return int|bool New value or false on failure
	 * @deprecated Since 1.38
	 */
	abstract public function incr( $key, $value = 1, $flags = 0 );

	/**
	 * Decrease stored value of $key by $value while preserving its TTL
	 *
	 * @param string $key
	 * @param int $value Value to subtract from $key (default: 1) [optional]
	 * @param int $flags Bit field of class WRITE_* constants [optional]
	 * @return int|bool New value or false on failure
	 * @deprecated Since 1.38
	 */
	abstract public function decr( $key, $value = 1, $flags = 0 );

	/**
	 * Increase the value of the given key (no TTL change) if it exists or create it otherwise
	 *
	 * This will create the key with the value $init and TTL $exptime instead if not present.
	 * Callers should make sure that both ($init - $step) and $exptime are invariants for all
	 * operations to any given key. The value of $init should be at least that of $step.
	 *
	 * The new value is returned, except if the WRITE_BACKGROUND flag is given, in which case
	 * the handler may choose to return true to indicate that the operation has been dispatched.
	 *
	 * @param string $key Key built via makeKey() or makeGlobalKey()
	 * @param int $exptime Time-to-live (in seconds) or a UNIX timestamp expiration
	 * @param int $step Amount to increase the key value by [default: 1]
	 * @param int|null $init Value to initialize the key to if it does not exist [default: $step]
	 * @param int $flags Bit field of class WRITE_* constants [optional]
	 * @return int|bool New value (or true if asynchronous) on success; false on failure
	 * @since 1.24
	 */
	abstract public function incrWithInit( $key, $exptime, $step = 1, $init = null, $flags = 0 );

	/**
	 * Get a "watch point" token that can be used to get the "last error" to occur after now
	 *
	 * @return int A token that the current error event
	 * @since 1.38
	 */
	public function watchErrors() {
		return self::$nextErrorMonitorId++;
	}

	/**
	 * Get the "last error" registry
	 *
	 * The method should be invoked by a caller as part of the following pattern:
	 *   - The caller invokes watchErrors() to get a "since token"
	 *   - The caller invokes a sequence of cache operation methods
	 *   - The caller invokes getLastError() with the "since token"
	 *
	 * External callers can also invoke this method as part of the following pattern:
	 *   - The caller invokes clearLastError()
	 *   - The caller invokes a sequence of cache operation methods
	 *   - The caller invokes getLastError()
	 *
	 * @param int $watchPoint Only consider errors from after this "watch point" [optional]
	 * @return int BagOStuff:ERR_* constant for the "last error" registry
	 * @note Parameters added in 1.38: $watchPoint
	 * @since 1.23
	 */
	public function getLastError( $watchPoint = 0 ) {
		return ( $this->lastErrorId > $watchPoint ) ? $this->lastError : self::ERR_NONE;
	}

	/**
	 * Clear the "last error" registry
	 *
	 * @since 1.23
	 * @deprecated Since 1.38
	 */
	public function clearLastError() {
		$this->lastError = self::ERR_NONE;
	}

	/**
	 * Set the "last error" registry due to a problem encountered during an attempted operation
	 *
	 * @param int $error BagOStuff:ERR_* constant
	 * @since 1.23
	 */
	protected function setLastError( $error ) {
		$this->lastError = $error;
		$this->lastErrorId = self::$nextErrorMonitorId++;
	}

	/**
	 * Let a callback be run to avoid wasting time on special blocking calls
	 *
	 * This is hard-deprecated and non-functional since 1.39. The callback
	 * will not be called.
	 *
	 * @param callable $workCallback
	 * @since 1.28
	 * @deprecated since 1.39
	 */
	abstract public function addBusyCallback( callable $workCallback );

	/**
	 * Make a cache key for the given keyspace and components
	 *
	 * Long components might be converted to respective hashes due to size constraints.
	 * In extreme cases, all of them might be combined into a single hash component.
	 *
	 * @internal This method should not be used outside of BagOStuff (since 1.36)
	 *
	 * @param string $keyspace Keyspace component
	 * @param string[]|int[] $components Key components (key collection name first)
	 * @return string Keyspace-prepended list of encoded components as a colon-separated value
	 * @since 1.27
	 */
	abstract public function makeKeyInternal( $keyspace, $components );

	/**
	 * Make a cache key for the default keyspace and given components
	 *
	 * @see IStoreKeyEncoder::makeGlobalKey()
	 *
	 * @param string $collection Key collection name component
	 * @param string|int ...$components Additional, ordered, key components for entity IDs
	 * @return string Colon-separated, keyspace-prepended, ordered list of encoded components
	 * @since 1.27
	 */
	abstract public function makeGlobalKey( $collection, ...$components );

	/**
	 * Make a cache key for the global keyspace and given components
	 *
	 * @see IStoreKeyEncoder::makeKey()
	 *
	 * @param string $collection Key collection name component
	 * @param string|int ...$components Additional, ordered, key components for entity IDs
	 * @return string Colon-separated, keyspace-prepended, ordered list of encoded components
	 * @since 1.27
	 */
	abstract public function makeKey( $collection, ...$components );

	/**
	 * Check whether a cache key is in the global keyspace
	 *
	 * @param string $key
	 * @return bool
	 * @since 1.35
	 */
	public function isKeyGlobal( $key ) {
		return str_starts_with( $key, self::GLOBAL_PREFIX );
	}

	/**
	 * @param int $flag BagOStuff::ATTR_* constant
	 * @return int BagOStuff:QOS_* constant
	 * @since 1.28
	 */
	public function getQoS( $flag ) {
		return $this->attrMap[$flag] ?? self::QOS_UNKNOWN;
	}

	/**
	 * @return int|float The chunk size, in bytes, of segmented objects (INF for no limit)
	 * @since 1.34
	 */
	public function getSegmentationSize() {
		return INF;
	}

	/**
	 * @return int|float Maximum total segmented object size in bytes (INF for no limit)
	 * @since 1.34
	 */
	public function getSegmentedValueMaxSize() {
		return INF;
	}

	/**
	 * @param int $field
	 * @param int $flags
	 * @return bool
	 * @since 1.34
	 */
	final protected function fieldHasFlags( $field, $flags ) {
		return ( ( $field & $flags ) === $flags );
	}

	/**
	 * Merge the flag maps of one or more BagOStuff objects into a "lowest common denominator" map
	 *
	 * @param BagOStuff[] $bags
	 * @return int[] Resulting flag map (class ATTR_* constant => class QOS_* constant)
	 */
	final protected function mergeFlagMaps( array $bags ) {
		$map = [];
		foreach ( $bags as $bag ) {
			foreach ( $bag->attrMap as $attr => $rank ) {
				if ( isset( $map[$attr] ) ) {
					$map[$attr] = min( $map[$attr], $rank );
				} else {
					$map[$attr] = $rank;
				}
			}
		}

		return $map;
	}

	/**
	 * Stage a set of new key values for storage and estimate the amount of bytes needed
	 *
	 * All previously prepared values will be cleared. Each of the new prepared values will be
	 * individually cleared as they get used by write operations for that key. This is done to
	 * avoid unchecked growth in PHP memory usage.
	 *
	 * Example usage:
	 * @code
	 *     $valueByKey = [ $key1 => $value1, $key2 => $value2, $key3 => $value3 ];
	 *     $cache->setNewPreparedValues( $valueByKey );
	 *     $cache->set( $key1, $value1, $cache::TTL_HOUR );
	 *     $cache->setMulti( [ $key2 => $value2, $key3 => $value3 ], $cache::TTL_HOUR );
	 * @endcode
	 *
	 * This is only useful if the caller needs an estimate of the serialized object sizes,
	 * such as cache wrappers with adaptive write slam avoidance or store wrappers with metrics.
	 * The caller cannot know the serialization format and even if it did, it could be expensive
	 * to serialize complex values twice just to get the size information before writing them to
	 * cache. This method solves both problems by making the cache instance do the serialization
	 * and having it reuse the result when the cache write happens.
	 *
	 * @param array $valueByKey Map of (cache key => PHP variable value to serialize)
	 * @return int[]|null[] Corresponding list of size estimates (null for invalid values)
	 * @since 1.35
	 */
	abstract public function setNewPreparedValues( array $valueByKey );

	/**
	 * Register info about a caching layer class that uses BagOStuff as a backing store
	 *
	 * Object cache wrappers are classes that implement generic caching/storage functionality,
	 * use a BagOStuff instance as the backing store, and implement IStoreKeyEncoder with the
	 * same "generic" style key encoding as BagOStuff. Such wrappers transform keys before
	 * passing them to BagOStuff methods; a wrapper-specific prefix component will be prepended
	 * along with other possible additions. Transformed keys still use the "generic" BagOStuff
	 * encoding.
	 *
	 * The provided callback takes a transformed key, having the specified prefix component,
	 * and extracts the key collection name. The callback must be able to handle
	 * keys that bear the prefix (by coincidence) but do not originate from the wrapper class.
	 *
	 * Calls to this method should be idempotent.
	 *
	 * @param string $prefixComponent Key prefix component used by the wrapper
	 * @param string $statsGroup Stats group to use for metrics from this wrapper
	 * @param callable $collectionCallback Static callback that gets the key collection name
	 * @internal For use with BagOStuff and WANObjectCache only
	 * @since 1.36
	 */
	public function registerWrapperInfoForStats(
		string $prefixComponent,
		string $statsGroup,
		callable $collectionCallback
	) {
		$this->wrapperInfoByPrefix[$prefixComponent] = [
			self::WRAPPER_STATS_GROUP => $statsGroup,
			self::WRAPPER_COLLECTION_CALLBACK => $collectionCallback
		];
	}

	/**
	 * At a minimum, there must be a keyspace and collection name component
	 *
	 * @param string|int ...$components Key components for keyspace, collection name, and IDs
	 * @return string Keyspace-prepended list of encoded components as a colon-separated value
	 * @since 1.35
	 */
	final protected function genericKeyFromComponents( ...$components ) {
		if ( count( $components ) < 2 ) {
			throw new InvalidArgumentException( "Missing keyspace or collection name" );
		}

		$key = '';
		foreach ( $components as $i => $component ) {
			if ( $i > 0 ) {
				$key .= ':';
			}
			// Escape delimiter (":") and escape ("%") characters
			$key .= strtr( $component, [ '%' => '%25', ':' => '%3A' ] );
		}

		return $key;
	}

	/**
	 * Extract the components from a "generic" reversible cache key
	 *
	 * @see BagOStuff::genericKeyFromComponents()
	 *
	 * @param string $key Keyspace-prepended list of encoded components as a colon-separated value
	 * @return string[] Key components for keyspace, collection name, and IDs
	 * @since 1.35
	 */
	final protected function componentsFromGenericKey( $key ) {
		// Note that the order of each corresponding search/replace pair matters
		return str_replace( [ '%3A', '%25' ], [ ':', '%' ], explode( ':', $key ) );
	}

	/**
	 * Convert a "generic" reversible cache key into one for this cache
	 *
	 * @see BagOStuff::genericKeyFromComponents()
	 *
	 * @param string $key Keyspace-prepended list of encoded components as a colon-separated value
	 * @return string Keyspace-prepended list of encoded components as a colon-separated value
	 */
	abstract protected function convertGenericKey( $key );

	/**
	 * Call a method on behalf of wrapper BagOStuff instance that uses "generic" keys
	 *
	 * @param string $method Name of a non-final public method that reads/changes keys
	 * @param int $arg0Sig BagOStuff::ARG0_* constant describing argument 0
	 * @param int $resSig BagOStuff::RES_* constant describing the return value
	 * @param array $genericArgs Method arguments passed to the wrapper instance
	 * @param BagOStuff $wrapper The wrapper BagOStuff instance using this result
	 * @return mixed Method result with any keys remapped to "generic" keys
	 */
	protected function proxyCall(
		string $method,
		int $arg0Sig,
		int $resSig,
		array $genericArgs,
		BagOStuff $wrapper
	) {
		// Get the corresponding store-specific cache keys...
		$storeArgs = $genericArgs;
		switch ( $arg0Sig ) {
			case self::ARG0_KEY:
				$storeArgs[0] = $this->convertGenericKey( $genericArgs[0] );
				break;
			case self::ARG0_KEYARR:
				foreach ( $genericArgs[0] as $i => $genericKey ) {
					$storeArgs[0][$i] = $this->convertGenericKey( $genericKey );
				}
				break;
			case self::ARG0_KEYMAP:
				$storeArgs[0] = [];
				foreach ( $genericArgs[0] as $genericKey => $v ) {
					$storeArgs[0][$this->convertGenericKey( $genericKey )] = $v;
				}
				break;
		}

		// Result of invoking the method with the corresponding store-specific cache keys
		$watchPoint = $this->watchErrors();
		$storeRes = $this->$method( ...$storeArgs );
		$lastError = $this->getLastError( $watchPoint );
		if ( $lastError !== self::ERR_NONE ) {
			$wrapper->setLastError( $lastError );
		}

		// Convert any store-specific cache keys in the result back to generic cache keys
		if ( $resSig === self::RES_KEYMAP ) {
			// Map of (store-specific cache key => generic cache key)
			$genericKeyByStoreKey = array_combine( $storeArgs[0], $genericArgs[0] );

			$genericRes = [];
			foreach ( $storeRes as $storeKey => $value ) {
				$genericRes[$genericKeyByStoreKey[$storeKey]] = $value;
			}
		} else {
			$genericRes = $storeRes;
		}

		return $genericRes;
	}

	/**
	 * @param string $key Key generated by an IStoreKeyEncoder instance
	 * @return string A stats prefix to describe this class of key (e.g. "objectcache.file")
	 */
	protected function determineKeyPrefixForStats( $key ) {
		$firstComponent = substr( $key, 0, strcspn( $key, ':' ) );

		$wrapperInfo = $this->wrapperInfoByPrefix[$firstComponent] ?? null;
		if ( $wrapperInfo ) {
			// Key has the prefix of a cache wrapper class that wraps BagOStuff
			$collection = $wrapperInfo[self::WRAPPER_COLLECTION_CALLBACK]( $key );
			$statsGroup = $wrapperInfo[self::WRAPPER_STATS_GROUP];
		} else {
			// Key came directly from BagOStuff::makeKey() or BagOStuff::makeGlobalKey()
			// and thus has the format of "<scope>:<collection>[:<constant or variable>]..."
			$components = explode( ':', $key, 3 );
			// Handle legacy callers that fail to use the key building methods
			$collection = $components[1] ?? $components[0];
			$statsGroup = 'objectcache';
		}

		// Replace dots because they are special in StatsD (T232907)
		return $statsGroup . '.' . strtr( $collection, '.', '_' );
	}

	/**
	 * @internal For testing only
	 * @return float UNIX timestamp
	 * @codeCoverageIgnore
	 */
	public function getCurrentTime() {
		return $this->wallClockOverride ?: microtime( true );
	}

	/**
	 * @internal For testing only
	 * @param float|null &$time Mock UNIX timestamp
	 * @codeCoverageIgnore
	 */
	public function setMockTime( &$time ) {
		$this->wallClockOverride =& $time;
	}
}
