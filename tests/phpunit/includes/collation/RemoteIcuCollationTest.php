<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @covers \RemoteIcuCollation
 */
class RemoteIcuCollationTest extends MediaWikiLangTestCase {
	public static function provideEncode() {
		return [
			[
				[],
				''
			],
			[
				[ 'foo' ],
				'00000003foo'
			],
			[
				[ 'foo', 'a somewhat longer string' ],
				'00000003foo00000018a somewhat longer string'
			],
		];
	}

	/** @dataProvider provideEncode */
	public function testEncode( $input, $expected ) {
		$coll = TestingAccessWrapper::newFromClass( RemoteIcuCollation::class );
		$this->assertSame( $expected, $coll->encode( $input ) );
	}

	public static function provideEncodeDecode() {
		return [
			[ [ "\000" ] ],
			[ [ "a\000b" ] ],
			[ [ str_repeat( "\001", 100 ) ] ],
			[ [ 'foo' ] ],
			[ [ 'foo', 'bar' ] ],
			[ [ 'foo', 'bar', str_repeat( 'x', 1000 ) ] ]
		];
	}

	/** @dataProvider provideEncodeDecode */
	public function testEncodeDecode( $input ) {
		$coll = TestingAccessWrapper::newFromClass( RemoteIcuCollation::class );
		$this->assertSame( $input, $coll->decode( $coll->encode( $input ) ) );
	}

	public static function provideGetSortKeys() {
		$cases = [
			[],
			[ '' ],
			[ 'test1' => 'bar', 'test2' => 'foo' ],
			[
				'bar',
				'foo'
			],
			[
				'first',
				'Second'
			],
			[
				'',
				'second'
			],
			[
				'Berić',
				'Berisha',
			],
			[
				'2',
				'10',
			]
		];
		foreach ( $cases as $case ) {
			yield [ $case ];
		}
	}

	/** @dataProvider provideGetSortKeys */
	public function testGetSortKeys( $inputs ) {
		$coll = new RemoteIcuCollation(
			$this->getServiceContainer()->getShellboxClientFactory(),
			'uca-default-u-kn'
		);
		$sortKeys = $coll->getSortKeys( $inputs );
		$prevKey = null;
		if ( count( $inputs ) ) {
			foreach ( $inputs as $i => $input ) {
				$key = $sortKeys[$i];
				$this->assertIsString( $key );
				if ( $prevKey ) {
					$this->assertLessThan( 0, strcmp( $prevKey, $key ) );
				}
				$prevKey = $key;
			}
		} else {
			$this->assertSame( [], $sortKeys );
		}
	}

	/** @dataProvider provideGetSortKeys */
	public function testGetSortKey( $inputs ) {
		if ( !count( $inputs ) ) {
			// Not risky, it's just handy to reuse the provider
			$this->assertTrue( true );
		}
		$coll = new RemoteIcuCollation(
			$this->getServiceContainer()->getShellboxClientFactory(),
			'uca-default-u-kn'
		);
		$prevKey = null;
		foreach ( $inputs as $input ) {
			$key = $coll->getSortKey( $input );
			$this->assertIsString( $key );
			if ( $prevKey ) {
				$this->assertLessThan( 0, strcmp( $prevKey, $key ) );
			}
			$prevKey = $key;
		}
	}
}
