<?php
/**
 * Implements the Pbkdf2PasswordUsingHashExtension class for the MediaWiki software.
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
 * A PBKDF2-hashed password, using PHP's hash extension
 *
 * This class exists for compatibility purposes only! Unless an installation's
 * existing password hashes were generated using an algorithm not supported by
 * OpenSSL, Pbkdf2PasswordUsingOpenSSL should be used.
 *
 * @since 1.40 (since 1.29 under the old name)
 */
class Pbkdf2PasswordUsingHashExtension extends AbstractPbkdf2Password {
	protected function getDigestAlgo( string $algo ): ?string {
		return in_array( $algo, hash_hmac_algos(), true ) ? $algo : null;
	}

	protected function pbkdf2(
		string $digestAlgo,
		string $password,
		string $salt,
		int $rounds,
		int $length
	): string {
		return hash_pbkdf2( $digestAlgo, $password, $salt, $rounds, $length, true );
	}
}

/** @deprecated class alias since 1.40; use MediaWiki\\Password\\Pbkdf2PasswordUsingHashExtension */
class_alias( Pbkdf2PasswordUsingHashExtension::class, 'Pbkdf2Password' );

/** @deprecated since 1.43 use MediaWiki\\Password\\Pbkdf2PasswordUsingHashExtension */
class_alias( Pbkdf2PasswordUsingHashExtension::class, 'Pbkdf2PasswordUsingHashExtension' );
