<?php
/**
 * Test class for ArrayUtils class
 *
 * @group Database
 */

class ArrayUtilsTest extends PHPUnit_Framework_TestCase {
	private $search;

	/**
	 * @covers ArrayUtils::findLowerBound
	 * @dataProvider provideFindLowerBound
	 */
	function testFindLowerBound(
		$valueCallback, $valueCount, $comparisonCallback, $target, $expected
	) {
		$this->assertSame(
			ArrayUtils::findLowerBound(
				$valueCallback, $valueCount, $comparisonCallback, $target
			), $expected
		);
	}

	function provideFindLowerBound() {
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
	function testArrayDiffAssocRecursive( $expected ) {
		$args = func_get_args();
		array_shift( $args );
		$this->assertEquals( call_user_func_array(
			'ArrayUtils::arrayDiffAssocRecursive', $args
		), $expected );
	}

	function provideArrayDiffAssocRecursive() {
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
}
