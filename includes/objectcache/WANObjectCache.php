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
 * All operations go to the local cluster cache, except the delete()
 * and purgeDependencies() methods, which broadcast to all clusters.
 * This class is intended for caching data from primary stores.
 * If the get() method does not return a value, then the caller
 * should query the new value and backfill the cache using set().
 * When the source data changes, the delete() method should be called.
 * Since delete() is expensive, it should be avoided. One can do so if:
 *   - a) The object cached is immutable; or
 *   - b) Validity is checked against the source after get(); or
 *   - c) Using a modest TTL is reasonably correct and performant
 * Conside using getWithCallback() instead of the get()/set() cycle.
 *
 * Instances of this class must be configured to point to a valid
 * PubSub endpoint, and there must be listeners on the cache servers
 * that subscribe to the endpoint and update the caches.
 *
 * All values are wrapped in metadata arrays. Keys use a "WOC:" prefix
 * to avoid collisions with keys that are not wrapped as metadata arrays.
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
	const INVALIDATE_TTL = 31536000; // 1 year
	/** Seconds to keep lock keys around */
	const LOCK_TTL = 5;

	/** Cache format version number */
	const VERSION = 1;

	/** Fields of value holder arrays */
	const FLD_VER = 0;
	const FLD_VAL = 1;
	const FLD_TTL = 2;
	const FLD_TME = 3;

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
	 *        the purgeDependencies() method is called on them
	 *
	 * For keys that are hot/expensive, consider using getWithCallback() instead.
	 *
	 * @param string $key Cache key
	 * @param mixed $ctl Approximate TTL left on the key if present [returned]
	 * @param array $cKeys List of "check" keys
	 * @return mixed Returns false on failure
	 */
	final public function get( $key, &$ctl = null, array $cKeys = array() ) {
		$key = "WOC:k:$key";

		if ( count( $cKeys ) ) {
			$cKeys = array_map( function( $k ) { return "WOC:c:$k"; }, $cKeys );
			$wVals = $this->cache->getMulti( array_merge( array( $key ), $cKeys ) );
			$wrapped = isset( $wVals[$key] ) ? $wVals[$key] : false;
			unset( $wVals[$key] );
		} else {
			$wVals = array();
			$wrapped = $this->cache->get( $key );
		}

		$now = microtime( true );
		// Get the main cache value and timestamp
		list( $value, $ctl ) = $this->unwrap( $wrapped, $now );
		$valTimestamp = ( $value === false ) ? 0 : $wrapped[self::FLD_TME];
		// Check/fill the timestamp of all the "check" keys
		foreach ( $cKeys as $cKey ) {
			$m = array();
			if ( isset( $wVals[$cKey] )
				&& preg_match( '/^PURGED:([^:]+)$/', $wVals[$cKey], $m )
			) {
				// Check if the "check" key tombstone is newer.
				// Tombstones look like "PURGED:<unix timestamp>".
				if ( (float)$m[1] > $valTimestamp ) {
					$ctl = 0; // stale
				}
			} else {
				// Key is not set or invalid; regenerate
				$this->cache->add( $cKey, "PURGED:" . microtime( true ) );
				$ctl = 0; // stale
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

		$prefixLen = strlen( "WOC:k:" );
		$keys = array_map( function( $k ) { return "WOC:k:$k"; }, $keys );
		$wVals = $this->cache->getMulti( $keys );

		$ctls = array();
		$now = microtime( true );
		foreach ( $wVals as $key => $wrapped ) {
			$key = substr( $key, $prefixLen );
			list( $value, $ctl ) = $this->unwrap( $wrapped, $now );
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
		$key = "WOC:k:$key";
		$wrapped = $this->wrap( $value, $ttl );

		$func = function ( $cache, $key, $cWrapped ) use ( $wrapped, $ttl ) {
			return ( is_string( $cWrapped ) )
				? false // key is purged; do nothing
				: $wrapped;
		};

		return $this->cache->merge( $key, $func, $ttl, 1 );
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
		$key = "WOC:k:$key";
		// Support immediate consistency in the local cluster
		$ok = $this->cache->set( $key, 'PURGED:' . microtime( true ), $ttl );
		// Publish the purge to all clusters
		return $this->relayPurge( $key, $ttl ) && $ok;
	}

	/**
	 * Purge a "check" key from all clusters, invalidating keys that use it
	 *
	 * This should only be called when the underlying data (being cached)
	 * changes in a significant way, and it impractical to call delete()
	 * on all keys that should be changed. When get() is called on those
	 * keys, the relevant "check" keys must be supplied for this to work.
	 *
	 * @see WANObjectCache::get()
	 *
	 * @param string $key Cache key
	 * @return bool True if the item was purged or not found, false on failure
	 */
	final public function purgeDependencies( $key ) {
		$key = "WOC:c:$key";
		// Support immediate consistency in the local cluster
		$ok = $this->cache->set( $key, 'PURGED:' . microtime( true ), self::INVALIDATE_TTL );
		// Publish the purge to all clusters
		return $this->relayPurge( $key, self::INVALIDATE_TTL ) && $ok;
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
	 * Usage of $cKeys is the same as with get().
	 *
	 * Example usage:
	 * <code>
	 *     $key = wfMemcKey( 'cat-recent-actions', $catId );
	 *     // Function that derives the new key value given the old value
	 *     $callback = function( $cValue ) { ... };
	 *     // Get the key value from cache or from source on cache miss;
	 *     // try to only let one cluster thread manage doing cache updates
	 *     $opts = array( 'lock' => true, 'lowCTL' => 10 );
	 *     $value = $cache->getWithCallback( $key, $callback, 60, array(), $opts );
	 * </code>
	 *
	 * Example usage:
	 * <code>
	 *     $key = wfMemcKey( 'cat-state', $catId );
	 *     // The "check" keys that represent things the value depends on;
	 *     // Calling purgeDependencies() on them invalidates "cat-state"
	 *     $cKeys = array(
	 *         wfMemcKey( 'water-bowls', $houseId ),
	 *         wfMemcKey( 'food-bowls', $houseId ),
	 *         wfMemcKey( 'people-present', $houseId )
	 *     );
	 *     // Function that derives the new key value
	 *     $callback = function() { ... };
	 *     // Get the key value from cache or from source on cache miss;
	 *     // try to only let one cluster thread manage doing cache updates
	 *     $opts = array( 'lock' => true, 'lowCTL' => 10 );
	 *     $value = $cache->getWithCallback( $key, $callback, 60, $cKeys, $opts );
	 * </code>
	 *
	 * @see WANObjectCache::get()
	 *
	 * @param string $key Cache key
	 * @param callable $callback Value generation function
	 * @param integer $ttl Seconds to live when the key is updated [0=forever]
	 * @param array $cKeys List of "check" keys
	 * @param array $opts Options map:
	 *   - lowCTL  : consider pre-emptive updates when the current TTL
	 *               of the key is less than this. It becomes more likely
	 *               over time, becoming a certainty once the key is expired.
	 *   - lock    : whether to have one cluster thread handle updates
	 *               at any given time (when possible). Other threads will
	 *               use stale values if possible, such as when a key is
	 *               tombstoned by delete(). Staleness is < 10 seconds.
	 *   - counter : To avoid stampedes when no stale cache is available,
	 *               pass in an appropriate PoolCounter object to use.
	 * @return mixed Value to use for the key
	 */
	final public function getWithCallback(
		$key, $callback, $ttl, array $cKeys = array(), array $opts = array()
	) {
		$lock = !empty( $opts['lock'] );
		$lowCTL = isset( $opts['lowCTL'] ) ? $opts['lowCTL'] : min( 10, $ttl );

		// Get the current key value
		$ctl = null;
		$cValue = $this->get( $key, $ctl, $cKeys ); // current value
		$value = $cValue; // return value

		// Determine if a regeneration is desired
		if ( $value !== false && $ctl > 0 && !$this->worthRefresh( $ctl, $lowCTL ) ) {
			return $value;
		}

		if ( !is_callable( $callback ) ) {
			throw new Exception( "Invalid cache miss callback provided." );
		}

		if ( $lock === 'lock' ) {
			// Acquire a cluster-local non-blocking lock
			if ( $this->cache->lock( $key, 0, self::LOCK_TTL ) ) {
				// Lock acquired; this thread should update the key
			} elseif ( $value !== false ) {
				// If it cannot be acquired; then the stale value can be used
				return $value;
			} else {
				// Either another thread has the lock or the lock failed.
				// Use the stash value, which is likely from the prior thread.
				$value = $this->cache->get( "WOC:s:$key" );
				// Renerate on timeout or if the other thread failed
				if ( $value !== false ) {
					return $value;
				}
			}
		}

		// Generate the new value from the callback...
		if ( isset( $opts['counter'] ) ) {
			$status = $opts['counter']->acquireForAnyone();
			if ( $status->value == PoolCounter::DONE ) {
				// Another thread did the work; retrieve it
				$value = $this->cache->get( "WOC:s:$key" );
				if ( $value === false ) {
					// EmptyBagOStuff, callback returned false, or error...
					$value = call_user_func( $callback, $cValue );
				}
			} else {
				// This thread needs to do the work; do it and save
				$value = call_user_func( $callback, $value );
				if ( $value !== false ) {
					$this->cache->set( "WOC:s:$key", $value, self::LOCK_TTL + 2 );
				}
				// Wake up any other threads if this one got the lock
				if ( $status->value == PoolCounter::LOCKED ) {
					$opts['counter']->release();
				}
			}
		} else {
			$value = call_user_func( $callback, $value );
			// When delete() is called, writes are write-holed by the tombstone,
			// so use a special stash key to pass the new value around threads.
			if ( $value !== false && $lock === 'lock' ) {
				$this->cache->set( "WOC:s:$key", $value, self::LOCK_TTL + 2 );
			}
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
	 * Do the actual async bus purge of a key for delete()
	 *
	 * This must set the key to "PURGED:<UNIX timestamp>"
	 *
	 * @param string $key Cache key
	 * @param integer $ttl How long to block writes to the key [seconds]
	 * @return bool Success
	 */
	abstract protected function relayPurge( $key, $ttl );

	/**
	 * Check if a key should be regenerated (using random probability)
	 *
	 * This returns false if $ctl >= $lowCTL. Otherwise, the chance
	 * of returning true increases steadily from 0% to 100% as the $ctl
	 * moves from $lowCTL to 0 seconds. This handles widely varying
	 * levels of cache access traffic.
	 *
	 * @param float|INF $ctl Approximate TTL left on the key if present
	 * @param float $lowCTL Consider a refresh when $ctl is less than this
	 * @return bool
	 */
	protected function worthRefresh( $ctl, $lowCTL ) {
		if ( $ctl >= $lowCTL ) {
			return false;
		} elseif ( $ctl <= 0 ) {
			return true;
		}

		$chance = ( 1 - $ctl / $lowCTL );

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
	protected function unwrap( $wrapped, $now ) {
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
			$ctl = max( $wrapped[self::FLD_TTL] - $age, 0.0 );
		} else {
			// Key had no TTL, so the time left is unbounded
			$ctl = INF;
		}

		return array( $wrapped[self::FLD_VAL], $ctl );
	}
}
