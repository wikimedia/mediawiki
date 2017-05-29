<?php
class CollationFaTest extends MediaWikiTestCase {

	public function testOrder() {
		$items = [
			5 => 'واو',
			3 => 'برلین',
			1 => 'ا',
			2 => 'ایران',
			4 => 'و',
		];

		$coll = new CollationFa;

		$items = array_map( [ $coll, 'getSortKey' ], $items );
		asort( $items );

		$cur = 1;
		foreach ( $items as $key => $val ) {
			$this->assertEquals( $key, $cur );
			$cur++;
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
			[ "\xd8\xa7", "\xd8\xa7Foo" ],
			[ "\xd9\x88", "\xd9\x88Foo" ],
			[ "\xd9\xb2", "\xd9\xb2Foo" ],
			[ "\xd9\xb3", "\xd9\xb3Foo" ],
		];
	}
}
