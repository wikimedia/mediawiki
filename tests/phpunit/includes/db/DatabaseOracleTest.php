<?php

class DatabaseOracleTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	public function provideBuildSubstring() {
		yield [ 'someField', 1, 2, 'SUBSTR(someField,1,2)' ];
		yield [ 'someField', 1, null, 'SUBSTR(someField,1)' ];
	}

	/**
	 * @covers DatabaseOracle::buildSubstring
	 * @dataProvider provideBuildSubstring
	 */
	public function testBuildSubstring( $input, $start, $length, $expected ) {
		$db = new FakeDatabaseOracle( [] );
		$output = $db->buildSubstring( $input, $start, $length );
		$this->assertSame( $expected, $output );
	}


	public function provideBuildSubstring_invalidParams() {
		yield [ -1, 1 ];
		yield [ 1, -1 ];
		yield [ 1, 'foo' ];
		yield [ 'foo', 1 ];
		yield [ null, 1 ];
	}

	/**
	 * @covers DatabaseOracle::buildSubstring
	 * @dataProvider provideBuildSubstring_invalidParams
	 */
	public function testBuildSubstring_invalidParams( $start, $length ) {
		$db = new FakeDatabaseOracle( [] );
		$this->setExpectedException( InvalidArgumentException::class );
		$db->buildSubstring( 'foo', $start, $length );
	

	/**
	 * @covers DatabaseOracle::buildIntegerCast
	 */
	public function testBuildIntegerCast() {
		$db = new FakeDatabaseOracle( [] );
		$output = $db->buildIntegerCast( 123 );
		$this->assertSame( 'CAST ( 123 AS INTEGER )', $output );
	}}

}
