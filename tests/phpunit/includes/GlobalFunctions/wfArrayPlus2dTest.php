<?php
/**
 * @group GlobalFunctions
 * @covers ::wfArrayPlus2d
 */
class WfArrayPlus2dTest extends MediaWikiTestCase {
	/**
	 * @dataProvider provideArrays
	 */
	public function testWfArrayPlus2d( $baseArray, $newValues, $expected, $testName ) {
		$this->assertEquals(
			$expected,
			wfArrayPlus2d( $baseArray, $newValues ),
			$testName
		);
	}

	/**
	 * Provider for testing wfArrayPlus2d
	 *
	 * @return array
	 */
	public static function provideArrays() {
		return array(
			// target array, new values array, expected result
			array(
				array( 0 => '1dArray' ),
				array( 1 => '1dArray' ),
				array( 0 => '1dArray', 1 => '1dArray' ),
				"Test simple union of two arrays with different keys",
			),
			array(
				array(
					0 => array( 0 => '2dArray' ),
				),
				array(
					0 => array( 1 => '2dArray' ),
				),
				array(
					0 => array( 0 => '2dArray', 1 => '2dArray' ),
				),
				"Test union of 2d arrays with different keys in the value array",
			),
			array(
				array(
					0 => array( 0 => '2dArray' ),
				),
				array(
					0 => array( 0 => '1dArray' ),
				),
				array(
					0 => array( 0 => '2dArray' ),
				),
				"Test union of 2d arrays with same keys in the value array",
			),
			array(
				array(
					0 => array( 0 => array( 0 => '3dArray' ) ),
				),
				array(
					0 => array( 0 => array( 1 => '2dArray' ) ),
				),
				array(
					0 => array( 0 => array( 0 => '3dArray' ) ),
				),
				"Test union of 3d array with different keys",
			),
			array(
				array(
					0 => array( 0 => array( 0 => '3dArray' ) ),
				),
				array(
					0 => array( 1 => array( 0 => '2dArray' ) ),
				),
				array(
					0 => array( 0 => array( 0 => '3dArray' ), 1 => array( 0 => '2dArray' ) ),
				),
				"Test union of 3d array with different keys in the value array",
			),
			array(
				array(
					0 => array( 0 => array( 0 => '3dArray' ) ),
				),
				array(
					0 => array( 0 => array( 0 => '2dArray' ) ),
				),
				array(
					0 => array( 0 => array( 0 => '3dArray' ) ),
				),
				"Test union of 3d array with same keys in the value array",
			),
		);
	}
}
