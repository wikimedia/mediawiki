<?php
/**
 * A cryptographic random generator class used for generating secret keys
 *
 * This is based in part on Drupal code as well as what we used in our own code
 * prior to introduction of this class.
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
 * @author Daniel Friesen
 * @file
 */

/**
 * @deprecated since 1.32, use random_bytes()/random_int()
 */
class CryptRand {
	/**
	 * @deprecated since 1.32, unused
	 */
	const MIN_ITERATIONS = 1000;

	/**
	 * @deprecated since 1.32, unused
	 */
	const MSEC_PER_BYTE = 0.5;

	/**
	 * Initialize an initial random state based off of whatever we can find
	 *
	 * @deprecated since 1.32, unused and does nothing
	 *
	 * @return string
	 */
	protected function initialRandomState() {
		wfDeprecated( __METHOD__, '1.32' );
		return '';
	}

	/**
	 * Randomly hash data while mixing in clock drift data for randomness
	 *
	 * @deprecated since 1.32, unused and does nothing
	 *
	 * @param string $data The data to randomly hash.
	 * @return string The hashed bytes
	 * @author Tim Starling
	 */
	protected function driftHash( $data ) {
		wfDeprecated( __METHOD__, '1.32' );
		return '';
	}

	/**
	 * Return a rolling random state initially build using data from unstable sources
	 *
	 * @deprecated since 1.32, unused and does nothing
	 *
	 * @return string A new weak random state
	 */
	protected function randomState() {
		wfDeprecated( __METHOD__, '1.32' );
		return '';
	}

	/**
	 * Return a boolean indicating whether or not the source used for cryptographic
	 * random bytes generation in the previously run generate* call
	 * was cryptographically strong.
	 *
	 * @deprecated since 1.32, always returns true
	 *
	 * @return bool Always true
	 */
	public function wasStrong() {
		wfDeprecated( __METHOD__, '1.32' );
		return true;
	}

	/**
	 * Generate a run of cryptographically random data and return
	 * it in raw binary form.
	 * You can use CryptRand::wasStrong() if you wish to know if the source used
	 * was cryptographically strong.
	 *
	 * @param int $bytes The number of bytes of random data to generate
	 * @return string Raw binary random data
	 */
	public function generate( $bytes ) {
		wfDeprecated( __METHOD__, '1.32' );
		$bytes = floor( $bytes );
		return random_bytes( $bytes );
	}

	/**
	 * Generate a run of cryptographically random data and return
	 * it in hexadecimal string format.
	 *
	 * @param int $chars The number of hex chars of random data to generate
	 * @return string Hexadecimal random data
	 */
	public function generateHex( $chars ) {
		wfDeprecated( __METHOD__, '1.32' );
		return MWCryptRand::generateHex( $chars );
	}
}
