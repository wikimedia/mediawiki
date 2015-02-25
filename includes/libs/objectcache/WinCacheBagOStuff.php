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
	 * @param string $key Cache key
	 * @param int $casToken [optional] Cas token
	 * @return mixed
	 */
	public function get( $key, &$casToken = null ) {
		$val = wincache_ucache_get( $key );

		$casToken = $val;

		if ( is_string( $val ) ) {
			$val = unserialize( $val );
		}

		return $val;
	}

	/**
	 * Store a value in the WinCache object cache
	 *
	 * @param string $key Cache key
	 * @param mixed $value Value to store
	 * @param int $expire Expiration time
	 * @return bool
	 */
	public function set( $key, $value, $expire = 0 ) {
		$result = wincache_ucache_set( $key, serialize( $value ), $expire );

		/* wincache_ucache_set returns an empty array on success if $value
		   was an array, bool otherwise */
		return ( is_array( $result ) && $result === array() ) || $result;
	}

	/**
	 * Store a value in the WinCache object cache, race condition-safe
	 *
	 * @param int $casToken Cas token
	 * @param string $key Cache key
	 * @param int $value Object to store
	 * @param int $exptime Expiration time
	 * @return bool
	 */
	protected function cas( $casToken, $key, $value, $exptime = 0 ) {
		return wincache_ucache_cas( $key, $casToken, serialize( $value ) );
	}

	/**
	 * Remove a value from the WinCache object cache
	 *
	 * @param string $key Cache key
	 * @return bool
	 */
	public function delete( $key ) {
		wincache_ucache_delete( $key );

		return true;
	}

	public function merge( $key, $callback, $exptime = 0, $attempts = 10 ) {
		if ( !is_callable( $callback ) ) {
			throw new Exception( "Got invalid callback." );
		}

		return $this->mergeViaCas( $key, $callback, $exptime, $attempts );
	}
}
