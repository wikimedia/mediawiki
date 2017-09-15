<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 */

/**
 * Server-side helper for the easy-deflate library
 *
 * @since 1.32
 */
class EasyDeflate {

	/**
	 * For content that has been compressed with deflate in the client,
	 * try to uncompress it with inflate.
	 *
	 * If data is not prefixed with 'rawdeflate,' it will be returned unmodified.
	 *
	 * Data can be compressed in the client using the 'easy-deflate.deflate'
	 * module:
	 *
	 * @code
	 *    mw.loader.using( 'easy-deflate.deflate' ).then( function () {
	 *        var deflated = EasyDeflate.deflate( myContent );
	 *    } );
	 * @endcode
	 *
	 * @param string $data Deflated data
	 * @return StatusValue Inflated data will be set as the value
	 */
	public static function tryInflate( $data ) {
		if ( substr( $data, 0, 11 ) === 'rawdeflate,' ) {
			$deflated = base64_decode( substr( $data, 11 ) );
			Wikimedia\suppressWarnings();
			$inflated = gzinflate( $deflated );
			Wikimedia\restoreWarnings();
			if ( $deflated === $inflated || $inflated === false ) {
				return StatusValue::newFatal( 'easydeflate-invaliddeflate' );
			}
			return StatusValue::newGood( $inflated );
		}
		return StatusValue::newGood( $data );
	}
}
