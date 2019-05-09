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

use Wikimedia\Assert\Assert;

/**
 * Represents a password hash for use in authentication
 *
 * Note: All password types are transparently prefixed with :<TYPE>:, where <TYPE>
 * is the registered type of the hash. This prefix is stripped in the constructor
 * and is added back in the toString() function.
 *
 * When inheriting this class, there are a couple of expectations
 * to be fulfilled:
 *  * If Password::toString() is called on an object, and the result is passed back in
 *    to PasswordFactory::newFromCiphertext(), the result will be identical to the original.
 * With these two points in mind, when creating a new Password sub-class, there are some functions
 * you have to override (because they are abstract) and others that you may want to override.
 *
 * The abstract functions that must be overridden are:
 *  * Password::crypt(), which takes a plaintext password and hashes it into a string hash suitable
 *    for being passed to the constructor of that class, and then stores that hash (and whatever
 *     other data) into the internal state of the object.
 * The functions that can optionally be overridden are:
 *  * Password::parseHash(), which can be useful to override if you need to extract values from or
 *    otherwise parse a password hash when it's passed to the constructor.
 *  * Password::needsUpdate(), which can be useful if a specific password hash has different
 *    logic for when the hash needs to be updated.
 *  * Password::toString(), which can be useful if the hash was changed in the constructor and
 *    needs to be re-assembled before being returned as a string. This function is expected to add
 *    the type back on to the hash, so make sure to do that if you override the function.
 *  * Password::verify() - This function checks if $this->hash was generated with the given
 *    password. The default is to just hash the password and do a timing-safe string comparison with
 *    $this->hash.
 *
 * After creating a new password hash type, it can be registered using the static
 * Password::register() method. The default type is set using the Password::setDefaultType() type.
 * Types must be registered before they can be set as the default.
 *
 * @since 1.24
 */
abstract class Password {
	/**
	 * @var PasswordFactory Factory that created the object
	 */
	protected $factory;

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
	 * Hash must fit in user_password, which is a tinyblob
	 */
	const MAX_HASH_SIZE = 255;

	/**
	 * Construct the Password object using a string hash
	 *
	 * It is strongly recommended not to call this function directly unless you
	 * have a reason to. Use the PasswordFactory class instead.
	 *
	 * @throws MWException If $config does not contain required parameters
	 *
	 * @param PasswordFactory $factory Factory object that created the password
	 * @param array $config Array of engine configuration options for hashing
	 * @param string|null $hash The raw hash, including the type
	 */
	final public function __construct( PasswordFactory $factory, array $config, $hash = null ) {
		if ( !$this->isSupported() ) {
			throw new Exception( 'PHP support not found for ' . get_class( $this ) );
		}
		if ( !isset( $config['type'] ) ) {
			throw new Exception( 'Password configuration must contain a type name.' );
		}
		$this->config = $config;
		$this->factory = $factory;

		if ( $hash !== null && strlen( $hash ) >= 3 ) {
			// Strip the type from the hash for parsing
			$hash = substr( $hash, strpos( $hash, ':', 1 ) + 1 );
		}

		$this->hash = $hash;
		$this->parseHash( $hash );
	}

	/**
	 * Get the type name of the password
	 *
	 * @return string Password type
	 */
	final public function getType() {
		return $this->config['type'];
	}

	/**
	 * Whether current password type is supported on this system.
	 *
	 * @return bool
	 */
	protected function isSupported() {
		return true;
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
	 * @return bool True if needs update, false otherwise
	 */
	abstract public function needsUpdate();

	/**
	 * Compare one Password object to this object
	 *
	 * By default, do a timing-safe string comparison on the result of
	 * Password::toString() for each object. This can be overridden to do
	 * custom comparison, but it is not recommended unless necessary.
	 *
	 * @deprecated since 1.33, use verify()
	 * @codeCoverageIgnore
	 *
	 * @param Password|string $other The other password
	 * @return bool True if equal, false otherwise
	 */
	public function equals( $other ) {
		wfDeprecated( __METHOD__, '1.33' );

		if ( is_string( $other ) ) {
			return $this->verify( $other );
		}

		return hash_equals( $this->toString(), $other->toString() );
	}

	/**
	 * Checks whether the given password matches the hash stored in this object.
	 *
	 * @param string $password Password to check
	 * @return bool
	 */
	public function verify( $password ) {
		Assert::parameterType( 'string', $password, '$password' );

		// No need to use the factory because we're definitely making
		// an object of the same type.
		$obj = clone $this;
		$obj->crypt( $password );

		return hash_equals( $this->toString(), $obj->toString() );
	}

	/**
	 * Convert this hash to a string that can be stored in the database
	 *
	 * The resulting string should be considered the seralized representation
	 * of this hash, i.e., if the return value were recycled back into
	 * PasswordFactory::newFromCiphertext, the returned object would be equivalent to
	 * this; also, if two objects return the same value from this function, they
	 * are considered equivalent.
	 *
	 * @return string
	 * @throws PasswordError if password cannot be serialized to fit a tinyblob.
	 */
	public function toString() {
		$result = ':' . $this->config['type'] . ':' . $this->hash;
		$this->assertIsSafeSize( $result );
		return $result;
	}

	/**
	 * Assert that hash will fit in a tinyblob field.
	 *
	 * This prevents MW from inserting it into the DB
	 * and having MySQL silently truncating it, locking
	 * the user out of their account.
	 *
	 * @param string $hash The hash in question.
	 * @throws PasswordError If hash does not fit in DB.
	 */
	final protected function assertIsSafeSize( $hash ) {
		if ( strlen( $hash ) > self::MAX_HASH_SIZE ) {
			throw new PasswordError( "Password hash is too big" );
		}
	}

	/**
	 * Hash a password and store the result in this object
	 *
	 * The result of the password hash should be put into the internal
	 * state of the hash object.
	 *
	 * @param string $password Password to hash
	 * @throws PasswordError If an internal error occurs in hashing
	 */
	abstract public function crypt( $password );
}
