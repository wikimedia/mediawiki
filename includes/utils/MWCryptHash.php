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
	 * Singleton instance for public use
	 */
	protected static $singleton = null;

	/**
	 * The hash algorithm being used
	 */
	protected $algo = null;

	/**
	 * The number of bytes outputted by the hash algorithm
	 */
	protected $hashLength = array(
		true => null,
		false => null,
	);

	/**
	 * @see self::hashAlgo()
	 */
	public function realHashAlgo() {
		if ( !is_null( $this->algo ) ) {
			return $this->algo;
		}

		$algos = hash_algos();
		$preference = array( 'whirlpool', 'sha256', 'sha1', 'md5' );

		foreach ( $preference as $algorithm ) {
			if ( in_array( $algorithm, $algos ) ) {
				$this->algo = $algorithm;
				wfDebug( __METHOD__ . ": Using the {$this->algo} hash algorithm.\n" );

				return $this->algo;
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
	 * @see self::hashLength()
	 */
	public function realHashLength( $raw = true ) {
		$raw = (bool)$raw;
		if ( is_null( $this->hashLength[$raw] ) ) {
			$this->hashLength[$raw] = strlen( $this->realHash( '', $raw ) );
		}

		return $this->hashLength[$raw];
	}

	/**
	 * @see self::hash()
	 */
	public function realHash( $data, $raw = true ) {
		return hash( $this->realHashAlgo(), $data, $raw );
	}

	/**
	 * @see self::hmac()
	 */
	public function realHmac( $data, $key, $raw = true ) {
		return hash_hmac( $this->realHashAlgo(), $data, $key, $raw );
	}

	/** Publicly exposed static methods **/

	/**
	 * Return a singleton instance of MWCryptHash
	 * @return MWCryptHash
	 */
	protected static function singleton() {
		if ( is_null( self::$singleton ) ) {
			self::$singleton = new self;
		}

		return self::$singleton;
	}

	/**
	 * Decide on the best acceptable hash algorithm we have available for hash()
	 * @return string A hash algorithm
	 */
	public static function hashAlgo() {
		return self::singleton()->realHashAlgo();
	}

	/**
	 * Return the byte-length output of the hash algorithm we are
	 * using in self::hash and self::hmac.
	 *
	 * @param boolean $raw True to return the length for binary data, false to
	 *   return for hex-encoded
	 * @return int Number of bytes the hash outputs
	 */
	public static function hashLength( $raw = true ) {
		return self::singleton()->realHashLength( $raw );
	}

	/**
	 * Generate an acceptably unstable one-way-hash of some text
	 * making use of the best hash algorithm that we have available.
	 *
	 * @param string $data
	 * @param boolean $raw True to return binary data, false to return it hex-encoded
	 * @return string A hash of the data
	 */
	public static function hash( $data, $raw = true ) {
		return self::singleton()->realHash( $data, $raw );
	}

	/**
	 * Generate an acceptably unstable one-way-hmac of some text
	 * making use of the best hash algorithm that we have available.
	 *
	 * @param string $data
	 * @param string $key
	 * @param boolean $raw True to return binary data, false to return it hex-encoded
	 * @return string An hmac hash of the data + key
	 */
	public static function hmac( $data, $key, $raw = true ) {
		return self::singleton()->realHmac( $data, $key, $raw );
	}
}
