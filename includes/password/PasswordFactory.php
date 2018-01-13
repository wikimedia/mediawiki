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
 * Factory class for creating and checking Password objects
 *
 * @since 1.24
 */
final class PasswordFactory {
	/**
	 * The default PasswordHash type
	 *
	 * @var string
	 * @see PasswordFactory::setDefaultType
	 */
	private $default = '';

	/**
	 * Mapping of password types to classes
	 * @var array
	 * @see PasswordFactory::register
	 * @see Setup.php
	 */
	private $types = [
		'' => [ 'type' => '', 'class' => InvalidPassword::class ],
	];

	/**
	 * Register a new type of password hash
	 *
	 * @param string $type Unique type name for the hash
	 * @param array $config Array of configuration options
	 */
	public function register( $type, array $config ) {
		$config['type'] = $type;
		$this->types[$type] = $config;
	}

	/**
	 * Set the default password type
	 *
	 * @throws InvalidArgumentException If the type is not registered
	 * @param string $type Password hash type
	 */
	public function setDefaultType( $type ) {
		if ( !isset( $this->types[$type] ) ) {
			throw new InvalidArgumentException( "Invalid password type $type." );
		}
		$this->default = $type;
	}

	/**
	 * Get the default password type
	 *
	 * @return string
	 */
	public function getDefaultType() {
		return $this->default;
	}

	/**
	 * Initialize the internal static variables using the global variables
	 *
	 * @param Config $config Configuration object to load data from
	 */
	public function init( Config $config ) {
		foreach ( $config->get( 'PasswordConfig' ) as $type => $options ) {
			$this->register( $type, $options );
		}

		$this->setDefaultType( $config->get( 'PasswordDefault' ) );
	}

	/**
	 * Get the list of types of passwords
	 *
	 * @return array
	 */
	public function getTypes() {
		return $this->types;
	}

	/**
	 * Create a new Hash object from an existing string hash
	 *
	 * Parse the type of a hash and create a new hash object based on the parsed type.
	 * Pass the raw hash to the constructor of the new object. Use InvalidPassword type
	 * if a null hash is given.
	 *
	 * @param string|null $hash Existing hash or null for an invalid password
	 * @return Password
	 * @throws PasswordError If hash is invalid or type is not recognized
	 */
	public function newFromCiphertext( $hash ) {
		if ( $hash === null || $hash === false || $hash === '' ) {
			return new InvalidPassword( $this, [ 'type' => '' ], null );
		} elseif ( $hash[0] !== ':' ) {
			throw new PasswordError( 'Invalid hash given' );
		}

		$type = substr( $hash, 1, strpos( $hash, ':', 1 ) - 1 );
		if ( !isset( $this->types[$type] ) ) {
			throw new PasswordError( "Unrecognized password hash type $type." );
		}

		$config = $this->types[$type];

		return new $config['class']( $this, $config, $hash );
	}

	/**
	 * Make a new default password of the given type.
	 *
	 * @param string $type Existing type
	 * @return Password
	 * @throws PasswordError If hash is invalid or type is not recognized
	 */
	public function newFromType( $type ) {
		if ( !isset( $this->types[$type] ) ) {
			throw new PasswordError( "Unrecognized password hash type $type." );
		}

		$config = $this->types[$type];

		return new $config['class']( $this, $config );
	}

	/**
	 * Create a new Hash object from a plaintext password
	 *
	 * If no existing object is given, make a new default object. If one is given, clone that
	 * object. Then pass the plaintext to Password::crypt().
	 *
	 * @param string|null $password Plaintext password, or null for an invalid password
	 * @param Password|null $existing Optional existing hash to get options from
	 * @return Password
	 */
	public function newFromPlaintext( $password, Password $existing = null ) {
		if ( $password === null ) {
			return new InvalidPassword( $this, [ 'type' => '' ], null );
		}

		if ( $existing === null ) {
			$config = $this->types[$this->default];
			$obj = new $config['class']( $this, $config );
		} else {
			$obj = clone $existing;
		}

		$obj->crypt( $password );

		return $obj;
	}

	/**
	 * Determine whether a password object needs updating
	 *
	 * Check whether the given password is of the default type. If it is,
	 * pass off further needsUpdate checks to Password::needsUpdate.
	 *
	 * @param Password $password
	 *
	 * @return bool True if needs update, false otherwise
	 */
	public function needsUpdate( Password $password ) {
		if ( $password->getType() !== $this->default ) {
			return true;
		} else {
			return $password->needsUpdate();
		}
	}

	/**
	 * Generate a random string suitable for a password
	 *
	 * @param int $minLength Minimum length of password to generate
	 * @return string
	 */
	public static function generateRandomPasswordString( $minLength = 10 ) {
		// Decide the final password length based on our min password length,
		// stopping at a minimum of 10 chars.
		$length = max( 10, $minLength );
		// Multiply by 1.25 to get the number of hex characters we need
		// Generate random hex chars
		$hex = MWCryptRand::generateHex( ceil( $length * 1.25 ) );
		// Convert from base 16 to base 32 to get a proper password like string
		return substr( Wikimedia\base_convert( $hex, 16, 32, $length ), -$length );
	}

	/**
	 * Create an InvalidPassword
	 *
	 * @return InvalidPassword
	 */
	public static function newInvalidPassword() {
		static $password = null;

		if ( $password === null ) {
			$factory = new self();
			$password = new InvalidPassword( $factory, [ 'type' => '' ], null );
		}

		return $password;
	}
}
