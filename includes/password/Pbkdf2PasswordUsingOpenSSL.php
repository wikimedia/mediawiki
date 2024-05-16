<?php
/**
 * Implements the Pbkdf2PasswordUsingOpenSSL class for the MediaWiki software.
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

declare( strict_types = 1 );

namespace MediaWiki\Password;

/**
 * A PBKDF2-hashed password, using OpenSSL
 *
 * @since 1.40
 */
class Pbkdf2PasswordUsingOpenSSL extends AbstractPbkdf2Password {
	/**
	 * @var array<string, string>
	 */
	private static $digestAlgos;

	/**
	 * List of hash algorithms we support and OpenSSL's names for them.
	 *
	 * We include only the algorithms that make sense to support, rather than
	 * all potentially available algorithms. In particular, we do not include:
	 *
	 * - Broken algorithms, such as "md5" and "sha1"
	 * - Algorithms no longer available by default, such as "whirlpool"
	 * - Algorithms that perform especially poorly on server CPUs relative
	 *   to other available hardware (as of 2022, this includes "sha3-512";
	 *   see <https://keccak.team/2017/is_sha3_slow.html>)
	 * - Variants for which there is no reason for use, such as "sha384"
	 *   (a truncated "sha512" that starts with a different initial state)
	 *
	 * The array keys should match the algorithm names known to hash_pbkdf2().
	 */
	private const DIGEST_ALGOS = [
		'sha256' => 'sha256',
		'sha512' => 'sha512',
	];

	protected function isSupported(): bool {
		return self::canUseOpenSSL();
	}

	protected function getDigestAlgo( string $algo ): ?string {
		if ( !isset( self::$digestAlgos ) ) {
			self::$digestAlgos = array_intersect( self::DIGEST_ALGOS, openssl_get_md_methods() );
		}
		return self::$digestAlgos[$algo] ?? null;
	}

	protected function pbkdf2(
		string $digestAlgo,
		string $password,
		string $salt,
		int $rounds,
		int $length
	): string {
		// Clear error string
		while ( openssl_error_string() !== false );
		$hash = openssl_pbkdf2( $password, $salt, $length, $rounds, $digestAlgo );
		if ( !is_string( $hash ) ) {
			throw new PasswordError( 'Error when hashing password: ' . openssl_error_string() );
		}
		return $hash;
	}
}

/** @deprecated since 1.43 use MediaWiki\\Password\\Pbkdf2PasswordUsingOpenSSL */
class_alias( Pbkdf2PasswordUsingOpenSSL::class, 'Pbkdf2PasswordUsingOpenSSL' );
