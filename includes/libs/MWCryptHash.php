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
	protected static $algo = null;

	/**
	 * The number of bytes outputted by the hash algorithm
	 */
	protected static $hashLength = [
		true => null,
		false => null,
	];

	/**
	 * Decide on the best acceptable hash algorithm we have available for hash()
	 * @return string A hash algorithm
	 */
	public static function hashAlgo() {
		if ( self::$algo !== null ) {
			return self::$algo;
		}

		$algos = hash_algos();
		$preference = [ 'whirlpool', 'sha256', 'sha1', 'md5' ];

		foreach ( $preference as $algorithm ) {
			if ( in_array( $algorithm, $algos ) ) {
				self::$algo = $algorithm;

				return self::$algo;
			}
		}

		// We only reach here if no acceptable hash is found in the list, this should
		// be a technical impossibility since most of php's hash list is fixed and
		// some of the ones we list are available as their own native functions
		// But since we already require at least 5.2 and hash() was default in
		// 5.1.2 we don't bother falling back to methods like sha1 and md5.
		throw new DomainException( "Could not find an acceptable hashing function in hash_algos()" );
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
		$raw = (bool)$raw;
		if ( self::$hashLength[$raw] === null ) {
			self::$hashLength[$raw] = strlen( self::hash( '', $raw ) );
		}

		return self::$hashLength[$raw];
	}

	/**
	 * Generate an acceptably unstable one-way-hash of some text
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
	 * Generate an acceptably unstable one-way-hmac of some text
	 * making use of the best hash algorithm that we have available.
	 *
	 * @param string $data
	 * @param string $key
	 * @param bool $raw True to return binary data, false to return it hex-encoded
	 * @return string An hmac hash of the data + key
	 */
	public static function hmac( $data, $key, $raw = true ) {
		if ( !is_string( $key ) ) {
			// hash_hmac tolerates non-string (would return null with warning)
			throw new InvalidArgumentException( 'Invalid key type: ' . gettype( $key ) );
		}
		return hash_hmac( self::hashAlgo(), $data, $key, $raw );
	}

}
