<?php
/**
 *
 * @group HKDF
 */

class MWCryptHKDFTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( 'wgSecretKey', '5bf1945342e67799cb50704a7fa19ac6' );
	}

	/**
	 * Test basic usage works
	 */
	public function testGenerate() {
		$a = MWCryptHKDF::generateHex( 64 );
		$b = MWCryptHKDF::generateHex( 64 );

		$this->assertTrue( strlen( $a ) == 64, "MWCryptHKDF produced fewer bytes than expected" );
		$this->assertTrue( strlen( $b ) == 64, "MWCryptHKDF produced fewer bytes than expected" );
		$this->assertFalse( $a == $b, "Two runs of MWCryptHKDF produced the same result." );
	}

	/**
	 * @dataProvider providerRfc5869
	 */
	public function testRfc5869( $hash, $ikm, $salt, $info, $L, $prk, $okm ) {
		$ikm = hex2bin( $ikm );
		$salt = hex2bin( $salt );
		$info = hex2bin( $info );
		$okm = hex2bin( $okm );
		$result = MWCryptHKDF::HKDF( $hash, $ikm, $salt, $info, $L );
		$this->assertEquals( $okm, $result );
	}

	/**
	 * Test vectors from Appendix A on http://tools.ietf.org/html/rfc5869
	 */
	public static function providerRfc5869() {

		// @codingStandardsIgnoreStart Generic.Files.LineLength
		return [
			// A.1
			[
				'sha256',
				'0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b', // ikm
				'000102030405060708090a0b0c', // salt
				'f0f1f2f3f4f5f6f7f8f9', // context
				42, // bytes
				'077709362c2e32df0ddc3f0dc47bba6390b6c73bb50f9c3122ec844ad7c2b3e5', // prk
				'3cb25f25faacd57a90434f64d0362f2a2d2d0a90cf1a5a4c5db02d56ecc4c5bf34007208d5b887185865' // okm
			],
			// A.2
			[
				'sha256',
				'000102030405060708090a0b0c0d0e0f101112131415161718191a1b1c1d1e1f202122232425262728292a2b2c2d2e2f303132333435363738393a3b3c3d3e3f404142434445464748494a4b4c4d4e4f',
				'606162636465666768696a6b6c6d6e6f707172737475767778797a7b7c7d7e7f808182838485868788898a8b8c8d8e8f909192939495969798999a9b9c9d9e9fa0a1a2a3a4a5a6a7a8a9aaabacadaeaf',
				'b0b1b2b3b4b5b6b7b8b9babbbcbdbebfc0c1c2c3c4c5c6c7c8c9cacbcccdcecfd0d1d2d3d4d5d6d7d8d9dadbdcdddedfe0e1e2e3e4e5e6e7e8e9eaebecedeeeff0f1f2f3f4f5f6f7f8f9fafbfcfdfeff',
				82,
				'06a6b88c5853361a06104c9ceb35b45cef760014904671014a193f40c15fc244',
				'b11e398dc80327a1c8e7f78c596a49344f012eda2d4efad8a050cc4c19afa97c59045a99cac7827271cb41c65e590e09da3275600c2f09b8367793a9aca3db71cc30c58179ec3e87c14c01d5c1f3434f1d87'
			],
			// A.3
			[
				'sha256',
				'0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b', // ikm
				'', // salt
				'', // context
				42, // bytes
				'19ef24a32c717b167f33a91d6f648bdf96596776afdb6377ac434c1c293ccb04', // prk
				'8da4e775a563c18f715f802a063c5a31b8a11f5c5ee1879ec3454e5f3c738d2d9d201395faa4b61a96c8' // okm
			],
			// A.4
			[
				'sha1',
				'0b0b0b0b0b0b0b0b0b0b0b', // ikm
				'000102030405060708090a0b0c', // salt
				'f0f1f2f3f4f5f6f7f8f9', // context
				42, // bytes
				'9b6c18c432a7bf8f0e71c8eb88f4b30baa2ba243', // prk
				'085a01ea1b10f36933068b56efa5ad81a4f14b822f5b091568a9cdd4f155fda2c22e422478d305f3f896' // okm
			],
			// A.5
			[
				'sha1',
				'000102030405060708090a0b0c0d0e0f101112131415161718191a1b1c1d1e1f202122232425262728292a2b2c2d2e2f303132333435363738393a3b3c3d3e3f404142434445464748494a4b4c4d4e4f', // ikm
				'606162636465666768696a6b6c6d6e6f707172737475767778797a7b7c7d7e7f808182838485868788898a8b8c8d8e8f909192939495969798999a9b9c9d9e9fa0a1a2a3a4a5a6a7a8a9aaabacadaeaf', // salt
				'b0b1b2b3b4b5b6b7b8b9babbbcbdbebfc0c1c2c3c4c5c6c7c8c9cacbcccdcecfd0d1d2d3d4d5d6d7d8d9dadbdcdddedfe0e1e2e3e4e5e6e7e8e9eaebecedeeeff0f1f2f3f4f5f6f7f8f9fafbfcfdfeff', // context
				82, // bytes
				'8adae09a2a307059478d309b26c4115a224cfaf6', // prk
				'0bd770a74d1160f7c9f12cd5912a06ebff6adcae899d92191fe4305673ba2ffe8fa3f1a4e5ad79f3f334b3b202b2173c486ea37ce3d397ed034c7f9dfeb15c5e927336d0441f4c4300e2cff0d0900b52d3b4' // okm
			],
		];
		// @codingStandardsIgnoreEnd
	}
}
