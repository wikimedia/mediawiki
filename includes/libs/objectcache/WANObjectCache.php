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

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Multi-datacenter aware caching interface
 *
 * All operations go to the local datacenter cache, except for delete(),
 * touchCheckKey(), and resetCheckKey(), which broadcast to all datacenters.
 *
 * This class is intended for caching data from primary stores.
 * If the get() method does not return a value, then the caller
 * should query the new value and backfill the cache using set().
 * When querying the store on cache miss, the closest DB replica
 * should be used. Try to avoid heavyweight DB master or quorum reads.
 * When the source data changes, a purge method should be called.
 * Since purges are expensive, they should be avoided. One can do so if:
 *   - a) The object cached is immutable; or
 *   - b) Validity is checked against the source after get(); or
 *   - c) Using a modest TTL is reasonably correct and performant
 *
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
class WANObjectCache implements IExpiringStore, LoggerAwareInterface {
	/** @var BagOStuff The local datacenter cache */
	protected $cache;
	/** @var HashBagOStuff Script instance PHP cache */
	protected $procCache;
	/** @var string Purge channel name */
	protected $purgeChannel;
	/** @var EventRelayer Bus that handles purge broadcasts */
	protected $purgeRelayer;
	/** @var LoggerInterface */
	protected $logger;

	/** @var int ERR_* constant for the "last error" registry */
	protected $lastRelayError = self::ERR_NONE;

	/** Max time expected to pass between delete() and DB commit finishing */
	const MAX_COMMIT_DELAY = 3;
	/** Max replication+snapshot lag before applying TTL_LAGGED or disallowing set() */
	const MAX_READ_LAG = 7;
	/** Seconds to tombstone keys on delete() */
	const HOLDOFF_TTL = 11; // MAX_COMMIT_DELAY + MAX_READ_LAG + 1

	/** Seconds to keep dependency purge keys around */
	const CHECK_KEY_TTL = self::TTL_YEAR;
	/** Seconds to keep lock keys around */
	const LOCK_TTL = 10;
	/** Default remaining TTL at which to consider pre-emptive regeneration */
	const LOW_TTL = 30;
	/** Default time-since-expiry on a miss that makes a key "hot" */
	const LOCK_TSE = 1;

	/** Idiom for getWithSetCallback() callbacks to avoid calling set() */
	const TTL_UNCACHEABLE = -1;
	/** Idiom for getWithSetCallback() callbacks to 'lockTSE' logic */
	const TSE_NONE = -1;
	/** Max TTL to store keys when a data sourced is lagged */
	const TTL_LAGGED = 30;
	/** Idiom for delete() for "no hold-off" */
	const HOLDOFF_NONE = 0;

	/** Tiny negative float to use when CTL comes up >= 0 due to clock skew */
	const TINY_NEGATIVE = -0.000001;

	/** Cache format version number */
	const VERSION = 1;

	const FLD_VERSION = 0;
	const FLD_VALUE = 1;
	const FLD_TTL = 2;
	const FLD_TIME = 3;
	const FLD_FLAGS = 4;
	const FLD_HOLDOFF = 5;

	/** @var integer Treat this value as expired-on-arrival */
	const FLG_STALE = 1;

	const ERR_NONE = 0; // no error
	const ERR_NO_RESPONSE = 1; // no response
	const ERR_UNREACHABLE = 2; // can't connect
	const ERR_UNEXPECTED = 3; // response gave some error
	const ERR_RELAY = 4; // relay broadcast failed

	const VALUE_KEY_PREFIX = 'WANCache:v:';
	const STASH_KEY_PREFIX = 'WANCache:s:';
	const TIME_KEY_PREFIX = 'WANCache:t:';

	const PURGE_VAL_PREFIX = 'PURGED:';

	const MAX_PC_KEYS = 1000; // max keys to keep in process cache

	const DEFAULT_PURGE_CHANNEL = 'wancache-purge';

	/**
	 * @param array $params
	 *   - cache    : BagOStuff object for a persistent cache
	 *   - channels : Map of (action => channel string). Actions include "purge".
	 *   - relayers : Map of (action => EventRelayer object). Actions include "purge".
	 *   - logger   : LoggerInterface object
	 */
	public function __construct( array $params ) {
		$this->cache = $params['cache'];
		$this->procCache = new HashBagOStuff( [ 'maxKeys' => self::MAX_PC_KEYS ] );
		$this->purgeChannel = isset( $params['channels']['purge'] )
			? $params['channels']['purge']
			: self::DEFAULT_PURGE_CHANNEL;
		$this->purgeRelayer = isset( $params['relayers']['purge'] )
			? $params['relayers']['purge']
			: new EventRelayerNull( [] );
		$this->setLogger( isset( $params['logger'] ) ? $params['logger'] : new NullLogger() );
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Get an instance that wraps EmptyBagOStuff
	 *
	 * @return WANObjectCache
	 */
	public static function newEmpty() {
		return new self( [
			'cache'   => new EmptyBagOStuff(),
			'pool'    => 'empty',
			'relayer' => new EventRelayerNull( [] )
		] );
	}

	/**
	 * Fetch the value of a key from cache
	 *
	 * If supplied, $curTTL is set to the remaining TTL (current time left):
	 *   - a) INF; if $key exists, has no TTL, and is not expired by $checkKeys
	 *   - b) float (>=0); if $key exists, has a TTL, and is not expired by $checkKeys
	 *   - c) float (<0); if $key is tombstoned, stale, or existing but expired by $checkKeys
	 *   - d) null; if $key does not exist and is not tombstoned
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
	 * Consider using getWithSetCallback() instead of get() and set() cycles.
	 * That method has cache slam avoiding features for hot/expensive keys.
	 *
	 * @param string $key Cache key
	 * @param mixed $curTTL Approximate TTL left on the key if present [returned]
	 * @param array $checkKeys List of "check" keys
	 * @return mixed Value of cache key or false on failure
	 */
	final public function get( $key, &$curTTL = null, array $checkKeys = [] ) {
		$curTTLs = [];
		$values = $this->getMulti( [ $key ], $curTTLs, $checkKeys );
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
	 * @param array $checkKeys List of check keys to apply to all $keys. May also apply "check"
	 *  keys to specific cache keys only by using cache keys as keys in the $checkKeys array.
	 * @return array Map of (key => value) for keys that exist
	 */
	final public function getMulti(
		array $keys, &$curTTLs = [], array $checkKeys = []
	) {
		$result = [];
		$curTTLs = [];

		$vPrefixLen = strlen( self::VALUE_KEY_PREFIX );
		$valueKeys = self::prefixCacheKeys( $keys, self::VALUE_KEY_PREFIX );

		$checkKeysForAll = [];
		$checkKeysByKey = [];
		$checkKeysFlat = [];
		foreach ( $checkKeys as $i => $keys ) {
			$prefixed = self::prefixCacheKeys( (array)$keys, self::TIME_KEY_PREFIX );
			$checkKeysFlat = array_merge( $checkKeysFlat, $prefixed );
			// Is this check keys for a specific cache key, or for all keys being fetched?
			if ( is_int( $i ) ) {
				$checkKeysForAll = array_merge( $checkKeysForAll, $prefixed );
			} else {
				$checkKeysByKey[$i] = isset( $checkKeysByKey[$i] )
					? array_merge( $checkKeysByKey[$i], $prefixed )
					: $prefixed;
			}
		}

		// Fetch all of the raw values
		$wrappedValues = $this->cache->getMulti( array_merge( $valueKeys, $checkKeysFlat ) );
		// Time used to compare/init "check" keys (derived after getMulti() to be pessimistic)
		$now = microtime( true );

		// Collect timestamps from all "check" keys
		$purgeValuesForAll = $this->processCheckKeys( $checkKeysForAll, $wrappedValues, $now );
		$purgeValuesByKey = [];
		foreach ( $checkKeysByKey as $cacheKey => $checks ) {
			$purgeValuesByKey[$cacheKey] =
				$this->processCheckKeys( $checks, $wrappedValues, $now );
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

				// Force dependant keys to be invalid for a while after purging
				// to reduce race conditions involving stale data getting cached
				$purgeValues = $purgeValuesForAll;
				if ( isset( $purgeValuesByKey[$key] ) ) {
					$purgeValues = array_merge( $purgeValues, $purgeValuesByKey[$key] );
				}
				foreach ( $purgeValues as $purge ) {
					$safeTimestamp = $purge[self::FLD_TIME] + $purge[self::FLD_HOLDOFF];
					if ( $safeTimestamp >= $wrappedValues[$vKey][self::FLD_TIME] ) {
						// How long ago this value was expired by *this* check key
						$ago = min( $purge[self::FLD_TIME] - $now, self::TINY_NEGATIVE );
						// How long ago this value was expired by *any* known check key
						$curTTL = min( $curTTL, $ago );
					}
				}
			}
			$curTTLs[$key] = $curTTL;
		}

		return $result;
	}

	/**
	 * @since 1.27
	 * @param array $timeKeys List of prefixed time check keys
	 * @param array $wrappedValues
	 * @param float $now
	 * @return array List of purge value arrays
	 */
	private function processCheckKeys( array $timeKeys, array $wrappedValues, $now ) {
		$purgeValues = [];
		foreach ( $timeKeys as $timeKey ) {
			$purge = isset( $wrappedValues[$timeKey] )
				? self::parsePurgeValue( $wrappedValues[$timeKey] )
				: false;
			if ( $purge === false ) {
				// Key is not set or invalid; regenerate
				$newVal = $this->makePurgeValue( $now, self::HOLDOFF_TTL );
				$this->cache->add( $timeKey, $newVal, self::CHECK_KEY_TTL );
				$purge = self::parsePurgeValue( $newVal );
			}
			$purgeValues[] = $purge;
		}
		return $purgeValues;
	}

	/**
	 * Set the value of a key in cache
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
	 * Setting 'lag' and 'since' help avoids keys getting stuck in stale states.
	 *
	 * Example usage:
	 * @code
	 *     $dbr = wfGetDB( DB_SLAVE );
	 *     $setOpts = Database::getCacheSetOptions( $dbr );
	 *     // Fetch the row from the DB
	 *     $row = $dbr->selectRow( ... );
	 *     $key = $cache->makeKey( 'building', $buildingId );
	 *     $cache->set( $key, $row, $cache::TTL_DAY, $setOpts );
	 * @endcode
	 *
	 * @param string $key Cache key
	 * @param mixed $value
	 * @param integer $ttl Seconds to live. Special values are:
	 *   - WANObjectCache::TTL_INDEFINITE: Cache forever
	 * @param array $opts Options map:
	 *   - lag     : Seconds of slave lag. Typically, this is either the slave lag
	 *               before the data was read or, if applicable, the slave lag before
	 *               the snapshot-isolated transaction the data was read from started.
	 *               Default: 0 seconds
	 *   - since   : UNIX timestamp of the data in $value. Typically, this is either
	 *               the current time the data was read or (if applicable) the time when
	 *               the snapshot-isolated transaction the data was read from started.
	 *               Default: 0 seconds
	 *   - pending : Whether this data is possibly from an uncommitted write transaction.
	 *               Generally, other threads should not see values from the future and
	 *               they certainly should not see ones that ended up getting rolled back.
	 *               Default: false
	 *   - lockTSE : if excessive replication/snapshot lag is detected, then store the value
	 *               with this TTL and flag it as stale. This is only useful if the reads for
	 *               this key use getWithSetCallback() with "lockTSE" set.
	 *               Default: WANObjectCache::TSE_NONE
	 * @return bool Success
	 */
	final public function set( $key, $value, $ttl = 0, array $opts = [] ) {
		$lockTSE = isset( $opts['lockTSE'] ) ? $opts['lockTSE'] : self::TSE_NONE;
		$age = isset( $opts['since'] ) ? max( 0, microtime( true ) - $opts['since'] ) : 0;
		$lag = isset( $opts['lag'] ) ? $opts['lag'] : 0;

		// Do not cache potentially uncommitted data as it might get rolled back
		if ( !empty( $opts['pending'] ) ) {
			$this->logger->info( "Rejected set() for $key due to pending writes." );

			return true; // no-op the write for being unsafe
		}

		$wrapExtra = []; // additional wrapped value fields
		// Check if there's a risk of writing stale data after the purge tombstone expired
		if ( $lag === false || ( $lag + $age ) > self::MAX_READ_LAG ) {
			// Case A: read lag with "lockTSE"; save but record value as stale
			if ( $lockTSE >= 0 ) {
				$ttl = max( 1, (int)$lockTSE ); // set() expects seconds
				$wrapExtra[self::FLD_FLAGS] = self::FLG_STALE; // mark as stale
			// Case B: any long-running transaction; ignore this set()
			} elseif ( $age > self::MAX_READ_LAG ) {
				$this->logger->warning( "Rejected set() for $key due to snapshot lag." );

				return true; // no-op the write for being unsafe
			// Case C: high replication lag; lower TTL instead of ignoring all set()s
			} elseif ( $lag === false || $lag > self::MAX_READ_LAG ) {
				$ttl = $ttl ? min( $ttl, self::TTL_LAGGED ) : self::TTL_LAGGED;
				$this->logger->warning( "Lowered set() TTL for $key due to replication lag." );
			// Case D: medium length request with medium replication lag; ignore this set()
			} else {
				$this->logger->warning( "Rejected set() for $key due to high read lag." );

				return true; // no-op the write for being unsafe
			}
		}

		// Wrap that value with time/TTL/version metadata
		$wrapped = $this->wrap( $value, $ttl ) + $wrapExtra;

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
	 * For this to always avoid stale value writes, the following must hold:
	 *   - a) Replication lag is bounded to being less than HOLDOFF_TTL; or
	 *   - b) If lag is higher, the DB will have gone into read-only mode already
	 *
	 * Note that set() can also be lag-aware and lower the TTL if it's high.
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
	 *     $dbw->begin( __METHOD__ ); // start of request
	 *     ... <execute some stuff> ...
	 *     // Update the row in the DB
	 *     $dbw->update( ... );
	 *     $key = $cache->makeKey( 'homes', $homeId );
	 *     // Purge the corresponding cache entry just before committing
	 *     $dbw->onTransactionPreCommitOrIdle( function() use ( $cache, $key ) {
	 *         $cache->delete( $key );
	 *     } );
	 *     ... <execute some stuff> ...
	 *     $dbw->commit( __METHOD__ ); // end of request
	 * @endcode
	 *
	 * The $ttl parameter can be used when purging values that have not actually changed
	 * recently. For example, a cleanup script to purge cache entries does not really need
	 * a hold-off period, so it can use HOLDOFF_NONE. Likewise for user-requested purge.
	 * Note that $ttl limits the effective range of 'lockTSE' for getWithSetCallback().
	 *
	 * If called twice on the same key, then the last hold-off TTL takes precedence. For
	 * idempotence, the $ttl should not vary for different delete() calls on the same key.
	 *
	 * @param string $key Cache key
	 * @param integer $ttl Tombstone TTL; Default: WANObjectCache::HOLDOFF_TTL
	 * @return bool True if the item was purged or not found, false on failure
	 */
	final public function delete( $key, $ttl = self::HOLDOFF_TTL ) {
		$key = self::VALUE_KEY_PREFIX . $key;

		if ( $ttl <= 0 ) {
			// Update the local datacenter immediately
			$ok = $this->cache->delete( $key );
			// Publish the purge to all datacenters
			$ok = $this->relayDelete( $key ) && $ok;
		} else {
			// Update the local datacenter immediately
			$ok = $this->cache->set( $key,
				$this->makePurgeValue( microtime( true ), self::HOLDOFF_NONE ),
				$ttl
			);
			// Publish the purge to all datacenters
			$ok = $this->relayPurge( $key, $ttl, self::HOLDOFF_NONE ) && $ok;
		}

		return $ok;
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
	 * Note that "check" keys won't collide with other regular keys.
	 *
	 * @param string $key
	 * @return float UNIX timestamp of the check key
	 */
	final public function getCheckKeyTime( $key ) {
		$key = self::TIME_KEY_PREFIX . $key;

		$purge = self::parsePurgeValue( $this->cache->get( $key ) );
		if ( $purge !== false ) {
			$time = $purge[self::FLD_TIME];
		} else {
			// Casting assures identical floats for the next getCheckKeyTime() calls
			$now = (string)microtime( true );
			$this->cache->add( $key,
				$this->makePurgeValue( $now, self::HOLDOFF_TTL ),
				self::CHECK_KEY_TTL
			);
			$time = (float)$now;
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
	 * When touched, keys using it via get(), getMulti(), or getWithSetCallback()
	 * will be invalidated. It is treated as being HOLDOFF_TTL seconds in the future
	 * by those methods to avoid race conditions where dependent keys get updated
	 * with stale values (e.g. from a DB slave).
	 *
	 * This is typically useful for keys with hardcoded names or in some cases
	 * dynamically generated names where a low number of combinations exist.
	 * When a few important keys get a large number of hits, a high cache
	 * time is usually desired as well as "lockTSE" logic. The resetCheckKey()
	 * method is less appropriate in such cases since the "time since expiry"
	 * cannot be inferred.
	 *
	 * Note that "check" keys won't collide with other regular keys.
	 *
	 * @see WANObjectCache::get()
	 * @see WANObjectCache::getWithSetCallback()
	 * @see WANObjectCache::resetCheckKey()
	 *
	 * @param string $key Cache key
	 * @param int $holdoff HOLDOFF_TTL or HOLDOFF_NONE constant
	 * @return bool True if the item was purged or not found, false on failure
	 */
	final public function touchCheckKey( $key, $holdoff = self::HOLDOFF_TTL ) {
		$key = self::TIME_KEY_PREFIX . $key;
		// Update the local datacenter immediately
		$ok = $this->cache->set( $key,
			$this->makePurgeValue( microtime( true ), $holdoff ),
			self::CHECK_KEY_TTL
		);
		// Publish the purge to all datacenters
		return $this->relayPurge( $key, self::CHECK_KEY_TTL, $holdoff ) && $ok;
	}

	/**
	 * Delete a "check" key from all datacenters, invalidating keys that use it
	 *
	 * This is similar to touchCheckKey() in that keys using it via get(), getMulti(),
	 * or getWithSetCallback() will be invalidated. The differences are:
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
	 * Note that "check" keys won't collide with other regular keys.
	 *
	 * @see WANObjectCache::get()
	 * @see WANObjectCache::getWithSetCallback()
	 * @see WANObjectCache::touchCheckKey()
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
	 * (unless the callback returns false) and that result will be returned.
	 * The arguments supplied to the callback are:
	 *   - $oldValue : current cache value or false if not present
	 *   - &$ttl : a reference to the TTL which can be altered
	 *   - &$setOpts : a reference to options for set() which can be altered
	 *
	 * It is strongly recommended to set the 'lag' and 'since' fields to avoid race conditions
	 * that can cause stale values to get stuck at keys. Usually, callbacks ignore the current
	 * value, but it can be used to maintain "most recent X" values that come from time or
	 * sequence based source data, provided that the "as of" id/time is tracked. Note that
	 * preemptive regeneration and $checkKeys can result in a non-false current value.
	 *
	 * Usage of $checkKeys is similar to get() and getMulti(). However, rather than the caller
	 * having to inspect a "current time left" variable (e.g. $curTTL, $curTTLs), a cache
	 * regeneration will automatically be triggered using the callback.
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
	 *         $cache->makeKey( 'cat-attributes', $catId ),
	 *         // Time-to-live (in seconds)
	 *         $cache::TTL_MINUTE,
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
	 *         $cache->makeKey( 'site-cat-config' ),
	 *         // Time-to-live (in seconds)
	 *         $cache::TTL_DAY,
	 *         // Function that derives the new key value
	 *         function ( $oldValue, &$ttl, array &$setOpts ) {
	 *             $dbr = wfGetDB( DB_SLAVE );
	 *             // Account for any snapshot/slave lag
	 *             $setOpts += Database::getCacheSetOptions( $dbr );
	 *
	 *             return CatConfig::newFromRow( $dbr->selectRow( ... ) );
	 *         },
	 *         [
	 *             // Calling touchCheckKey() on this key invalidates the cache
	 *             'checkKeys' => [ $cache->makeKey( 'site-cat-config' ) ],
	 *             // Try to only let one datacenter thread manage cache updates at a time
	 *             'lockTSE' => 30
	 *         ]
	 *     );
	 * @endcode
	 *
	 * Example usage (key with dynamic dependencies):
	 * @code
	 *     $catState = $cache->getWithSetCallback(
	 *         // Key to store the cached value under
	 *         $cache->makeKey( 'cat-state', $cat->getId() ),
	 *         // Time-to-live (seconds)
	 *         $cache::TTL_HOUR,
	 *         // Function that derives the new key value
	 *         function ( $oldValue, &$ttl, array &$setOpts ) {
	 *             // Determine new value from the DB
	 *             $dbr = wfGetDB( DB_SLAVE );
	 *             // Account for any snapshot/slave lag
	 *             $setOpts += Database::getCacheSetOptions( $dbr );
	 *
	 *             return CatState::newFromResults( $dbr->select( ... ) );
	 *         },
	 *         [
	 *              // The "check" keys that represent things the value depends on;
	 *              // Calling touchCheckKey() on any of them invalidates the cache
	 *             'checkKeys' => [
	 *                 $cache->makeKey( 'sustenance-bowls', $cat->getRoomId() ),
	 *                 $cache->makeKey( 'people-present', $cat->getHouseId() ),
	 *                 $cache->makeKey( 'cat-laws', $cat->getCityId() ),
	 *             ]
	 *         ]
	 *     );
	 * @endcode
	 *
	 * Example usage (hot key holding most recent 100 events):
	 * @code
	 *     $lastCatActions = $cache->getWithSetCallback(
	 *         // Key to store the cached value under
	 *         $cache->makeKey( 'cat-last-actions', 100 ),
	 *         // Time-to-live (in seconds)
	 *         10,
	 *         // Function that derives the new key value
	 *         function ( $oldValue, &$ttl, array &$setOpts ) {
	 *             $dbr = wfGetDB( DB_SLAVE );
	 *             // Account for any snapshot/slave lag
	 *             $setOpts += Database::getCacheSetOptions( $dbr );
	 *
	 *             // Start off with the last cached list
	 *             $list = $oldValue ?: [];
	 *             // Fetch the last 100 relevant rows in descending order;
	 *             // only fetch rows newer than $list[0] to reduce scanning
	 *             $rows = iterator_to_array( $dbr->select( ... ) );
	 *             // Merge them and get the new "last 100" rows
	 *             return array_slice( array_merge( $new, $list ), 0, 100 );
	 *        },
	 *        // Try to only let one datacenter thread manage cache updates at a time
	 *        [ 'lockTSE' => 30 ]
	 *     );
	 * @endcode
	 *
	 * @see WANObjectCache::get()
	 * @see WANObjectCache::set()
	 *
	 * @param string $key Cache key
	 * @param integer $ttl Seconds to live for key updates. Special values are:
	 *   - WANObjectCache::TTL_INDEFINITE: Cache forever
	 *   - WANObjectCache::TTL_UNCACHEABLE: Do not cache at all
	 * @param callable $callback Value generation function
	 * @param array $opts Options map:
	 *   - checkKeys: List of "check" keys. The key at $key will be seen as invalid when either
	 *      touchCheckKey() or resetCheckKey() is called on any of these keys.
	 *   - lowTTL: Consider pre-emptive updates when the current TTL (sec) of the key is less than
	 *      this. It becomes more likely over time, becoming a certainty once the key is expired.
	 *      Default: WANObjectCache::LOW_TTL seconds.
	 *   - lockTSE: If the key is tombstoned or expired (by checkKeys) less than this many seconds
	 *      ago, then try to have a single thread handle cache regeneration at any given time.
	 *      Other threads will try to use stale values if possible. If, on miss, the time since
	 *      expiration is low, the assumption is that the key is hot and that a stampede is worth
	 *      avoiding. Setting this above WANObjectCache::HOLDOFF_TTL makes no difference. The
	 *      higher this is set, the higher the worst-case staleness can be.
	 *      Use WANObjectCache::TSE_NONE to disable this logic.
	 *      Default: WANObjectCache::TSE_NONE.
	 *   - pcTTL : process cache the value in this PHP instance with this TTL. This avoids
	 *      network I/O when a key is read several times. This will not cache if the callback
	 *      returns false however. Note that any purges will not be seen while process cached;
	 *      since the callback should use slave DBs and they may be lagged or have snapshot
	 *      isolation anyway, this should not typically matter.
	 *      Default: WANObjectCache::TTL_UNCACHEABLE.
	 * @return mixed Value to use for the key
	 */
	final public function getWithSetCallback( $key, $ttl, $callback, array $opts = [] ) {
		$pcTTL = isset( $opts['pcTTL'] ) ? $opts['pcTTL'] : self::TTL_UNCACHEABLE;

		// Try the process cache if enabled
		$value = ( $pcTTL >= 0 ) ? $this->procCache->get( $key ) : false;

		if ( $value === false ) {
			// Fetch the value over the network
			$value = $this->doGetWithSetCallback( $key, $ttl, $callback, $opts );
			// Update the process cache if enabled
			if ( $pcTTL >= 0 && $value !== false ) {
				$this->procCache->set( $key, $value, $pcTTL );
			}
		}

		return $value;
	}

	/**
	 * Do the actual I/O for getWithSetCallback() when needed
	 *
	 * @see WANObjectCache::getWithSetCallback()
	 *
	 * @param string $key
	 * @param integer $ttl
	 * @param callback $callback
	 * @param array $opts
	 * @return mixed
	 */
	protected function doGetWithSetCallback( $key, $ttl, $callback, array $opts ) {
		$lowTTL = isset( $opts['lowTTL'] ) ? $opts['lowTTL'] : min( self::LOW_TTL, $ttl );
		$lockTSE = isset( $opts['lockTSE'] ) ? $opts['lockTSE'] : self::TSE_NONE;
		$checkKeys = isset( $opts['checkKeys'] ) ? $opts['checkKeys'] : [];

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
		$setOpts = [];
		$value = call_user_func_array( $callback, [ $cValue, &$ttl, &$setOpts ] );
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
	 * @see BagOStuff::makeKey()
	 * @param string ... Key component
	 * @return string
	 * @since 1.27
	 */
	public function makeKey() {
		return call_user_func_array( [ $this->cache, __FUNCTION__ ], func_get_args() );
	}

	/**
	 * @see BagOStuff::makeGlobalKey()
	 * @param string ... Key component
	 * @return string
	 * @since 1.27
	 */
	public function makeGlobalKey() {
		return call_user_func_array( [ $this->cache, __FUNCTION__ ], func_get_args() );
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
	 * Clear the in-process caches; useful for testing
	 *
	 * @since 1.27
	 */
	public function clearProcessCache() {
		$this->procCache->clear();
	}

	/**
	 * Do the actual async bus purge of a key
	 *
	 * This must set the key to "PURGED:<UNIX timestamp>:<holdoff>"
	 *
	 * @param string $key Cache key
	 * @param integer $ttl How long to keep the tombstone [seconds]
	 * @param integer $holdoff HOLDOFF_* constant controlling how long to ignore sets for this key
	 * @return bool Success
	 */
	protected function relayPurge( $key, $ttl, $holdoff ) {
		$event = $this->cache->modifySimpleRelayEvent( [
			'cmd' => 'set',
			'key' => $key,
			'val' => 'PURGED:$UNIXTIME$:' . (int)$holdoff,
			'ttl' => max( $ttl, 1 ),
			'sbt' => true, // substitute $UNIXTIME$ with actual microtime
		] );

		$ok = $this->purgeRelayer->notify( $this->purgeChannel, $event );
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
		$event = $this->cache->modifySimpleRelayEvent( [
			'cmd' => 'delete',
			'key' => $key,
		] );

		$ok = $this->purgeRelayer->notify( $this->purgeChannel, $event );
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
	 * @return array
	 */
	protected function wrap( $value, $ttl ) {
		return [
			self::FLD_VERSION => self::VERSION,
			self::FLD_VALUE => $value,
			self::FLD_TTL => $ttl,
			self::FLD_TIME => microtime( true )
		];
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
		$purge = self::parsePurgeValue( $wrapped );
		if ( $purge !== false ) {
			// Purged values should always have a negative current $ttl
			$curTTL = min( $purge[self::FLD_TIME] - $now, self::TINY_NEGATIVE );
			return [ false, $curTTL ];
		}

		if ( !is_array( $wrapped ) // not found
			|| !isset( $wrapped[self::FLD_VERSION] ) // wrong format
			|| $wrapped[self::FLD_VERSION] !== self::VERSION // wrong version
		) {
			return [ false, null ];
		}

		$flags = isset( $wrapped[self::FLD_FLAGS] ) ? $wrapped[self::FLD_FLAGS] : 0;
		if ( ( $flags & self::FLG_STALE ) == self::FLG_STALE ) {
			// Treat as expired, with the cache time as the expiration
			$age = $now - $wrapped[self::FLD_TIME];
			$curTTL = min( -$age, self::TINY_NEGATIVE );
		} elseif ( $wrapped[self::FLD_TTL] > 0 ) {
			// Get the approximate time left on the key
			$age = $now - $wrapped[self::FLD_TIME];
			$curTTL = max( $wrapped[self::FLD_TTL] - $age, 0.0 );
		} else {
			// Key had no TTL, so the time left is unbounded
			$curTTL = INF;
		}

		return [ $wrapped[self::FLD_VALUE], $curTTL ];
	}

	/**
	 * @param array $keys
	 * @param string $prefix
	 * @return string[]
	 */
	protected static function prefixCacheKeys( array $keys, $prefix ) {
		$res = [];
		foreach ( $keys as $key ) {
			$res[] = $prefix . $key;
		}

		return $res;
	}

	/**
	 * @param string $value Wrapped value like "PURGED:<timestamp>:<holdoff>"
	 * @return array|bool Array containing a UNIX timestamp (float) and holdoff period (integer),
	 *  or false if value isn't a valid purge value
	 */
	protected static function parsePurgeValue( $value ) {
		if ( !is_string( $value ) ) {
			return false;
		}
		$segments = explode( ':', $value, 3 );
		if ( !isset( $segments[0] ) || !isset( $segments[1] )
			|| "{$segments[0]}:" !== self::PURGE_VAL_PREFIX
		) {
			return false;
		}
		if ( !isset( $segments[2] ) ) {
			// Back-compat with old purge values without holdoff
			$segments[2] = self::HOLDOFF_TTL;
		}
		return [
			self::FLD_TIME => (float)$segments[1],
			self::FLD_HOLDOFF => (int)$segments[2],
		];
	}

	/**
	 * @param float $timestamp
	 * @param int $holdoff In seconds
	 * @return string Wrapped purge value
	 */
	protected function makePurgeValue( $timestamp, $holdoff ) {
		return self::PURGE_VAL_PREFIX . (float)$timestamp . ':' . (int)$holdoff;
	}
}
