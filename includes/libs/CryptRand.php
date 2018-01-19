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
use Psr\Log\LoggerInterface;

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
	 * @var LoggerInterface
	 */
	protected $logger;

	public function __construct( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Initialize an initial random state based off of whatever we can find
	 *
	 * @deprecated since 1.32, unused and does nothing
	 *
	 * @return string
	 */
	protected function initialRandomState() {
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
		return '';
	}

	/**
	 * Return a boolean indicating whether or not the source used for cryptographic
	 * random bytes generation in the previously run generate* call
	 * was cryptographically strong.
	 *
	 * @deprecated since 1.32, always returns true
	 *
	 * @return bool Returns true if the source was strong, false if not.
	 */
	public function wasStrong() {
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
		$bytes = floor( $bytes );
		return random_bytes( $bytes );
	}

	/**
	 * Generate a run of cryptographically random data and return
	 * it in hexadecimal string format.
	 * You can use CryptRand::wasStrong() if you wish to know if the source used
	 * was cryptographically strong.
	 *
	 * @param int $chars The number of hex chars of random data to generate
	 * @return string Hexadecimal random data
	 */
	public function generateHex( $chars ) {
		// hex strings are 2x the length of raw binary so we divide the length in half
		// odd numbers will result in a .5 that leads the generate() being 1 character
		// short, so we use ceil() to ensure that we always have enough bytes
		$bytes = ceil( $chars / 2 );
		// Generate the data and then convert it to a hex string
		$hex = bin2hex( $this->generate( $bytes ) );

		// A bit of paranoia here, the caller asked for a specific length of string
		// here, and it's possible (eg when given an odd number) that we may actually
		// have at least 1 char more than they asked for. Just in case they made this
		// call intending to insert it into a database that does truncation we don't
		// want to give them too much and end up with their database and their live
		// code having two different values because part of what we gave them is truncated
		// hence, we strip out any run of characters longer than what we were asked for.
		return substr( $hex, 0, $chars );
	}
}
