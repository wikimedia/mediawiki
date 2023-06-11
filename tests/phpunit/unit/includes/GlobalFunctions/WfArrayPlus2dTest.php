<?php
/**
 * @group GlobalFunctions
 * @covers ::wfArrayPlus2d
 */
class WfArrayPlus2dTest extends MediaWikiUnitTestCase {
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
		return [
			// target array, new values array, expected result
			[
				[ 0 => '1dArray' ],
				[ 1 => '1dArray' ],
				[ 0 => '1dArray', 1 => '1dArray' ],
				"Test simple union of two arrays with different keys",
			],
			[
				[
					0 => [ 0 => '2dArray' ],
				],
				[
					0 => [ 1 => '2dArray' ],
				],
				[
					0 => [ 0 => '2dArray', 1 => '2dArray' ],
				],
				"Test union of 2d arrays with different keys in the value array",
			],
			[
				[
					0 => [ 0 => '2dArray' ],
				],
				[
					0 => [ 0 => '1dArray' ],
				],
				[
					0 => [ 0 => '2dArray' ],
				],
				"Test union of 2d arrays with same keys in the value array",
			],
			[
				[
					0 => [ 0 => [ 0 => '3dArray' ] ],
				],
				[
					0 => [ 0 => [ 1 => '2dArray' ] ],
				],
				[
					0 => [ 0 => [ 0 => '3dArray' ] ],
				],
				"Test union of 3d array with different keys",
			],
			[
				[
					0 => [ 0 => [ 0 => '3dArray' ] ],
				],
				[
					0 => [ 1 => [ 0 => '2dArray' ] ],
				],
				[
					0 => [ 0 => [ 0 => '3dArray' ], 1 => [ 0 => '2dArray' ] ],
				],
				"Test union of 3d array with different keys in the value array",
			],
			[
				[
					0 => [ 0 => [ 0 => '3dArray' ] ],
				],
				[
					0 => [ 0 => [ 0 => '2dArray' ] ],
				],
				[
					0 => [ 0 => [ 0 => '3dArray' ] ],
				],
				"Test union of 3d array with same keys in the value array",
			],
		];
	}
}
