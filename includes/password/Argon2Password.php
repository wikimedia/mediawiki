<?php
/**
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

use LogicException;

/**
 * Implements Argon2, a modern key derivation algorithm designed to resist GPU cracking and
 * side-channel attacks.
 *
 * @see https://en.wikipedia.org/wiki/Argon2
 */
class Argon2Password extends Password {
	/**
	 * @var null[] Array with known password_hash() option names as keys
	 */
	private const KNOWN_OPTIONS = [
		'memory_cost' => null,
		'time_cost' => null,
		'threads' => null,
	];

	/**
	 * @inheritDoc
	 */
	protected function isSupported(): bool {
		return defined( 'PASSWORD_ARGON2ID' );
	}

	/**
	 * @return mixed[] Array of 2nd and third parameters to password_hash()
	 */
	private function prepareParams(): array {
		switch ( $this->config['algo'] ) {
			case 'argon2i':
				$algo = PASSWORD_ARGON2I;
				break;
			case 'argon2id':
			case 'auto':
				$algo = PASSWORD_ARGON2ID;
				break;
			default:
				throw new LogicException( "Unexpected algo: {$this->config['algo']}" );

		}

		$params = array_intersect_key( $this->config, self::KNOWN_OPTIONS );

		return [ $algo, $params ];
	}

	/**
	 * @inheritDoc
	 */
	public function crypt( string $password ): void {
		[ $algo, $params ] = $this->prepareParams();
		$this->hash = password_hash( $password, $algo, $params );
	}

	/**
	 * @inheritDoc
	 */
	public function verify( string $password ): bool {
		return password_verify( $password, $this->hash );
	}

	/**
	 * @inheritDoc
	 */
	public function toString(): string {
		$res = ":argon2:{$this->hash}";
		$this->assertIsSafeSize( $res );
		return $res;
	}

	/**
	 * @inheritDoc
	 */
	public function needsUpdate(): bool {
		[ $algo, $params ] = $this->prepareParams();
		return password_needs_rehash( $this->hash, $algo, $params );
	}
}

/** @deprecated since 1.43 use MediaWiki\\Password\\Argon2Password */
class_alias( Argon2Password::class, 'Argon2Password' );
