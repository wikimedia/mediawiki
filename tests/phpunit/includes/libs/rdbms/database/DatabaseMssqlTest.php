<?php

class DatabaseMssqlTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	public function provideBuildSubstring() {
		yield [ 'someField', 1, 2, 'SUBSTRING(someField,1,2)' ];
		yield [ 'someField', 1, null, 'SUBSTRING(someField,1,2147483647)' ];
		yield [ 'someField', 1, 3333333333, 'SUBSTRING(someField,1,3333333333)' ];
	}

	/**
	 * @covers Wikimedia\Rdbms\DatabaseMssql::buildSubstring
	 * @dataProvider provideBuildSubstring
	 */
	public function testBuildSubstring( $input, $start, $length, $expected ) {
		$db = new FakeDatabaseMssql( [] );
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
	 * @covers Wikimedia\Rdbms\DatabaseMssql::buildSubstring
	 * @dataProvider provideBuildSubstring_invalidParams
	 */
	public function testBuildSubstring_invalidParams( $start, $length ) {
		$db = new FakeDatabaseMssql( [] );
		$this->setExpectedException( InvalidArgumentException::class );
		$db->buildSubstring( 'foo', $start, $length );
	}

}
