<?php
/**
 * Object caching using XCache.
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
 * @ingroup Cache
 */

/**
 * Wrapper for XCache object caching functions; identical interface
 * to the APC wrapper
 *
 * @ingroup Cache
 */
class XCacheBagOStuff extends BagOStuff {
	/**
	 * Get a value from the XCache object cache
	 *
	 * @param string $key Cache key
	 * @param mixed $casToken Cas token
	 * @return mixed
	 */
	public function get( $key, &$casToken = null ) {
		$val = xcache_get( $key );

		if ( is_string( $val ) ) {
			if ( $this->isInteger( $val ) ) {
				$val = intval( $val );
			} else {
				$val = unserialize( $val );
			}
		} elseif ( is_null( $val ) ) {
			return false;
		}

		return $val;
	}

	/**
	 * Store a value in the XCache object cache
	 *
	 * @param string $key Cache key
	 * @param mixed $value Object to store
	 * @param int $expire Expiration time
	 * @return bool
	 */
	public function set( $key, $value, $expire = 0 ) {
		if ( !$this->isInteger( $value ) ) {
			$value = serialize( $value );
		}

		xcache_set( $key, $value, $expire );
		return true;
	}

	/**
	 * @param mixed $casToken
	 * @param string $key
	 * @param mixed $value
	 * @param int $exptime
	 * @return bool
	 */
	public function cas( $casToken, $key, $value, $exptime = 0 ) {
		// Can't find any documentation on xcache cas
		throw new MWException( "CAS is not implemented in " . __CLASS__ );
	}

	/**
	 * Remove a value from the XCache object cache
	 *
	 * @param string $key Cache key
	 * @param int $time Not used in this implementation
	 * @return bool
	 */
	public function delete( $key, $time = 0 ) {
		xcache_unset( $key );
		return true;
	}

	/**
	 * Merge an item.
	 * XCache does not seem to support any way of performing CAS - this however will
	 * provide a way to perform CAS-like functionality.
	 *
	 * @param string $key
	 * @param Closure $callback Callback method to be executed
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @param int $attempts The amount of times to attempt a merge in case of failure
	 * @return bool Success
	 */
	public function merge( $key, Closure $callback, $exptime = 0, $attempts = 10 ) {
		return $this->mergeViaLock( $key, $callback, $exptime, $attempts );
	}

	public function incr( $key, $value = 1 ) {
		return xcache_inc( $key, $value );
	}

	public function decr( $key, $value = 1 ) {
		return xcache_dec( $key, $value );
	}
}
