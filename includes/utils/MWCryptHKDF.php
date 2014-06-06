<?php
/**
 * Extract-and-Expand Key Derivation Function (HKDF). A cryptographicly
 * secure key expansion function based on RFC 5869.
 *
 * This relies on the secrecy of $wgSecretKey (by default).
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

class MWCryptHKDF {

	/**
	 * Singleton instance for public use
	 */
	protected static $singleton = null;

	/**
	 * The persistant cache
	 */
	protected $cache = null;

	/**
	 * Cache key we'll use for our salt
	 */
	protected $cacheKey = null;

	/**
	 * The hash algorithm being used
	 */
	protected $algorithm = null;

	/**
	 * binary string, the salt for the HKDF
	 */
	protected $salt;

	/**
	 * The pseudorandom key
	 */
	private $prk;

	/**
	 * The secret key material. This must be kept secret to preserve
	 * the security properties of this RNG.
	 */
	private $skm;

	/**
	 * The last block (K(i)) of the most recent expanded key
	 */
	protected $lastK;

	/**
	 * a "context information" string CTXinfo (which may be null)
	 * See http://eprint.iacr.org/2010/264.pdf Section 4.1
	 */
	protected $context = array();

	/**
	 * Round count is computed based on the hash'es output length,
	 * which neither php nor openssl seem to provide easily.
	 */
	public static $hashLength = array(
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
	);


	/**
	 * @param string $hash Name of hashing algorithm
	 * @param BagOStuff $cache
	 * @param string|array $context to mix into HKDF context
	 */
	public function __construct( $secretKeyMaterial, $algorithm, $cache, $context ) {
		if ( strlen( $secretKeyMaterial ) < 16 ) {
			throw new MWException( "MWCryptHKDF secret was too short." );
		}
		$this->skm = $secretKeyMaterial;
		$this->algorithm = $algorithm;
		$this->cache = $cache;
		$this->salt = ''; // Initialize a blank salt, see getSaltUsingCache()
		$this->prk = '';
		$this->context = is_array( $context ) ? $context : array( $context );

		// To prevent every call from hitting the same memcache server, pick
		// from a set of keys to use. mt_rand is only use to pick a random
		// server, and does not affect the security of the process.
		$this->cacheKey = wfMemcKey( 'HKDF', mt_rand( 0, 16 ) );
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
	 * @return string binary string
	 */
	protected function getSaltUsingCache() {
		if ( $this->salt == '' ) {
			$lastSalt = $this->cache->get( $this->cacheKey );
			if ( $lastSalt === false ) {
				// If we don't have a previous value to use as our salt, we use
				// the cryptographically insecure mt_rand. Note, "XTR may be
				// deterministic or keyed via an optional “salt value”  (i.e., a
				// non-secret random value)..." - http://eprint.iacr.org/2010/264.pdf
				$lastSalt = mt_rand();
			}
			// Get a binary string that is hashLen long
			$this->salt = hash( $this->algorithm, $lastSalt, true );
		}
		return $this->salt;
	}

	/**
	 * Setup PRK using supplied salt and the defined secret (a.k.a,
	 * HKDF-Extract( salt, IKM ). Cache the resulting PRK.
	 * @param string $salt the optional, possibly known to attacker, expansion salt
	 */
	protected function setupPRK( $salt ) {
		if ( $this->prk === '' ) {
			$this->prk = self::HKDFExtract(
				$this->algorithm,
				$salt,
				$this->skm
			);
		}
	}

	/**
	 * Return a singleton instance, based on the global configs.
	 * @param string $context to mix into HMAC
	 * @return HKDF
	 */
	protected static function singleton( $context ) {
		global $wgHKDFAlgorithm, $wgHKDFSecret, $wgSecretKey;

		$secret = $wgHKDFSecret ?: $wgSecretKey;
		if ( !$secret ) {
			throw new MWException( "Cannot use MWCryptHKDF without a secret." );
		}

		// In HKDF, the context can be known to the attacker, but this will
		// keep simultaneous runs from producing the same output.
		$context = array( $context );
		$context[] = microtime();
		$context[] = getmypid();
		$context[] = gethostname();

		// Setup salt cache. Default to main, but use APC, etc if available
		$cache = wfGetMainCache();
		try {
			$cache = ObjectCache::newAccelerator( array() );
		} catch ( Exception $e ) {
		}

		if ( is_null( self::$singleton ) ) {
			self::$singleton = new self( $secret, $wgHKDFAlgorithm, $cache, $context );
		}

		return self::$singleton;
	}

	/**
	 * Produce $bytes of secure random data. As a side-effect,
	 * $this->lastK is set to the last hashLen block of key material.
	 * @param int $bytes number of bytes of data
	 * @return binary string of length $bytes
	 */
	protected function realGenerate( $bytes ) {
		$initSalt = $this->getSaltUsingCache();
		$this->setupPRK( $initSalt );
		$CTXinfo = implode( ':', $this->context );

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
	 * @param string $hash the hashing function to use (e.g., sha256)
	 * @param string $ikm the input keying material
	 * @param string $salt the salt to add to the ikm, to get the prk
	 * @param string $info optional context (change the output without affecting
	 *	the randomness properties of the output)
	 * @param integer $L number of bytes to return
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
	 * @param string $hash the hashing function to use (e.g., sha256)
	 * @param string $ikm the input keying material
	 * @param string $salt the salt to add to the ikm, to get the prk
	 */
	private static function HKDFExtract( $hash, $salt, $ikm ) {
		return hash_hmac( $hash, $ikm, $salt, true );
	}

	/**
	 * Expand the key with the given context
	 *
	 * @param $hash Hashing Algorithm
	 * @param $prk a pseudorandom key of at least HashLen octets
         * 	(usually, the output from the extract step)
	 * @param $info optional context and application specific information
         * 	(can be a zero-length string)
	 * @param $bytes length of output keying material in bytes
         * 	(<= 255*HashLen)
	 * @param &$lastK set by this function to the last block of the expansion.
	 *	In MediaWiki, this is used to seed future Extractions.
	 */
	private static function HKDFExpand( $hash, $prk, $info, $bytes, &$lastK = '' ) {
		$hashLen = MWCryptHKDF::$hashLength[$hash];
		$rounds = ceil( $bytes / $hashLen );
		$output = '';

		if ( $bytes > 255 * $hashLen ) {
			throw new MWException( "Too many bytes requested from HDKFExpand" );
		}

		// K(1) = HMAC(PRK, CTXinfo || 1);
		// K(i) = HMAC(PRK, K(i-1) || CTXinfo || i); 1 < i <= t;
		for ( $counter = 1; $counter <= $rounds; ++$counter ) {
			$lastK = hash_hmac(
				$hash,
				$lastK . $info . chr($counter),
				$prk,
				true
			);
			$output .= $lastK;
		}

		return substr( $output, 0, $bytes );
	}

	/**
	 * Generate cryptographically random data and return it in raw binary form.
	 *
	 * @param int $bytes the number of bytes of random data to generate
	 * @param string $context string to mix into HMAC context
	 * @return String Raw binary random data
	 */
	public static function generate( $bytes, $context ) {
		return self::singleton( $context )->realGenerate( $bytes );
	}

	/**
	 * Generate cryptographically random data and return it in hexadecimal string format.
	 * See MWCryptRand::realGenerateHex for details of the char-to-byte conversion logic.
	 *
	 * @param int $chars the number of hex chars of random data to generate
	 * @param string $context string to mix into HMAC context
	 * @return String Hexadecimal random data
	 */
	public static function generateHex( $chars, $context = '' ) {
		$bytes = ceil( $chars / 2 );
		$hex = bin2hex( self::singleton( $context )->realGenerate( $bytes ) );
		return substr( $hex, 0, $chars );
	}

}
