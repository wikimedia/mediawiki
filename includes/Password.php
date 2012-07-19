<?php

/**
 * Stores a user password. Mostly a wrapper object for the PasswordHash
 * class. It provides password comparison and hash updating functioanlity
 */
class Password {
	private $hash;

	/**
	 * Create either a new empty Password or a Password from a hash.
	 *
	 * @param $hash Hash to fill the password with
	 */
	public function __construct( $hash = false ) {
		if( $hash ) {
			$this->hash = PasswordHash::newHash( $hash );
		} else {
			$this->hash = null;
		}
	}

	/**
	 * Return the string representation of the password, i.e., the hash.
	 *
	 * @return string The hashed password
	 */
	public function __toString() {
		return (string) ($this->hash);
	}

	/**
	 * Update the password with a new plaintext value.
	 *
	 * Take the new plaintext password and hash it with the
	 * default Hash class. Then store the new hash.
	 *
	 * @param $plaintext New password
	 * @return bool True on success
	 */
	public function update( $plaintext ) {
		wfProfileIn( __METHOD__ );
		if( PasswordHash::$default != get_class( $this->hash ) ) {
			// Current hash algorithm is no longer default. Make new object.
			$this->hash = PasswordHash::newHash();
		}
		$success = $this->hash->update( $plaintext );
		wfProfileOut( __METHOD__ );
		return $success;
	}

        /**
         * Determine if the hash needs to be updated.
         *
         * Check the options of the hash agains the default. If they do not
         * match, then the hash needs to be updated. Otherwise, it is fine.
         *
         * @return bool True if needs update, false otherwise
         */
	public function needsUpdate() {
		if( PasswordHash::$default != get_class( $this->hash ) ) {
			return true;
		}
		return $this->hash->needsUpdate();
	}

	/**
	 * Compare the given password to this.
	 *
	 * @see PasswordHash::compare
	 * @param $plaintext Password to compare
	 * @return bool True if passwords match, false otherwise
	 */
	public function compare( $plaintext ) {
		wfProfileIn( __METHOD__ );
		if( $this->hash !== null ) {
			$retval = $this->hash->compare( $plaintext );
		} else {
			$retval = false;
		}
		wfProfileOut( __METHOD__ );
		return $retval;
	}
}

/**
 * Stores a password hash. The static functions of this class are used to
 * register new hashing algorithms and to make Hash objects from given string
 * hashes. The non-static methods provide functionality for comparison and
 * updating the hash. The class is abstract as you can only make an object
 * of a sub-class that specifies a specific hashing algorithm.
 */
abstract class PasswordHash {
	public static $default;
	private static $types = false;

	/**
	 * Initialize the MW hashing infrastructure by registering
	 * default internal hashing classes and storing the intial default.
	 */
	public static function init() {
		global $wgPasswordHash;
		self::$types = array();

		// Register the default internal classes.
		self::register( 'PasswordHash_MWUnsalted' );
		self::register( 'PasswordHash_MWSalted' );
		self::register( 'PasswordHash_PBKDF2' );

		// Default to PBKDF2 unless $wgPasswordHash is set.
		self::register( 'PasswordHash_' . $wgPasswordHash );
		self::$default = 'PasswordHash_' . $wgPasswordHash;

		// Run hooks to register external algorithms.
		wfRunHooks( 'PasswordHashInit', array( &self::$default ) );
	}

	/**
	 * Register a new type of password hash.
	 *
	 * @param $class Name of the class to register
	 */
	public static function register( $class ) {
		if( in_array( $class, self::$types ) ) {
			// Class already registered.
			return false;
		} elseif( !is_subclass_of( $class, 'PasswordHash' ) ) {
			return false;
		} else {
			self::$types[] = $class;
		}
	}

	/**
	 * Get the list of registered hash types.
	 *
	 * @return array Array of hash types
	 */
	public static function getTypes() {
		return self::$types;
	}

	/**
	 * Create a new Hash object.
	 *
	 * If a hash is passed, create a new Hash object with
	 * the existing hash. Otherwise, create a new default, empty
	 * Hash object.
	 *
	 * @param $hash Existing hash, if applicable
	 */
	public static function newHash( $hash = false ) {
		if( self::$types === false ) {
			self::init();
		}

		$class_name = false;
		if( $hash ) {
			// Existing hash given. Search for what type it is.
			foreach( self::$types as $class ) {
				if( $class::isInstance( $hash ) ) {
					$class_name = $class;
					break;
				}
			}

			if( $class_name ) {
				return new $class_name( $hash );
			} else {
				// No type found for the hash.
				return null;
			}
		} else {
			// No hash given. Use the default.
			$class_name = self::$default;
			return new $class_name();
		}
	}

	/**
	 * Determine whether a given hash is an instance of this particular
	 * hash type.
	 *
	 * This is an abstract method that must be implemented by each
	 * individual hashing type.
	 *
	 * @param $hash Hash to use
	 * @return bool True if it matches the type, false otherwise.
	 */
	abstract protected static function isInstance( $hash );

	/**
	 * Get an array of options to use for hashing.
	 *
	 * If a hash is given, extract the parameters from the hash
	 * and return them. Otherwise, use defaults.
	 *
	 * This is an abstract method that must be implemented by each
	 * individual hashing type.
	 *
	 * @see Hash::crypt()
	 * @param $hash Hash to extract parameters from.
	 * @return array Array of options
	 */
	abstract protected static function getParams( $hash = false );

	/**
	 * Hash a password with the given options.
	 *
	 * This is an abstract method that must be implemented by each
	 * individual hashing type. See individual hashing subclasses
	 * to determine what options this takes.
	 *
	 * @param $password Password to hash
	 * @param $options Type-specific options for hashing
	 * @return string The hashed password
	 */
	abstract protected static function crypt( $password, $options );

	/**
	 * Get an array of test vectors for this specific hash type. Each
	 * entry is a two-index array, with the first element being the
	 * password and the second element being the hash.
	 *
	 * @return array Array of arrays of test vectors
	 */
	 abstract public static function tests();

	/**
	 * Create a new Hash, either based on an existing hash or an empty object.
	 *
	 * @param $hash Existing hash to fill data from
	 */
	protected function __construct( $hash = false ) {
		$this->hash = $hash;
		$this->options = static::getParams( $hash );

		if( $this->options === false ) {
			throw new PasswordError( 'Invalid hash of type ' . __CLASS__ . '.' );
		}
	}

	/**
	 * Return the string representation of the Hash, i.e., the hash itself.
	 *
	 * @return string The hashed password
	 */
	public function __toString() {
		return (string) $this->hash;
	}

	/**
	 * Hash a password and compare it to this.
	 *
	 * Hash the given password using the internally stored options.
	 * Then compare it to the existing hash.
	 *
	 * @param $plaintext Password to compare
	 * @return bool True if matched, false otherwise
	 */
	public function compare( $plaintext ) {
		$hash = $this->hash;
		$result = false;
		$salt = array_key_exists( 'salt', $this->options ) ? $this->options['salt'] : '';
		if( !wfRunHooks( 'UserComparePasswords', array( &$hash, &$plaintext, &$salt, &$result ) ) ) {
			return $result;
		}

		$test = static::crypt( $plaintext, $this->options );
		return $test === $this->hash;
	}

	/**
	 * Update a hash with the given password using default options.
	 *
	 * Take the given password and hash it, then update the object
	 * state. The primary purposes of this function are for changing
	 * passwords and re-hashing an existing password when the configuration
	 * is changed.
	 *
	 * @param $plaintext Password to hash
	 */
	public function update( $plaintext ) {
		global $wgPasswordSalt;
		$this->options = static::getParams();
		$salt = array_key_exists( 'salt', $this->options ) ? $this->options['salt'] : '';

		if( !wfRunHooks( 'UserCryptPassword', array( &$plaintext, &$salt, &$wgPasswordSalt, &$this->hash ) ) ) {
			return;
		}
		$this->hash = static::crypt( $plaintext, $this->options );
	}

	/**
	 * Determine if the hash needs to be updated.
	 *
	 * Check the options of the hash agains the default. If they do not
	 * match, then the hash needs to be updated. Otherwise, it is fine.
	 *
	 * @return bool True if needs update, false otherwise
	 */
	public function needsUpdate() {
		$default = static::getParams();
		$current = $this->options;

		// Remove salts as they'll always be different
		unset( $default['salt'] );
		unset( $current['salt'] );

		return $default != $current;
	}
}

/**
 * Hash type for MediaWiki's original unsalted hash. It is
 * simply an MD5 of the password (or an MD5 of the user id
 * plus password, depending on configuration).
 */
class PasswordHash_MWOldCrypt extends PasswordHash {
	/**
	 * Determine if the given hash is a old-MediaWiki-style hash.
	 *
	 * Old MediaWiki-style hashes usually are just plain hex digests,
	 * but the User class will add :A: to the beginning. If the hash
	 * begins with :A:, then it is indeed this type.
	 *
	 * @see PasswordHash::isInstance
	 * @param $hash Hash to analyze
	 * @return True if the type matches, false otherwise
	 */
	protected static function isInstance( $hash ) {
		return substr( $hash, 0, 3 ) == ':A:';
	}

	/**
	 * Get the parameters for an old-MediaWiki-style hash.
	 *
	 * Split the hash using colons as the delimeter. Some hashes have
	 * only a hash while others also have a salt, i.e., the user ID.
	 * Determine which type it is and return an array with the salt, or
	 * lack thereof. If no hash is provided, give the default of no hash.
	 *
	 * @see PasswordHash::getParams
	 * @param $hash Hash to analyze
	 * @return array An array with the salt, if it exists
	 */
	protected static function getParams( $hash = false ) {
		if( $hash ) {
			$split = explode( ':', $hash );
			if( count( $split ) == 4 ) {
				// Salted version
				list( $empty, $type, $salt, $hash ) = $split;
			} elseif( count( $split ) == 3 ) {
				// Unsalted version
				list( $empty, $type, $hash ) = $split;
				$salt = null;
			} else {
				// Invalid hash
				return false;
			}
		} else {
			// Default options
			$salt = null;
		}
		return array( 'salt' => $salt );
	}

	/**
	 * Create an old-MediaWiki-style hash.
	 *
	 * A old-MediaWiki-style hash can optionally have a salt.
	 * Otherwise there are no other options. If there is no
	 * salt, then simply MD5 the password. If there is indeed
	 * a salt, then MD5 the salt || '-' || password.
	 *
	 * @see PasswordHash::crypt
	 * @param $password Password to hash
	 * @param $options Array with salt
	 * @return string Hash
	 */
	protected static function crypt( $password, $options ) {
		if( $options['salt'] === null ) {
			return ':A:' . md5( $password );
		} else {
			return ":A:{$options['salt']}:" . md5( $options['salt'] . '-' . $password );
		}
	}

	/**
	 * Return an array of test vectors for passwords hashed old-MediaWiki-style.
	 *
	 * @see PasswordHash::tests
	 * @return array Array of test vectors
	 */
	public static function tests() {
		return array(
			array( '',                           ':A:d41d8cd98f00b204e9800998ecf8427e' ),
			array( 'a',                          ':A:0cc175b9c0f1b6a831c399e269772661' ),
			array( 'abc',                        ':A:900150983cd24fb0d6963f7d28e17f72' ),
			array( 'message digest',             ':A:f96b697d7cb7938d525a2f31aaf161d0' ),
			array( 'abcdefghijklmnopqrstuvwxyz', ':A:c3fcd3d76192e4007dfb496cca67e13b' ),
			array( 'eYb9HEBsAvGj',               ':A:ad0846302f78942be18f36ab2f828cb6' ),
			array( 'jK6R2fqM43sp',               ':A:e473fcfd0131747f71a069dbce188327' ),
			array( 'RnQLEMB5fGkm',               ':A:c667b8ad16dcb8c259607184ae312e33' ),
			// Invalid hashes
			array( false,                        ':A:'                                 ),
			array( false,                        ':A:one:two:three'                    ),
		);
	}
}

/**
 * Hash type for MediaWiki's old salted hash. It creates an
 * MD5 of an 8-character hex salt, a dash, and an MD5 of the
 * password, all concatenated together.
 */
class PasswordHash_MWSalted extends PasswordHash {
	/**
	 * Determine if the given hash is a salted MediaWiki-style hash.
	 *
	 * Salted MediaWiki-style hashes begin with the characters :B: and
	 * contain a number of colon separate options. If the first three
	 * character match, then it is indeed this type of hash.
	 *
	 * @see PasswordHash::isInstance
	 * @param $hash Hash to analyze
	 * @return True if the type matches, false otherwise
	 */
	protected static function isInstance( $hash ) {
		return substr( $hash, 0, 3 ) == ':B:';
	}

	/**
	 * Get the parameters for a salted MediaWiki-style hash.
	 *
	 * A MediaWiki-style hash has a salt and the actual hash.
	 * If no hash is provided, generate a salt randomly.
	 * Otherwise, extract the salt in the hash and return.
	 *
	 * @see PasswordHash::getParams
	 * @param $hash Hash to analyze
	 * @return array Array with the salt for the hash
	 */
	protected static function getParams( $hash = false ) {
		if( $hash ) {
			$split = explode( ':', $hash );
			if( count( $split ) == 4 ) {
				list( $empty, $type, $salt, $realHash ) = explode( ':', $hash, 4 );
			} else {
				// Invalid hash
				return false;
			}
		} else {
			global $wgPasswordHashOptions;
			// Default: random salt.
			$salt = MWCryptRand::generateHex( ceil( $wgPasswordHashOptions['MWSalted']['saltbits'] / 8 ) );
		}
		return array( 'salt' => $salt );
	}

	/**
	 * Create a salted MediaWiki-style hash.
	 *
	 * Take a password and make an MD5 hash of it. Then concatenate
	 * a salt, '-', and the hashed password, and hash the combination.
	 *
	 * For $options, a salt must be provided, though commonly this is not a problem as only internal
	 * methods will be calling this function with already validated options.
	 *
	 * @see PasswordHash::crypt
	 * @param $hash Hash to analyze
	 * @param $options Array with a salt
	 * @return string Hash
	 */
	protected static function crypt( $password, $options ) {
		return ":B:{$options['salt']}:" . md5( $options['salt'] . '-' . md5( $password ) );
	}

	/**
	 * Return an array of test vectors for passwords hashed MediaWiki-style.
	 *
	 * @see PasswordHash::tests
	 * @return array Array of test vectors
	 */
	public static function tests() {
		return array(
			array( 'eYb9HEBsAvGj', ':B:dd67a8c4:abc2cdb1c7893c39ac98726707e70277' ),
			array( 'jK6R2fqM43sp', ':B:d37b511a:81b962e863bd9c76ed403c3e7e86a8a0' ),
			array( 'RnQLEMB5fGkm', ':B:f3d2594e:7968b356bbde59812608e44dd590f4c3' ),
			// Invalid hashes
			array( false,          ':B:'                                          ),
			array( false,          ':B:onlyone'                                   ),
			array( false,          ':B:more:than:two'                             ),
		);
	}
}

/**
 * Hash type for PBKDF2, the PKCS key derivation function. It uses
 * HMAC hashing and XORs together a specific number of hash iterations.
 * It then repeats the process for a number of blocks, concatenating
 * each block onto the last, to generate a key of a specific length.
 */
class PasswordHash_PBKDF2 extends PasswordHash {
	/**
	 * Determine if the given hash is a salted PBKDF2 hash.
	 *
	 * Salted PBKDF2 hashes begin with the characters :PBKDF2: and
	 * contain a number of colon separate options. If the first
	 * character match, then it is indeed this type of hash.
	 *
	 * @see PasswordHash::isInstance
	 * @param $hash Hash to analyze
	 * @return True if the type matches, false otherwise
	 */
	protected static function isInstance( $hash ) {
		return substr( $hash, 0, 8 ) == ':PBKDF2:';
	}

	/**
	 * Get the parameters for a PBKDF2 hash.
	 *
	 * The PBKDF2 function takes four parameters: a hash function,
	 * the number of iterations, the desired key length, and a salt.
	 * If no hash is specified, give defaults based on $wgPasswordHashOptions.
	 * Otherwise, extract the parameters from the hash and return.
	 *
	 * @see PasswordHash::getParams
	 * @param $hash Hash to analyze
	 * @return array Array with the hash function, iteration count, key length, and salt
	 */
	protected static function getParams( $hash = false ) {
		if( $hash ) {
			$split = explode( ':', $hash );
			if( count( $split ) == 6 ) {
				list( $empty, $type, $hash, $iterations, $salt, $realHash ) = $split;

				// Decode the salt and hash.
				$salt = base64_decode( $salt );
				$realHash = base64_decode( $realHash );
				// Key length is inferred from the length of the hash.
				$length = strlen( $realHash );
			} else {
				// Invalid hash
				return false;
			}
		} else {
			// Default to configuration options with random salt.
			global $wgPasswordHashOptions;
			$hash = $wgPasswordHashOptions['PBKDF2']['hash'];
			$iterations = $wgPasswordHashOptions['PBKDF2']['iterations'];
			$salt = MWCryptRand::generate( ceil( $wgPasswordHashOptions['PBKDF2']['saltbits'] / 8 ) );

			if( array_key_exists( 'length', $wgPasswordHashOptions['PBKDF2'] ) ) {
				$length = $wgPasswordHashOptions['PBKDF2']['length'];
			} else {
				// Length isn't specified in config, so default to length of hash function.
				$length = strlen( hash( $wgPasswordHashOptions['PBKDF2']['hash'], null, true ) );
			}
		}
		return array( 'hash' => $hash, 'iterations' => $iterations, 'length' => $length, 'salt' => $salt );
	}

	/**
	 * Create a PBKDF2 style hash.
	 *
	 * Take the password and, using the given options, HMAC the password and
	 * salt a number of times and XOR all the iterations together. Then repeat
	 * this process until the key is long enough for the desired length.
	 *
	 * For $options, a hash function, iteration count, key length, and salt
	 * must be provided, though commonly this is not a problem as only internal
	 * methods will be calling this function with already validated options.
	 *
	 * @see PasswordHash::crypt
	 * @param $password Password to hash
	 * @param $options Options with hash function, iteration count, key length, and salt
	 * @return string Hash
	 */
	protected static function crypt( $password, $options ) {
		$hash_len = strlen( hash( $options['hash'], null, true ) );
		$blocks = ceil( $options['length'] / $hash_len );

		$key = '';
		for( $block = 1; $block <= $blocks; $block++ ) {
			// Salt scheduling is done by concatenating the salt and the block number.
			$block_salt = $options['salt'] . pack( 'N', $block );

			// Initial HMAC iteration.
			$final = $hash = hash_hmac( $options['hash'], $block_salt, $password, true );

			// Loop the rest of the iterations, XORing each time.
			for( $i = 1; $i < $options['iterations']; $i++ ) {
				$hash = hash_hmac( $options['hash'], $hash, $password, true );
				$final ^= $hash;
			}
			$key .= $final;
		}
		// Narrow down to the final key length.
		$key = substr( $key, 0, $options['length'] );

		return ":PBKDF2:{$options['hash']}:{$options['iterations']}:" . base64_encode( $options['salt'] ) . ':' . base64_encode( $key );
	}

	/**
	 * Return an array of test vectors for passwords hashed with PBKDF2.
	 *
	 * The test vectors returned are taken directly from PKCS #5 and converted
	 * to base64. Additional tests can be added, but the current ones must not
	 * be removed.
	 *
	 * @see PasswordHash::tests
	 * @return array Array of test vectors
	 */
	public static function tests() {
		return array(
			array( 'password',                 ':PBKDF2:sha1:1:c2FsdA==:DGDID5YfDnHzqbUkr2ASBi/gN6Y=' ),
			array( 'password',                 ':PBKDF2:sha1:2:c2FsdA==:6mwBTcctb4zNHtkqzh1B8NjeiVc=' ),
			array( 'password',                 ':PBKDF2:sha1:4096:c2FsdA==:SwB5AbdlSJq+rUnZJvch0GWkKcE=' ),
			array( 'password',                 ':PBKDF2:sha1:16777216:c2FsdA==:7v49Yc1NpOTplFs9a6IVjCY06YQ=' ),
			array( 'passwordPASSWORDpassword', ':PBKDF2:sha1:4096:c2FsdFNBTFRzYWx0U0FMVHNhbHRTQUxUc2FsdFNBTFRzYWx0:PS7sT+QchJuAyNg2YsDkSospGpZM8vBwOA==' ),
			// Invalid hashes
			array( false,                      ':PBKDF2:one'                                                                                             ),
			array( false,                      ':PBKDF2:one:two'                                                                                         ),
			array( false,                      ':PBKDF2:one:two:three'                                                                                   ),
			array( false,                      ':PBKDF2:more:than:four:parts:test'                                                                        ),
		);
	}
}
