<?php
/**
 * Implements a hash_pbkdf2 wrapper for php < 5.5
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
 * A PBKDF2-hash b/c wrapper
 * @since 1.27
 */

class Pbkdf2 {

	/**
	 * @param string $algo the name of the underlying hashing algorithm (e.g., 'sha256')
	 * @param string $password the password to seed the key derivation
	 * @param string $salt the binary bytes to use as the hash
	 * @param int $rounds the underlying hash name
	 * @param int $length the underlying hash name
	 */
	public static function hash( $algo, $password, $salt, $rounds, $length ) {

		$hash = '';

		if ( function_exists( 'hash_pbkdf2' ) ) {
			$hash = hash_pbkdf2(
				$algo,
				$password,
				$salt,
				(int)$rounds,
				(int)$length,
				true
			);
		} else {
			$hashLen = strlen( hash( $algo, '', true ) );
			if ( $hashLen == 0 ) {
				throw new InvalidArgumentException( 'Invalid hash algorithm' );
			}
			$blockCount = ceil( $length / $hashLen );

			for ( $i = 1; $i <= $blockCount; ++$i ) {
				$roundTotal = $lastRound = hash_hmac(
					$algo,
					$salt . pack( 'N', $i ),
					$password,
					true
				);

				for ( $j = 1; $j < $rounds; ++$j ) {
					$lastRound = hash_hmac( $algo, $lastRound, $password, true );
					$roundTotal ^= $lastRound;
				}

				$hash .= $roundTotal;
			}

			$hash = substr( $hash, 0, $length );
		}

		return $hash;
	}
}
