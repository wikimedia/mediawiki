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
 * All operations go to the local cache, except the delete()
 * and touchCheckKey(), which broadcast to all clusters.
 * This class is intended for caching data from primary stores.
 * If the get() method does not return a value, then the caller
 * should query the new value and backfill the cache using set().
 * When the source data changes, the delete() method should be called.
 * Since delete() is expensive, it should be avoided. One can do so if:
 *   - a) The object cached is immutable; or
 *   - b) Validity is checked against the source after get(); or
 *   - c) Using a modest TTL is reasonably correct and performant
 * Consider using getWithCallback() instead of the get()/set() cycle.
 *
 * Instances of this class must be configured to point to a valid
 * PubSub endpoint, and there must be listeners on the cache servers
 * that subscribe to the endpoint and update the caches.
 *
 * All values are wrapped in metadata arrays. Keys use a "WANCache:" prefix
 * to avoid collisions with keys that are not wrapped as metadata arrays. The
 * prefixes are as follows:
 *   - a) "WANCache:v" : used for regular value keys
 *   - b) "WANCache:s" : used for temporarily storing values of tomstoned keys
 *   - c) "WANCache:t" : used for storing timestamp "check" keys
 *
 * @ingroup Cache
 * @since 1.25
 */
abstract class WANObjectCache {
	/** @var BagOStuff The local cluster cache */
	protected $cache;

	/** Seconds to tombstone keys on delete() */
	const HOLDOFF_TTL = 10;
	/** Seconds to keep dependency purge keys around */
	const CHECK_KEY_TTL = 31536000; // 1 year
	/** Seconds to keep lock keys around */
	const LOCK_TTL = 5;

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

	/**
	 * @param array $params
	 *   - cache   : BagOStuff object
	 */
	public function __construct( array $params ) {
		$this->cache = $params['cache'];
	}

	/**
	 * Fetch the value of a key from cache
	 *
	 * If passed in, $curTTL is set to the remaining TTL (current time left):
	 *   - a) INF; if the key exists and has no TTL
	 *   - b) float (>=0); if the key exists and has a TTL
	 *   - c) float (<0); if the key is tombstoned or expired by $checkKeys
	 *   - d) null; if the key does not exist and is not tombstoned
	 * On miss, callers can avoid doing writes if $curTTL is false, as they
	 * will most likely be rejected (tombstones help avoid race-conditions).
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
	 * For keys that are hot/expensive, consider using getWithCallback() instead.
	 *
	 * @param string $key Cache key
	 * @param mixed $curTTL Approximate TTL left on the key if present [returned]
	 * @param array $checkKeys List of "check" keys
	 * @return mixed Returns false on failure
	 */
	final public function get( $key, &$curTTL = null, array $checkKeys = array() ) {
		$curTTLs = array();
		$values = $this->getMulti( array( $key ), $curTTLs, $checkKeys );
		$curTTL = $curTTLs[$key];

		return isset( $values[$key] ) ? $values[$key] : false;
	}

	/**
	 * Fetch the value of several keys from cache
	 *
	 * @see WANObjectCache::get()
	 *
	 * @param array $keys List of cache keys
	 * @param array $curTTLs Map of (key => approximate TTL left) [returned]
	 * @param array $checkKeys List of "check" keys
	 * @return array Map of (key => value) for keys that exist
	 */
	final public function getMulti(
		array $keys, &$curTTLs = array(), array $checkKeys = array()
	) {
		$result = array();
		$curTTLs = array();

		$vPrefixLen = strlen( "WANCache:v:" );
		$valueKeys = self::prefixCacheKeys( $keys, "WANCache:v:" );
		$checkKeys = self::prefixCacheKeys( $checkKeys, "WANCache:t:" );

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
				$this->cache->add( $checkKey, "PURGED:$now", self::CHECK_KEY_TTL );
				$timestamp = $now;
			}

			$checkKeyTimes[] = $timestamp;
		}

		// Get the main cache value for each key and validate them
		foreach ( $valueKeys as $vKey ) {
			$key = substr( $vKey, $vPrefixLen ); // unprefix

			if ( !isset( $wrappedValues[$vKey] ) ) {
				$curTTLs[$key] = null;
				continue; // not found
			}

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
	 * @param string $key Cache key
	 * @param mixed $value
	 * @param integer $ttl Seconds to live [0=forever]
	 * @return bool Success
	 */
	final public function set( $key, $value, $ttl = 0 ) {
		$key = "WANCache:v:$key";
		$wrapped = $this->wrap( $value, $ttl );

		$func = function ( $cache, $key, $cWrapped ) use ( $wrapped, $ttl ) {
			return ( is_string( $cWrapped ) )
				? false // key is tombstoned; do nothing
				: $wrapped;
		};

		return $this->cache->merge( $key, $func, $ttl, 1 );
	}

	/**
	 * Purge a key from all clusters
	 *
	 * This instantiates a hold-off period where the key cannot be
	 * written to avoid race conditions where dependent keys get updated
	 * with a stale value (e.g. from a DB slave).
	 *
	 * This should only be called when the underlying data (being cached)
	 * changes in a significant way. If called twice on the same key, then
	 * the last TTL takes precedence.
	 *
	 * @param string $key Cache key
	 * @param integer $ttl How long to block writes to the key [seconds]
	 * @return bool True if the item was purged or not found, false on failure
	 */
	final public function delete( $key, $ttl = self::HOLDOFF_TTL ) {
		$key = "WANCache:v:$key";
		// Update the local cluster immediately
		$ok = $this->cache->set( $key, 'PURGED:' . microtime( true ), $ttl );
		// Publish the purge to all clusters
		return $this->relayPurge( $key, $ttl ) && $ok;
	}

	/**
	 * Fetch the value of a timestamp "check" key
	 *
	 * @param type $key
	 * @return float|bool TS_UNIX timestamp of the key; false if not present
	 */
	final public function getCheckKeyTime( $key ) {
		return self::parsePurgeValue( $this->cache->get( "WANCache:t:$key" ) );
	}

	/**
	 * Purge a "check" key from all clusters, invalidating keys that use it
	 *
	 * This should only be called when the underlying data (being cached)
	 * changes in a significant way, and it impractical to call delete()
	 * on all keys that should be changed. When get() is called on those
	 * keys, the relevant "check" keys must be supplied for this to work.
	 *
	 * The "check" key essentially represents a last-modified field.
	 * It is set in the future a few seconds when this is called, to
	 * avoid race conditions where dependent keys get updated with a
	 * stale value (e.g. from a DB slave).
	 *
	 * @see WANObjectCache::get()
	 *
	 * @param string $key Cache key
	 * @return bool True if the item was purged or not found, false on failure
	 */
	final public function touchCheckKey( $key ) {
		$key = "WANCache:t:$key";
		// Update the local cluster immediately
		$ok = $this->cache->set( $key, 'PURGED:' . microtime( true ), self::CHECK_KEY_TTL );
		// Publish the purge to all clusters
		return $this->relayPurge( $key, self::CHECK_KEY_TTL ) && $ok;
	}

	/**
	 * Method to fetch/regenerate cache keys
	 *
	 * On cache miss, the key will be set to the callback result.
	 * The callback function returns the new value given the current
	 * value (false if not present). If false is returned, then nothing
	 * will be saved to cache.
	 *
	 * Most callers should ignore the current value, but it can be used
	 * to maintain "most recent X" values that come from time or sequence
	 * based source data, provided that the "as of" id/time is tracked.
	 *
	 * Usage of $checkKeys is the same as with get().
	 *
	 * The simplest way to avoid stampedes for hot keys is to use
	 * the 'lockTSE' option in $opts. If cache purges are needed, also:
	 *   a) Pass $key into $checkKeys
	 *   b) Use touchCheckKey( $key ) instead of delete( $key )
	 * Following this pattern lets the old cache be used until a
	 * single thread updates it as needed. Also consider tweaking
	 * the 'lowTTL' parameter.
	 *
	 * Example usage:
	 * <code>
	 *     $key = wfMemcKey( 'cat-recent-actions', $catId );
	 *     // Function that derives the new key value given the old value
	 *     $callback = function( $cValue ) { ... };
	 *     // Get the key value from cache or from source on cache miss;
	 *     // try to only let one cluster thread manage doing cache updates
	 *     $opts = array( 'lockTSE' => 5, 'lowTTL' => 10 );
	 *     $value = $cache->getWithCallback( $key, $callback, 60, array(), $opts );
	 * </code>
	 *
	 * Example usage:
	 * <code>
	 *     $key = wfMemcKey( 'cat-state', $catId );
	 *     // The "check" keys that represent things the value depends on;
	 *     // Calling touchCheckKey() on them invalidates "cat-state"
	 *     $checkKeys = array(
	 *         wfMemcKey( 'water-bowls', $houseId ),
	 *         wfMemcKey( 'food-bowls', $houseId ),
	 *         wfMemcKey( 'people-present', $houseId )
	 *     );
	 *     // Function that derives the new key value
	 *     $callback = function() { ... };
	 *     // Get the key value from cache or from source on cache miss;
	 *     // try to only let one cluster thread manage doing cache updates
	 *     $opts = array( 'lockTSE' => 5, 'lowTTL' => 10 );
	 *     $value = $cache->getWithCallback( $key, $callback, 60, $checkKeys, $opts );
	 * </code>
	 *
	 * @see WANObjectCache::get()
	 *
	 * @param string $key Cache key
	 * @param callable $callback Value generation function
	 * @param integer $ttl Seconds to live when the key is updated [0=forever]
	 * @param array $checkKeys List of "check" keys
	 * @param array $opts Options map:
	 *   - lowTTL  : consider pre-emptive updates when the current TTL (sec)
	 *               of the key is less than this. It becomes more likely
	 *               over time, becoming a certainty once the key is expired.
	 *   - lockTSE : if the key is tombstoned or expired less (by $checkKeys)
	 *               than this many seconds ago, then try to have a single
	 *               thread handle cache regeneration at any given time.
	 *               Other threads will try to use stale values if possible.
	 *   - tempTTL : when 'lockTSE' is set, this determines the TTL of the temp
	 *               key used to cache values while a key is tombstoned.
	 *               This avoids excessive regeneration of hot keys on delete() but
	 *               may result in stale values.
	 * @return mixed Value to use for the key
	 */
	final public function getWithCallback(
		$key, $callback, $ttl, array $checkKeys = array(), array $opts = array()
	) {
		$lowTTL = isset( $opts['lowTTL'] ) ? $opts['lowTTL'] : min( 10, $ttl );
		$lockTSE = isset( $opts['lockTSE'] ) ? $opts['lockTSE'] : -1;
		$tempTTL = isset( $opts['tempTTL'] ) ? $opts['tempTTL'] : 5;

		// Get the current key value
		$curTTL = null;
		$cValue = $this->get( $key, $curTTL, $checkKeys ); // current value
		$value = $cValue; // return value

		// Determine if a regeneration is desired
		if ( $value !== false && $curTTL > 0 && !$this->worthRefresh( $curTTL, $lowTTL ) ) {
			return $value;
		}

		if ( !is_callable( $callback ) ) {
			throw new Exception( "Invalid cache miss callback provided." );
		}

		// Assume a key is hot if requested soon after invalidation
		$isHot = ( $curTTL !== null && $curTTL <= 0 && abs( $curTTL ) <= $lockTSE );
		$isTombstone = ( $curTTL !== null && $value === false );

		$locked = false;
		if ( $isHot || $isTombstone ) {
			// Acquire a cluster-local non-blocking lock
			if ( $this->cache->lock( $key, 0, self::LOCK_TTL ) ) {
				// Lock acquired; this thread should update the key
				$locked = true;
			} elseif ( $value !== false ) {
				// If it cannot be acquired; then the stale value can be used
				return $value;
			} else {
				// Either another thread has the lock or the lock failed.
				// Use the stash value, which is likely from the prior thread.
				$value = $this->cache->get( "WANCache:s:$key" );
				// Regenerate on timeout or if the other thread failed
				if ( $value !== false ) {
					return $value;
				}
			}
		}

		// Generate the new value from the callback...
		$value = call_user_func( $callback, $cValue );
		// When delete() is called, writes are write-holed by the tombstone,
		// so use a special stash key to pass the new value around threads.
		if ( $value !== false && ( $isHot || $isTombstone ) ) {
			$this->cache->set( "WANCache:s:$key", $value, $tempTTL );
		}

		if ( $locked ) {
			$this->cache->unlock( $key );
		}

		if ( $value !== false ) {
			// Update the cache; this will fail if the key is tombstoned
			$this->set( $key, $value, $ttl );
		}

		return $value;
	}

	/**
	 * Get the "last error" registered; clearLastError() should be called manually
	 * @return int ERR_* constant for the "last error" registry
	 */
	final public function getLastError() {
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
	abstract protected function relayPurge( $key, $ttl );

	/**
	 * Check if a key should be regenerated (using random probability)
	 *
	 * This returns false if $curTTL >= $lowTTL. Otherwise, the chance
	 * of returning true increases steadily from 0% to 100% as the $curTTL
	 * moves from $lowTTL to 0 seconds. This handles widely varying
	 * levels of cache access traffic.
	 *
	 * @param float|INF $curTTL Approximate TTL left on the key if present
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
	 * @param float $now Unix timestamp
	 * @return array (value or false, current time left)
	 */
	protected function unwrap( $wrapped, $now ) {
		// Check if the value is a tombstone
		$purgeTimestamp = self::parsePurgeValue( $wrapped );
		if ( is_float( $purgeTimestamp ) ) {
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
	 * @return string
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
		if ( is_string( $value ) && preg_match( '/^PURGED:([^:]+)$/', $value, $m ) ) {
			return (float)$m[1];
		} else {
			return false;
		}
	}
}
