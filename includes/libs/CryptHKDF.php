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

class CryptHKDF {

	/**
	 * @var BagOStuff The persistent cache
	 */
	protected $cache = null;

	/**
	 * @var string Cache key we'll use for our salt
	 */
	protected $cacheKey = null;

	/**
	 * @var string The hash algorithm being used
	 */
	protected $algorithm = null;

	/**
	 * @var string binary string, the salt for the HKDF
	 * @see getSaltUsingCache
	 */
	protected $salt = '';

	/**
	 * @var string The pseudorandom key
	 */
	private $prk = '';

	/**
	 * The secret key material. This must be kept secret to preserve
	 * the security properties of this RNG.
	 *
	 * @var string
	 */
	private $skm;

	/**
	 * @var string The last block (K(i)) of the most recent expanded key
	 */
	protected $lastK;

	/**
	 * a "context information" string CTXinfo (which may be null)
	 * See http://eprint.iacr.org/2010/264.pdf Section 4.1
	 *
	 * @var array
	 */
	protected $context = [];

	/**
	 * Round count is computed based on the hash'es output length,
	 * which neither php nor openssl seem to provide easily.
	 *
	 * @var int[]
	 */
	public static $hashLength = [
		'md5' => 16,
		'sha1' => 20,
		'sha224' => 28,
		'sha256' => 32,
		'sha384' => 48,
		'sha512' => 64,
		'ripemd128' => 16,
		'ripemd160' => 20,
		'ripemd256' => 32,
		'ripemd320' => 40,
		'whirlpool' => 64,
	];

	/**
	 * @var CryptRand
	 */
	private $cryptRand;

	/**
	 * @param string $secretKeyMaterial
	 * @param string $algorithm Name of hashing algorithm
	 * @param BagOStuff $cache
	 * @param string|array $context Context to mix into HKDF context
	 * @param CryptRand $cryptRand
	 * @throws InvalidArgumentException if secret key material is too short
	 */
	public function __construct( $secretKeyMaterial, $algorithm, BagOStuff $cache, $context,
		CryptRand $cryptRand
	) {
		if ( strlen( $secretKeyMaterial ) < 16 ) {
			throw new InvalidArgumentException( "secret was too short." );
		}
		$this->skm = $secretKeyMaterial;
		$this->algorithm = $algorithm;
		$this->cache = $cache;
		$this->context = is_array( $context ) ? $context : [ $context ];
		$this->cryptRand = $cryptRand;

		// To prevent every call from hitting the same memcache server, pick
		// from a set of keys to use. mt_rand is only use to pick a random
		// server, and does not affect the security of the process.
		$this->cacheKey = $cache->makeKey( 'HKDF', mt_rand( 0, 16 ) );
	}

	/**
	 * Save the last block generated, so the next user will compute a different PRK
	 * from the same SKM. This should keep things unpredictable even if an attacker
	 * is able to influence CTXinfo.
	 */
	function __destruct() {
		if ( $this->lastK ) {
			$this->cache->set( $this->cacheKey, $this->lastK );
		}
	}

	/**
	 * MW specific salt, cached from last run
	 * @return string Binary string
	 */
	protected function getSaltUsingCache() {
		if ( $this->salt == '' ) {
			$lastSalt = $this->cache->get( $this->cacheKey );
			if ( $lastSalt === false ) {
				// If we don't have a previous value to use as our salt, we use
				// 16 bytes from CryptRand, which will use a small amount of
				// entropy from our pool. Note, "XTR may be deterministic or keyed
				// via an optional “salt value”  (i.e., a non-secret random
				// value)..." - http://eprint.iacr.org/2010/264.pdf. However, we
				// use a strongly random value since we can.
				$lastSalt = $this->cryptRand->generate( 16 );
			}
			// Get a binary string that is hashLen long
			$this->salt = hash( $this->algorithm, $lastSalt, true );
		}
		return $this->salt;
	}

	/**
	 * Produce $bytes of secure random data. As a side-effect,
	 * $this->lastK is set to the last hashLen block of key material.
	 *
	 * @param int $bytes Number of bytes of data
	 * @param string $context Context to mix into CTXinfo
	 * @return string Binary string of length $bytes
	 */
	public function generate( $bytes, $context = '' ) {
		if ( $this->prk === '' ) {
			$salt = $this->getSaltUsingCache();
			$this->prk = self::HKDFExtract(
				$this->algorithm,
				$salt,
				$this->skm
			);
		}

		$CTXinfo = implode( ':', array_merge( $this->context, [ $context ] ) );

		return self::HKDFExpand(
			$this->algorithm,
			$this->prk,
			$CTXinfo,
			$bytes,
			$this->lastK
		);
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
		$prk = self::HKDFExtract( $hash, $salt, $ikm );
		$okm = self::HKDFExpand( $hash, $prk, $info, $L );
		return $okm;
	}

	/**
	 * Extract the PRK, PRK = HMAC(XTS, SKM)
	 * Note that the hmac is keyed with XTS (the salt),
	 * and the SKM (source key material) is the "data".
	 *
	 * @param string $hash The hashing function to use (e.g., sha256)
	 * @param string $salt The salt to add to the ikm, to get the prk
	 * @param string $ikm The input keying material
	 * @return string Binary string (pseudorandm key) used as input to HKDFExpand
	 */
	private static function HKDFExtract( $hash, $salt, $ikm ) {
		return hash_hmac( $hash, $ikm, $salt, true );
	}

	/**
	 * Expand the key with the given context
	 *
	 * @param string $hash Hashing Algorithm
	 * @param string $prk A pseudorandom key of at least HashLen octets
	 *    (usually, the output from the extract step)
	 * @param string $info Optional context and application specific information
	 *    (can be a zero-length string)
	 * @param int $bytes Length of output keying material in bytes
	 *    (<= 255*HashLen)
	 * @param string &$lastK Set by this function to the last block of the expansion.
	 *    In MediaWiki, this is used to seed future Extractions.
	 * @return string Cryptographically secure random string $bytes long
	 * @throws InvalidArgumentException
	 */
	private static function HKDFExpand( $hash, $prk, $info, $bytes, &$lastK = '' ) {
		$hashLen = self::$hashLength[$hash];
		$rounds = ceil( $bytes / $hashLen );
		$output = '';

		if ( $bytes > 255 * $hashLen ) {
			throw new InvalidArgumentException( 'Too many bytes requested from HDKFExpand' );
		}

		// K(1) = HMAC(PRK, CTXinfo || 1);
		// K(i) = HMAC(PRK, K(i-1) || CTXinfo || i); 1 < i <= t;
		for ( $counter = 1; $counter <= $rounds; ++$counter ) {
			$lastK = hash_hmac(
				$hash,
				$lastK . $info . chr( $counter ),
				$prk,
				true
			);
			$output .= $lastK;
		}

		return substr( $output, 0, $bytes );
	}
}
