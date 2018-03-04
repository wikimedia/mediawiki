<?php

/**
 * DatabaseSqliteTest is already defined in mediawiki core hence the 'Rdbms' included in this
 * class name.
 * The test in core should have mediawiki specific stuff removed and the tests moved to this
 * rdbms libs test.
 */
class DatabaseSqliteRdbmsTest extends PHPUnit\Framework\TestCase {

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

}
