<?php
/**
 * Class implementing password hashing and comparison for MediaWiki
 *
 * @file
 */

class PasswordStatusException extends Exception {

	protected $status;

	public function __construct( $status ) {
		$this->status = $status;
		parent::__construct( $status->getWikiText() );
	}

	public function getStatus() {
		return $this->status;
	}

}

class Password {

	/**
	 * Map of registered PasswordTypes
	 */
	protected static $types = array();

	/**
	 * The preferred PasswordType
	 */
	protected static $preferredType;

	/**
	 * Initialize the class
	 * - Register password types
	 * - Pick the preferred type
	 * - Run a hook for extensions to register new password types
	 */
	protected static function init() {
		if ( isset( self::$preferredType ) ) {
			return;
		}
		self::registerType( 'A', 'Password_TypeA' );
		self::registerType( 'B', 'Password_TypeB' );
		self::registerType( 'PBKHM', 'Password_TypePBKHM' );

		// If wgPasswordSalt is set the preferred type is t he best implementation we have now, otherwise it's type A.
		global $wgPasswordSalt;
		if( $wgPasswordSalt ) {
			$preferredType = 'PBKHM';
		} else {
			$preferredType = 'A';
		}

		// Run a hook that'll let extensions register types and changes the preferred type
		wfRunHooks( 'PasswordClassInit', array( &$preferredType ) );

		self::$preferredType = $preferredType;
	}

	/**
	 * Register a password class type
	 *
	 * @param $type The name of the type. Core uses names like 'A', 'B', ...
	 *              extensions should use more specific names.
	 * @param $className The class implementing this password type. The class
	 *                   must implement the PasswordType interface.
	 */
	public static function registerType( $type, $className ) {
		self::$types[$type] = $className;
	}

	/**
	 * Return a new instance of a password class type
	 *
	 * @param $type string The password type to return. If left out will return the preferred type.
	 * @return mixed A PasswordType implementing class, or null.
	 */
	public static function getType( $type = null ) {
		self::init();
		if ( is_null( $type ) ) {
			$type = self::$preferredType;
		}
		if ( isset( self::$types[$type] ) ) {
			$className = self::$types[$type];
			$cryptType = new $className( $type );
			if ( $cryptType instanceof PasswordType ) {
				return $cryptType;
			}
			wfWarn( __METHOD__ . ": Password crypt type $type class $className does not implement PasswordType." );
			return null;
		}
		wfWarn( __METHOD__ . ": Password crypt type $type does not exist." );
		return null;
	}

	/**
	 * Create a hashed password we can store in the database given a user's plaintext password.
	 *
	 * @param $password The plaintext password
	 * @return string The raw hashed password output along with parameters and a type.
	 */
	public static function crypt( $password ) {
		$cryptType = self::getType();
		return ':' . $cryptType->getName() . ':' . $cryptType->crypt( $password );
	}

	/**
	 * Parse the hashed form of a password stored in the database
	 * Used by compare() and isPreferredFormat() to avoid repeating common
	 * parsing code.
	 *
	 * @param $data string The raw hashed password data with all params and types stuck on the front.
	 * @return Status or an array containing a PasswordType class and the remaining portion of $data
	 */
	protected static function parseHash( $data ) {
		$params = explode( ':', $data, 3 );

		// Shift off the blank (When ":A:..." is split the first : should mean the first element is '')
		$blank = array_shift( $params );
		if ( $blank !== '' ) {
			// If the first piece is not '' then this is invalid
			// Note that old style passwords (oldCrypt) are handled by User internally since they require
			// data which we do not have.
			return Status::newFatal( 'password-crypt-invalid' );
		}
		$type = array_shift( $params );
		if ( !$type ) {
			// A type was not specified
			return Status::newFatal( 'password-crypt-invalid' );
		}

		$cryptType = self::getType( $type );
		if ( !$cryptType ) {
			// Crypt type does not exist
			return Status::newFatal( 'password-crypt-notype' );
		}

		return array( $cryptType, $params[0] );
	}

	/**
	 * Compare the hashed db contents of a password with a plaintext password to see if the
	 * password is correct.
	 *
	 * @param $data string The raw hashed password data with all params and types stuck on the front.
	 * @param $password The plaintext password
	 * @return Status A Status object;
	 *         - Good with a value of true for a password match
	 *         - Good with a value of false for a bad password
	 *         - Fatal if the password data was badly formed or there was some issue with
	 *           comparing the passwords which is not the user's fault.
	 */
	public static function compare( $data, $password ) {
		$status = self::parseHash( $data );
		if ( $status instanceof Status ) {
			return $status;
		}
		list( $cryptType, $remainingData ) = $status;
		try {
			return $cryptType->compare( $remainingData, $password );
		} catch( PasswordStatusException $e ) {
			return $e->getStatus();
		}
	}

	/**
	 * Check and see if the hashed data of a password is in preferred format.
	 * This may return false when the password type is not the same as the specified preferred type
	 * or when the password type implementation says that some of the parameters are different than
	 * what is preferred.
	 *
	 * When this method returns false the User's password may be 'upgraded' by calling
	 * crypt() again to generate a new hash for the password.
	 *
	 * @param $data string The raw hashed password data with all params and types stuck on the front.
	 * @return bool
	 */
	public static function isPreferredFormat( $data ) {
		$status = self::parseHash( $data );
		if ( $status instanceof Status ) {
			// If parseHash had issues then this is naturally not preferred
			return false;
		}
		list( $cryptType, $remainingData ) = $status;
		
		if ( $cryptType->getName() !== self::$preferredType ) {
			// If cryptType's name does not match the preferred type it's not preferred
			return false;
		}

		try {
			if ( $cryptType->isPreferredFormat( $remainingData ) === false ) {
				// If cryptType's isPreferredFormat returns false it's not preferred
				return false;
			}
		} catch( PasswordStatusException $e ) {
			// If there was an issue with the data, it's not preferred
			return false;
		}

		// If everything looked fine, then it's preferred
		return true;
	}

}

/**
 * The basic PasswordType interface
 * Defines the methods that are required of a PasswordType class implementation
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
	 * Create a hashed password to be stored in the database given a user's plaintext password.
	 *
	 * @param $password The plaintext password
	 */
	public function crypt( $password );

	/**
	 * Compare the hashed db form of a password with a plaintext password to see if the
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
	 * Check and see if the hashed data of a password is in preferred format.
	 * For example if you use a variable hash type and let site config specify what hash
	 * function to use this could return false if the params in $data does not use
	 * the hash that was configured.
	 *
	 * When this method returns false the User's password may be 'upgraded' by calling
	 * crypt() again to generate a new hash for the password.
	 *
	 * @param $data string The password data. Same format as outputted by crypt()
	 * @return bool
	 */
	public function isPreferredFormat( $data );

}

/**
 * Base class that implements most of the common things to most PasswordType implementations
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

/**
 * An old non-salted password type.
 * Simply md5s the password.
 */
class Password_TypeA extends BasePasswordType {

	protected function run( $params, $password ) {
		self::params( $params, 0 );
		return md5( $password );
	}

	protected function cryptParams() {
		return array();
	}

}

/**
 * Our first salted password type.
 * md5s a combination of a 32bit salt a '-' separator and
 * the md5 of the password.
 */
class Password_TypeB extends BasePasswordType {

	protected function run( $params, $password ) {
		list( $salt ) = self::params( $params, 1 );
		return md5( $salt . '-' . md5( $password ) );
	}

	protected function cryptParams() {
		$salt = MWCryptRand::generateHex( 8 );
		return array( $salt );
	}

}

/**
 * Class implementing PBKDF2-HMAC password hashing
 */
class Password_TypePBKHM  extends BasePasswordType {

	protected function run( $params, $password ) {
		list( $salt, $hashFunc, $iterations, $dkLength ) = self::params( $params, 4 );
		$salt = base64_decode( $salt );
		if ( !in_array( $hashFunc, hash_algos() ) ) {
			return Status::newFatal( 'password-crypt-nohashfunc', $hashFunc );
		}
		$iterations = intval( $iterations );
		$dkLength = intval( $dkLength );

		// Compute the hash length (#bytes)
		$hashLength = strlen( hash( $hashFunc, null, true ) ); 
		// Calculate the number of blocks to generate to create the correct size of derived key
		$blocks = ceil( $dkLength / $hashLength );

		$derivedKey = '';

		for ( $block = 1; $block <= $blocks; $block++ ) {
			// The first iteration of PBKDF2 uses the salt as the hmac key
			$blockTotal = $blockStep = hash_hmac( $hashFunc, $salt . pack( 'N', $block ), $password, true );
			// Further iterations use the previous hmac's result as the key
			// The result is then XORed into the block's total
			for ( $i = 1; $i < $iterations; $i++ ) {
				$blockStep = hash_hmac( 'sha512', $blockStep, $password, true );
				$blockTotal ^= $blockStep;
			}
			// The block total is then finally appended to the derived key
			$derivedKey .= $blockTotal;
		}

		// The derived key is then finally truncated to the desired length
		$derivedKey = substr( $derivedKey, 0, $dkLength );

		return base64_encode( $derivedKey );
	}

	protected function cryptParams() {
		global $wgPasswordPbkdf2Hmac;

		// Number of salt bits, never use less than 32 bits (the same as our old 8 hex chars)
		$saltBits = max( 32, $wgPasswordPbkdf2Hmac['saltbits'] );
		// Number of iterations to make, never use less than 1000 iterations
		$iterations = max( 1000, $wgPasswordPbkdf2Hmac['iterations'] );
		// The hash function
		$hashFunc = $wgPasswordPbkdf2Hmac['hash'];
		if ( !in_array( $hashFunc, hash_algos() ) ) {
			return Status::newFatal( 'password-crypt-nohashfunc', $hashFunc );
		}
		// The number of bits to output in the derived key
		$hashedBits = $wgPasswordPbkdf2Hmac['hashedbits'];
		if ( !$hashedBits ) {
			// If hashed bits is not set default to the number of bits outputted by the chosen hash function
			$hashedBits = strlen( hash( $hashFunc, null, true ) ) * 8;
		}

		return array( 
			/* salt */       base64_encode( MWCryptRand::generate( ceil( $saltBits / 8 ) ) ),
			/* hash */       $hashFunc,
			/* iterations */ $iterations,
			/* dkLength */   $hashedBits / 8,
		);
	}

	protected function preferredFormat( $params ) {
		global $wgPasswordPbkdf2Hmac;
		list( $salt, $usedHashFunc, $usedIterations, $dkLength ) = self::params( $params, 4 );
		
		$saltBits = max( 32, $wgPasswordPbkdf2Hmac['saltbits'] );
		if ( strlen( base64_decode( $salt ) ) < ceil( $saltBits / 8 ) ) {
			// This is not preferred if there are less salt bits than configured
			return false;
		}

		// Number of iterations to make, never use less than 1000 iterations
		$iterations = max( 1000, $wgPasswordPbkdf2Hmac['iterations'] );
		if ( $usedIterations < $iterations ) {
			// This is not preferred if there are less iterations than configured
			return false;
		}

		$hashFunc = $wgPasswordPbkdf2Hmac['hash'];
		if ( in_array( $hashFunc, hash_algos() ) && $usedHashFunc !== $hashFunc ) {
			// This is not preferred if the hash function does not match configuration
			return false;
		}

		// The number of bits to output in the derived key
		$hashedBits = $wgPasswordPbkdf2Hmac['hashedbits'];
		if ( !$hashedBits ) {
			// If hashed bits is not set default to the number of bits outputted by the chosen hash function
			$hashedBits = strlen( hash( $hashFunc, null, true ) ) * 8;
		}

		if ( $dkLength < ( $hashedBits / 8 ) ) {
			// If the key length is less than 
			return false;
		}

		return true;
	}

}
