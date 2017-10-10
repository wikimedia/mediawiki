<?php
class CollationFaTest extends MediaWikiTestCase {

	/*
	 * The ordering is a weird hack designed to work only with a very
	 * specific version of libicu, and as such can't really be unit tested
	 * against a random version of libicu
	 */

	public function setUp() {
		parent::setUp();
		if ( !extension_loaded( 'intl' ) ) {
			$this->markTestSkipped( "PHP extension 'intl' is not loaded, skipping." );
		}
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
			[ "\u{0627}", "\u{0627}Foo" ],
			[ "\u{0648}", "\u{0648}Foo" ],
			[ "\u{0672}", "\u{0672}Foo" ],
			[ "\u{0673}", "\u{0673}Foo" ],
		];
	}
}
