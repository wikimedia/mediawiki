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

use MediaWiki\MediaWikiServices;

class MWCryptRand {
	/**
	 * @return CryptRand
	 */
	protected static function singleton() {
		return MediaWikiServices::getInstance()->getCryptRand();
	}

	/**
	 * Return a boolean indicating whether or not the source used for cryptographic
	 * random bytes generation in the previously run generate* call
	 * was cryptographically strong.
	 *
	 * @return bool Returns true if the source was strong, false if not.
	 */
	public static function wasStrong() {
		return self::singleton()->wasStrong();
	}

	/**
	 * Generate a run of (ideally) cryptographically random data and return
	 * it in raw binary form.
	 * You can use MWCryptRand::wasStrong() if you wish to know if the source used
	 * was cryptographically strong.
	 *
	 * @param int $bytes The number of bytes of random data to generate
	 * @param bool $forceStrong Pass true if you want generate to prefer cryptographically
	 *                          strong sources of entropy even if reading from them may steal
	 *                          more entropy from the system than optimal.
	 * @return string Raw binary random data
	 */
	public static function generate( $bytes, $forceStrong = false ) {
		return self::singleton()->generate( $bytes, $forceStrong );
	}

	/**
	 * Generate a run of (ideally) cryptographically random data and return
	 * it in hexadecimal string format.
	 * You can use MWCryptRand::wasStrong() if you wish to know if the source used
	 * was cryptographically strong.
	 *
	 * @param int $chars The number of hex chars of random data to generate
	 * @param bool $forceStrong Pass true if you want generate to prefer cryptographically
	 *                          strong sources of entropy even if reading from them may steal
	 *                          more entropy from the system than optimal.
	 * @return string Hexadecimal random data
	 */
	public static function generateHex( $chars, $forceStrong = false ) {
		return self::singleton()->generateHex( $chars, $forceStrong );
	}
}
