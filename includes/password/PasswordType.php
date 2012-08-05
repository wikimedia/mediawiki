<?php
/**
 * PasswordType interface
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
 * @author Daniel Friesen <mediawiki@danielfriesen.name>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @ingroup Password
 */

/**
 * The basic PasswordType interface
 * Defines the methods that are required of a PasswordType class implementation
 * @ingroup Password
 * @since 1.20
 */
interface PasswordType {

	/**
	 * Return the name of the PasswordType
	 * The password system will use this at the start of the data we store in the database
	 * eg: if getName returns 'A' the data will take the format ':A:...'.
	 *
	 * @return string The type name.
	 */
	public function getName();

	/**
	 * Create password output data to be stored in the database given a user's plaintext password.
	 *
	 * @param $password The plaintext password
	 */
	public function crypt( $password );

	/**
	 * Compare the password output db form of a password with a plaintext password to see if the
	 * password is correct.
	 *
	 * @param $data string The password data. Same format as outputted by crypt()
	 * @param $password The plaintext password
	 * @return Status A Status object;
	 *         - Good with a value of true for a password match
	 *         - Good with a value of false for a bad password
	 *         - Fatal if the password data was badly formed or there was some issue with
	 *           comparing the passwords which is not the user's fault.
	 */
	public function compare( $data, $password );

	/**
	 * Check and see if the password output data of a password is in preferred format.
	 * For example if you use a variable hash algorithm type in a key derivation algorithm
	 * and let site config specify what hash function to use this could return false if the
	 * params in $data does not use the hash that was configured.
	 *
	 * When this method returns false the User's password may be 'upgraded' by calling
	 * crypt() again to generate new password output data for the password.
	 *
	 * @param $data string The password data. Same format as outputted by crypt()
	 * @return bool
	 */
	public function isPreferredFormat( $data );

}
