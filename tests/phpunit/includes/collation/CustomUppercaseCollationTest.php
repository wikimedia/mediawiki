<?php

use MediaWiki\MediaWikiServices;

/**
 * @covers CustomUppercaseCollation
 */
class CustomUppercaseCollationTest extends MediaWikiIntegrationTestCase {

	protected function setUp() : void {
		$this->collation = new CustomUppercaseCollation( [
			'D',
			'C',
			'Cs',
			'B'
		], MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( 'en' ) );

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
			[ 'Cz', 'Cs', 'digraphs' ],
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
			[ "\u{F3000}Foo", 'D' ],
			[ "\u{F3001}Foo", 'C' ],
			[ "\u{F3002}Foo", 'Cs' ],
			[ "\u{F3003}Foo", 'B' ],
			[ "\u{F3004}Foo", "\u{F3004}" ],
			[ 'C', 'C' ],
			[ 'Cz', 'C' ],
			[ 'Cs', 'Cs' ],
			[ 'CS', 'Cs' ],
			[ 'cs', 'Cs' ],
		];
	}
}
