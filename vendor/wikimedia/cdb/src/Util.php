<?php

namespace Cdb;

/**
 * This is a port of D.J. Bernstein's CDB to PHP. It's based on the copy that
 * appears in PHP 5.3. Changes are:
 *    * Error returns replaced with exceptions
 *    * Exception thrown if sizes or offsets are between 2GB and 4GB
 *    * Some variables renamed
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
 * Common functions for readers and writers
 */
class Util {
	/**
	 * Take a modulo of a signed integer as if it were an unsigned integer.
	 * $b must be less than 0x40000000 and greater than 0
	 *
	 * @param int $a
	 * @param int $b
	 *
	 * @return int
	 */
	public static function unsignedMod( $a, $b ) {
		if ( $a & 0x80000000 ) {
			$m = ( $a & 0x7fffffff ) % $b + 2 * ( 0x40000000 % $b );

			return $m % $b;
		} else {
			return $a % $b;
		}
	}

	/**
	 * Shift a signed integer right as if it were unsigned
	 * @param int $a
	 * @param int $b
	 * @return int
	 */
	public static function unsignedShiftRight( $a, $b ) {
		if ( $b == 0 ) {
			return $a;
		}
		if ( $a & 0x80000000 ) {
			return ( ( $a & 0x7fffffff ) >> $b ) | ( 0x40000000 >> ( $b - 1 ) );
		} else {
			return $a >> $b;
		}
	}

	/**
	 * The CDB hash function.
	 *
	 * @param string $s
	 *
	 * @return int
	 */
	public static function hash( $s ) {
		$h = 5381;
		$len = strlen( $s );
		for ( $i = 0; $i < $len; $i++ ) {
			$h5 = ( $h << 5 ) & 0xffffffff;
			// Do a 32-bit sum
			// Inlined here for speed
			$sum = ( $h & 0x3fffffff ) + ( $h5 & 0x3fffffff );
			$h =
				(
					( $sum & 0x40000000 ? 1 : 0 )
					+ ( $h & 0x80000000 ? 2 : 0 )
					+ ( $h & 0x40000000 ? 1 : 0 )
					+ ( $h5 & 0x80000000 ? 2 : 0 )
					+ ( $h5 & 0x40000000 ? 1 : 0 )
				) << 30
				| ( $sum & 0x3fffffff );
			$h ^= ord( $s[$i] );
			$h &= 0xffffffff;
		}

		return $h;
	}
}
