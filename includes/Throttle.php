<?php

/**
 * Implements the Throttle class for the %MediaWiki software.
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
 * A throttle that can be used with various MediaWiki actions.
 * Keeps track of how many times an action has been performed
 * using the object cache.
 *
 * @since 1.21
 */
class Throttle extends ContextSource {
	/**
	 * Flag that limits the throttle to per-user, meaning
	 * one user's actions will not throttle another's.
	 */
	const PERUSER = 1;

	/**
	 * Flags that limts the throttle to per-IP address, meaning
	 * one computer's actions will not throttle another's.
	 */
	const PERIP = 2;

	/**
	 * Array of identifying data for this throttle.
	 */
	protected $id = array();

	/**
	 * Memcached key used to store the throttle.
	 */
	protected $cacheKey = null;

	/**
	 * Limit to the throttle; once incremented at least
	 * this many times, it will be throttled.
	 */
	protected $limit;

	/**
	 * Time in seconds that the throttle will expire.
	 */
	protected $expiry;

	/**
	 * Flags set by caller to change the way the throttle acts.
	 */
	protected $flags;

	/**
	 * The current value of the throttle.
	 */
	protected $value = 0;

	/**
	 * Store the identifying data and properties of the throttle.
	 *
	 * @param array $id Identifying data for the throttle
	 * @param int $limit Number at which the throttle kicks in
	 * @param int $expry Expiry of the throttle in seconds
	 * @param int $flags Flags affecting behavior of the throttle
	 */
	public function __construct( array $id, $limit, $expiry, $flags = 0 ) {
		$this->id = array_merge( $this->id, $id );
		$this->limit = $limit;
		$this->expiry = $expiry;
		$this->flags = $flags;
	}

	/**
	 * Create a memcached key for the throttle (or return a cached one).
	 *
	 * Make a key consisting of a default 'throttled' prefix, the identifying
	 * data given to the constructor, and the user ID and/or IP address if
	 * applicable.
	 *
	 * @return string
	 */
	public function getCacheKey() {
		if( $this->cacheKey === null ) {
			$cacheKey = $this->id;
			array_unshift( $cacheKey, 'throttle' );

			if( $this->flags & self::PERUSER ) {
				$cacheKey[] = $this->getUser()->getId();
			}
			if( $this->flags & self::PERIP ) {
				$cacheKey[] = $this->getRequest()->getIP();
			}

			$this->cacheKey = wfMemcKey( $cacheKey );
		}

		return $this->cacheKey;
	}

	/**
	 * Increment the value of the throttle.
	 */
	public function increment() {
		global $wgMemc;
		$memcKey = $this->getCacheKey();
		$value = $wgMemc->get( $memcKey );

		if( $value === false ) {
			$this->value++;
			$wgMemc->set( $memcKey, $this->value, $this->expiry );
		} else {
			$this->value = $wgMemc->incr( $memcKey );
		}
	}

	/**
	 * Reset the throttle count to 0.
	 */
	public function clear() {
		global $wgMemc;
		$wgMemc->delete( $this->getCacheKey() );
		$this->value = 0;
	}

	/**
	 * Check whether the throttle limit has been exceeded.
	 *
	 * @return bool True if throttled, false otherwise
	 */
	public function throttled() {
		return $this->value >= $this->limit;
	}

	/**
	 * Shortcut for incrementing the throttle and checking if throttled
	 *
	 * @return bool True if throttled, false otherwise
	 */
	public function __invoke() {
		$this->increment();
		return $this->throttled();
	}
}
