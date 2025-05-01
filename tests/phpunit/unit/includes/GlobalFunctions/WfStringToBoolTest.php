<?php

/**
 * @group GlobalFunctions
 * @covers ::wfStringToBool
 */
class WfStringToBoolTest extends MediaWikiUnitTestCase {

	public static function provideTestCases() {
		return [
			[ 'true', true ],
			[ 'on', true ],
			[ 'yes', true ],
			[ 'TRUE', true ],
			[ 'YeS', true ],
			[ 'On', true ],
			[ '1', true ],
			[ '+1', true ],
			[ '01', true ],
			[ '-001', true ],
			[ '  1', true ],
			[ '-1  ', true ],
			[ '', false ],
			[ '0', false ],
			[ 'false', false ],
			[ 'NO', false ],
			[ 'NOT', false ],
			[ 'never', false ],
			[ '!&', false ],
			[ '-0', false ],
			[ '+0', false ],
			[ 'forget about it', false ],
			[ ' on', false ],
			[ 'true ', false ],
		];
	}

	/**
	 * @dataProvider provideTestCases
	 * @param string $str
	 * @param bool $bool
	 */
	public function testStr2Bool( $str, $bool ) {
		if ( $bool ) {
			$this->assertTrue( wfStringToBool( $str ) );
		} else {
			$this->assertFalse( wfStringToBool( $str ) );
		}
	}

}
