<?php
/**
 * Utility functions for generating hashes
 *
 * This is based in part on Drupal code as well as what we used in our own code
 * prior to introduction of this class, by way of MWCryptRand.
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

class MWCryptHash {
	/**
	 * The hash algorithm being used
	 */
	protected static ?string $algo = null;

	/**
	 * The number of bytes outputted by the hash algorithm
	 */
	protected static int $hashLength;

	/**
	 * Decide on the best acceptable hash algorithm we have available for hash()
	 * @return string A hash algorithm
	 */
	public static function hashAlgo() {
		$algorithm = self::$algo;
		if ( $algorithm !== null ) {
			return $algorithm;
		}

		$algos = hash_hmac_algos();
		$preference = [ 'whirlpool', 'sha256' ];

		foreach ( $preference as $algorithm ) {
			if ( in_array( $algorithm, $algos, true ) ) {
				self::$algo = $algorithm;
				return $algorithm;
			}
		}

		throw new DomainException( 'Could not find an acceptable hashing function.' );
	}

	/**
	 * Return the byte-length output of the hash algorithm we are
	 * using in self::hash and self::hmac.
	 *
	 * @param bool $raw True to return the length for binary data, false to
	 *   return for hex-encoded
	 * @return int Number of bytes the hash outputs
	 */
	public static function hashLength( $raw = true ) {
		self::$hashLength ??= strlen( self::hash( '', true ) );
		// Optimisation: Skip computing the length of non-raw hashes.
		// The algos in hashAlgo() all produce a digest that is a multiple
		// of 8 bits, where hex is always twice the length of binary byte length.
		return $raw ? self::$hashLength : self::$hashLength * 2;
	}

	/**
	 * Generate a cryptographic hash value (message digest) for a string,
	 * making use of the best hash algorithm that we have available.
	 *
	 * @param string $data
	 * @param bool $raw True to return binary data, false to return it hex-encoded
	 * @return string A hash of the data
	 */
	public static function hash( $data, $raw = true ) {
		return hash( self::hashAlgo(), $data, $raw );
	}

	/**
	 * Generate a keyed cryptographic hash value (HMAC) for a string,
	 * making use of the best hash algorithm that we have available.
	 *
	 * @param string $data
	 * @param string $key
	 * @param bool $raw True to return binary data, false to return it hex-encoded
	 * @return string An HMAC hash of the data + key
	 */
	public static function hmac( $data, $key, $raw = true ) {
		if ( !is_string( $key ) ) {
			// hash_hmac tolerates non-string (would return null with warning)
			throw new InvalidArgumentException( 'Invalid key type: ' . get_debug_type( $key ) );
		}
		return hash_hmac( self::hashAlgo(), $data, $key, $raw );
	}

}
