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
	 * Whether the content is deflated
	 *
	 * @param string $data
	 *
	 * @return bool
	 */
	public static function isDeflated( $data ) {
		return substr( $data, 0, 11 ) === 'rawdeflate,';
	}

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
	 * @throws InvalidArgumentException If the data wasn't deflated
	 */
	public static function inflate( $data ) {
		if ( !self::isDeflated( $data ) ) {
			throw new InvalidArgumentException( 'Data does not begin with deflated prefix' );
		}
		$deflated = base64_decode( substr( $data, 11 ), true );
		if ( $deflated === false ) {
			return StatusValue::newFatal( 'easydeflate-invaliddeflate' );
		}
		Wikimedia\suppressWarnings();
		$inflated = gzinflate( $deflated );
		Wikimedia\restoreWarnings();
		if ( $inflated === false ) {
			return StatusValue::newFatal( 'easydeflate-invaliddeflate' );
		}
		return StatusValue::newGood( $inflated );
	}
}
