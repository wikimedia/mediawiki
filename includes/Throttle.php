<?php

/**
 * Implements the Throttle class for the MediaWiki software.
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
 */

/**
 * A value object that keeps a temporary incrementing counter in
 * the cache for use in throttles throughout MediaWiki.
 *
 * Keeps track - using the object cache - of how many times an
 * action has been performed
 *
 * @since 1.22
 */
class Throttle extends ContextSource {
	/**
	 * Flag that limits the throttle to per-user, meaning
	 * one user's actions will not throttle another user's actions.
	 */
	const PER_USER = 1;

	/**
	 * Flags that limts the throttle to per-IP address, meaning
	 * one computer's actions will not throttle another's.
	 */
	const PER_IP = 2;

	/**
	 * Flag that makes a throttle apply across all wikis using
	 * the same cache, rather than just this wiki.
	 *
	 * @warning This won't work with non-central caches, e.g., EmptyBagOStuff
	 */
	const GLOBAL_KEY = 4;

	/**
	 * Whether to reset the throttle on success.
	 */
	const RESET_ON_SUCCESS = 8;

	/**
	 * Array of identifying data for this throttle.
	 * @var array
	 */
	protected $id = array();

	/**
	 * Memcached key used to store the throttle.
	 * @var string
	 */
	private $cacheKey = null;

	/**
	 * Limit to the throttle; once incremented at least
	 * this many times, it will be throttled.
	 * @var int
	 */
	protected $limit;

	/**
	 * Time in seconds that the throttle will expire.
	 * @var int
	 */
	protected $expiry;

	/**
	 * Flags set by caller to change the way the throttle acts.
	 * @var int
	 */
	protected $flags;

	/**
	 * The current value of the throttle.
	 * @var int
	 */
	private $value = null;

	/**
	 * The current timestamp of the expiry.
	 * @var MWTimestamp
	 */
	private $currentExpiry = false;

	/**
	 * Cache to be used
	 * @var BagOStuff
	 */
	private $cache;

	/**
	 * Store the identifying data and properties of the throttle.
	 *
	 * @param array $id Identifying data for the throttle
	 * @param int $limit Number at which the throttle kicks in
	 * @param int $expiry Expiry of the throttle in seconds
	 * @param int $flags Flags affecting behavior of the throttle
	 * @param BagOStuff $cache Optional cache to use in place of the main one
	 */
	public function __construct( array $id, $limit, $expiry, $flags = 0, BagOStuff $cache = null ) {
		$this->id = $id;
		$this->limit = $limit;
		$this->expiry = $expiry;
		$this->flags = $flags;
		$this->cache = $cache ?: wfGetMainCache();
	}

	/**
	 * Create a memcached key for the throttle (or return a cached one).
	 *
	 * Make a key consisting of a default 'throttled' prefix, the identifying
	 * data given to the constructor, and the user ID and/or IP address if
	 * applicable.
	 *
	 * @param string $sub Sub-key to get (for storing extra info)
	 * @return string
	 */
	protected function getCacheKey( $sub = '' ) {
		if ( $this->cacheKey === null ) {
			$cacheKey = $this->id;
			array_unshift( $cacheKey, 'throttle' );

			// Add the applicable identifying information.
			if ( $this->flags & self::PER_USER ) {
				$cacheKey[] = $this->getUser()->getId();
			}
			if ( $this->flags & self::PER_IP ) {
				$cacheKey[] = $this->getRequest()->getIP();
			}

			// If global, avoid adding the cache prefix for this wiki.
			if ( $this->flags & self::GLOBAL_KEY ) {
				// Add $db and $prefix args onto argument stack.
				array_unshift( $cacheKey, 'global', null );
				$this->cacheKey = call_user_func_array( 'wfForeignMemcKey', $cacheKey );
			} else {
				$this->cacheKey = call_user_func_array( 'wfMemcKey', $cacheKey );
			}
		}

		return "{$this->cacheKey}:{$sub}";
	}

	/**
	 * Get the current expiration for the throttle.
	 *
	 * @return MWTimestamp|null
	 */
	public function getCurrentExpiry() {
		if ( $this->currentExpiry === false ) {
			$this->currentExpiry = $this->cache->get( $this->getCacheKey( 'expiry' ) );
		}
		return $this->currentExpiry ?: null;
	}

	/**
	 * Increment the value of the throttle.
	 */
	public function increment() {
		$memcKey = $this->getCacheKey();
		$this->value = $this->cache->incr( $memcKey );

		// Set to 1 if nothing was in the cache.
		if ( $this->value === false ) {
			$this->value = 1;
			$this->currentExpiry = new MWTimestamp( time() + $this->expiry );
			$this->cache->set( $memcKey, $this->value, $this->expiry );
			$this->cache->set( $this->getCacheKey( 'expiry' ), $this->currentExpiry, $this->expiry );
		}

		return $this->throttled();
	}

	/**
	 * Immediately put the throttle past it's limit.
	 */
	public function throttleNow() {
		// First increment to set up the expiry.
		$this->increment();
		// Now go past the limit.
		$this->value = $this->limit;
		$this->cache->set( $this->getCacheKey(), $this->limit );
	}

	/**
	 * Reset the throttle count to 0.
	 */
	public function clear() {
		$this->cache->delete( $this->getCacheKey() );
		$this->cache->delete( $this->getCacheKey( 'expiry' ) );
		$this->value = 0;
		$this->currentExpiry = null;
	}

	/**
	 * Convenience function that will clear the throttle
	 * only if it is configured to reset on success.
	 */
	public function success() {
		if ( $this->flags & self::RESET_ON_SUCCESS ) {
			$this->clear();
		}
	}

	/**
	 * Check whether the throttle limit has been exceeded.
	 *
	 * @return bool True if throttled, false otherwise
	 */
	public function throttled() {
		if ( $this->value === null ) {
			$this->value = (int)$this->cache->get( $this->getCacheKey() );
		}
		return $this->value >= $this->limit;
	}
}
