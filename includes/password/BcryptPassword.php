<?php
/**
 * Implements the BcryptPassword class for the MediaWiki software.
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

/**
 * A Bcrypt-hashed password
 *
 * This is a computationally complex password hash for use in modern applications.
 * The number of rounds can be configured by $wgPasswordConfig['bcrypt']['cost'].
 *
 * @since 1.24
 */
class BcryptPassword extends ParameterizedPassword {
	protected function getDefaultParams() {
		return [
			'rounds' => $this->config['cost'],
		];
	}

	protected function getDelimiter() {
		return '$';
	}

	protected function parseHash( $hash ) {
		parent::parseHash( $hash );

		$this->params['rounds'] = (int)$this->params['rounds'];
	}

	/**
	 * @param string $password Password to encrypt
	 *
	 * @throws PasswordError If bcrypt has an unknown error
	 * @throws MWException If bcrypt is not supported by PHP
	 */
	public function crypt( $password ) {
		if ( !defined( 'CRYPT_BLOWFISH' ) ) {
			throw new MWException( 'Bcrypt is not supported.' );
		}

		// Either use existing hash or make a new salt
		// Bcrypt expects 22 characters of base64-encoded salt
		// Note: bcrypt does not use MIME base64. It uses its own base64 without any '=' padding.
		//       It expects a 128 bit salt, so it will ignore anything after the first 128 bits
		if ( !isset( $this->args[0] ) ) {
			$this->args[] = substr(
				// Replace + with ., because bcrypt uses a non-MIME base64 format
				strtr(
					// Random base64 encoded string
					base64_encode( MWCryptRand::generate( 16, true ) ),
					'+', '.'
				),
				0, 22
			);
		}

		$hash = crypt( $password,
			sprintf( '$2y$%02d$%s', (int)$this->params['rounds'], $this->args[0] ) );

		if ( !is_string( $hash ) || strlen( $hash ) <= 13 ) {
			throw new PasswordError( 'Error when hashing password.' );
		}

		// Strip the $2y$
		$parts = explode( $this->getDelimiter(), substr( $hash, 4 ) );
		$this->params['rounds'] = (int)$parts[0];
		$this->args[0] = substr( $parts[1], 0, 22 );
		$this->hash = substr( $parts[1], 22 );
	}
}
