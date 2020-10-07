<?php
/**
 * Implements the MWSaltedPassword class for the MediaWiki software.
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
 * The old style of MediaWiki password hashing, with a salt. It involves
 * running MD5 on the password, and then running MD5 on the salt concatenated
 * with the first hash.
 *
 * @since 1.24
 */
class MWSaltedPassword extends ParameterizedPassword {
	protected function getDefaultParams() : array {
		return [];
	}

	protected function getDelimiter() : string {
		return ':';
	}

	public function crypt( string $plaintext ) : void {
		if ( count( $this->args ) == 0 ) {
			$this->args[] = MWCryptRand::generateHex( 8 );
		}

		$this->hash = md5( $this->args[0] . '-' . md5( $plaintext ) );

		if ( !is_string( $this->hash ) || strlen( $this->hash ) < 32 ) {
			throw new PasswordError( 'Error when hashing password.' );
		}
	}
}
