<?php
/**
 * Implements the MWOldPassword class for the MediaWiki software.
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
 * The old style of MediaWiki password hashing. It involves
 * running MD5 on the password.
 *
 * @since 1.24
 */
class MWOldPassword extends ParameterizedPassword {
	protected function getDefaultParams() {
		return [];
	}

	protected function getDelimiter() {
		return ':';
	}

	public function crypt( $plaintext ) {
		global $wgPasswordSalt;

		if ( $wgPasswordSalt && count( $this->args ) === 1 ) {
			$this->hash = md5( $this->args[0] . '-' . md5( $plaintext ) );
		} else {
			$this->args = [];
			$this->hash = md5( $plaintext );
		}

		if ( !is_string( $this->hash ) || strlen( $this->hash ) < 32 ) {
			throw new PasswordError( 'Error when hashing password.' );
		}
	}
}
