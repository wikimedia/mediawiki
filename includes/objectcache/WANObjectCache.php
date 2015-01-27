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
 * The get()/set() cycle can be made to resist cache stampedes by using
 * getWithCallback() instead.
 *
 * Instances of this class must be configured to point to a valid
 * PubSub endpoint, and there must be listeners on the cache servers
 * that subscribe to the endpoint and update the caches.
 *
 * All values are internally stored in small arrays, which will always be
 * serialized in storage. This lets raw string values to have special meanings,
 * and they can easily be set by purging daemons, even in other languages.
 * For memcached, this means that daemons can just set flags=0.
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

	/** Possible values for getLastError() */
	const ERR_NONE = 0; // no error
	const ERR_NO_RESPONSE = 1; // no response
	const ERR_UNREACHABLE = 2; // can't connect
	const ERR_UNEXPECTED = 3; // response gave some error

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
		// Prefix the "check" keys to avoid collisions
		foreach ( $cKeys as &$cKey ) {
			$cKey = "WanOCache:c:$cKey";
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
		// Publish the purge to all clusters
		return $this->relayer->notify(
			"{$this->pool}:purge",
			json_encode( array(
				'cmd' => 'set',
				'key' => $key,
				'val' => 'PURGED:$UNIXTIME$',
				'flg' => 0,
				'ttl' => max( $ttl, 1 ),
				'sbt' => true // interpolate $UNIXTIME$
			) )
		) && $ok;
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
	 * @param integer $ttl How long to block writes to the key [seconds]
	 * @return bool True if the item was purged or not found, false on failure
	 */
	final public function purgeDependencies( $key ) {
		return $this->delete( "WanOCache:c:$key", 365 * 86400 );
	}

	/**
	 * Method to fetch/regenerate cache keys in a way that avoid stampedes
	 *
	 * If the key does not exist, it will be generated by $callback and
	 * saved back into cache using set(). The callback function returns
	 * the new value given the current value (false if not present).
	 * Most callers should ignore this value, but it can be used to
	 * maintain "most recent X" values that come from time or sequence
	 * based source data, provided that the "as of" id/time is tracked.
	 *
	 * Usage of $cKeys is the same as with get().
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
	 *     $opts = array( 'lock' => true, 'ctlThreshold' => 10 );
	 *     $value = $cache->getWithCallback( $key, $callback, 60, $cKeys, $opts );
	 * </code>
	 *
	 * Example usage:
	 * <code>
	 *     $key = wfMemcKey( 'cat-recent-actions', $catId );
	 *     // Function that derives the new key value given the old value
	 *     $callback = function( $cache, $key, $cValue, $cTimestamp ) { ... };
	 *     // Get the key value from cache or from source on cache miss;
	 *     // try to only let one cluster thread manage doing cache updates
	 *     $opts = array( 'lock' => true, 'ctlThreshold' => 10 );
	 *     $value = $cache->getWithCallback( $key, $callback, 60, array(), $opts );
	 * </code>
	 *
	 * @param string $key Cache key
	 * @param Closure $callback Function that returns the new value to cache
	 * @param integer $ttl Seconds to live when the key is updated [0=forever]
	 * @param array $cKeys List of "check" keys
	 * @param array $opts Options map:
	 *   - lock         : whether to have one cluster thread handle updates
	 *                    at any given time (when possible)
	 *   - ctlThreshold : consider pre-emptive updates when the current TTL
	 *                    of the key is less than this. It becomes more likely
	 *                    over time, becoming a certainty once the key is expired.
	 * @return mixed Value to use for the key
	 */
	final public function getWithCallback(
		$key, Closure $callback, $ttl, array $cKeys = array(), array $opts = array()
	) {
		$lock = !empty( $opts['lock'] );
		$threshold = isset( $opts['ctlThreshold'] )
			? $opts['ctlThreshold']
			: min( 10, $ttl );

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

		$timeout = 10; // how long to wait on threads

		if ( $lock === 'lock' ) {
			$this->clearLastError();
			$sLock = $this->getScopedLock( $key, 0 ); // non-blocking
			if ( $value !== false ) {
				// If it cannot be acquired; then the stale value can be used
				$regen = $sLock ? true : false;
			} elseif ( $sLock ) {
				// Lock acquired; this thread should update the key
			} elseif ( $this->getLastError() ) {
				// Lock failed due to error; this thread should update the key
			} else {
				// Another thread has the lock; use the stash value,
				// which could be from that thread or some prior one
				$value = $this->getAndWaitRaw( "WanOCache:s:$key", $timeout );
				// Renerate on timeout or if the other thread failed
				$regen = ( $value === false );
			}
		}

		if ( $regen ) {
			// Generate the new value
			$value = $callback( $value );
			if ( $value !== false ) {
				// When delete() is called, writes are write-holed by the tombstone,
				// so use a special stash key to pass the new value around processes.
				if ( $lock === 'lock' ) {
					$this->cache->set( "WanOCache:s:$key", $value, $timeout + 2 );
				}
				// Update the cached value (which will fail if tombstoned)
				$this->set( $key, $value, $ttl );
			}
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
	 * Get a key, waiting for it to exist
	 *
	 * This ignores any key tombstoning and simply returns the raw value
	 *
	 * @param string $key
	 * @param int $timeout Lock wait timeout
	 * @return mixed Key value or false on failure
	 */
	protected function getAndWaitRaw( $key, $timeout ) {
		$this->clearLastError();
		$timestamp = microtime( true ); // starting UNIX timestamp

		$value = $this->cache->get( $key );
		if ( $value !== false ) {
			return $value; // key exists or is purged
		} elseif ( $this->getLastError() ) {
			return false;
		}

		$uRTT = ceil( 1e6 * ( microtime( true ) - $timestamp ) ); // estimate RTT (us)
		$sleep = 2 * $uRTT; // rough time to do get()+set()

		$attempts = 0; // failed attempts
		do {
			if ( ++$attempts >= 3 && $sleep <= 1e6 ) {
				// Exponentially back off after failed attempts to avoid network spam.
				// About 2*$uRTT*(2^n-1) us of "sleep" happen for the next n attempts.
				$sleep *= 2;
			}
			usleep( $sleep ); // back off
			$this->clearLastError();
			$value = $this->cache->get( $key );
			if ( $this->getLastError() ) {
				return false;
			}
		} while ( $value === false && ( microtime( true ) - $timestamp ) < $timeout );

		return $value;
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
