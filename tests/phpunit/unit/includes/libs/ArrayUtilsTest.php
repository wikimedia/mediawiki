<?php
/**
 * Test class for ArrayUtils class
 *
 * @group Database
 */
class ArrayUtilsTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	/**
	 * @covers ArrayUtils::findLowerBound
	 * @dataProvider provideFindLowerBound
	 */
	public function testFindLowerBound(
		$valueCallback, $valueCount, $comparisonCallback, $target, $expected
	) {
		$this->assertSame(
			ArrayUtils::findLowerBound(
				$valueCallback, $valueCount, $comparisonCallback, $target
			), $expected
		);
	}

	public function provideFindLowerBound() {
		$indexValueCallback = function ( $size ) {
			return function ( $val ) use ( $size ) {
				$this->assertTrue( $val >= 0 );
				$this->assertTrue( $val < $size );
				return $val;
			};
		};
		$comparisonCallback = function ( $a, $b ) {
			return $a - $b;
		};

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
	 * @covers ArrayUtils::arrayDiffAssocRecursive
	 * @dataProvider provideArrayDiffAssocRecursive
	 */
	public function testArrayDiffAssocRecursive( $expected, ...$args ) {
		$this->assertEquals( $expected, ArrayUtils::arrayDiffAssocRecursive( ...$args ) );
	}

	public function provideArrayDiffAssocRecursive() {
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
	 * @covers ArrayUtils::cartesianProduct
	 */
	public function testCartesianProduct( $inputs, $expected ) {
		$result = ArrayUtils::cartesianProduct( ...$inputs );
		$this->assertSame( $expected, $result );
	}

	public function provideCartesianProduct() {
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
}
