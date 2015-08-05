<?php
/**
 * Object caching using PHP's APC accelerator.
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
 * This is a wrapper for APC's shared memory functions
 *
 * @ingroup Cache
 */
class APCBagOStuff extends BagOStuff {
	/**
	 * @var string String to append to each APC key. This may be changed
	 *  whenever the handling of values is changed, to prevent existing code
	 *  from encountering older values which it cannot handle.
	 **/
	const KEY_SUFFIX = ':1';

	public function get( $key, &$casToken = null, $flags = 0 ) {
		$val = apc_fetch( $key . self::KEY_SUFFIX );

		$casToken = $val;

		return $val;
	}

	public function set( $key, $value, $exptime = 0 ) {
		apc_store( $key . self::KEY_SUFFIX, $value, $exptime );

		return true;
	}

	public function delete( $key ) {
		apc_delete( $key . self::KEY_SUFFIX );

		return true;
	}

	public function incr( $key, $value = 1 ) {
		return apc_inc( $key . self::KEY_SUFFIX, $value );
	}

	public function decr( $key, $value = 1 ) {
		return apc_dec( $key . self::KEY_SUFFIX, $value );
	}
}
