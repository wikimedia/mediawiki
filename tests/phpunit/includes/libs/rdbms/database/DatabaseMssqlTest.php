<?php

require_once __DIR__ . '/FakeDatabaseMssql.php';

class DatabaseMssqlTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	public function provideBuildSubstring() {
		yield [ 'someField', 1, 2, 'SUBSTRING(someField,1,2)' ];
		yield [ 'someField', 1, null, 'SUBSTRING(someField,1)' ];
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

}
