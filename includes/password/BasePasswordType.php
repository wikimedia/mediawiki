<?php
/**
 * High-Level BasePasswordType abstract class
 *
 * This class implements the common elements that are used by most custom
 * implemented derived key comparison based password storage schemes.
 * Among these common elements are:
 *  - Serialization and parsing of parameter lists
 *  - Password validation based on the comparison of a derived key
 *  - crypt() using a set of default params and the same method used by verify()
 *  - Preferred format checks based on parameters
 *
 * If you are implementing some password storage format yourself you will most
 * likely want to make it a subclass of BasePasswordType. You only need to use
 * PasswordType if you are implementing password storage schemes that are not based
 * on derived key comparison, a password interface into existing systems such as crypt()
 * which already have their own format for serialized params, or building a storage
 * container layer which calls the password system itself and provides some extra
 * feature such as a out-of-band shared salt or encryption key for passwords.
 *
 * To implement a derived key based password implementation you subclass BasePasswordType
 * and implement the following methods:
 *  public function run( $params, $password );
 *    The key derivation implementation of your password storage algorithm.
 *    Simply take the parameters and the plaintext password and create the
 *    derived key for the password.
 *    BasePasswordType will call your key derivation method for both crypt()
 *    and verify() with whatever parameters are needed and will handle the
 *    comparison of derived keys for you.
 *    If there is something wrong with the parameters you should `throw self::error( ... );`
 *    to indicate that the parameters are bad rather than the password being invalid.
 *
 *  public function cryptParams();
 *    Default params for a new password. This method will be called when running a
 *    password through crypt() these params will be passed to your run() and naturally
 *    any salt included should be a brand new randomly generate salt rather than an old one.
 *
 *  public function paramsNeedUpdate( $params );
 *    This method is optional. If your password implementation has parameters which cryptParams
 *    uses site configuration for you can use this method to return true when the params do not
 *    match the ones used in site configuration. This will trigger an update that will generate
 *    a new derived key for the password using brand new parameters.
 *
 *  public function knownPasswordData();
 *    This method should be written after you have finished your password implementation.
 *    You should generate some password data using your implementation and list that data
 *    along with the associated password in the output from this method. This will then
 *    ensure that your password implementation has tests to ensure it is not broken.
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
 * Base class that implements most of the common things to most PasswordType implementations
 * @ingroup Password
 * @since 1.20
 */
abstract class BasePasswordType extends PasswordType {

	/**
	 * Helper function for self::run() implementations
	 * Validates that the inputted set of parameters is of the correct length
	 * If not throws an exception that considers the password derived key invalid
	 * Can be used like so:
	 *   $params = self::params( $params, 2 );
	 *
	 * @param $params The array of parameters
	 * @param $length The parameter length that is valid for this password type
	 * @return Array the $params array
	 */
	protected static function params( $params, $length ) {
		if ( count( $params ) != $length ) {
			throw self::error( 'password-crypt-invalidparamlength' );
		}
		return $params;
	}

	/**
	 * Abstract method to be defined by password type implementations.
	 * Is expected to take a set of params and password and then output the
	 * derived key for the password according to those parameters.
	 * This is used by both crypt() and verify() implementations
	 *
	 * @param $params The params (without derived key) to the key derivation implementation
	 * @param $password The raw user inputted password
	 * @return String A string containing the password's derived key.
	 * @throws PasswordDataError if there is an error in the params to be handled by verify()
	 */
	abstract protected function run( $params, $password );

	/**
	 * Abstract method to be defined by password type implementations.
	 * Is expected to output a set of params to be used by run() when called
	 * from crypt() rather than verify().
	 *
	 * @return Array
	 */
	abstract protected function cryptParams();

	/**
	 * Semi-abstract method to be defined by password type implementations.
	 * @param $params The params to the key derivation implementation
	 * @return bool
	 * @see PasswordType::needsUpdate
	 */
	protected function paramsNeedUpdate( $params ) {
		// Basic implementations don't have internal parameter preferences
		// so we just return false.
		return false;
	}

	/**
	 * @see PasswordType::crypt
	 * Default implementation of password crypt that fits most implementations
	 * - Gets the parameters from cryptParams()
	 * - Calls run to execute the crypt function
	 * - Outputs the params and derived key together in a : delimited string
	 */
	public function crypt( $password ) {
		try {
			$params = $this->cryptParams();
		} catch ( PasswordDataError $e ) {
			throw new MWException( __METHOD__ . ': Programming error inside the ' . $this->getName() .
				' password crypt implementation. Implementation\'s cryptParams() method' .
				' throwed a PasswordDataError object.' );
		}
		try {
			$dkey = $this->run( $params, $password );
		} catch ( PasswordDataError $e ) {
			throw new MWException( __METHOD__ . ': Programming error inside the ' . $this->getName() .
				' password crypt implementation. Implementation\'s run() method' .
				' returned a PasswordDataError object when using default parameters.' );
		}
		$out = $params;
		$out[] = $dkey;
		return implode( ':', $out );
	}

	/**
	 * @see PasswordType::verify
	 * Default implementation of password comparison that fits most implementations.
	 * - Data is split by : to create the params, the last one being treated as the real derived key to verify against
	 * - self::run() is run with the parameters and password in order to do the derived key comparison
	 */
	public function verify( $data, $password ) {
		$params = explode( ':', $data );
		$realDK = array_pop( $params );
		$dkey = $this->run( $params, $password );
		return $dkey === $realDK;
	}

	/**
	 * @see PasswordType::needsUpdate
	 */
	public function needsUpdate( $data ) {
		$params = explode( ':', $data );
		$realHash = array_pop( $params );
		return $this->paramsNeedUpdate( $params );
	}

}
