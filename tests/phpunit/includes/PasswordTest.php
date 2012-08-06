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
		return array(
			# Type A
			array( 'A', '912ec803b2ce49e4a541068d495ab570', 'asdf' ),
			array( 'A', '098f6bcd4621d373cade4e832627b4f6', 'test' ),
			array( 'A', 'b10a8db164e0754105b7a99be72e3fe5', 'Hello World' ),
			array( 'A', 'd41e98d1eafa6d6011d3a70f1a5b92f0', 'Passw0rd' ),
			array( 'A', 'ace0aab238adb911d27db0f767dda13e', 'D0g.....................' ),
			array( 'A', '19bf4669ce80fe42c09fc68ceb6fc75d', 'KiWVic0F6Le&%Ejn8p3j1vm@#XQclWOV' ),

			# Type B (3 sets with different salts)
			## Set 1
			array( 'B', '0549900c:8490f5e1c4283c1986a8a59b287d74dc', 'asdf' ),
			array( 'B', 'fa2630c2:469d0174c83ad114235dd50379bfd61a', 'test' ),
			array( 'B', '7ed91bde:3a29f005342c15f880843c575cdadab6', 'Hello World' ),
			array( 'B', '4392b6ac:9097931dbcffb3db2601fb73fb4fd969', 'Passw0rd' ),
			array( 'B', 'ca4a0984:36a2ee13001b274f573b2a8b4437398a', 'D0g.....................' ),
			array( 'B', 'ca0421e9:62b8224db9b3bd81b2293c97fb45ee15', 'KiWVic0F6Le&%Ejn8p3j1vm@#XQclWOV' ),
			## Set 2
			array( 'B', 'b6bfaddc:8335b0e24a382a69afd58bc08a8e8902', 'asdf' ),
			array( 'B', 'ddcc27bb:b59259f80a5354b3965b94e3caf88501', 'test' ),
			array( 'B', 'de7c3335:d5f0e74389e9ccc616853aad681a6c88', 'Hello World' ),
			array( 'B', 'd93f4959:05abd0d0fcc4713a22a4b43a1a831207', 'Passw0rd' ),
			array( 'B', '3156ea98:9d568e1ea7580bf299e4f09aba27477d', 'D0g.....................' ),
			array( 'B', 'b615768d:4b3ced67aaedd8917442ab258e40ec2e', 'KiWVic0F6Le&%Ejn8p3j1vm@#XQclWOV' ),
			## Set 3
			array( 'B', '6a2143cb:4d8a02f799cf64016dbd212e94e25bfb', 'asdf' ),
			array( 'B', 'd6c6c08d:03b4545eca23d05e26ffffa0cc833cf8', 'test' ),
			array( 'B', '2bcca772:b845721cdaaecaf6095ba5d5ad686b86', 'Hello World' ),
			array( 'B', '018cba3a:fadfb2f1b012d692969666335f8b9779', 'Passw0rd' ),
			array( 'B', 'e4408eef:55df39e98f4bad1485a38be9bc19559f', 'D0g.....................' ),
			array( 'B', 'ebf3811c:a7d1df3cdd22b972a104b03c47679c3b', 'KiWVic0F6Le&%Ejn8p3j1vm@#XQclWOV' ),

			# Type PBKHM (PBKDF2-HMAC)
			## Set 1 (sha256)
			array( 'PBKHM', 'yCaxCRg0J3I=:sha256:10000:32:IdNMgnu31vxOYZcZw+cuxHEV32AN0E1MUxlHUhI20iw=', 'asdf' ),
			array( 'PBKHM', '0365j6hCBrU=:sha256:10000:32:Ob/NnzUsA2H4GoztuLQuIknNnas0sUg/sHU2oUjfOzM=', 'test' ),
			array( 'PBKHM', '8RGGumgq6B4=:sha256:10000:32:/Ud4zVh9Ipn7GRHj9BKkHK2Xvaz1zrRvjEa07FfHAuw=', 'Hello World' ),
			array( 'PBKHM', 'XAZQ0Fx45JQ=:sha256:10000:32:SUKiNMCzhZ6BrRBdCXSaBY2w18twvPaKH3Eap98XbEo=', 'Passw0rd' ),
			array( 'PBKHM', 'hhgIP1sNKs8=:sha256:10000:32:rGVfA+2FLC3UeVEKt1GckpGpknGf9Nx87aietmlonHA=', 'D0g.....................' ),
			array( 'PBKHM', 'EbZ57a06GGw=:sha256:10000:32:WK+BzCnrW6x6PNSvjYK3km1udl14wY33YsV2lt42Gek=', 'KiWVic0F6Le&%Ejn8p3j1vm@#XQclWOV' ),
			## Set 2 (whirlpool)
			array( 'PBKHM', '4faSJAfABg8=:whirlpool:10000:64:ZC/qLHX0D24wPuznFwb+I4UBkE18JyZ9K1bGOo9J+IUtsuVHDvGhJXMSwvXuxFySuBLofzkjk0ITgcmGYp20yw==', 'asdf' ),
			array( 'PBKHM', 'QSwzOfVFWmY=:whirlpool:10000:64:Pxkr8D5dVFf/qD0LCmz3angHOeFOBBX6y0UBMAoUdr8IqE2OMe7yXD4rDO2rbdLas152HvUgIwl9LyagTuY89Q==', 'test' ),
			array( 'PBKHM', 'IgzraxBdmdM=:whirlpool:10000:64:uPwBse+hg2EFQ0s5wxCymC4O3KUfetSI+PRf6362pc8Cl7TlwKoh4KZAl2HukRiqBRXnhUUyl/NGjpveQJC3+w==', 'Hello World' ),
			array( 'PBKHM', 'rYNd9nHdKVs=:whirlpool:10000:64:V56yyy4XwyRISVhPcGeWpNpYFL4j/5wPEAWxUHDKe3grsE/HzzxVRKdTcO8ny2gttvQ9ofpcS4Ru2oUS7m2ALw==', 'Passw0rd' ),
			array( 'PBKHM', '2N0JKZRnNjQ=:whirlpool:10000:64:Og83KC5PP0Dxl5XnJ8RIywVF96Wm+GZrAtniE9eDj/znYqJ/JqqK+YDEqLrjaE8nOuozWeBwRd8Gx9KgHCiMzA==', 'D0g.....................' ),
			array( 'PBKHM', 'PYMVMwXUNn8=:whirlpool:10000:64:htNdMzShGUzqghNGh5K4ihP0K1XbCzDd6qs7X+HfEKUXLAEs06MmqMwWUSmi7IrgJ8kfF6kUjB+MBZPnSXVnZg==', 'KiWVic0F6Le&%Ejn8p3j1vm@#XQclWOV' ),
			## Set 3 (50000 sha1)
			array( 'PBKHM', 'zzet1WmGu+M=:sha1:50000:20:l+kWWTrjOjM6J1EObmDjzgyN1lE=', 'asdf' ),
			array( 'PBKHM', '9l/3BOJW4II=:sha1:50000:20:P00yS3YRcPUCn2yyI9/jlTaC9JA=', 'test' ),
			array( 'PBKHM', 'kXQjQwZsMY0=:sha1:50000:20:tPFfeEVbdAF9Bq24rD8CBiTaaf0=', 'Hello World' ),
			array( 'PBKHM', 'XCvqIz5QFjc=:sha1:50000:20:siG9gkUUNka7P9uvBo8dA7ZCZlE=', 'Passw0rd' ),
			array( 'PBKHM', '9buA3LvsLog=:sha1:50000:20:mAgZxNlL+4dWC8JCsJlq/5Tjn50=', 'D0g.....................' ),
			array( 'PBKHM', 'FnhgbiEVecw=:sha1:50000:20:0OU+1R0AM7v/0mlULaz366rTgz4=', 'KiWVic0F6Le&%Ejn8p3j1vm@#XQclWOV' ),
		);
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