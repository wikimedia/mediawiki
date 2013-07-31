<?php

class PasswordTest extends MediaWikiTestCase {
	public static function providePasswordTypes() {
		return Password::getTests();
	}

	/**
	 * @dataProvider providePasswordTypes
	 */
	public function testHashing( $shouldMatch, $hash, $password ) {
		$hash = Password::newFromCiphertext( $hash );
		$this->assertEquals( $shouldMatch, $hash->equals( $password ) );
	}
}
