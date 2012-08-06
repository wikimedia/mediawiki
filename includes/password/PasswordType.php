<?php
/**
 * Low-Level PasswordType abstract class
 *
 * This class implements the lowest level interface a password type implementation
 * may use to implement a password type. This level gives you direct access
 * to the raw data with no parameter parsing and direct control over the verify
 * method. If you are writing a normal hash or derived key comparison based
 * password type implementation you should use BasePasswordType instead.
 *
 * To implement a raw password type implementation you subclass PasswordType
 * and implement the following methods:
 *  abstract public function crypt( $password );
 *    From this method you must take the password given to you by the user and return
 *    the raw data that will be stored inside the database.
 *
 *  abstract public function verify( $data, $password );
 *    From this method you must accept data in the format you output from crypt() and
 *    verify a password against it. You must return true or false to indicate whether
 *    the password is correct or incorrect.
 *    If there is something wrong with the data you should `throw self::error( ... );`
 *    to indicate that the data is bad rather than the password being invalid.
 *
 *  abstract public function needsUpdate( $data );
 *    This method is optional. If your password implementation has parameters which use
 *    site configuration for you can use this method to return true when the params do not
 *    match the ones used in site configuration. This will trigger an update that will
 *    regenerate the password data for the password.
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
 * The basic PasswordType class
 * Defines the methods that are required of a PasswordType class implementation
 * @ingroup Password
 * @since 1.20
 */
abstract class PasswordType {

	/**
	 * The name of the password type
	 */
	protected $name;

	/**
	 * Constructors that simply records the password type name we were given
	 *
	 * @param $name The password type name.
	 */
	function __construct( $name ) {
		$this->name = $name;
	}

	/**
	 * Return the name of the PasswordType
	 * The password system will use this at the start of the data we store in the database
	 * eg: if getName returns 'A' the data will take the format ':A:...'.
	 *
	 * @return string The type name.
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Helper function for password type implementations.
	 * Returns a PasswordDataError that can be thrown when there is an issue with
	 * the password data that we've been passed from the database.
	 *
	 * @param $key String: message key
	 * @param Varargs: parameters as Strings
	 * @return PasswordDataError
	 */
	protected static function error( $key /* ... */ ) {
		$params = func_get_args();
		array_shift( $params );
		$msg = new Message( $args, $params );
		return new PasswordDataError( $msg );
	}

	/**
	 * Create password output data to be stored in the database given a user's plaintext password.
	 *
	 * @param $password The plaintext password
	 */
	abstract public function crypt( $password );

	/**
	 * Verify a plaintext password against the password data of a password
	 * to see if the password is correct.
	 *
	 * @param $data string The password data. Same format as outputted by crypt()
	 * @param $password The plaintext password
	 * @return boolean true for a password match, false for a bad password
	 * @throws PasswordDataError if the password data was badly formed or there was some issue with
	 *           comparing the passwords which is not the user's fault.
	 */
	abstract public function verify( $data, $password );

	/**
	 * Check and see if the password output data of a password needs to be rehashed.
	 * For example if you use a variable hash algorithm type in a key derivation algorithm
	 * and let site config specify what hash function to use this could return true if the
	 * params in $data does not use the hash that was configured.
	 *
	 * When this method returns true the User's password may be 'upgraded' by calling
	 * crypt() again to generate new password output data for the password.
	 *
	 * @param $data string The password data. Same format as outputted by crypt()
	 * @return bool
	 */
	abstract public function needsUpdate( $data );

}
