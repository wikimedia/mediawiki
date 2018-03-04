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

	/**
	 * @covers DatabaseOracle::buildIntegerCast
	 */
	public function testBuildIntegerCast() {
		$db = new FakeDatabaseOracle( [] );
		$output = $db->buildIntegerCast( 123 );
		$this->assertSame( 'CAST ( 123 AS INTEGER )', $output );
	}

}
