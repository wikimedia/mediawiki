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
	protected function doGet( $key, $flags = 0 ) {
		$casToken = null;

		return $this->getWithToken( $key, $casToken, $flags );
	}

	protected function getWithToken( $key, &$casToken, $flags = 0 ) {
		$val = wincache_ucache_get( $key );

		$casToken = $val;

		if ( is_string( $val ) ) {
			$val = unserialize( $val );
		}

		return $val;
	}

	public function set( $key, $value, $expire = 0, $flags = 0 ) {
		$result = wincache_ucache_set( $key, serialize( $value ), $expire );

		/* wincache_ucache_set returns an empty array on success if $value
		   was an array, bool otherwise */
		return ( is_array( $result ) && $result === [] ) || $result;
	}

	protected function cas( $casToken, $key, $value, $exptime = 0 ) {
		return wincache_ucache_cas( $key, $casToken, serialize( $value ) );
	}

	public function delete( $key ) {
		wincache_ucache_delete( $key );

		return true;
	}

	public function merge( $key, $callback, $exptime = 0, $attempts = 10, $flags = 0 ) {
		if ( !is_callable( $callback ) ) {
			throw new Exception( "Got invalid callback." );
		}

		return $this->mergeViaCas( $key, $callback, $exptime, $attempts );
	}
}
