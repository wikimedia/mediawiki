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
 * Interface to be used by caches that can handle the multi data-center scenarios.
 *
 * All writes and reads go to the local cluster cache, except the delete() method.
 * It broadcasts purges to all clusters, while updating the local one immediatly.
 * The class must be configured to point to a valid PubSub endpoint, and there must
 * be listeners on the cache servers that subscribe to it and update the caches.
 *
 * This class is intended for caching data from primary stores.
 * If the get() method does not return a value, then the caller
 * should query the new value and backfill the cache using set().
 * When the source data changes, the delete() method should be called.
 *
 * All values are internally stored in small arrays, which will always be serialized
 * in storage. This allows for raw string values to have special meanings, and they
 * can easily be set by purging daemons, even in other languages. For memcached, this
 * means that the daemons can just set flags=0 and use raw strings.
 *
 * @ingroup Cache
 * @since 1.25
 */
class WANObjectCache {
	/** @var string Cache pool name */
	protected $pool;
	/** @var BagOStuff The local cluster cache */
	protected $cache;
	/** @var EventRelayer */
	protected $relayer;

	/** Seconds to block cache updates for purge values */
	const HOLDOFF_TTL = 10;

	/** Cache format version number */
	const VERSION = 1;

	/** Fields of value holder arrays */
	const FLD_VER = 0;
	const FLD_VAL = 1;
	const FLD_TTL = 2;
	const FLD_TME = 3;

	/**
	 * @param array $params
	 *   - pool    : pool name
	 *   - cache   : BagOStuff object
	 *   - relayer : EventRelayer class
	 * @throws Exception
	 */
	public function __construct( array $params ) {
		$this->pool = $params['pool'];
		$this->cache = $params['cache'];
		$this->relayer = $params['relayer'];
	}

	/**
	 * Fetch the value of a key from cache
	 *
	 * If passed in, $ctl is set to the remaining TTL (current time left):
	 *   - a) INF; if the key exists and has no TTL
	 *   - b) float; if the key exists and has a TTL
	 *   - c) null; if the key does not exist and is not tombstoned by delete()
	 *   - d) false; if the key is still tombstoned by delete()
	 * On miss, callers can avoid doing writes if $ctl is false, as they
	 * will most likely be rejected (tombstones help avoid race-conditions).
	 *
	 * The timestamp of $key will be checked against the timestamp of the last
	 * delete() call to each of $cKeys. If one of these keys was never deleted,
	 * or the last-deletion time fell out of cache, it will be initialized to the
	 * current time. If any of $cKeys have a last-deletion timestamp greater than
	 * the timestamp of $key, then $ctl will be set to 0. Callers can use $ctl to
	 * know when the value is stale.
	 *
	 * The $cKeys parameter allow mass invalidations by updating a single key:
	 *   - a) Each "check" key represents "last purged" of some source data
	 *   - b) Callers pass in relevant "check" keys as $cKeys in get()
	 *   - c) When the source data that "check" keys represent changes,
	 *        the purgeDependencies() method is called on them; this is
	 *        they only method used on such keys
	 *
	 * For keys that are hot/expensive, consider using getWithCallback() instead.
	 *
	 * @param string $key Cache key
	 * @param mixed $ctl Approximate TTL left on the key if present [returned]
	 * @param array $cKeys List of "check" keys
	 * @return mixed Returns false on failure
	 */
	final public function get( $key, &$ctl = null, array $cKeys = array() ) {
		// Prefix the "check" keys to avoid collisions
		foreach ( $cKeys as &$cKey ) {
			$cKey = "WanOC:check:$cKey";
		}
		unset( $cKey );

		if ( count( $cKeys ) ) {
			$wrappedVals = $this->cache->getMulti( array_merge( array( $key ), $cKeys ) );
			$wrapped = isset( $wrappedVals[$key] ) ? $wrappedVals[$key] : false;
			unset( $wrappedVals[$key] );
		} else {
			$wrappedVals = array();
			$wrapped = $this->cache->get( $key );
		}

		$now = microtime( true );
		// Get the main cache value and timestamp
		list( $value, $ctl ) = $this->unwrapInternal( $wrapped, $now );
		$valTimestamp = ( $value === false ) ? 0 : $wrapped[self::FLD_TME];
		// Check/fill the timestamp of all the "check" keys
		foreach ( $wrappedVals as $cKey => $cWrapped ) {
			if ( is_string( $cWrapped ) ) {
				// Check if the "check" key tombstone is newer.
				// Tombstones should look like "PURGED:<unix timestamp>".
				list( , $tme ) = explode( ':', $cWrapped );
				if ( (float)$tme > $valTimestamp ) {
					$ctl = 0; // stale
				}
			} else {
				$ctl = 0; // stale
				// Key is not set or invalid; regenerate
				$this->cache->set( $cKey, "PURGED:" . microtime( true ) );
			}
		}

		return $value;
	}

	/**
	 * Fetch the value of several keys from cache
	 *
	 * @param array $keys List of cache keys
	 * @param array $ctls Map of (key => approximate TTL left) [returned]
	 * @return array Map of (key => value) for keys that exist
	 */
	final public function getMulti( array $keys, &$ctls = array() ) {
		$result = array();

		$wrappedVals = $this->cache->getMulti( $keys );

		$ctls = array();
		$now = microtime( true );
		foreach ( $wrappedVals as $key => $wrapped ) {
			list( $value, $ctl ) = $this->unwrapInternal( $wrapped, $now );
			if ( $value !== false ) {
				$result[$key] = $value;
				$ctls[$key] = $ctl;
			}
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
		$that = $this;
		$func = function ( $cache, $key, $cWrapped ) use ( $that, $value, $ttl ) {
			return ( is_string( $cWrapped ) )
				? false // key is purged; do nothing
				: $that->wrapInternal( $value, $ttl );
		};

		return $this->cache->merge( $key, $func, $ttl, 1 );
	}

	/**
	 * Merge changes into the existing cache value (possibly creating a new one)
	 *
	 * The callback function returns the new value given the current value (possibly false),
	 * and takes the arguments: (this BagOStuff object, cache key, current value).
	 *
	 * Simply calling this method when source data changes is not valid because
	 * the changes do not replicate to the other WAN sites. In that case, delete()
	 * should be used instead. This method is intended for use on cache misses
	 * or for timed-based updates of cache values.
	 *
	 * @param string $key Cache key
	 * @param Closure $callback Callback method to be executed
	 * @param integer $ttl Seconds to live [0=forever]
	 * @param integer $attempts The amount of times to attempt a merge in case of failure
	 * @return bool Success
	 */
	final public function merge( $key, Closure $callback, $ttl = 0, $attempts = 10 ) {
		$that = $this;
		$now = microtime( true );
		$wrapper = function ( $cache, $key, $cWrapped ) use ( $that, $callback, $ttl, $now ) {
			if ( is_string( $cWrapped ) ) {
				return false; // key is purged; do nothing
			}
			list( $cValue ) = $that->unwrapInternal( $cWrapped, $now );
			$newVal = $callback( $cache, $key, $cValue );
			if ( $newVal === false ) {
				return false; // do nothing
			}
			return $that->wrapInternal( $newVal, $ttl );
		};

		return $this->cache->merge( $key, $wrapper, $ttl, $attempts );
	}

	/**
	 * Purge a key from all clusters, instaiting a hold-off period where the
	 * key cannot be written to (avoiding stale update races) for a given TTL
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
		// Support immediate consistency in the local cluster
		$ok = $this->cache->set( $key, 'PURGED:' . microtime( true ), $ttl );
		// Publish the purge to all clusters...
		return $this->relayer->notify(
			"{$this->pool}:purge",
			json_encode( array(
				'cmd' => 'set-subst',
				'key' => $key,
				'val' => 'PURGED:$UNIXTIME$',
				'flg' => 0,
				'ttl' => max( $ttl, 1 )
			) )
		) && $ok;
	}

	/**
	 * Purge a "check" key from all clusters, invalidating anything that uses it
	 *
	 * This should only be called when the underlying data (being cached)
	 * changes in a significant way.
	 *
	 * @see WANObjectCache::get()
	 *
	 * @param string $key Cache key
	 * @param integer $ttl How long to block writes to the key [seconds]
	 * @return bool True if the item was purged or not found, false on failure
	 */
	final public function purgeDependencies( $key ) {
		return $this->delete( "WanOC:check:$key", 365 * 86400 );
	}

	/**
	 * Method to regenerate cache keys in a way that avoid stampedes
	 *
	 * Preemptive regeneration becomes possible once the key's remaining
	 * time-to-live is less than $threshold. It becomes more likely over
	 * time, becoming a certainty once the key is expired.
	 *
	 * Use $lock to control whether a mutex should be used or not.
	 * It only makes sense to use this if $callback is expensive.
	 *
	 * Usage of $cKeys is the same as with get().
	 *
	 * Example usage:
	 * <code>
	 *     // Function that derives the new key value
	 *     $callback = function() { ... };
	 *     // Optional "check" keys that represent things the value depends on
	 *     $cKeys = array( 'water-bowl', 'food-bowl', 'people-present' );
	 *     // Get the key value from cache or from source on cache miss
	 *     $value = $cache->getWithCallback( 'cat-happiness', 10, 'lock', $callback, $cKeys );
	 * </code>
	 *
	 * @param string $key Cache key
	 * @param float $threshold Consider preemptive refresh when $ctl is less than this
	 * @param string $lock One of (lock, nolock)
	 * @param integer $ttl Seconds to live [0=forever]
	 * @param Closure $callback Function that returns the new value to cache
	 * @param array $cKeys List of "check" keys
	 * @return mixed Value to use for the key
	 */
	final public function getWithCallback(
		$key, $threshold, $lock, $ttl, Closure $callback, array $cKeys = array()
	) {
		$ctl = null;
		$value = $this->get( $key, $ctl, $cKeys );
		if ( $value === false || $ctl <= 0 ) {
			$regen = true;
		} else {
			$regen = $this->worthPreemptiveRefresh( $ctl, $threshold );
		}

		if ( !$regen ) {
			return $value;
		}

		$timeout = 10; // how long to wait on locks

		if ( $lock === 'lock' ) {
			if ( $value !== false ) {
				// If the key has a value, but it is stale, acquiring a non-blocking
				// lock. If it cannot be acquired, then the stale value can be used.
				$sLock = $this->getScopedLock( $key, 0 );
				if ( !$sLock ) {
					$regen = false;
				}
			} else {
				// If the key has no value, then acquire the lock in blocking mode
				// and wait for the thread with the lock to finish updating the cache.
				$sLock = $this->getScopedLock( $key, $timeout );
				// Check the stash to avoid delete() tombstone write-hole.
				// The value will still be false if there was no lock contention.
				$value = $sLock ? $this->cache->get( "WanOC:stash:$key" ) : false;
				$regen = ( $value === false );
			}
		}

		if ( $regen ) {
			// Generate the new value
			$value = $callback();
			// Update the cached value (which will fail if tombstoned)
			$this->set( $key, $value, $ttl );
			// When delete() is called, writes are write-holed by the tombstone,
			// so use a special stash key to pass the new value around processes.
			$this->cache->set( "WanOC:stash:$key", $value, $timeout + 3 );
		}

		return $value;
	}

	/**
	 * Acquire a cooperative cluster-local lock on a cache key
	 *
	 * This cannot be used a global semaphore, but can be to help
	 * reduce wasted effort by preventing many threads from doing
	 * the same thing (e.g. rebuilding an expensive cache value).
	 *
	 * @param string $key
	 * @param integer $timeout Lock wait timeout; use 0 for non-blocking [optional]
	 * @param integer $expiry Lock expiry [optional]
	 * @return ScopedCallback|bool Returns false on failure
	 */
	final public function getScopedLock( $key, $timeout = 6, $expiry = 6 ) {
		$cache = $this->cache;

		return $cache->lock( $key, $timeout, $expiry )
			? new ScopedCallback(
				function() use ( $cache, $key ) { $cache->unlock( $key ); } )
			: false;
	}

	/**
	 * Check if a key should be regenerated (using random probability)
	 *
	 * This returns false if $ctl >= $threshold. Otherwise, the chance
	 * of returning true increases steadily from 0% to 100% as the $ctl
	 * moves from $threshold to 0 seconds. This handles widely varying
	 * levels of cache access traffic.
	 *
	 * @param float|INF $ctl Approximate TTL left on the key if present
	 * @param float $threshold Consider a refresh when $ctl is less than this
	 * @return bool
	 */
	protected function worthPreemptiveRefresh( $ctl, $threshold ) {
		if ( $ctl >= $threshold ) {
			return false;
		} elseif ( $ctl <= 0 ) {
			return true;
		}

		$chance = ( 1 - $ctl / $threshold );

		return mt_rand( 1, 1e9 ) <= 1e9 * $chance;
	}

	/**
	 * Get the "last error" registered; clearLastError() should be called manually
	 * @return int ERR_* constant for the "last error" registry
	 */
	final public function getLastError() {
		return $this->cache->getLastError();
	}

	/**
	 * Clear the "last error" registry
	 */
	final public function clearLastError() {
		$this->cache->clearLastError();
	}

	/**
	 * Do not use this method outside WANObjectCache
	 *
	 * @param mixed $value
	 * @param integer $ttl
	 * @return string
	 */
	public function wrapInternal( $value, $ttl ) {
		return array(
			self::FLD_VER => self::VERSION,
			self::FLD_VAL => $value,
			self::FLD_TTL => $ttl,
			self::FLD_TME => microtime( true )
		);
	}

	/**
	 * Do not use this method outside WANObjectCache
	 *
	 * @param array|string|bool $wrapped
	 * @param float $now Unix timestamp
	 * @return array (value or false, current time left)
	 */
	public function unwrapInternal( $wrapped, $now ) {
		if ( is_string( $wrapped ) ) {
			return array( false, false );
		}

		if ( !is_array( $wrapped ) // not found
			|| !isset( $wrapped[self::FLD_VER] ) // wrong format
			|| $wrapped[self::FLD_VER] !== self::VERSION // wrong version
		) {
			return array( false, null );
		}

		if ( $wrapped[self::FLD_TTL] > 0 ) {
			// Get the approximate time left on the key
			$age = $now - $wrapped[self::FLD_TME];
			$ctl = max( $age - $wrapped[self::FLD_TTL], 0.0 );
		} else {
			// Key had no TTL, so the time left is unbounded
			$ctl = INF;
		}

		return array( $wrapped[self::FLD_VAL], $ctl );
	}
}
