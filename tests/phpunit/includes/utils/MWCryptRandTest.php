<?php

/**
 * @covers MWCryptRand
 */
class MWCryptRandTest extends MediaWikiTestCase {

	public function testGenerateBase32() {
		$a = MWCryptRand::generateBase32( 7 );
		$this->assertRegExp( '/[2-7A-Z]{7}/', $a );

		$b = MWCryptRand::generateBase32( 7 );
		$this->assertRegExp( '/[2-7A-Z]{7}/', $b );

		$this->assertNotEquals( $a, $b );
	}

	public function testGenerateHex() {
		$a = MWCryptRand::generateHex( 3 );
		$this->assertRegExp( '/[0-9a-f]{3}/', $a );

		$b = MWCryptRand::generateHex( 3 );
		$this->assertRegExp( '/[0-9a-f]{3}/', $b );

		$this->assertNotEquals( $a, $b );
	}
}
