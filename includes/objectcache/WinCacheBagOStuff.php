<?php
/**
 * Object caching using WinCache.
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
 * Wrapper for WinCache object caching functions; identical interface
 * to the APC wrapper
 *
 * @ingroup Cache
 */
class WinCacheBagOStuff extends BagOStuff {

	/**
	 * Get a value from the WinCache object cache
	 *
	 * @param $key String: cache key
	 * @return mixed
	 */
	public function get( $key ) {
		$val = wincache_ucache_get( $key );

		if ( is_string( $val ) ) {
			$val = unserialize( $val );
		}

		return $val;
	}

	/**
	 * Store a value in the WinCache object cache
	 *
	 * @param $key String: cache key
	 * @param $value Mixed: object to store
	 * @param $expire Int: expiration time
	 * @return bool
	 */
	public function set( $key, $value, $expire = 0 ) {
		$result = wincache_ucache_set( $key, serialize( $value ), $expire );

		/* wincache_ucache_set returns an empty array on success if $value
		   was an array, bool otherwise */
		return ( is_array( $result ) && $result === array() ) || $result;
	}

	/**
	 * Remove a value from the WinCache object cache
	 *
	 * @param $key String: cache key
	 * @param $time Int: not used in this implementation
	 * @return bool
	 */
	public function delete( $key, $time = 0 ) {
		wincache_ucache_delete( $key );

		return true;
	}

	/**
	 * @return Array
	 */
	public function keys() {
		$info = wincache_ucache_info();
		$list = $info['ucache_entries'];
		$keys = array();

		if ( is_null( $list ) ) {
			return array();
		}

		foreach ( $list as $entry ) {
			$keys[] = $entry['key_name'];
		}

		return $keys;
	}
}
