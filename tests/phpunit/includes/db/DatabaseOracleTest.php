<?php

class DatabaseOracleTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;
	use PHPUnit4And6Compat;

	/**
	 * @return PHPUnit_Framework_MockObject_MockObject|DatabaseOracle
	 */
	private function getMockDb() {
		return $this->getMockBuilder( DatabaseOracle::class )
			->disableOriginalConstructor()
			->setMethods( null )
			->getMock();
	}

	public function provideBuildSubstring() {
		yield [ 'someField', 1, 2, 'SUBSTR(someField,1,2)' ];
		yield [ 'someField', 1, null, 'SUBSTR(someField,1)' ];
	}

	/**
	 * @covers DatabaseOracle::buildSubstring
	 * @dataProvider provideBuildSubstring
	 */
	public function testBuildSubstring( $input, $start, $length, $expected ) {
		$mockDb = $this->getMockDb();
		$output = $mockDb->buildSubstring( $input, $start, $length );
		$this->assertSame( $expected, $output );
	}

	public function provideBuildSubstring_invalidParams() {
		yield [ -1, 1 ];
		yield [ 1, -1 ];
		yield [ 1, 'foo' ];
		yield [ 'foo', 1 ];
		yield [ null, 1 ];
		yield [ 0, 1 ];
	}

	/**
	 * @covers DatabaseOracle::buildSubstring
	 * @dataProvider provideBuildSubstring_invalidParams
	 */
	public function testBuildSubstring_invalidParams( $start, $length ) {
		$mockDb = $this->getMockDb();
		$this->setExpectedException( InvalidArgumentException::class );
		$mockDb->buildSubstring( 'foo', $start, $length );
	}

}
