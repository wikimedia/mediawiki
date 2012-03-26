<?php

class PasswordExposed extends Password {

	public static function getTypes() {
		self::init();
		return self::$types;
	}

	public static function getPreferredType() {
		self::init();
		return self::$preferredType;
	}

}

// PLAINTEXT Password type this is registered before the others as a test case for basic
// functionality rather than for testing a password type itself.
class PasswordTestType_PLAINTEXT extends PasswordType {

	public function crypt( $password ) {
		return $password;
	}

	public function verify( $data, $password ) {
		return $data === $password;
	}

	public function needsUpdate( $data ) {
		return false;
	}

	public function knownPasswordData() {
		return array(
			array( 'asdf', 'asdf' ),
			array( 'test', 'test' ),
			array( 'Hello World', 'Hello World' ),
			array( 'Passw0rd', 'Passw0rd' ),
			array( 'D0g.....................', 'D0g.....................' ),
			array( 'KiWVic0F6Le&%Ejn8p3j1vm@#XQclWOV', 'KiWVic0F6Le&%Ejn8p3j1vm@#XQclWOV' ),
		);
	}

}
PasswordExposed::registerType( 'PLAINTEXT', 'PasswordTestType_PLAINTEXT' );

class PasswordTest extends MediaWikiTestCase {

	protected $types;

	/**
	 * Simply ensure that the preferredType is a valid type
	 */
	function testPreferred() {
		$typeKeys = array_keys( PasswordExposed::getTypes() );
		$this->assertTrue( in_array( PasswordExposed::getPreferredType(), $typeKeys ), 'preferredType is defined in types.' );
	}

	/**
	 * Ensure that all password types are valid classes.
	 */
	function testClasses() {
		$types = PasswordExposed::getTypes();
		foreach ( $types as $typeCode => $className ) {
			$this->assertTrue( MWInit::classExists( $className ), "Type $typeCode's class $className exists." );
			$cryptType = new $className( $typeCode );
			$this->assertInstanceOf( 'PasswordType', $cryptType, "Type $typeCode's class $className implements PasswordType." );
		}
	}

	/**
	 * Data provider with a collection of pre-made passwords and their hashes for certain types.
	 */
	function dataKnownPasswords() {
		$testData = array();

		$types = PasswordExposed::getTypes();
		foreach ( $types as $typeCode => $className ) {
			$cryptType = PasswordExposed::getType( $typeCode );
			$typeTestData = $cryptType->knownPasswordData();
			foreach ( $typeTestData as $test ) {
				list( $data, $password ) = $test;
				$testData[] = array( $typeCode, $data, $password );
			}
		}

		return $testData;
	}

	/**
	 * Test a selection of pre-made password hashes and ensure that they are still valid
	 * @dataProvider dataKnownPasswords
	 */
	function testKnownPasswords( $typeCode, $data, $password ) {
		static $allPasswords = array( 'asdf', 'test', 'Hello World', 'Passw0rd', 'KiWVic0F6Le&%Ejn8p3j1vm@#XQclWOV' );
		$type = PasswordExposed::getType( $typeCode );

		// Test comparison against good password
		$result = $type->verify( $data, $password );
		$this->assertTrue( $result, 'Good password is valid.' );

		// Test negative comparison against incorrect passwords
		foreach ( $allPasswords as $badPass ) {
			if ( $password == $badPass ) {
				// Skip the good password
				continue;
			}
			$result = $type->verify( $data, $badPass );
			$this->assertFalse( $result, 'Bad password is invalid.' );
		}
	}

	/**
	 * Helper method to generate a random password to use for fuzz testing
	 * This is NOT a method for cryptographic use, it does not use a cryptographically strong random number generator.
	 */
	public function generatePasswordFuzz() {
		// Vary the password length, for our tests use passwords in between 7 and 32 characters in length
		$length = mt_rand( 7, 32 );
		// Characters to use for passwords, letters, numbers, and a few symbols
		static $chars = 'ABCDEFGHIJKLMNOPQRSTUVXYZabcdefghijklmnopqrstuvxy0123456789!@#$%^&*';

		$str = '';
		for ( $c = 0; $c < $length; $c++ ) {
			$pos = mt_rand( 0, strlen( $chars ) - 1 );
			$str .= $chars[$pos];
		}
		return $str;
	}

	/**
	 * Password fuzz testing for every type
	 */
	function testFuzzType() {
		$types = PasswordExposed::getTypes();
		foreach ( $types as $typeCode => $className ) {
			$cryptType = PasswordExposed::getType( $typeCode );

			// Fuzz test 100 passwords for each type
			for ( $count = 100; $count > 0; --$count ) {
				$password = $this->generatePasswordFuzz();
				$data = $cryptType->crypt( $password );
				$result = $cryptType->verify( $data, $password );
				$this->assertTrue( $result, "Good password is valid for $typeCode test on password $password." );
				// Fuzz test a bad password too
				$badPass = $this->generatePasswordFuzz();
				if ( $password === $badPass ) {
					// On the extremely unlikely edge case that we randomly generate the same password
					// sequentually skip the bad password test
					continue;
				}
				$result = $cryptType->verify( $data, $badPass );
				$this->assertFalse( $result, "Bad password is invalid for $typeCode test on password $password with bad password $badPass." );
			}
		}
	}

	/**
	 * Password fuzz testing for the preferred type using the standard interface
	 */
	function testFuzz() {
		// Fuzz test 100 passwords
		for ( $count = 100; $count > 0; --$count ) {
			$password = $this->generatePasswordFuzz();
			$data = PasswordExposed::crypt( $password );
			$result = PasswordExposed::verify( $data, $password );
			$this->assertTrue( $result, "Good password is valid for password $password." );
			// Fuzz test a bad password too
			$badPass = $this->generatePasswordFuzz();
			if ( $password === $badPass ) {
				// On the extremely unlikely edge case that we randomly generate the same password
				// sequentually skip the bad password test
				continue;
			}
			$result = PasswordExposed::verify( $data, $badPass );
			$this->assertFalse( $result, "Bad password is invalid for password $password with bad password $badPass." );
		}
	}

	/**
	 * Data for the rfc6070 test
	 */
	function dataRFC6070() {
		// These are the tests taken from rfc6070
		// They are left primarily in the format defined there to avoid errors
		return array(
			/* P = Password, S = Salt, c = Iterations, dkLen = dkLength, DK = DerivedKey */
			array( "password", "salt", 1, 20, "0c 60 c8 0f 96 1f 0e 71 f3 a9 b5 24 af 60 12 06 2f e0 37 a6" ),
			array( "password", "salt", 2, 20, "ea 6c 01 4d c7 2d 6f 8c cd 1e d9 2a ce 1d 41 f0 d8 de 89 57" ),
			array( "password", "salt", 4096, 20, "4b 00 79 01 b7 65 48 9a be ad 49 d9 26 f7 21 d0 65 a4 29 c1" ),
			array( "password", "salt", 16777216, 20, "ee fe 3d 61 cd 4d a4 e4 e9 94 5b 3d 6b a2 15 8c 26 34 e9 84" ),
			array(
				/* P */ "passwordPASSWORDpassword",
				/* S */ "saltSALTsaltSALTsaltSALTsaltSALTsalt",
				/* c */ 4096,
				/* dkLen */ 25,
				/* DK */ "3d 2e ec 4f e4 1c 84 9b 80 c8 d8 36 62 c0 e4 4a 8b 29 1a 96 4c f2 f0 70 38",
			),
		);
	}

	/**
	 * Test our PBKDF2-HMAC implementation with rfc6070's PBKDF2-HMAC-SHA1 test vectors to ensure that
	 * our implementation is a valid implementation of PBKDF2
	 * @dataProvider dataRFC6070
	 */
	function testRFC6070( $P, $S, $c, $dkLen, $DK ) {
		// Convert the list of ASCII bytes rfc6070 uses into a formatted binary dkey
		$dkey = '';
		$DKbytes = preg_split( '/\s+/', $DK );
		foreach( $DKbytes as $hex ) {
			$dkey .= chr( hexdec( $hex ) );
		}

		$implKey = Password_TypePBKHM::pbkdf2_hmac( 'sha1', $P, $S, $c, $dkLen );

		$result = $implKey === $dkey;
		$this->assertTrue( $result, "rfc6070 test vector match (Output: " . base64_encode( $implKey )
			. ") !== (DK: " . base64_encode( $dkey ) . ")" );
	}

	// @todo When PHP 5.5 is released start testing hash_pbkdf2 with the same tests we use on our own implementation

}