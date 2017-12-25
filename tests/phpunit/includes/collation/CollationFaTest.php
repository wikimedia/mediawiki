<?php

/**
 * @covers CollationFa
 */
class CollationFaTest extends MediaWikiTestCase {

	/*
	 * The ordering is a weird hack designed to work only with a very
	 * specific version of libicu, and as such can't really be unit tested
	 * against a random version of libicu
	 */

	public function setUp() {
		parent::setUp();
		$this->checkPHPExtension( 'intl' );
	}

	/**
	 * @dataProvider provideGetFirstLetter
	 */
	public function testGetFirstLetter( $letter, $str ) {
		$coll = new CollationFa;
		$this->assertEquals( $letter, $coll->getFirstLetter( $str ), $str );
	}

	public function provideGetFirstLetter() {
		return [
			[
				'۷',
				'۷'
			],
			[
				'ا',
				'ا'
			],
			[
				'ا',
				'ایران'
			],
			[
				'ب',
				'برلین'
			],
			[
				'و',
				'واو'
			],
			[ "\xd8\xa7", "\xd8\xa7Foo" ],
			[ "\xd9\x88", "\xd9\x88Foo" ],
			[ "\xd9\xb2", "\xd9\xb2Foo" ],
			[ "\xd9\xb3", "\xd9\xb3Foo" ],
		];
	}
}
