<?php

class CustomUppercaseCollationTest extends MediaWikiTestCase {

	public function setUp() {
		$this->collation = new CustomUppercaseCollation( [
			'D',
			'C',
			'B'
		], Language::factory( 'en' ) );

		parent::setUp();
	}

	/**
	 * @dataProvider providerOrder
	 */
	public function testOrder( $first, $second, $msg ) {
		$sortkey1 = $this->collation->getSortKey( $first );
		$sortkey2 = $this->collation->getSortKey( $second );

		$this->assertTrue( strcmp( $sortkey1, $sortkey2 ) < 0, $msg );
	}

	public function providerOrder() {
		return [
			[ 'X', 'Z', 'Maintain order of unrearranged' ],
			[ 'D', 'C', 'Actually resorts' ],
			[ 'D', 'B', 'resort test 2' ],
			[ 'Adobe', 'Abode', 'not first letter' ],
			[ '💩 ', 'C', 'Test relocated to end' ],
			[ 'c', 'b', 'lowercase' ],
			[ 'x', 'z', 'lowercase original' ],
			[ 'C50D', 'C100', 'Numbers' ]
		];
	}

	/**
	 * @dataProvider provideGetFirstLetter
	 */
	public function testGetFirstLetter( $string, $first ) {
		$this->assertSame( $this->collation->getFirstLetter( $string ), $first );
	}

	public function provideGetFirstLetter() {
		return [
			[ 'Do', 'D' ],
			[ 'do', 'D' ],
			[ 'Ao', 'A' ],
			[ 'afdsa', 'A' ],
			[ "\xF3\xB3\x80\x80Foo", 'D' ],
			[ "\xF3\xB3\x80\x81Foo", 'C' ],
			[ "\xF3\xB3\x80\x82Foo", 'B' ],
			[ "\xF3\xB3\x80\x83Foo", "\xF3\xB3\x80\x83" ],
		];
	}
}
