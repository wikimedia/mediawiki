<?php
/**
 * Extract-and-Expand Key Derivation Function (HKDF). A cryptographicly
 * secure key expansion function based on RFC 5869.
 *
 * This relies on the secrecy of $wgSecretKey (by default), or $wgHKDFSecret.
 * By default, sha256 is used as the underlying hashing algorithm, but any other
 * algorithm can be used. Finding the secret key from the output would require
 * an attacker to discover the input key (the PRK) to the hmac that generated
 * the output, and discover the particular data, hmac'ed with an evolving key
 * (salt), to produce the PRK. Even with md5, no publicly known attacks make
 * this currently feasible.
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
 * @author Chris Steipp
 * @file
 */

use MediaWiki\MediaWikiServices;

class MWCryptHKDF {

	/**
	 * Return a singleton instance, based on the global configs.
	 * @return CryptHKDF
	 */
	protected static function singleton() {
		return MediaWikiServices::getInstance()->getCryptHKDF();
	}

	/**
	 * RFC5869 defines HKDF in 2 steps, extraction and expansion.
	 * From http://eprint.iacr.org/2010/264.pdf:
	 *
	 * The scheme HKDF is specifed as:
	 * 	HKDF(XTS, SKM, CTXinfo, L) = K(1) || K(2) || ... || K(t)
	 * where the values K(i) are defined as follows:
	 * 	PRK = HMAC(XTS, SKM)
	 * 	K(1) = HMAC(PRK, CTXinfo || 0);
	 * 	K(i+1) = HMAC(PRK, K(i) || CTXinfo || i), 1 <= i < t;
	 * where t = [L/k] and the value K(t) is truncated to its first d = L mod k bits;
	 * the counter i is non-wrapping and of a given fixed size, e.g., a single byte.
	 * Note that the length of the HMAC output is the same as its key length and therefore
	 * the scheme is well defined.
	 *
	 * XTS is the "extractor salt"
	 * SKM is the "secret keying material"
	 *
	 * N.B. http://eprint.iacr.org/2010/264.pdf seems to differ from RFC 5869 in that the test
	 * vectors from RFC 5869 only work if K(0) = '' and K(1) = HMAC(PRK, K(0) || CTXinfo || 1)
	 *
	 * @param string $hash The hashing function to use (e.g., sha256)
	 * @param string $ikm The input keying material
	 * @param string $salt The salt to add to the ikm, to get the prk
	 * @param string $info Optional context (change the output without affecting
	 *	the randomness properties of the output)
	 * @param int $L Number of bytes to return
	 * @return string Cryptographically secure pseudorandom binary string
	 */
	public static function HKDF( $hash, $ikm, $salt, $info, $L ) {
		return CryptHKDF::HKDF( $hash, $ikm, $salt, $info, $L );
	}

	/**
	 * Generate cryptographically random data and return it in raw binary form.
	 *
	 * @param int $bytes The number of bytes of random data to generate
	 * @param string $context String to mix into HMAC context
	 * @return string Binary string of length $bytes
	 */
	public static function generate( $bytes, $context ) {
		return self::singleton()->generate( $bytes, $context );
	}

	/**
	 * Generate cryptographically random data and return it in hexadecimal string format.
	 * See MWCryptRand::realGenerateHex for details of the char-to-byte conversion logic.
	 *
	 * @param int $chars The number of hex chars of random data to generate
	 * @param string $context String to mix into HMAC context
	 * @return string Random hex characters, $chars long
	 */
	public static function generateHex( $chars, $context = '' ) {
		$bytes = ceil( $chars / 2 );
		$hex = bin2hex( self::singleton()->generate( $bytes, $context ) );
		return substr( $hex, 0, $chars );
	}

}
