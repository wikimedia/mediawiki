<?php
/**
 * Implements the AbstractPbkdf2Password class for the MediaWiki software.
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
 * A PBKDF2-hashed password
 *
 * This is a computationally complex password hash for use in modern applications.
 * The number of rounds can be configured by $wgPasswordConfig['pbkdf2']['cost'].
 *
 * To support different native implementations of PBKDF2 and the underlying
 * hash algorithms, the following subclasses are available:
 *
 * - Pbkdf2PasswordUsingOpenSSL is the preferred, more efficient option
 *   and is used by default.
 * - Pbkdf2PasswordUsingHashExtension provides compatibility with PBKDF2
 *   password hashes computed using legacy algorithms.
 *
 * @since 1.40
 */
abstract class AbstractPbkdf2Password extends ParameterizedPassword {
	protected function getDefaultParams(): array {
		return [
			'algo' => $this->config['algo'],
			'rounds' => $this->config['cost'],
			'length' => $this->config['length']
		];
	}

	protected function getDelimiter(): string {
		return ':';
	}

	public function crypt( string $password ): void {
		if ( count( $this->args ) == 0 ) {
			$this->args[] = base64_encode( random_bytes( 16 ) );
		}

		$algo = $this->params['algo'];
		$salt = base64_decode( $this->args[0] );
		$rounds = (int)$this->params['rounds'];
		$length = (int)$this->params['length'];

		$digestAlgo = $this->getDigestAlgo( $algo );
		if ( $digestAlgo === null ) {
			throw new PasswordError( "Unknown or unsupported algo: $algo" );
		}
		if ( $rounds <= 0 || $rounds >= 0x7fffffff ) {
			throw new PasswordError( 'Invalid number of rounds.' );
		}
		if ( $length <= 0 || $length >= 0x7fffffff ) {
			throw new PasswordError( 'Invalid length.' );
		}

		$hash = $this->pbkdf2( $digestAlgo, $password, $salt, $rounds, $length );
		$this->hash = base64_encode( $hash );
	}

	/**
	 * Get the implementation specific name for a hash algorithm.
	 *
	 * @param string $algo Algorithm specified in the password hash string
	 * @return string|null $algo Implementation specific name, or null if unsupported
	 */
	abstract protected function getDigestAlgo( string $algo ): ?string;

	/**
	 * Call the PBKDF2 implementation, which hashes the password.
	 *
	 * @param string $digestAlgo Implementation specific hash algorithm name
	 * @param string $password Password to hash
	 * @param string $salt Salt as a binary string
	 * @param int $rounds Number of iterations
	 * @param int $length Length of the hash value in bytes
	 * @return string Hash value as a binary string
	 * @throws PasswordError If an internal error occurs in hashing
	 */
	abstract protected function pbkdf2(
		string $digestAlgo,
		string $password,
		string $salt,
		int $rounds,
		int $length
	): string;
}

/** @deprecated since 1.43 use MediaWiki\\Password\\AbstractPbkdf2Password */
class_alias( AbstractPbkdf2Password::class, 'AbstractPbkdf2Password' );
