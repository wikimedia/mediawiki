<?php

namespace Wikimedia\Tests;

use MediaWikiCoversValidator;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Wikimedia\ArrayUtils\ArrayUtils;

/**
 * @covers \Wikimedia\ArrayUtils\ArrayUtils
 */
class ArrayUtilsTest extends TestCase {

	use MediaWikiCoversValidator;

	/**
	 * @dataProvider provideFindLowerBound
	 */
	public function testFindLowerBound(
		$valueCallback, $valueCount, $comparisonCallback, $target, $expected
	) {
		$this->assertSame(
			$expected,
			ArrayUtils::findLowerBound(
				$valueCallback, $valueCount, $comparisonCallback, $target
			)
		);
	}

	public static function provideFindLowerBound() {
		$indexValueCallback = static function ( $size ) {
			return static function ( $val ) use ( $size ) {
				Assert::assertTrue( $val >= 0 );
				Assert::assertTrue( $val < $size );
				return $val;
			};
		};
		$comparisonCallback = static fn ( $a, $b ) => $a - $b;

		return [
			[
				$indexValueCallback( 0 ),
				0,
				$comparisonCallback,
				1,
				false,
			],
			[
				$indexValueCallback( 1 ),
				1,
				$comparisonCallback,
				-1,
				false,
			],
			[
				$indexValueCallback( 1 ),
				1,
				$comparisonCallback,
				0,
				0,
			],
			[
				$indexValueCallback( 1 ),
				1,
				$comparisonCallback,
				1,
				0,
			],
			[
				$indexValueCallback( 2 ),
				2,
				$comparisonCallback,
				-1,
				false,
			],
			[
				$indexValueCallback( 2 ),
				2,
				$comparisonCallback,
				0,
				0,
			],
			[
				$indexValueCallback( 2 ),
				2,
				$comparisonCallback,
				0.5,
				0,
			],
			[
				$indexValueCallback( 2 ),
				2,
				$comparisonCallback,
				1,
				1,
			],
			[
				$indexValueCallback( 2 ),
				2,
				$comparisonCallback,
				1.5,
				1,
			],
			[
				$indexValueCallback( 3 ),
				3,
				$comparisonCallback,
				1,
				1,
			],
			[
				$indexValueCallback( 3 ),
				3,
				$comparisonCallback,
				1.5,
				1,
			],
			[
				$indexValueCallback( 3 ),
				3,
				$comparisonCallback,
				2,
				2,
			],
			[
				$indexValueCallback( 3 ),
				3,
				$comparisonCallback,
				3,
				2,
			],
		];
	}

	/**
	 * @dataProvider provideArrayDiffAssocRecursive
	 */
	public function testArrayDiffAssocRecursive( $expected, ...$args ) {
		$this->assertEquals( $expected, ArrayUtils::arrayDiffAssocRecursive( ...$args ) );
	}

	public static function provideArrayDiffAssocRecursive() {
		return [
			[
				[],
				[],
				[],
			],
			[
				[],
				[],
				[],
				[],
			],
			[
				[ 1 ],
				[ 1 ],
				[],
			],
			[
				[ 1 ],
				[ 1 ],
				[],
				[],
			],
			[
				[],
				[],
				[ 1 ],
			],
			[
				[],
				[],
				[ 1 ],
				[ 2 ],
			],
			[
				[ '' => 1 ],
				[ '' => 1 ],
				[],
			],
			[
				[],
				[],
				[ '' => 1 ],
			],
			[
				[ 1 ],
				[ 1 ],
				[ 2 ],
			],
			[
				[],
				[ 1 ],
				[ 2 ],
				[ 1 ],
			],
			[
				[],
				[ 1 ],
				[ 1, 2 ],
			],
			[
				[ 1 => 1 ],
				[ 1 => 1 ],
				[ 1 ],
			],
			[
				[],
				[ 1 => 1 ],
				[ 1 ],
				[ 1 => 1 ],
			],
			[
				[],
				[ 1 => 1 ],
				[ 1, 1, 1 ],
			],
			[
				[],
				[ [] ],
				[],
			],
			[
				[],
				[ [ [] ] ],
				[],
			],
			[
				[ 1, [ 1 ] ],
				[ 1, [ 1 ] ],
				[],
			],
			[
				[ 1 ],
				[ 1, [ 1 ] ],
				[ 2, [ 1 ] ],
			],
			[
				[],
				[ 1, [ 1 ] ],
				[ 2, [ 1 ] ],
				[ 1, [ 2 ] ],
			],
			[
				[ 1 ],
				[ 1, [] ],
				[ 2 ],
			],
			[
				[],
				[ 1, [] ],
				[ 2 ],
				[ 1 ],
			],
			[
				[ 1, [ 1 => 2 ] ],
				[ 1, [ 1, 2 ] ],
				[ 2, [ 1 ] ],
			],
			[
				[ 1 ],
				[ 1, [ 1, 2 ] ],
				[ 2, [ 1 ] ],
				[ 2, [ 1 => 2 ] ],
			],
			[
				[ 1 => [ 1, 2 ] ],
				[ 1, [ 1, 2 ] ],
				[ 1, [ 2 ] ],
			],
			[
				[ 1 => [ [ 2, 3 ], 2 ] ],
				[ 1, [ [ 2, 3 ], 2 ] ],
				[ 1, [ 2 ] ],
			],
			[
				[ 1 => [ [ 2 ], 2 ] ],
				[ 1, [ [ 2, 3 ], 2 ] ],
				[ 1, [ [ 1 => 3 ] ] ],
			],
			[
				[ 1 => [ 1 => 2 ] ],
				[ 1, [ [ 2, 3 ], 2 ] ],
				[ 1, [ [ 1 => 3, 0 => 2 ] ] ],
			],
			[
				[ 1 => [ 1 => 2 ] ],
				[ 1, [ [ 2, 3 ], 2 ] ],
				[ 1, [ [ 1 => 3 ] ] ],
				[ 1 => [ [ 2 ] ] ],
			],
			[
				[],
				[ 1, [ [ 2, 3 ], 2 ] ],
				[ 1 => [ 1 => 2, 0 => [ 1 => 3, 0 => 2 ] ], 0 => 1 ],
			],
			[
				[],
				[ 1, [ [ 2, 3 ], 2 ] ],
				[ 1 => [ 1 => 2 ] ],
				[ 1 => [ [ 1 => 3 ] ] ],
				[ 1 => [ [ 2 ] ] ],
				[ 1 ],
			],
		];
	}

	/**
	 * @dataProvider provideCartesianProduct
	 */
	public function testCartesianProduct( $inputs, $expected ) {
		$result = ArrayUtils::cartesianProduct( ...$inputs );
		$this->assertSame( $expected, $result );
	}

	public static function provideCartesianProduct() {
		$ab = [ 'a', 'b' ];
		$cd = [ 'c', 'd' ];
		$ac = [ 'a', 'c' ];
		$ad = [ 'a', 'd' ];
		$bc = [ 'b', 'c' ];
		$bd = [ 'b', 'd' ];

		return [
			'no inputs' => [
				[],
				[]
			],
			'one empty input' => [
				[ [] ],
				[]
			],
			'one non-empty input' => [
				[ $ab ],
				[ [ 'a' ], [ 'b' ] ]
			],
			'non-empty times empty' => [
				[ $ab, [] ],
				[]
			],
			'empty times non-empty' => [
				[ [], $ab ],
				[]
			],
			'ab x cd' => [
				[ $ab, $cd ],
				[ $ac, $ad, $bc, $bd ]
			],
			'keys are ignored' => [
				[
					[ 99 => 'a', 98 => 'b' ],
					[ 97 => 'c', 96 => 'd' ],
				],
				[ $ac, $ad, $bc, $bd ]
			],
		];
	}

	public static function provideConsistentSort() {
		yield 'initial' => [
			[
				'tag1',
				'tag2',
				'tag3',
			],
			'foobar',
			[ 'tag2', 'tag3', 'tag1' ],
			[ 1 => 'tag2', 2 => 'tag3', 0 => 'tag1' ],
		];
		yield 'adding preserves order' => [
			[
				'tag1',
				'tag2',
				'tag3',
				'tag4',
			],
			'foobar',
			[ 'tag2', 'tag4', 'tag3', 'tag1' ],
			[ 1 => 'tag2', 3 => 'tag4', 2 => 'tag3', 0 => 'tag1' ],
		];
		yield 'depool preserves order' => [
			[
				'tag1',
				'tag3',
			],
			'foobar',
			[ 'tag3', 'tag1' ],
			[ 1 => 'tag3', 0 => 'tag1' ],
		];
		yield 'input order is not significant' => [
			[
				// Backwards
				'tag3',
				'tag2',
				'tag1',
			],
			'foobar',
			[ 'tag2', 'tag3', 'tag1' ],
			[ 1 => 'tag2', 0 => 'tag3', 2 => 'tag1' ],
		];
		yield 'input keys are not significant' => [
			[
				'z' => 'tag2',
				'y' => 'tag3',
				'x' => 'tag1',
			],
			'foobar',
			[ 'tag2', 'tag3', 'tag1' ],
			[ 'z' => 'tag2', 'y' => 'tag3', 'x' => 'tag1' ],
		];
	}

	/**
	 * @dataProvider provideConsistentSort
	 */
	public function testConsistentSort( $array, string $key, array $expectList, array $expect ) {
		$actual = $array;
		ArrayUtils::consistentHashSort( $actual, $key );
		$this->assertSame( $expect, $actual );
		$this->assertSame( $expectList, array_values( $actual ) );
	}

	public static function provideConsistentSortStability() {
		yield 'initial' => [
			[ 'tag1', 'tag2', 'tag3', 'tag4' ],
			[
				'foo' => 'tag3',
				'bar' => 'tag2',
				'qux' => 'tag4',
				'quux' => 'tag1',
				'garply' => 'tag3',
			],
		];
		yield 'depool tag2' => [
			[ 'tag1', 'tag3', 'tag4' ],
			[
				'foo' => 'tag3',
				'bar' => 'tag1', # changed from tag2
				'qux' => 'tag4',
				'quux' => 'tag1',
				'garply' => 'tag3',
			],
		];
		yield 'add tag5' => [
			[ 'tag1', 'tag3', 'tag4', 'tag5' ],
			[
				'foo' => 'tag3',
				'bar' => 'tag1',
				'qux' => 'tag4',
				'quux' => 'tag5', # changed from tag1
				'garply' => 'tag3',
			],
		];
	}

	/**
	 * @dataProvider provideConsistentSortStability
	 */
	public function testConsistentSortStability( $serverTags, $expected ) {
		$actual = [];
		foreach ( $expected as $key => $_ ) {
			$sortedTags = $serverTags;
			ArrayUtils::consistentHashSort( $sortedTags, $key );
			$dest = $serverTags[array_key_first( $sortedTags )];

			$actual[$key] = $dest;
		}

		$this->assertSame( $expected, $actual );
	}

	/**
	 * @dataProvider provideArrayPlus2d
	 */
	public function testArrayPlus2d( array $baseArray, array $newValues, array $expected ): void {
		$this->assertSame(
			$expected,
			ArrayUtils::arrayPlus2d( $baseArray, $newValues )
		);
	}

	public static function provideArrayPlus2d(): array {
		return [
			// target array, new values array, expected result
			'Simple union of two arrays with different keys' => [
				[ 0 => '1dArray' ],
				[ 1 => '1dArray' ],
				[ 0 => '1dArray', 1 => '1dArray' ],
			],
			'Union of 2d arrays with different keys in the value array' => [
				[
					0 => [ 0 => '2dArray' ],
				],
				[
					0 => [ 1 => '2dArray' ],
				],
				[
					0 => [ 0 => '2dArray', 1 => '2dArray' ],
				],
			],
			'Union of 2d arrays with same keys in the value array' => [
				[
					0 => [ 0 => '2dArray' ],
				],
				[
					0 => [ 0 => '1dArray' ],
				],
				[
					0 => [ 0 => '2dArray' ],
				],
			],
			'Union of 3d array with different keys' => [
				[
					0 => [ 0 => [ 0 => '3dArray' ] ],
				],
				[
					0 => [ 0 => [ 1 => '2dArray' ] ],
				],
				[
					0 => [ 0 => [ 0 => '3dArray' ] ],
				],
			],
			'Union of 3d array with different keys in the value array' => [
				[
					0 => [ 0 => [ 0 => '3dArray' ] ],
				],
				[
					0 => [ 1 => [ 0 => '2dArray' ] ],
				],
				[
					0 => [ 0 => [ 0 => '3dArray' ], 1 => [ 0 => '2dArray' ] ],
				],
			],
			'Union of 3d array with same keys in the value array' => [
				[
					0 => [ 0 => [ 0 => '3dArray' ] ],
				],
				[
					0 => [ 0 => [ 0 => '2dArray' ] ],
				],
				[
					0 => [ 0 => [ 0 => '3dArray' ] ],
				],
			],
		];
	}
}
