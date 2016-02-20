<?php
/**
 *
 * @group Hash
 */

class MWCryptHashTest extends MediaWikiTestCase {

	public function testHashLength() {
		if ( MWCryptHash::hashAlgo() !== 'whirlpool' ) {
			$this->markTestSkipped( 'Hash algorithm isn\'t whirlpool' );
		}

		$this->assertEquals( 64, MWCryptHash::hashLength(), 'Raw hash length' );
		$this->assertEquals( 128, MWCryptHash::hashLength( false ), 'Hex hash length' );
	}

	public function testHash() {
		if ( MWCryptHash::hashAlgo() !== 'whirlpool' ) {
			$this->markTestSkipped( 'Hash algorithm isn\'t whirlpool' );
		}

		$data = 'foobar';
		// @codingStandardsIgnoreStart Generic.Files.LineLength
		$hash = '9923afaec3a86f865bb231a588f453f84e8151a2deb4109aebc6de4284be5bebcff4fab82a7e51d920237340a043736e9d13bab196006dcca0fe65314d68eab9';
		// @codingStandardsIgnoreEnd

		$this->assertEquals(
			hex2bin( $hash ),
			MWCryptHash::hash( $data ),
			'Raw hash'
		);
		$this->assertEquals(
			$hash,
			MWCryptHash::hash( $data, false ),
			'Hex hash'
		);
	}

	public function testHmac() {
		if ( MWCryptHash::hashAlgo() !== 'whirlpool' ) {
			$this->markTestSkipped( 'Hash algorithm isn\'t whirlpool' );
		}

		$data = 'foobar';
		$key = 'secret';
		// @codingStandardsIgnoreStart Generic.Files.LineLength
		$hash = 'ddc94177b2020e55ce2049199fd9cc6327f416ff6dc621cc34cb43d9bec61d73372b4790c0e24957f565ecaf2d42821e6303619093e99cbe14a3b9250bda5f81';
		// @codingStandardsIgnoreEnd

		$this->assertEquals(
			hex2bin( $hash ),
			MWCryptHash::hmac( $data, $key ),
			'Raw hmac'
		);
		$this->assertEquals(
			$hash,
			MWCryptHash::hmac( $data, $key, false ),
			'Hex hmac'
		);
	}

}
