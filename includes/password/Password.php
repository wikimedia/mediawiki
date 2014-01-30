<?php
/**
 * Implements the Password class for the MediaWiki software.
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
 * Represents a password hash for use in authentication
 *
 * This is the central factory for password hashing in MediaWiki. It functions as
 * both a static registry for registering new password types as well as a base
 * class for other password types.
 *
 * Note: All password types are transparently prefixed with :<TYPE>:, where <TYPE>
 * is the registered type of the hash. This prefix is stripped in the constructor
 * and is added back in the toString() function.
 *
 * When inheriting this class, there are a couple of expectations
 * to be fulfilled:
 *  * If Password::toString() is called on an object, and the result is passed back in
 *    to Password::newFromCiphertext(), the resulting object will be identical to the original.
 *    The Password class implements PHP's Serializable interface, and will call toString()
 *    as a means of seralizing the object.
 *  * The string representations of two Password objects are equal if and only if the
 *    the original plaintext passwords match. In other words, if the toString() result of
 *    two objects match, the passwords are the same, and the user will be logged in.
 *    If a hashing scheme does not fulfill this expectation, it must make sure to override the
 *    Password::equals() function and use custom comparison logic. However, this is not
 *    recommended unless absolutely required by the hashing mechanism.
 * With these two points in mind, when creating a new Password sub-class, there are some functions
 * you have to override (because they are abstract) and others that you may want to override.
 *
 * The abstract functions that must be overridden are:
 *  * Password::crypt(), which takes a plaintext password and hashes it into a string hash suitable
 *    for being passed to the constructor of that class, and then stores that hash (and whatever
 *     other data) into the internal state of the object.
 * The functions that can optionally be overridden are:
 *  * Password::tests(), which provides unit tests for the framework.
 *  * Password::parseHash(), which can be useful to override if you need to extract values from or
 *    otherwise parse a password hash when it's passed to the constructor.
 *  * Password::needsUpdate(), which can be useful if a specific password hash has different
 *    logic for when the hash needs to be updated.
 *  * Password::toString(), which can be useful if the hash was changed in the constructor and
 *    needs to be re-assembled before being returned as a string. This function is expected to add
 *    the type back on to the hash, so make sure to do that if you override the function.
 *  * Password::equals() - This function compares two Password objects to see if they are equal.
 *    The default is to just do a timing-safe string comparison on the $this->hash values.
 *
 * After creating a new password hash type, it can be registered using the static
 * Password::register() method. The default type is set using the Password::setDefault() type.
 * Types must be registered before they can be set as the default.
 *
 * @since 1.23
 */
abstract class Password implements Serializable {
	/**
	 * The default PasswordHash type
	 * @var string
	 * @see Password::setDefault
	 */
	private static $default = 'bcrypt';

	/**
	 * Mapping of password types to classes
	 * @var array
	 * @see Password::register
	 * @see Setup.php
	 */
	private static $types = array(
		'' => array( 'type' => '', 'class' => 'InvalidPassword' ),
	);

	/**
	 * Register a new type of password hash
	 *
	 * @param string $type Unique type name for the hash
	 * @param array $config Array of configuration options
	 */
	final public static function register( $type, array $config ) {
		$config['type'] = $type;
		self::$types[$type] = $config;
	}

	/**
	 * Set the default password type
	 *
	 * @throws InvalidArgumentException If the type is not registered
	 * @param string $type Password hash type
	 */
	final public static function setDefault( $type ) {
		if ( !isset( self::$types[$type] ) ) {
			throw new InvalidArgumentException( "Invalid password type $type." );
		}
		self::$default = $type;
	}

	/**
	 * Initialize the internal static variables using the global variables
	 */
	final public static function initFromGlobals() {
		global $wgPasswordConfig, $wgPasswordDefault;

		foreach ( $wgPasswordConfig as $type => $config ) {
			self::register( $type, $config );
		}

		self::setDefault( $wgPasswordDefault );
	}

	/**
	 * Get the list of types of passwords
	 *
	 * @return array
	 */
	final public static function getTypes() {
		return self::$types;
	}

	/**
	 * Get the list of all hashing tests
	 *
	 * @return array
	 */
	final public static function getTests() {
		$tests = array();
		foreach ( self::$types as $type => $config ) {
			if ( $type === '' ) {
				continue;
			}
			$password = Password::newFromType( $type );
			$tests += $password->tests();
		}

		return $tests;
	}

	/**
	 * Create a new Hash object from an existing string hash
	 *
	 * Parse the type of a hash and create a new hash object based on the parsed type.
	 * Pass the raw hash to the constructor of the new object. Use InvalidPassword type
	 * if a null hash is given.
	 *
	 * @param string|null $hash Existing hash or null for an invalid password
	 * @return Password object
	 * @throws PasswordError if hash is invalid or type is not recognized
	 */
	final public static function newFromCiphertext( $hash ) {
		if ( !$hash ) {
			return new InvalidPassword( array( 'type' => '' ), null );
		} elseif ( $hash[0] !== ':' ) {
			throw new PasswordError( 'Invalid hash given' );
		}

		$type = substr( $hash, 1, strpos( $hash, ':', 1 ) - 1 );
		if ( !isset( self::$types[$type] ) ) {
			throw new PasswordError( "Unrecognized password hash type $type." );
		}

		$config = self::$types[$type];

		return new $config['class']( $config, $hash );
	}

	/**
	 * Make a new default password of the given type.
	 *
	 * @param string $type Existing type
	 * @return Password object
	 * @throws PasswordError if hash is invalid or type is not recognized
	 */
	final public static function newFromType( $type ) {
		if ( !isset( self::$types[$type] ) ) {
			throw new PasswordError( "Unrecognized password hash type $type." );
		}

		$config = self::$types[$type];

		return new $config['class']( $config );
	}

	/**
	 * Create a new Hash object from a plaintext password
	 *
	 * If no existing object is given, make a new default object. If one is given, clone that
	 * object. Then pass the plaintext to Password::crypt().
	 *
	 * @param string $password Plaintext password
	 * @param Password|null $existing Optional existing hash to get options from
	 * @return Password object
	 */
	final public static function newFromPlaintext( $password, Password $existing = null ) {
		if ( $existing === null ) {
			$config = self::$types[self::$default];
			$obj = new $config['class']( $config );
		} else {
			$obj = clone $existing;
		}

		$obj->crypt( $password );

		return $obj;
	}

	/**
	 * String representation of the hash without the type
	 * @var string
	 */
	protected $hash;

	/**
	 * Array of configuration variables injected from the constructor
	 * @var array
	 */
	protected $config;

	/**
	 * Construct the Password object using a string hash
	 *
	 * @throws MWException If $config does not contain required parameters
	 *
	 * @param array $config Array of engine configuration options for hashing
	 * @param string|null $hash The raw hash, including the type
	 */
	final private function __construct( array $config, $hash = null ) {
		if ( !isset( $config['type'] ) ) {
			throw new MWException( 'Password configuration must contain a type name.' );
		}
		$this->config = $config;
		$this->unserialize( $hash );
	}

	/**
	 * Get the type name of the password
	 *
	 * @return string Password type
	 */
	final public function getType() {
		return $this->config['type'];
	}

	final function serialize() {
		return $this->toString();
	}

	final function unserialize( $hash ) {
		if ( $hash !== null && strlen( $hash ) >= 3 ) {
			// Strip the type from the hash for parsing
			$separator = strpos( $hash, ':', 1 );
			$type = substr( $hash, 1, $separator - 1 );
			$hash = substr( $hash, $separator + 1 );

			$this->config = self::$types[$type];
		}

		$this->hash = $hash;
		$this->parseHash( $hash );
	}

	/**
	 * Perform any parsing necessary on the hash to see if the hash is valid
	 * and/or to perform logic for seeing if the hash needs updating.
	 *
	 * @param string $hash The hash, with the :<TYPE>: prefix stripped
	 * @throws PasswordError If there is an error in parsing the hash
	 */
	protected function parseHash( $hash ) {
	}

	/**
	 * Determine if the hash needs to be updated
	 *
	 * When overriding this function, always make sure to include the result of
	 * parent::needsUpdate(), so that update requirements are propogated.
	 *
	 * @return bool True if needs update, false otherwise
	 */
	public function needsUpdate() {
		return $this->config['type'] !== self::$default;
	}

	/**
	 * Compare one Password object to this object
	 *
	 * By default, do a timing-safe string comparison on the result of
	 * Password::toString() fir each object. This can be overridden to do
	 * custom comparison, but it is not recommended unless necessary.
	 *
	 * @param Password|string $other The other password
	 * @return bool True if equal, false otherwise
	 */
	public function equals( $other ) {
		if ( !$other instanceof self ) {
			$other = self::newFromPlaintext( $other, $this );
		}

		$internal = $this->toString() . chr( 0 );
		$external = $other->toString() . chr( 0 );
		$intLen = strlen( $internal );
		$extLen = strlen( $external );

		$match = $intLen - $extLen;

		for ( $i = 0; $i < $extLen; $i++ ) {
			$match |= ( ord( $internal[$i % $intLen] ) ^ ord( $external[$i] ) );
		}

		return $match === 0;
	}

	/**
	 * Convert this hash to a string that can be stored in the database
	 *
	 * The resulting string should be considered the seralized representation
	 * of this hash, i.e., if the return value were recycled back into
	 * Password::newFromCiphertext, the returned object would be equivalent to
	 * this; also, if two objects return the same value from this function, they
	 * are considered equivalent.
	 *
	 * @return string
	 */
	public function toString() {
		return ':' . $this->config['type'] . ':' . $this->hash;
	}

	/**
	 * Hash a password with the given options
	 *
	 * The result of the password hash should be put into the internal
	 * state of the hash object.
	 *
	 * @param string $password Password to hash
	 * @throws PasswordError If an internal error occurs in hashing
	 */
	abstract protected function crypt( $password );

	/**
	 * Get unit tests for this class (used for integrity testing)
	 *
	 * Return an array of arrays, each containing the following:
	 *  * Boolean of whether they should be equal or not
	 *  * The expected hash result
	 *  * The original plaintext password
	 *
	 * The tests will construct a new Password object based on the existing
	 * hash, then call equals() on it with the original password and assert that
	 * it is true or false depending on the first item.
	 *
	 * @return array
	 */
	public function tests() {
		return array();
	}
}
