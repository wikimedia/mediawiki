<?php

class DatabaseSqliteTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	public function provideBuildSubstring() {
		yield [ 'someField', 1, 2, 'SUBSTR(someField,1,2)' ];
		yield [ 'someField', 1, null, 'SUBSTR(someField,1)' ];
	}

	/**
	 * @covers Wikimedia\Rdbms\DatabaseSqlite::buildSubstring
	 * @dataProvider provideBuildSubstring
	 */
	public function testBuildSubstring( $input, $start, $length, $expected ) {
		$db = new FakeDatabaseSqlite( [] );
		$output = $db->buildSubstring( $input, $start, $length );
		$this->assertSame( $expected, $output );
	}

	/**
	 * @covers \Wikimedia\Rdbms\DatabaseSqlite::buildIntegerCast
	 */
	public function testBuildIntegerCast() {
		$db = new FakeDatabaseSqlite( [] );
		$output = $db->buildIntegerCast( 'fieldName' );
		$this->assertSame( 'CAST ( fieldName AS INTEGER )', $output );
	}

}
