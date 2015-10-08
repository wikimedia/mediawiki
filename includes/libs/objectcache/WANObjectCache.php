<?php
/**
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
 * @author Aaron Schulz
 */

/**
 * Multi-datacenter aware caching interface
 *
 * All operations go to the local datacenter cache, except for delete(),
 * touchCheckKey(), and resetCheckKey(), which broadcast to all datacenters.
 *
 * This class is intended for caching data from primary stores.
 * If the get() method does not return a value, then the caller
 * should query the new value and backfill the cache using set().
 * When the source data changes, a purge method should be called.
 * Since purges are expensive, they should be avoided. One can do so if:
 *   - a) The object cached is immutable; or
 *   - b) Validity is checked against the source after get(); or
 *   - c) Using a modest TTL is reasonably correct and performant
 * The simplest purge method is delete().
 *
 * Instances of this class must be configured to point to a valid
 * PubSub endpoint, and there must be listeners on the cache servers
 * that subscribe to the endpoint and update the caches.
 *
 * Broadcasted operations like delete() and touchCheckKey() are done
 * synchronously in the local datacenter, but are relayed asynchronously.
 * This means that callers in other datacenters will see older values
 * for however many milliseconds the datacenters are apart. As with
 * any cache, this should not be relied on for cases where reads are
 * used to determine writes to source (e.g. non-cache) data stores.
 *
 * All values are wrapped in metadata arrays. Keys use a "WANCache:" prefix
 * to avoid collisions with keys that are not wrapped as metadata arrays. The
 * prefixes are as follows:
 *   - a) "WANCache:v" : used for regular value keys
 *   - b) "WANCache:s" : used for temporarily storing values of tombstoned keys
 *   - c) "WANCache:t" : used for storing timestamp "check" keys
 *
 * @ingroup Cache
 * @since 1.26
 */
class WANObjectCache {
	/** @var BagOStuff The local datacenter cache */
	protected $cache;
	/** @var string Cache pool name */
	protected $pool;
	/** @var EventRelayer */
	protected $relayer;

	/** @var int */
	protected $lastRelayError = self::ERR_NONE;

	/** Max time expected to pass between delete() and DB commit finishing */
	const MAX_COMMIT_DELAY = 3;
	/** Max replication lag before applying TTL_LAGGED to set() */
	const MAX_REPLICA_LAG = 5;
	/** Max time since snapshot transaction start to avoid no-op of set() */
	const MAX_SNAPSHOT_LAG = 5;
	/** Seconds to tombstone keys on delete() */
	const HOLDOFF_TTL = 14; // MAX_COMMIT_DELAY + MAX_REPLICA_LAG + MAX_SNAPSHOT_LAG + 1

	/** Seconds to keep dependency purge keys around */
	const CHECK_KEY_TTL = 31536000; // 1 year
	/** Seconds to keep lock keys around */
	const LOCK_TTL = 5;
	/** Default remaining TTL at which to consider pre-emptive regeneration */
	const LOW_TTL = 30;
	/** Default time-since-expiry on a miss that makes a key "hot" */
	const LOCK_TSE = 1;

	/** Idiom for set()/getWithSetCallback() TTL being "forever" */
	const TTL_NONE = 0;
	/** Idiom for getWithSetCallback() callbacks to avoid calling set() */
	const TTL_UNCACHEABLE = -1;
	/** Idiom for getWithSetCallback() callbacks to 'lockTSE' logic */
	const TSE_NONE = -1;
	/** Max TTL to store keys when a data sourced is lagged */
	const TTL_LAGGED = 30;

	/** Cache format version number */
	const VERSION = 1;

	/** Fields of value holder arrays */
	const FLD_VERSION = 0;
	const FLD_VALUE = 1;
	const FLD_TTL = 2;
	const FLD_TIME = 3;

	/** Possible values for getLastError() */
	const ERR_NONE = 0; // no error
	const ERR_NO_RESPONSE = 1; // no response
	const ERR_UNREACHABLE = 2; // can't connect
	const ERR_UNEXPECTED = 3; // response gave some error
	const ERR_RELAY = 4; // relay broadcast failed

	const VALUE_KEY_PREFIX = 'WANCache:v:';
	const STASH_KEY_PREFIX = 'WANCache:s:';
	const TIME_KEY_PREFIX = 'WANCache:t:';

	const PURGE_VAL_PREFIX = 'PURGED:';

	/**
	 * @param array $params
	 *   - cache   : BagOStuff object
	 *   - pool    : pool name
	 *   - relayer : EventRelayer object
	 */
	public function __construct( array $params ) {
		$this->cache = $params['cache'];
		$this->pool = $params['pool'];
		$this->relayer = $params['relayer'];
	}

	/**
	 * @return WANObjectCache Cache that wraps EmptyBagOStuff
	 */
	public static function newEmpty() {
		return new self( array(
			'cache'   => new EmptyBagOStuff(),
			'pool'    => 'empty',
			'relayer' => new EventRelayerNull( array() )
		) );
	}

	/**
	 * Fetch the value of a key from cache
	 *
	 * If passed in, $curTTL is set to the remaining TTL (current time left):
	 *   - a) INF; if the key exists, has no TTL, and is not expired by $checkKeys
	 *   - b) float (>=0); if the key exists, has a TTL, and is not expired by $checkKeys
	 *   - c) float (<0); if the key is tombstoned or existing but expired by $checkKeys
	 *   - d) null; if the key does not exist and is not tombstoned
	 *
	 * If a key is tombstoned, $curTTL will reflect the time since delete().
	 *
	 * The timestamp of $key will be checked against the last-purge timestamp
	 * of each of $checkKeys. Those $checkKeys not in cache will have the last-purge
	 * initialized to the current timestamp. If any of $checkKeys have a timestamp
	 * greater than that of $key, then $curTTL will reflect how long ago $key
	 * became invalid. Callers can use $curTTL to know when the value is stale.
	 * The $checkKeys parameter allow mass invalidations by updating a single key:
	 *   - a) Each "check" key represents "last purged" of some source data
	 *   - b) Callers pass in relevant "check" keys as $checkKeys in get()
	 *   - c) When the source data that "check" keys represent changes,
	 *        the touchCheckKey() method is called on them
	 *
	 * Source data entities might exists in a DB that uses snapshot isolation
	 * (e.g. the default REPEATABLE-READ in innoDB). Even for mutable data, that
	 * isolation can largely be maintained by doing the following:
	 *   - a) Calling delete() on entity change *and* creation, before DB commit
	 *   - b) Keeping transaction duration shorter than delete() hold-off TTL
	 *
	 * However, pre-snapshot values might still be seen if an update was made
	 * in a remote datacenter but the purge from delete() didn't relay yet.
	 *
	 * Consider using getWithSetCallback() instead of get()/set() cycles.
	 * That method has cache slam avoiding features for hot/expensive keys.
	 *
	 * @param string $key Cache key
	 * @param mixed $curTTL Approximate TTL left on the key if present [returned]
	 * @param array $checkKeys List of "check" keys
	 * @return mixed Value of cache key or false on failure
	 */
	final public function get( $key, &$curTTL = null, array $checkKeys = array() ) {
		$curTTLs = array();
		$values = $this->getMulti( array( $key ), $curTTLs, $checkKeys );
		$curTTL = isset( $curTTLs[$key] ) ? $curTTLs[$key] : null;

		return isset( $values[$key] ) ? $values[$key] : false;
	}

	/**
	 * Fetch the value of several keys from cache
	 *
	 * @see WANObjectCache::get()
	 *
	 * @param array $keys List of cache keys
	 * @param array $curTTLs Map of (key => approximate TTL left) for existing keys [returned]
	 * @param array $checkKeys List of "check" keys
	 * @return array Map of (key => value) for keys that exist
	 */
	final public function getMulti(
		array $keys, &$curTTLs = array(), array $checkKeys = array()
	) {
		$result = array();
		$curTTLs = array();

		$vPrefixLen = strlen( self::VALUE_KEY_PREFIX );
		$valueKeys = self::prefixCacheKeys( $keys, self::VALUE_KEY_PREFIX );
		$checkKeys = self::prefixCacheKeys( $checkKeys, self::TIME_KEY_PREFIX );

		// Fetch all of the raw values
		$wrappedValues = $this->cache->getMulti( array_merge( $valueKeys, $checkKeys ) );
		$now = microtime( true );

		// Get/initialize the timestamp of all the "check" keys
		$checkKeyTimes = array();
		foreach ( $checkKeys as $checkKey ) {
			$timestamp = isset( $wrappedValues[$checkKey] )
				? self::parsePurgeValue( $wrappedValues[$checkKey] )
				: false;
			if ( !is_float( $timestamp ) ) {
				// Key is not set or invalid; regenerate
				$this->cache->add( $checkKey,
					self::PURGE_VAL_PREFIX . $now, self::CHECK_KEY_TTL );
				$timestamp = $now;
			}

			$checkKeyTimes[] = $timestamp;
		}

		// Get the main cache value for each key and validate them
		foreach ( $valueKeys as $vKey ) {
			if ( !isset( $wrappedValues[$vKey] ) ) {
				continue; // not found
			}

			$key = substr( $vKey, $vPrefixLen ); // unprefix

			list( $value, $curTTL ) = $this->unwrap( $wrappedValues[$vKey], $now );
			if ( $value !== false ) {
				$result[$key] = $value;
				foreach ( $checkKeyTimes as $checkKeyTime ) {
					// Force dependant keys to be invalid for a while after purging
					// to reduce race conditions involving stale data getting cached
					$safeTimestamp = $checkKeyTime + self::HOLDOFF_TTL;
					if ( $safeTimestamp >= $wrappedValues[$vKey][self::FLD_TIME] ) {
						$curTTL = min( $curTTL, $checkKeyTime - $now );
					}
				}
			}

			$curTTLs[$key] = $curTTL;
		}

		return $result;
	}

	/**
	 * Set the value of a key from cache
	 *
	 * Simply calling this method when source data changes is not valid because
	 * the changes do not replicate to the other WAN sites. In that case, delete()
	 * should be used instead. This method is intended for use on cache misses.
	 *
	 * If the data was read from a snapshot-isolated transactions (e.g. the default
	 * REPEATABLE-READ in innoDB), use 'since' to avoid the following race condition:
	 *   - a) T1 starts
	 *   - b) T2 updates a row, calls delete(), and commits
	 *   - c) The HOLDOFF_TTL passes, expiring the delete() tombstone
	 *   - d) T1 reads the row and calls set() due to a cache miss
	 *   - e) Stale value is stuck in cache
	 *
	 * Setting 'lag' helps avoids keys getting stuck in long-term stale states.
	 *
	 * Example usage:
	 * @code
	 *     $dbr = wfGetDB( DB_SLAVE );
	 *     $setOpts = Database::getCacheSetOptions( $dbr );
	 *     // Fetch the row from the DB
	 *     $row = $dbr->selectRow( ... );
	 *     $key = wfMemcKey( 'building', $buildingId );
	 *     $cache->set( $key, $row, 86400, $setOpts );
	 * @endcode
	 *
	 * @param string $key Cache key
	 * @param mixed $value
	 * @param integer $ttl Seconds to live [0=forever]
	 * @param array $opts Options map:
	 *   - lag     : Seconds of slave lag. Typically, this is either the slave lag
	 *               before the data was read or, if applicable, the slave lag before
	 *               the snapshot-isolated transaction the data was read from started.
	 *               [Default: 0 seconds]
	 *   - since   : UNIX timestamp of the data in $value. Typically, this is either
	 *               the current time the data was read or (if applicable) the time when
	 *               the snapshot-isolated transaction the data was read from started.
	 *               [Default: 0 seconds]
	 *   - lockTSE : if excessive possible snapshot lag is detected,
	 *               then stash the value into a temporary location
	 *               with this TTL. This is only useful if the reads
	 *               use getWithSetCallback() with "lockTSE" set.
	 *               [Default: WANObjectCache::TSE_NONE]
	 * @return bool Success
	 */
	final public function set( $key, $value, $ttl = 0, array $opts = array() ) {
		$lockTSE = isset( $opts['lockTSE'] ) ? $opts['lockTSE'] : self::TSE_NONE;
		$age = isset( $opts['since'] ) ? max( 0, microtime( true ) - $opts['since'] ) : 0;
		$lag = isset( $opts['lag'] ) ? $opts['lag'] : 0;

		if ( $lag > self::MAX_REPLICA_LAG ) {
			// Too much lag detected; lower TTL so it converges faster
			$ttl = $ttl ? min( $ttl, self::TTL_LAGGED ) : self::TTL_LAGGED;
		}

		if ( $age > self::MAX_SNAPSHOT_LAG ) {
			if ( $lockTSE >= 0 ) {
				$tempTTL = max( 1, (int)$lockTSE ); // set() expects seconds
				$this->cache->set( self::STASH_KEY_PREFIX . $key, $value, $tempTTL );
			}

			return true; // no-op the write for being unsafe
		}

		$wrapped = $this->wrap( $value, $ttl );

		$func = function ( $cache, $key, $cWrapped ) use ( $wrapped ) {
			return ( is_string( $cWrapped ) )
				? false // key is tombstoned; do nothing
				: $wrapped;
		};

		return $this->cache->merge( self::VALUE_KEY_PREFIX . $key, $func, $ttl, 1 );
	}

	/**
	 * Purge a key from all datacenters
	 *
	 * This should only be called when the underlying data (being cached)
	 * changes in a significant way. This deletes the key and starts a hold-off
	 * period where the key cannot be written to for a few seconds (HOLDOFF_TTL).
	 * This is done to avoid the following race condition:
	 *   - a) Some DB data changes and delete() is called on a corresponding key
	 *   - b) A request refills the key with a stale value from a lagged DB
	 *   - c) The stale value is stuck there until the key is expired/evicted
	 *
	 * This is implemented by storing a special "tombstone" value at the cache
	 * key that this class recognizes; get() calls will return false for the key
	 * and any set() calls will refuse to replace tombstone values at the key.
	 * For this to always avoid writing stale values, the following must hold:
	 *   - a) Replication lag is bounded to being less than HOLDOFF_TTL; or
	 *   - b) If lag is higher, the DB will have gone into read-only mode already
	 *
	 * When using potentially long-running ACID transactions, a good pattern is
	 * to use a pre-commit hook to issue the delete. This means that immediately
	 * after commit, callers will see the tombstone in cache in the local datacenter
	 * and in the others upon relay. It also avoids the following race condition:
	 *   - a) T1 begins, changes a row, and calls delete()
	 *   - b) The HOLDOFF_TTL passes, expiring the delete() tombstone
	 *   - c) T2 starts, reads the row and calls set() due to a cache miss
	 *   - d) T1 finally commits
	 *   - e) Stale value is stuck in cache
	 *
	 * Example usage:
	 * @code
	 *     $dbw->begin(); // start of request
	 *     ... <execute some stuff> ...
	 *     // Update the row in the DB
	 *     $dbw->update( ... );
	 *     $key = wfMemcKey( 'homes', $homeId );
	 *     // Purge the corresponding cache entry just before committing
	 *     $dbw->onTransactionPreCommitOrIdle( function() use ( $cache, $key ) {
	 *         $cache->delete( $key );
	 *     } );
	 *     ... <execute some stuff> ...
	 *     $dbw->commit(); // end of request
	 * @endcode
	 *
	 * If called twice on the same key, then the last hold-off TTL takes
	 * precedence. For idempotence, the $ttl should not vary for different
	 * delete() calls on the same key. Also note that lowering $ttl reduces
	 * the effective range of the 'lockTSE' parameter to getWithSetCallback().
	 *
	 * @param string $key Cache key
	 * @param integer $ttl How long to block writes to the key [seconds]
	 * @return bool True if the item was purged or not found, false on failure
	 */
	final public function delete( $key, $ttl = self::HOLDOFF_TTL ) {
		$key = self::VALUE_KEY_PREFIX . $key;
		// Avoid indefinite key salting for sanity
		$ttl = max( $ttl, 1 );
		// Update the local datacenter immediately
		$ok = $this->cache->set( $key, self::PURGE_VAL_PREFIX . microtime( true ), $ttl );
		// Publish the purge to all datacenters
		return $this->relayPurge( $key, $ttl ) && $ok;
	}

	/**
	 * Fetch the value of a timestamp "check" key
	 *
	 * The key will be *initialized* to the current time if not set,
	 * so only call this method if this behavior is actually desired
	 *
	 * The timestamp can be used to check whether a cached value is valid.
	 * Callers should not assume that this returns the same timestamp in
	 * all datacenters due to relay delays.
	 *
	 * The level of staleness can roughly be estimated from this key, but
	 * if the key was evicted from cache, such calculations may show the
	 * time since expiry as ~0 seconds.
	 *
	 * Note that "check" keys won't collide with other regular keys
	 *
	 * @param string $key
	 * @return float UNIX timestamp of the key
	 */
	final public function getCheckKeyTime( $key ) {
		$key = self::TIME_KEY_PREFIX . $key;

		$time = self::parsePurgeValue( $this->cache->get( $key ) );
		if ( $time === false ) {
			// Casting assures identical floats for the next getCheckKeyTime() calls
			$time = (string)microtime( true );
			$this->cache->add( $key, self::PURGE_VAL_PREFIX . $time, self::CHECK_KEY_TTL );
			$time = (float)$time;
		}

		return $time;
	}

	/**
	 * Purge a "check" key from all datacenters, invalidating keys that use it
	 *
	 * This should only be called when the underlying data (being cached)
	 * changes in a significant way, and it is impractical to call delete()
	 * on all keys that should be changed. When get() is called on those
	 * keys, the relevant "check" keys must be supplied for this to work.
	 *
	 * The "check" key essentially represents a last-modified field.
	 * It is set in the future a few seconds when this is called, to
	 * avoid race conditions where dependent keys get updated with a
	 * stale value (e.g. from a DB slave).
	 *
	 * This is typically useful for keys with static names or some cases
	 * dynamically generated names where a low number of combinations exist.
	 * When a few important keys get a large number of hits, a high cache
	 * time is usually desired as well as lockTSE logic. The resetCheckKey()
	 * method is less appropriate in such cases since the "time since expiry"
	 * cannot be inferred.
	 *
	 * Note that "check" keys won't collide with other regular keys
	 *
	 * @see WANObjectCache::get()
	 *
	 * @param string $key Cache key
	 * @return bool True if the item was purged or not found, false on failure
	 */
	final public function touchCheckKey( $key ) {
		$key = self::TIME_KEY_PREFIX . $key;
		// Update the local datacenter immediately
		$ok = $this->cache->set( $key,
			self::PURGE_VAL_PREFIX . microtime( true ), self::CHECK_KEY_TTL );
		// Publish the purge to all datacenters
		return $this->relayPurge( $key, self::CHECK_KEY_TTL ) && $ok;
	}

	/**
	 * Delete a "check" key from all datacenters, invalidating keys that use it
	 *
	 * This is similar to touchCheckKey() in that keys using it via
	 * getWithSetCallback() will be invalidated. The differences are:
	 *   - a) The timestamp will be deleted from all caches and lazily
	 *        re-initialized when accessed (rather than set everywhere)
	 *   - b) Thus, dependent keys will be known to be invalid, but not
	 *        for how long (they are treated as "just" purged), which
	 *        effects any lockTSE logic in getWithSetCallback()
	 *
	 * The advantage is that this does not place high TTL keys on every cache
	 * server, making it better for code that will cache many different keys
	 * and either does not use lockTSE or uses a low enough TTL anyway.
	 *
	 * This is typically useful for keys with dynamically generated names
	 * where a high number of combinations exist.
	 *
	 * Note that "check" keys won't collide with other regular keys
	 *
	 * @see WANObjectCache::touchCheckKey()
	 * @see WANObjectCache::get()
	 *
	 * @param string $key Cache key
	 * @return bool True if the item was purged or not found, false on failure
	 */
	final public function resetCheckKey( $key ) {
		$key = self::TIME_KEY_PREFIX . $key;
		// Update the local datacenter immediately
		$ok = $this->cache->delete( $key );
		// Publish the purge to all datacenters
		return $this->relayDelete( $key ) && $ok;
	}

	/**
	 * Method to fetch/regenerate cache keys
	 *
	 * On cache miss, the key will be set to the callback result via set()
	 * unless the callback returns false. The arguments supplied to it are:
	 *     (current value or false, &$ttl, &$setOpts)
	 * The callback function returns the new value given the current
	 * value (false if not present). Preemptive re-caching and $checkKeys
	 * can result in a non-false current value. The TTL of the new value
	 * can be set dynamically by altering $ttl in the callback (by reference).
	 * The $setOpts array can be altered and is given to set() when called;
	 * it is recommended to set the 'since' field to avoid race conditions.
	 * Setting 'lag' helps avoids keys getting stuck in long-term stale states.
	 *
	 * Usually, callbacks ignore the current value, but it can be used
	 * to maintain "most recent X" values that come from time or sequence
	 * based source data, provided that the "as of" id/time is tracked.
	 *
	 * Usage of $checkKeys is similar to get() and getMulti(). However,
	 * rather than the caller having to inspect a "current time left"
	 * variable (e.g. $curTTL, $curTTLs), a cache regeneration will be
	 * triggered using the callback.
	 *
	 * The simplest way to avoid stampedes for hot keys is to use
	 * the 'lockTSE' option in $opts. If cache purges are needed, also:
	 *   - a) Pass $key into $checkKeys
	 *   - b) Use touchCheckKey( $key ) instead of delete( $key )
	 *
	 * Example usage (typical key):
	 * @code
	 *     $catInfo = $cache->getWithSetCallback(
	 *         // Key to store the cached value under
	 *         wfMemcKey( 'cat-attributes', $catId ),
	 *         // Time-to-live (seconds)
	 *         60,
	 *         // Function that derives the new key value
	 *         function ( $oldValue, &$ttl, array &$setOpts ) {
	 *             $dbr = wfGetDB( DB_SLAVE );
	 *             // Account for any snapshot/slave lag
	 *             $setOpts += Database::getCacheSetOptions( $dbr );
	 *
	 *             return $dbr->selectRow( ... );
	 *        }
	 *     );
	 * @endcode
	 *
	 * Example usage (key that is expensive and hot):
	 * @code
	 *     $catConfig = $cache->getWithSetCallback(
	 *         // Key to store the cached value under
	 *         wfMemcKey( 'site-cat-config' ),
	 *         // Time-to-live (seconds)
	 *         86400,
	 *         // Function that derives the new key value
	 *         function ( $oldValue, &$ttl, array &$setOpts ) {
	 *             $dbr = wfGetDB( DB_SLAVE );
	 *             // Account for any snapshot/slave lag
	 *             $setOpts += Database::getCacheSetOptions( $dbr );
	 *
	 *             return CatConfig::newFromRow( $dbr->selectRow( ... ) );
	 *         },
	 *         // Calling touchCheckKey() on this key invalidates the cache
	 *         array(
	 *             'checkKeys' => array( wfMemcKey( 'site-cat-config' ) ),
	 *             // Try to only let one datacenter thread manage cache updates at a time
	 *             'lockTSE' => 30
	 *         )
	 *     );
	 * @endcode
	 *
	 * Example usage (key with dynamic dependencies):
	 * @code
	 *     $catState = $cache->getWithSetCallback(
	 *         // Key to store the cached value under
	 *         wfMemcKey( 'cat-state', $cat->getId() ),
	 *         // Time-to-live (seconds)
	 *         900,
	 *         // Function that derives the new key value
	 *         function ( $oldValue, &$ttl, array &$setOpts ) {
	 *             // Determine new value from the DB
	 *             $dbr = wfGetDB( DB_SLAVE );
	 *             // Account for any snapshot/slave lag
	 *             $setOpts += Database::getCacheSetOptions( $dbr );
	 *
	 *             return CatState::newFromResults( $dbr->select( ... ) );
	 *         },
	 *         // The "check" keys that represent things the value depends on;
	 *         // Calling touchCheckKey() on any of them invalidates the cache
	 *         array(
	 *             'checkKeys' => array(
	 *                 wfMemcKey( 'sustenance-bowls', $cat->getRoomId() ),
	 *                 wfMemcKey( 'people-present', $cat->getHouseId() ),
	 *                 wfMemcKey( 'cat-laws', $cat->getCityId() ),
	 *             )
	 *         )
	 *     );
	 * @endcode
	 *
	 * Example usage (hot key holding most recent 100 events):
	 * @code
	 *     $lastCatActions = $cache->getWithSetCallback(
	 *         // Key to store the cached value under
	 *         wfMemcKey( 'cat-last-actions', 100 ),
	 *         // Time-to-live (seconds)
	 *         10,
	 *         // Function that derives the new key value
	 *         function ( $oldValue, &$ttl, array &$setOpts ) {
	 *             $dbr = wfGetDB( DB_SLAVE );
	 *             // Account for any snapshot/slave lag
	 *             $setOpts += Database::getCacheSetOptions( $dbr );
	 *
	 *             // Start off with the last cached list
	 *             $list = $oldValue ?: array();
	 *             // Fetch the last 100 relevant rows in descending order;
	 *             // only fetch rows newer than $list[0] to reduce scanning
	 *             $rows = iterator_to_array( $dbr->select( ... ) );
	 *             // Merge them and get the new "last 100" rows
	 *             return array_slice( array_merge( $new, $list ), 0, 100 );
	 *        },
	 *        // Try to only let one datacenter thread manage cache updates at a time
	 *        array( 'lockTSE' => 30 )
	 *     );
	 * @endcode
	 *
	 * @see WANObjectCache::get()
	 * @see WANObjectCache::set()
	 *
	 * @param string $key Cache key
	 * @param integer $ttl Seconds to live for key updates. Special values are:
	 *   - WANObjectCache::TTL_NONE : Cache forever
	 *   - WANObjectCache::TTL_UNCACHEABLE: Do not cache at all
	 * @param callable $callback Value generation function
	 * @param array $opts Options map:
	 *   - checkKeys: List of "check" keys.
	 *   - lowTTL: Consider pre-emptive updates when the current TTL (sec) of the key is less than
	 *      this. It becomes more likely over time, becoming a certainty once the key is expired.
	 *      Default: WANObjectCache::LOW_TTL seconds.
	 *   - lockTSE: If the key is tombstoned or expired (by checkKeys) less than this many seconds
	 *      ago, then try to have a single thread handle cache regeneration at any given time.
	 *      Other threads will try to use stale values if possible. If, on miss, the time since
	 *      expiration is low, the assumption is that the key is hot and that a stampede is worth
	 *      avoiding. Setting this above WANObjectCache::HOLDOFF_TTL makes no difference. The
	 *      higher this is set, the higher the worst-case staleness can be.
	 *      Use WANObjectCache::TSE_NONE to disable this logic. Default: WANObjectCache::TSE_NONE.
	 * @return mixed Value to use for the key
	 */
	final public function getWithSetCallback(
		$key, $ttl, $callback, array $opts = array(), $oldOpts = array()
	) {
		// Back-compat with 1.26: Swap $ttl and $callback
		if ( is_int( $callback ) ) {
			$temp = $ttl;
			$ttl = $callback;
			$callback = $temp;
		}
		// Back-compat with 1.26: $checkKeys as separate parameter
		if ( $oldOpts || ( is_array( $opts ) && isset( $opts[0] ) ) ) {
			$checkKeys = $opts;
			$opts = $oldOpts;
		} else {
			$checkKeys = isset( $opts['checkKeys'] ) ? $opts['checkKeys'] : array();
		}

		$lowTTL = isset( $opts['lowTTL'] ) ? $opts['lowTTL'] : min( self::LOW_TTL, $ttl );
		$lockTSE = isset( $opts['lockTSE'] ) ? $opts['lockTSE'] : self::TSE_NONE;

		// Get the current key value
		$curTTL = null;
		$cValue = $this->get( $key, $curTTL, $checkKeys ); // current value
		$value = $cValue; // return value

		// Determine if a regeneration is desired
		if ( $value !== false && $curTTL > 0 && !$this->worthRefresh( $curTTL, $lowTTL ) ) {
			return $value;
		}

		// A deleted key with a negative TTL left must be tombstoned
		$isTombstone = ( $curTTL !== null && $value === false );
		// Assume a key is hot if requested soon after invalidation
		$isHot = ( $curTTL !== null && $curTTL <= 0 && abs( $curTTL ) <= $lockTSE );
		// Decide whether a single thread should handle regenerations.
		// This avoids stampedes when $checkKeys are bumped and when preemptive
		// renegerations take too long. It also reduces regenerations while $key
		// is tombstoned. This balances cache freshness with avoiding DB load.
		$useMutex = ( $isHot || ( $isTombstone && $lockTSE > 0 ) );

		$lockAcquired = false;
		if ( $useMutex ) {
			// Acquire a datacenter-local non-blocking lock
			if ( $this->cache->lock( $key, 0, self::LOCK_TTL ) ) {
				// Lock acquired; this thread should update the key
				$lockAcquired = true;
			} elseif ( $value !== false ) {
				// If it cannot be acquired; then the stale value can be used
				return $value;
			} else {
				// Use the stash value for tombstoned keys to reduce regeneration load.
				// For hot keys, either another thread has the lock or the lock failed;
				// use the stash value from the last thread that regenerated it.
				$value = $this->cache->get( self::STASH_KEY_PREFIX . $key );
				if ( $value !== false ) {
					return $value;
				}
			}
		}

		if ( !is_callable( $callback ) ) {
			throw new InvalidArgumentException( "Invalid cache miss callback provided." );
		}

		// Generate the new value from the callback...
		$setOpts = array();
		$value = call_user_func_array( $callback, array( $cValue, &$ttl, &$setOpts ) );
		// When delete() is called, writes are write-holed by the tombstone,
		// so use a special stash key to pass the new value around threads.
		if ( $useMutex && $value !== false && $ttl >= 0 ) {
			$tempTTL = max( 1, (int)$lockTSE ); // set() expects seconds
			$this->cache->set( self::STASH_KEY_PREFIX . $key, $value, $tempTTL );
		}

		if ( $lockAcquired ) {
			$this->cache->unlock( $key );
		}

		if ( $value !== false && $ttl >= 0 ) {
			// Update the cache; this will fail if the key is tombstoned
			$setOpts['lockTSE'] = $lockTSE;
			$this->set( $key, $value, $ttl, $setOpts );
		}

		return $value;
	}

	/**
	 * Get the "last error" registered; clearLastError() should be called manually
	 * @return int ERR_* constant for the "last error" registry
	 */
	final public function getLastError() {
		if ( $this->lastRelayError ) {
			// If the cache and the relayer failed, focus on the later.
			// An update not making it to the relayer means it won't show up
			// in other DCs (nor will consistent re-hashing see up-to-date values).
			// On the other hand, if just the cache update failed, then it should
			// eventually be applied by the relayer.
			return $this->lastRelayError;
		}

		$code = $this->cache->getLastError();
		switch ( $code ) {
			case BagOStuff::ERR_NONE:
				return self::ERR_NONE;
			case BagOStuff::ERR_NO_RESPONSE:
				return self::ERR_NO_RESPONSE;
			case BagOStuff::ERR_UNREACHABLE:
				return self::ERR_UNREACHABLE;
			default:
				return self::ERR_UNEXPECTED;
		}
	}

	/**
	 * Clear the "last error" registry
	 */
	final public function clearLastError() {
		$this->cache->clearLastError();
		$this->lastRelayError = self::ERR_NONE;
	}

	/**
	 * Do the actual async bus purge of a key
	 *
	 * This must set the key to "PURGED:<UNIX timestamp>"
	 *
	 * @param string $key Cache key
	 * @param integer $ttl How long to keep the tombstone [seconds]
	 * @return bool Success
	 */
	protected function relayPurge( $key, $ttl ) {
		$event = $this->cache->modifySimpleRelayEvent( array(
			'cmd' => 'set',
			'key' => $key,
			'val' => 'PURGED:$UNIXTIME$',
			'ttl' => max( $ttl, 1 ),
			'sbt' => true, // substitute $UNIXTIME$ with actual microtime
		) );

		$ok = $this->relayer->notify( "{$this->pool}:purge", $event );
		if ( !$ok ) {
			$this->lastRelayError = self::ERR_RELAY;
		}

		return $ok;
	}

	/**
	 * Do the actual async bus delete of a key
	 *
	 * @param string $key Cache key
	 * @return bool Success
	 */
	protected function relayDelete( $key ) {
		$event = $this->cache->modifySimpleRelayEvent( array(
			'cmd' => 'delete',
			'key' => $key,
		) );

		$ok = $this->relayer->notify( "{$this->pool}:purge", $event );
		if ( !$ok ) {
			$this->lastRelayError = self::ERR_RELAY;
		}

		return $ok;
	}

	/**
	 * Check if a key should be regenerated (using random probability)
	 *
	 * This returns false if $curTTL >= $lowTTL. Otherwise, the chance
	 * of returning true increases steadily from 0% to 100% as the $curTTL
	 * moves from $lowTTL to 0 seconds. This handles widely varying
	 * levels of cache access traffic.
	 *
	 * @param float $curTTL Approximate TTL left on the key if present
	 * @param float $lowTTL Consider a refresh when $curTTL is less than this
	 * @return bool
	 */
	protected function worthRefresh( $curTTL, $lowTTL ) {
		if ( $curTTL >= $lowTTL ) {
			return false;
		} elseif ( $curTTL <= 0 ) {
			return true;
		}

		$chance = ( 1 - $curTTL / $lowTTL );

		return mt_rand( 1, 1e9 ) <= 1e9 * $chance;
	}

	/**
	 * Do not use this method outside WANObjectCache
	 *
	 * @param mixed $value
	 * @param integer $ttl [0=forever]
	 * @return string
	 */
	protected function wrap( $value, $ttl ) {
		return array(
			self::FLD_VERSION => self::VERSION,
			self::FLD_VALUE => $value,
			self::FLD_TTL => $ttl,
			self::FLD_TIME => microtime( true )
		);
	}

	/**
	 * Do not use this method outside WANObjectCache
	 *
	 * @param array|string|bool $wrapped
	 * @param float $now Unix Current timestamp (preferrable pre-query)
	 * @return array (mixed; false if absent/invalid, current time left)
	 */
	protected function unwrap( $wrapped, $now ) {
		// Check if the value is a tombstone
		$purgeTimestamp = self::parsePurgeValue( $wrapped );
		if ( is_float( $purgeTimestamp ) ) {
			// Purged values should always have a negative current $ttl
			$curTTL = min( -0.000001, $purgeTimestamp - $now );
			return array( false, $curTTL );
		}

		if ( !is_array( $wrapped ) // not found
			|| !isset( $wrapped[self::FLD_VERSION] ) // wrong format
			|| $wrapped[self::FLD_VERSION] !== self::VERSION // wrong version
		) {
			return array( false, null );
		}

		if ( $wrapped[self::FLD_TTL] > 0 ) {
			// Get the approximate time left on the key
			$age = $now - $wrapped[self::FLD_TIME];
			$curTTL = max( $wrapped[self::FLD_TTL] - $age, 0.0 );
		} else {
			// Key had no TTL, so the time left is unbounded
			$curTTL = INF;
		}

		return array( $wrapped[self::FLD_VALUE], $curTTL );
	}

	/**
	 * @param array $keys
	 * @param string $prefix
	 * @return string[]
	 */
	protected static function prefixCacheKeys( array $keys, $prefix ) {
		$res = array();
		foreach ( $keys as $key ) {
			$res[] = $prefix . $key;
		}

		return $res;
	}

	/**
	 * @param string $value String like "PURGED:<timestamp>"
	 * @return float|bool UNIX timestamp or false on failure
	 */
	protected static function parsePurgeValue( $value ) {
		$m = array();
		if ( is_string( $value ) &&
			preg_match( '/^' . self::PURGE_VAL_PREFIX . '([^:]+)$/', $value, $m )
		) {
			return (float)$m[1];
		} else {
			return false;
		}
	}
}
