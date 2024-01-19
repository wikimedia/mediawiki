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
 *   and is used by default when possible.
 * - Pbkdf2PasswordUsingHashExtension provides compatibility with PBKDF2
 *   password hashes computed using legacy algorithms, as well as PHP
 *   installations lacking OpenSSL support.
 *
 * @since 1.40
 */
abstract class AbstractPbkdf2Password extends ParameterizedPassword {
	/**
	 * Create a new AbstractPbkdf2Password object.
	 *
	 * In the default configuration, this is used as a factory function
	 * in order to select a PBKDF2 implementation automatically.
	 *
	 * @internal
	 * @see Password::__construct
	 * @param PasswordFactory $factory Factory object that created the password
	 * @param array $config Array of engine configuration options for hashing
	 * @param string|null $hash The raw hash, including the type
	 * @return AbstractPbkdf2Password The created object
	 */
	public static function newInstance(
		PasswordFactory $factory,
		array $config,
		string $hash = null
	): self {
		if ( isset( $config['class'] ) && is_subclass_of( $config['class'], self::class ) ) {
			// Use the configured subclass
			return new $config['class']( $factory, $config, $hash );
		} elseif ( self::canUseOpenSSL() ) {
			return new Pbkdf2PasswordUsingOpenSSL( $factory, $config, $hash );
		} else {
			return new Pbkdf2PasswordUsingHashExtension( $factory, $config, $hash );
		}
	}

	/**
	 * Check if OpenSSL can be used for computing PBKDF2 password hashes.
	 *
	 * @return bool
	 */
	protected static function canUseOpenSSL(): bool {
		// OpenSSL 1.0.1f (released 2014-01-06) is the earliest version supported by
		// PHP 7.1 through 8.0 that hashes the HMAC key blocks only once rather than
		// on each iteration. Once support for these PHP versions is dropped, the
		// version check can be removed. (PHP 8.1 requires OpenSSL >= 1.0.2)
		return function_exists( 'openssl_pbkdf2' ) && OPENSSL_VERSION_NUMBER >= 0x1000106f;
	}

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
