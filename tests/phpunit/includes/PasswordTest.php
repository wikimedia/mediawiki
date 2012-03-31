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
			array( 'PBKHM', 'dHh0luxQuHY=:sha256:10000:32:kckuugkKXfXVbnFb31d2qhEabjJwbGfsw0yDuqC7FYQ=', 'asdf' ),
			array( 'PBKHM', 'NKInyrRlIFI=:sha256:10000:32:XdP2IjvWeSKuKdlizJhDShpYeGTgZjtTcpChZ6+S3d0=', 'test' ),
			array( 'PBKHM', 'BZj6RlcUk8E=:sha256:10000:32:4yt4FRipoPjrsOwb3/LDeOBpBzrtysB9xgjUvmF70Is=', 'Hello World' ),
			array( 'PBKHM', 'tAWZn1GvOt8=:sha256:10000:32:Fyy/gs6Rfif0t5ieui2L5Gka7pgFhhpS4vux+dFHHyM=', 'Passw0rd' ),
			array( 'PBKHM', 'yTFdI1Wo9jM=:sha256:10000:32:3pepWERts9s8oBrsME3OIpbExRX3CRVCb4Op+T1Abzw=', 'D0g.....................' ),
			array( 'PBKHM', 'nplOY9RgPpM=:sha256:10000:32:cx5cjr2SpLBZi2wTsKcZUlZKXwc+sPkUsThBLlVevTg=', 'KiWVic0F6Le&%Ejn8p3j1vm@#XQclWOV' ),
			## Set 2 (whirlpool)
			array( 'PBKHM', 'wjkCoIsFtUY=:whirlpool:10000:64:wk52FpVk3CqqVnQNdcoGlfyBnWmWmXhyGJCYu3/yZCRDRk1wB6H4GwCTGcqWJKTR/dPqcBL7+ms9A4A72+T4rA==', 'asdf' ),
			array( 'PBKHM', 'jd57jFUArKw=:whirlpool:10000:64:PV/GFJGO3xlmAnCxYXg18ifxuojvWMTQnHIC4iXrTVKmdhFZ93p09gXfr80cCElo9baZtqblMcISUaZYt0zmHw==', 'test' ),
			array( 'PBKHM', 'nFVZnAyGYYg=:whirlpool:10000:64:QOz8yQFT0zRqBxrI4Q+lELJvqNLt25Gx6K1FjfoI8Qtuhql7Qu2BI87MkSKndGlW5hUPHs00O/0N1PdZ8T41ww==', 'Hello World' ),
			array( 'PBKHM', 'wETbmyUF348=:whirlpool:10000:64:wjRS2GnffDM9wMO1zUzrEMem23ZJcFHtfeqip1joW3GUlbzvWfaWZr/lR3QDILMyyEdvWfVie3y2bgjXqv4LwA==', 'Passw0rd' ),
			array( 'PBKHM', 'XnArcNNnZwU=:whirlpool:10000:64:2ibbEHUwQF7puzg32VHbdMuWOsdZz48FiBoeABo50uBpl8Vxj6Drv2CYJekmlmQvNlqYmzdUsSvqZhSpFY/Bjw==', 'D0g.....................' ),
			array( 'PBKHM', 'BzzF5px168Y=:whirlpool:10000:64:VFnmaCJbH+ZmXli46jlLfrtTDwn/2oo03PBsxp/YpfHHjUMiGPXIYf1J4gGRhoScFQqnvsE9wzbdz/JyLKAN4Q==', 'KiWVic0F6Le&%Ejn8p3j1vm@#XQclWOV' ),
			## Set 3 (50000 sha1)
			array( 'PBKHM', 'PCMjXt0O6C0=:sha1:50000:20:1z7n/IBx1pWFHctEl/m6YzjMxfs=', 'asdf' ),
			array( 'PBKHM', 'O/hYbezDyrs=:sha1:50000:20:YQFznLKCUL4h60iaAiM2f/RosyQ=', 'test' ),
			array( 'PBKHM', 'JmVkBc4VXMc=:sha1:50000:20:LflHMVmRz1SZkdJ7gAZW/cXD5cQ=', 'Hello World' ),
			array( 'PBKHM', 'Ec8lbsBpw+Q=:sha1:50000:20:UYR/XGmawqLJODi/A7U/UlyRvfU=', 'Passw0rd' ),
			array( 'PBKHM', 'BR0PFHK4mBI=:sha1:50000:20:xnB1jxbM9EE8RZ8BRbd/ISY0NwA=', 'D0g.....................' ),
			array( 'PBKHM', 'mgIISHgeYfY=:sha1:50000:20:FCBarvMyX7WURbypBireSGN6zcI=', 'KiWVic0F6Le&%Ejn8p3j1vm@#XQclWOV' ),
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
		$result = $type->compare( $data, $password );
		$this->assertInstanceOf( 'Status', $result, 'Result is Status object' );
		$this->assertTrue( $result->isGood(), 'Status result is not fatal error.' );
		$this->assertTrue( $result->getValue(), 'Good password is valid.' );

		// Test negative comparison against incorrect passwords
		foreach ( $allPasswords as $badPass ) {
			if ( $password == $badPass ) {
				// Skip the good password
				continue;
			}
			$result = $type->compare( $data, $badPass );
			$this->assertInstanceOf( 'Status', $result, 'Result is Status object' );
			$this->assertTrue( $result->isGood(), 'Status result is not fatal error.' );
			$this->assertFalse( $result->getValue(), 'Bad password is invalid.' );
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
				$result = $cryptType->compare( $data, $password );
				$this->assertTrue( $result->getValue(), "Good password is valid for $typeCode test on password $password." );
				// Fuzz test a bad password too
				$badPass = $this->generatePasswordFuzz();
				if ( $password === $badPass ) {
					// On the extremely unlikely edge case that we randomly generate the same password
					// sequentually skip the bad password test
					continue;
				}
				$result = $cryptType->compare( $data, $badPass );
				$this->assertFalse( $result->getValue(), "Bad password is invalid for $typeCode test on password $password with bad password $badPass." );
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
			$result = PasswordExposed::compare( $data, $password );
			$this->assertTrue( $result->getValue(), "Good password is valid for password $password." );
			// Fuzz test a bad password too
			$badPass = $this->generatePasswordFuzz();
			if ( $password === $badPass ) {
				// On the extremely unlikely edge case that we randomly generate the same password
				// sequentually skip the bad password test
				continue;
			}
			$result = PasswordExposed::compare( $data, $badPass );
			$this->assertFalse( $result->getValue(), "Bad password is invalid for password $password with bad password $badPass." );
		}
	}

}