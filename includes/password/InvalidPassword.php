<?php
/**
 * Implements the InvalidPassword class for the MediaWiki software.
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
 * Represents an invalid password hash. It is represented as the empty string (i.e.,
 * a password hash with no type).
 *
 * No two invalid passwords are equal. Comparing anything to an invalid password will
 * return false.
 *
 * @since 1.24
 */
class InvalidPassword extends Password {
	public function crypt( $plaintext ) {
	}

	public function toString() {
		return '';
	}

	public function equals( $other ) {
		return false;
	}

	public function verify( $password ) {
		return false;
	}

	public function needsUpdate() {
		return false;
	}
}
