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
	 * @param $key String: cache key
	 * @return mixed
	 */
	public function get( $key ) {
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
	 * @param $key String: cache key
	 * @param $value Mixed: object to store
	 * @param $expire Int: expiration time
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
	 * Remove a value from the XCache object cache
	 *
	 * @param $key String: cache key
	 * @param $time Int: not used in this implementation
	 * @return bool
	 */
	public function delete( $key, $time = 0 ) {
		xcache_unset( $key );
		return true;
	}

	public function incr( $key, $value = 1 ) {
		return xcache_inc( $key, $value );
	}

	public function decr( $key, $value = 1 ) {
		return xcache_dec( $key, $value );
	}
}
