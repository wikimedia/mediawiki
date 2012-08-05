<?php
/**
 * BasePasswordType abstract class
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
 */
abstract class BasePasswordType implements PasswordType {

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
	 * @see PasswordType::getName
	 */
	function getName() {
		return $this->name;
	}

	/**
	 * Helper function for self::run() implementations
	 * Validates that the inputted set of parameters is of the correct length
	 * If not throws an exception that considers the password hash invalid
	 * Can be used like so:
	 *   $params = self::params( $params, 2 );
	 *
	 * @param $params The array of parameters
	 * @param $length The parameter length that is valid for this password type
	 * @return Array the $params array
	 */
	protected static function params( $params, $length ) {
		if ( count( $params ) != $length ) {
			throw new PasswordStatusException( Status::newFatal( 'password-crypt-invalidparamlength' ) );
		}
		return $params;
	}

	/**
	 * Abstract method to be defined by password type implementations.
	 * Is expected to take a set of params and password and then output the
	 * hash for the password according to those parameters.
	 * This is used by both crypt() and compare() implementations
	 *
	 * @param $params The params (without hash) to the hashing implementation
	 * @param $password The raw user inputted password
	 * @param mixed A string containing the hashed password or a fatal
	 *        Status object indicating an error in the params that will be
	 *        handled by compare().
	 */
	abstract protected function run( $params, $password );

	/**
	 * Abstract method to be defined by password type implementations.
	 * Is expected to output a set of params to be used by run() when called
	 * from crypt() rather than compare().
	 *
	 * @return Array
	 */
	abstract protected function cryptParams();

	/**
	 * Semi-abstract method to be defined by password type implementations.
	 * @param $params The params to the hashing implementation
	 * @return bool
	 * @see PasswordType::isPreferredFormat
	 */
	protected function preferredFormat( $params ) {
		// Basic implementations don't have internal parameter preferences
		// so we just return true.
		return true;
	}

	/**
	 * @see PasswordType::crypt
	 * Default implementation of password crypt that fits most implementations
	 * - Gets the parameters from cryptParams()
	 * - Calls run to execute the crypt function
	 * - Outputs the params and hash together in a : delimited string
	 */
	public function crypt( $password ) {
		$params = $this->cryptParams();
		if ( $params instanceof Status ) {
			throw new MWException( __METHOD__ . ': Programming error inside the ' . $this->getName() .
				' password crypt implementation. Implementation\'s cryptParams() method' .
				' returned a status object.' );
		}
		$hash = $this->run( $params, $password );
		if ( $hash instanceof Status ) {
			throw new MWException( __METHOD__ . ': Programming error inside the ' . $this->getName() .
				' password crypt implementation. Implementation\'s run() method' .
				' returned a status object when using default parameters.' );
		}
		$out = $params;
		$out[] = $hash;
		return implode( ':', $out );
	}

	/**
	 * @see PasswordType::compare
	 * Default implementation of password comparison that fits most implementations.
	 * - Data is split by : to create the params, the last one being treated as the real hash to compare against
	 * - self::run() is run with the parameters and password in order to do the hash comparison
	 */
	public function compare( $data, $password ) {
		$params = explode( ':', $data );
		$realHash = array_pop( $params );
		$hash = $this->run( $params, $password );
		if ( $hash instanceof Status ) {
			return $hash;
		}
		return Status::newGood( $hash === $realHash );
	}

	/**
	 * @see PasswordType::isPreferredFormat
	 */
	public function isPreferredFormat( $data ) {
		$params = explode( ':', $data );
		$realHash = array_pop( $params );
		return $this->preferredFormat( $params );
	}

}
