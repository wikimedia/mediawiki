<?php

namespace MediaWiki\Tests\Unit\Settings\Config;

use MediaWiki\Settings\Config\MergeStrategy;
use MediaWiki\Settings\SettingsBuilderException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Settings\Config\MergeStrategy
 */
class MergeStrategyTest extends TestCase {

	public function testUnknownStrategy() {
		$this->expectException( SettingsBuilderException::class );
		MergeStrategy::newFromName( 'unknown' )->merge( [], [] );
	}

	public static function provideMergeStrategies() {
		yield 'array_merge_recursive' => [
			'strategy' => MergeStrategy::ARRAY_MERGE_RECURSIVE,
			'newArray' => [ 'a' => [ 'b' => [ 'd' ] ] ],
			'baseArray' => [ 'a' => [ 'b' => [ 'c' ], 'e' => [ 'f' ] ] ],
			'expected' => [ 'a' => [ 'b' => [ 'c', 'd' ], 'e' => [ 'f' ] ] ],
		];
		yield 'array_replace_recursive' => [
			'strategy' => MergeStrategy::ARRAY_REPLACE_RECURSIVE,
			'newArray' => [ 'a' => [ 'b' => [ 'c' ] ] ],
			'baseArray' => [ 'a' => [ 'b' => [ 'd' ], 'e' => [ 'f' ] ] ],
			'expected' => [ 'a' => [ 'b' => [ 'c' ], 'e' => [ 'f' ] ] ],
		];
		yield 'array_plus_2d' => [
			'strategy' => MergeStrategy::ARRAY_PLUS_2D,
			'newArray' => [ 'a' => [ 'b' => [ 'c' => [ 'e' => 'f' ] ] ] ],
			'baseArray' => [ 'a' => [ 'b' => [ 'c' => [ 'g' => 'h' ] ], 'i' => 'j' ] ] ,
			'expected' => [ 'a' => [ 'b' => [ 'c' => [ 'e' => 'f' ] ], 'i' => 'j' ] ] ,
		];
		yield 'array_plus' => [
			'strategy' => MergeStrategy::ARRAY_PLUS,
			'newArray' => [ 100 => 'Goo', 101 => 'Boo' ],
			'baseArray' => [ 100 => 'Foo', 102 => 'Too' ],
			'expected' => [ 100 => 'Goo', 101 => 'Boo', 102 => 'Too' ],
		];
		yield 'array_merge' => [
			'strategy' => MergeStrategy::ARRAY_MERGE,
			'newArray' => [ 'a' => [ 'b' => [ 'c' ] ] ],
			'baseArray' => [ 'a' => [ 'b' => [ 'd' ] ], 'e' => [ 'f' ] ],
			'expected' => [ 'a' => [ 'b' => [ 'c' ] ], 'e' => [ 'f' ] ],
		];
		yield 'replace' => [
			'strategy' => MergeStrategy::REPLACE,
			'newArray' => [ 'a' => [ 'b' => [ 'c' ] ] ],
			'baseArray' => [ 'a' => [ 'b' => [ 'd' ] ], 'e' => [ 'f' ] ],
			'expected' => [ 'a' => [ 'b' => [ 'c' ] ] ],
		];
	}

	/**
	 * @dataProvider provideMergeStrategies
	 */
	public function testMergeStrategies( string $strategy, array $newArray, array $baseArray, array $expected ) {
		$mergeStrategy = MergeStrategy::newFromName( $strategy );
		$this->assertEquals( $expected, $mergeStrategy->merge( $baseArray, $newArray ) );
	}

	/**
	 * @dataProvider provideMergeStrategies
	 */
	public function testReverse( string $strategy, array $newArray, array $baseArray, array $expected ) {
		$mergeStrategy = MergeStrategy::newFromName( $strategy )->reverse();
		$this->assertEquals( $expected, $mergeStrategy->merge( $newArray, $baseArray ) );
	}

	public function testMergeStrategyReusesObjects() {
		$this->assertSame(
			MergeStrategy::newFromName( MergeStrategy::ARRAY_MERGE ),
			MergeStrategy::newFromName( MergeStrategy::ARRAY_MERGE )
		);
	}

	public function testReverseReusesObjects() {
		$this->assertSame(
			MergeStrategy::newFromName( MergeStrategy::ARRAY_MERGE )->reverse(),
			MergeStrategy::newFromName( MergeStrategy::ARRAY_MERGE )->reverse()
		);
	}

	public function testGetName() {
		$this->assertSame(
			MergeStrategy::ARRAY_MERGE,
			MergeStrategy::newFromName( MergeStrategy::ARRAY_MERGE )->getName()
		);
	}
}
