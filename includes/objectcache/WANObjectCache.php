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
 * All writes and reads go to the local cluster cache, though a special purge()
 * method can broadcast purges to all clusters (updating the local one immediatly).
 * The class must be configured to point to a valid PubSub endpoint, and there must
 * be listeners on the cache servers that subscribe to it and update the caches.
 *
 * All values are internally stored in small arrays, which will always be serialized
 * in storage. This allows for raw string values to have special meanings, and they
 * can easily be set by other languages and daemons (such as purgers).
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
	 * @throws Exception
	 */
	public function __construct( array $params ) {
		$this->pool = $params['pool'];
		$this->cache = $params['cache'];
		$this->relayer = $params['relayer'];
	}

	/**
	 * @param string $key
	 * @param mixed $casToken [optional]
	 * @return mixed Returns false on failure
	 */
	final public function get( $key, &$casToken = null ) {
		$wrapped = $this->cache->get( $key, $casToken );

		return $this->unwrapInternal( $wrapped );
	}

	/**
	 * Get an associative array containing the item for each of the keys that have items
	 *
	 * @param array $keys List of strings
	 * @return array
	 */
	final public function getMulti( array $keys ) {
		$result = array();

		foreach ( $this->cache->getMulti( $keys ) as $key => $wrapped ) {
			$val = $this->unwrapInternal( $wrapped );
			if ( $val !== false ) {
				$result[$key] = $val;
			}
		}

		return $result;
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @param integer $ttl Seconds to live [0=forever]
	 * @return bool Success
	 */
	final public function set( $key, $value, $ttl = 0 ) {
		$that = $this;
		$func = function ( BagOStuff $cache, $key, $cValue ) use ( $that, $value, $ttl ) {
			// Do nothing if the key is currently salted
			return ( $cValue === 'PURGED' ) ? false : $that->wrapInternal( $value, $ttl );
		};

		return $this->cache->merge( $key, $func, $ttl, 1 );
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @param integer $ttl Seconds to live [0=forever]
	 * @return bool Success
	 */
	final public function add( $key, $value, $ttl = 0 ) {
		return $this->cache->add( $key, $this->wrapInternal( $value, $ttl ), $ttl );
	}

	/**
	 * @param string $key Key to increase
	 * @param integer $value Value to add to $key (Default 1)
	 * @return int|bool New value or false on failure
	 */
	final public function incr( $key, $value = 1 ) {
		$that = $this;
		$func = function ( BagOStuff $cache, $key, $cWrapped ) use ( $that, $value ) {
			if ( $that->unwrapInternal( $cWrapped ) === false ) {
				return false; // do nothing (key is not there or is salted)
			} elseif ( !is_int( $cWrapped[WANObjectCache::FLD_VAL] ) ) {
				return false; // wrong type
			}
			$cWrapped[WANObjectCache::FLD_VAL] += $value;
			$cWrapped[WANObjectCache::FLD_VAL] = max( 0, $cWrapped[WANObjectCache::FLD_VAL] );

			return $cWrapped;
		};

		// @note: TTL is enforced client side
		return $this->cache->merge( $key, $func, 0 );
	}

	/**
	 * @param string $key Key to decrease
	 * @param integer $value Value to subtract from $key (Default 1)
	 * @return int|bool New value or false on failure
	 */
	final public function decr( $key, $value = 1 ) {
		return $this->incr( $key, - $value );
	}

	/**
	 * Increase stored value of $key by $value while preserving its TTL
	 *
	 * This will create the key with value $init and TTL $ttl if not present
	 *
	 * @param string $key
	 * @param integer $ttl Seconds to live [0=forever]
	 * @param integer $value
	 * @param integer $init
	 * @return bool
	 */
	final public function incrWithInit( $key, $ttl, $value = 1, $init = 1 ) {
		$that = $this;
		$func = function ( BagOStuff $cache, $key, $cWrapped ) use ( $that, $value, $init, $ttl ) {
			if ( $cWrapped === 'PURGED' ) {
				return false; // do nothing (key is salted)
			}
			if ( $that->unwrapInternal( $cWrapped ) === false ) {
				// Create the key if it does not exist
				return $that->wrapInternal( $init, $ttl );
			} elseif ( is_int( $cWrapped[WANObjectCache::FLD_VAL] ) ) {
				// Update the key if it does exist
				$cWrapped[WANObjectCache::FLD_VAL] += $value;
				$cWrapped[WANObjectCache::FLD_VAL] = max( 0, $cWrapped[WANObjectCache::FLD_VAL] );
				return $cWrapped;
			} else {
				return false; // wrong type
			}
		};

		// @note: TTL is enforced client side
		return $this->cache->merge( $key, $func, 0 );
	}

	/**
	 * Merge changes into the existing cache value (possibly creating a new one).
	 * The callback function returns the new value given the current value (possibly false),
	 * and takes the arguments: (this BagOStuff object, cache key, current value).
	 *
	 * @param string $key
	 * @param Closure $callback Callback method to be executed
	 * @param integer $ttl Either an interval in seconds or a unix timestamp for expiry
	 * @param integer $attempts The amount of times to attempt a merge in case of failure
	 * @return bool Success
	 */
	final public function merge( $key, Closure $callback, $ttl = 0, $attempts = 10 ) {
		$that = $this;
		$wrapper = function ( BagOStuff $cache, $key, $cWrapped ) use ( $that, $callback, $ttl ) {
			if ( $cWrapped === 'PURGED' ) {
				return false; // do nothing (key is salted)
			}
			$cValue = $that->unwrapInternal( $cWrapped );
			$newVal = $callback( $cache, $key, $cValue );
			if ( $newVal === false ) {
				return false; // do nothing
			}
			return $that->wrapInternal( $newVal, $ttl );
		};

		return $this->cache->merge( $key, $wrapper, $ttl, $attempts );
	}

	/**
	 * @param string $key
	 * @return bool True if the item was deleted or not found, false on failure
	 */
	final public function delete( $key ) {
		// Get rid of the key while maintaining purge() TTL semantics
		$func = function ( BagOStuff $cache, $key, $cValue ) {
			return ( $cValue === 'PURGED' )
				? false // do nothing (key is salted)
				: 'PURGED';
		};

		// @note: the salt key will quickly expire after 1 second
		return $this->cache->merge( $key, $func, 1, 1 );
	}

	/**
	 * Purge a key from all clusters, instaiting a hold-off period where the
	 * key cannot be written to (avoiding stale update races) for a given TTL
	 *
	 * This is the only non-local operation and should only be called when the
	 * underlying data (being cached) changes in a significant way.
	 *
	 * @param string $key
	 * @param integer $ttl How long to block writes to the key [seconds]
	 * @return bool True if the item was purged or not found, false on failure
	 */
	final public function purge( $key, $ttl = self::HOLDOFF_TTL ) {
		// Support immediate consistency in the local cluster
		$ok = $this->cache->set( $key, 'PURGED', $ttl );
		// Publish the purge to all clusters...
		$ok = $this->relayer->notify(
			"{$this->pool}:purge",
			json_encode( array(
				'cmd' => 'set',
				'key' => $key,
				'val' => 'PURGED',
				'flg' => 0,
				'ttl' => $ttl
			) )
		) && $ok;

		return $ok;
	}

	/**
	 * Acquire a cooperative cluster-local lock on a cache key
	 *
	 * @param string $key
	 * @param integer $timeout Lock wait timeout [optional]
	 * @param integer $expiry Lock expiry [optional]
	 * @return bool Success
	 */
	final public function lock( $key, $timeout = 6, $expiry = 6 ) {
		return $this->cache->lock( $key, $timeout, $expiry );
	}

	/**
	 * Release a cooperative cluster-local lock on a cache key
	 *
	 * @param string $key
	 * @return bool Success
	 */
	final public function unlock( $key ) {
		return $this->cache->unlock( $key );
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
	 * @param array|bool $wrapped
	 * @return mixed
	 */
	public function unwrapInternal( $wrapped ) {
		if ( !is_array( $wrapped )
			|| !isset( $wrapped[self::FLD_VER] ) // wrong format
			|| $wrapped[self::FLD_VER] !== self::VERSION // wrong version
			|| ( microtime( true ) - $wrapped[self::FLD_TME] ) > $wrapped[self::FLD_TTL] // stale
		) {
			return false;
		}

		return $wrapped[self::FLD_VAL];
	}
}
