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
 * @ingroup Cache
 * @since 1.25
 */
class WANObjectCache {
	/** @var string Cache pool name */
	protected $pool;
	/** @var BagOStuff The local cluster cache */
	protected $cache;
	/** @var PubSubPublisher */
	protected $publisher;

	/** @var integer Seconds to block cache updates for purge values */
	const HOLDOFF_TTL = 15;

	/**
	 * @param array $params
	 * @throws Exception
	 */
	public function __construct( array $params ) {
		$this->pool = $params['pool'];
		$this->cache = $params['cache'];
		$this->publisher = $params['publisher'];
	}

	/**
	 * @param string $key
	 * @param mixed $casToken [optional]
	 * @return mixed Returns false on failure
	 */
	final public function get( $key, &$casToken = null ) {
		$value = $this->cache->get( $key, $casToken );

		return ( $value instanceof PurgedHoldOffValue ) ? false : $value;
	}

	/**
	 * Get an associative array containing the item for each of the keys that have items
	 *
	 * @param array $keys List of strings
	 * @return array
	 */
	final public function getMulti( array $keys ) {
		return array_filter(
			$this->cache->getMulti( $keys ),
			function ( $v ) { return !( $v instanceof PurgedHoldOffValue ); }
		);
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @return bool Success
	 */
	final public function set( $key, $value, $exptime = 0 ) {
		$callback = function ( BagOStuff $cache, $key, $cValue ) use ( $value ) {
			return ( $cValue instanceof PurgedHoldOffValue )
				? false // do nothing (cache is salted)
				: $value;
		};

		return $this->cache->merge( $key, $callback, $exptime, 1 );
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @param int $exptime
	 * @return bool Success
	 */
	final public function add( $key, $value, $exptime = 0 ) {
		return $this->cache->add( $key, $value, $exptime );
	}

	/**
	 * @param string $key Key to increase
	 * @param int $value Value to add to $key (Default 1)
	 * @return int|bool New value or false on failure
	 */
	final public function incr( $key, $value = 1 ) {
		// Memcached/Redis fail when incrementing PurgedHoldOffValue values.
		// This is desired behavoir, but adds log noise, so cut down on it.
		return ( $this->cache->get( $key ) instanceof PurgedHoldOffValue )
			? false // key is salted; avoid errors
			: $this->cache->incr( $key, $value );
	}

	/**
	 * @param string $key Key to decrease
	 * @param int $value Value to subtract from $key (Default 1)
	 * @return int|bool New value or false on failure
	 */
	final public function decr( $key, $value = 1 ) {
		// Memcached/Redis fail when incrementing PurgedHoldOffValue values.
		// This is desired behavoir, but adds log noise, so cut down on it.
		return ( $this->cache->get( $key ) instanceof PurgedHoldOffValue )
			? false // key is salted; avoid errors
			: $this->cache->decr( $key, $value );
	}

	/**
	 * Increase stored value of $key by $value while preserving its TTL
	 *
	 * This will create the key with value $init and TTL $ttl if not present
	 *
	 * @param string $key
	 * @param int $ttl
	 * @param int $value
	 * @param int $init
	 * @return bool
	 */
	final public function incrWithInit( $key, $ttl, $value = 1, $init = 1 ) {
		// Memcached/Redis fail when incrementing PurgedHoldOffValue values.
		// This is desired behavoir, but adds log noise, so cut down on it.
		return ( $this->cache->get( $key ) instanceof PurgedHoldOffValue )
			? false // key is salted; avoid errors
			: $this->cache->incrWithInit( $key, $ttl, $value, $init );
	}

	/**
	 * Merge changes into the existing cache value (possibly creating a new one).
	 * The callback function returns the new value given the current value (possibly false),
	 * and takes the arguments: (this BagOStuff object, cache key, current value).
	 *
	 * @param string $key
	 * @param Closure $callback Callback method to be executed
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @param int $attempts The amount of times to attempt a merge in case of failure
	 * @return bool Success
	 */
	final public function merge( $key, Closure $callback, $exptime = 0, $attempts = 10 ) {
		$wrapper = function ( BagOStuff $cache, $key, $cValue ) use ( $callback ) {
			return ( $cValue instanceof PurgedHoldOffValue )
				? false // do nothing (cache is salted)
				: $callback( $cache, $key, $cValue );
		};

		return $this->cache->merge( $key, $wrapper, $exptime, $attempts );
	}

	/**
	 * @param string $key
	 * @return bool True if the item was deleted or not found, false on failure
	 */
	final public function delete( $key ) {
		$value = new PurgedHoldOffValue;
		// Get rid of the key while maintaining purge() TTL semantics
		$callback = function ( BagOStuff $cache, $key, $cValue ) use ( $value ) {
			return ( $cValue instanceof PurgedHoldOffValue )
				? false // do nothing (cache is salted)
				: $value;
		};

		return $this->cache->merge( $key, $callback, 1, 1 );
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
		$value = new PurgedHoldOffValue;
		// Support immediate consistency in the local cluster
		$ok = $this->cache->set( $key, $value, $ttl );
		// Publish the purge to all clusters...
		try {
			$this->publisher->publish(
				"{$this->pool}:purge",
				json_encode( array(
					'cmd'   => 'set',
					'key'   => $key,
					'value' => serialize( $value ),
					'ttl'   => $ttl
				) )
			);
		} catch ( PubSubException $e ) {
			$ok = false;
		}

		return $ok;
	}

	/**
	 * Acquire a cooperative cluster-local lock on a cache key
	 *
	 * @param string $key
	 * @param int $timeout Lock wait timeout [optional]
	 * @param int $expiry Lock expiry [optional]
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
}

class PurgedHoldOffValue {}
