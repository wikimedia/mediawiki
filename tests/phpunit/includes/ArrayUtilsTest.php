<?php
/**
 * Test class for ArrayUtils class
 *
 * @group Database
 */

class ArrayUtilsTest extends MediaWikiTestCase {
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
		$self = $this;
		$indexValueCallback = function( $size ) use ( $self ) {
			return function( $val ) use ( $self, $size ) {
				$self->assertTrue( $val >= 0 );
				$self->assertTrue( $val < $size );
				return $val;
			};
		};
		$comparisonCallback = function( $a, $b ) {
			return $a - $b;
		};

		return array(
			array(
				$indexValueCallback( 0 ),
				0,
				$comparisonCallback,
				1,
				false,
			),
			array(
				$indexValueCallback( 1 ),
				1,
				$comparisonCallback,
				-1,
				false,
			),
			array(
				$indexValueCallback( 1 ),
				1,
				$comparisonCallback,
				0,
				0,
			),
			array(
				$indexValueCallback( 1 ),
				1,
				$comparisonCallback,
				1,
				0,
			),
			array(
				$indexValueCallback( 2 ),
				2,
				$comparisonCallback,
				-1,
				false,
			),
			array(
				$indexValueCallback( 2 ),
				2,
				$comparisonCallback,
				0,
				0,
			),
			array(
				$indexValueCallback( 2 ),
				2,
				$comparisonCallback,
				0.5,
				0,
			),
			array(
				$indexValueCallback( 2 ),
				2,
				$comparisonCallback,
				1,
				1,
			),
			array(
				$indexValueCallback( 2 ),
				2,
				$comparisonCallback,
				1.5,
				1,
			),
			array(
				$indexValueCallback( 3 ),
				3,
				$comparisonCallback,
				1,
				1,
			),
			array(
				$indexValueCallback( 3 ),
				3,
				$comparisonCallback,
				1.5,
				1,
			),
			array(
				$indexValueCallback( 3 ),
				3,
				$comparisonCallback,
				2,
				2,
			),
			array(
				$indexValueCallback( 3 ),
				3,
				$comparisonCallback,
				3,
				2,
			),
		);
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
		return array(
			array(
				array(),
				array(),
				array(),
			),
			array(
				array(),
				array(),
				array(),
				array(),
			),
			array(
				array( 1 ),
				array( 1 ),
				array(),
			),
			array(
				array( 1 ),
				array( 1 ),
				array(),
				array(),
			),
			array(
				array(),
				array(),
				array( 1 ),
			),
			array(
				array(),
				array(),
				array( 1 ),
				array( 2 ),
			),
			array(
				array( '' => 1 ),
				array( '' => 1 ),
				array(),
			),
			array(
				array(),
				array(),
				array( '' => 1 ),
			),
			array(
				array( 1 ),
				array( 1 ),
				array( 2 ),
			),
			array(
				array(),
				array( 1 ),
				array( 2 ),
				array( 1 ),
			),
			array(
				array(),
				array( 1 ),
				array( 1, 2 ),
			),
			array(
				array( 1 => 1 ),
				array( 1 => 1 ),
				array( 1 ),
			),
			array(
				array(),
				array( 1 => 1 ),
				array( 1 ),
				array( 1 => 1),
			),
			array(
				array(),
				array( 1 => 1 ),
				array( 1, 1, 1 ),
			),
			array(
				array(),
				array( array() ),
				array(),
			),
			array(
				array(),
				array( array( array() ) ),
				array(),
			),
			array(
				array( 1, array( 1 ) ),
				array( 1, array( 1 ) ),
				array(),
			),
			array(
				array( 1 ),
				array( 1, array( 1 ) ),
				array( 2, array( 1 ) ),
			),
			array(
				array(),
				array( 1, array( 1 ) ),
				array( 2, array( 1 ) ),
				array( 1, array( 2 ) ),
			),
			array(
				array( 1 ),
				array( 1, array() ),
				array( 2 ),
			),
			array(
				array(),
				array( 1, array() ),
				array( 2 ),
				array( 1 ),
			),
			array(
				array( 1, array( 1 => 2 ) ),
				array( 1, array( 1, 2 ) ),
				array( 2, array( 1 ) ),
			),
			array(
				array( 1 ),
				array( 1, array( 1, 2 ) ),
				array( 2, array( 1 ) ),
				array( 2, array( 1 => 2 ) ),
			),
			array(
				array( 1 => array( 1, 2 ) ),
				array( 1, array( 1, 2 ) ),
				array( 1, array( 2 ) ),
			),
			array(
				array( 1 => array( array( 2, 3 ), 2 ) ),
				array( 1, array( array( 2, 3 ), 2 ) ),
				array( 1, array( 2 ) ),
			),
			array(
				array( 1 => array( array( 2 ), 2 ) ),
				array( 1, array( array( 2, 3 ), 2 ) ),
				array( 1, array( array( 1 => 3 ) ) ),
			),
			array(
				array( 1 => array( 1 => 2 ) ),
				array( 1, array( array( 2, 3 ), 2 ) ),
				array( 1, array( array( 1 => 3, 0 => 2 ) ) ),
			),
			array(
				array( 1 => array( 1 => 2 ) ),
				array( 1, array( array( 2, 3 ), 2 ) ),
				array( 1, array( array( 1 => 3 ) ) ),
				array( 1 => array( array( 2 ) ) ),
			),
			array(
				array(),
				array( 1, array( array( 2, 3 ), 2 ) ),
				array( 1 => array( 1 => 2, 0 => array( 1 => 3, 0 => 2 ) ), 0 => 1 ),
			),
			array(
				array(),
				array( 1, array( array( 2, 3 ), 2 ) ),
				array( 1 => array( 1 => 2 ) ),
				array( 1 => array( array( 1 => 3 ) ) ),
				array( 1 => array( array( 2 ) ) ),
				array( 1 ),
			),
		);
	}
}
