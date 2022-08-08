<?php

use MediaWiki\Tests\Unit\Libs\Rdbms\AddQuoterMock;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\Platform\SqlitePlatform;

/**
 * @covers Wikimedia\Rdbms\Platform\SqlitePlatform
 */
class SqlitePlatformTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	/**
	 * @return SqlitePlatform
	 */
	private function getPlatform() {
		return new SqlitePlatform(
			new AddQuoterMock(),
			null,
			new DatabaseDomain( null, null, '' )
		);
	}

	public function provideBuildSubstring() {
		yield [ 'someField', 1, 2, 'SUBSTR(someField,1,2)' ];
		yield [ 'someField', 1, null, 'SUBSTR(someField,1)' ];
	}

	/**
	 * @dataProvider provideBuildSubstring
	 */
	public function testBuildSubstring( $input, $start, $length, $expected ) {
		$output = $this->getPlatform()->buildSubstring( $input, $start, $length );
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
	 * @dataProvider provideBuildSubstring_invalidParams
	 */
	public function testBuildSubstring_invalidParams( $start, $length ) {
		$this->expectException( InvalidArgumentException::class );
		$this->getPlatform()->buildSubstring( 'foo', $start, $length );
	}

	/**
	 * @dataProvider provideGreatest
	 */
	public function testBuildGreatest( $fields, $values, $sqlText ) {
		$this->assertEquals(
			$sqlText,
			trim( $this->getPlatform()->buildGreatest( $fields, $values ) )
		);
	}

	public static function provideGreatest() {
		return [
			[
				'field',
				'value',
				"MAX(\"field\",'value')"
			],
			[
				[ 'field' ],
				[ 'value' ],
				"MAX(\"field\",'value')"
			],
			[
				[ 'field', 'field2' ],
				[ 'value', 'value2', 3, 7.6 ],
				"MAX(\"field\",\"field2\",'value','value2',3,7.6)"
			],
			[
				[ 'field', 'a' => "\"field2\"+1" ],
				[ 'value', 'value2', 3, 7.6 ],
				"MAX(\"field\",\"field2\"+1,'value','value2',3,7.6)"
			],
		];
	}

	/**
	 * @dataProvider provideLeast
	 */
	public function testBuildLeast( $fields, $values, $sqlText ) {
		$this->assertEquals(
			$sqlText,
			trim( $this->getPlatform()->buildLeast( $fields, $values ) )
		);
	}

	public static function provideLeast() {
		return [
			[
				'field',
				'value',
				"MIN(\"field\",'value')"
			],
			[
				[ 'field' ],
				[ 'value' ],
				"MIN(\"field\",'value')"
			],
			[
				[ 'field', 'field2' ],
				[ 'value', 'value2', 3, 7.6 ],
				"MIN(\"field\",\"field2\",'value','value2',3,7.6)"
			],
			[
				[ 'field', 'a' => "\"field2\"+1" ],
				[ 'value', 'value2', 3, 7.6 ],
				"MIN(\"field\",\"field2\"+1,'value','value2',3,7.6)"
			],
		];
	}

	public static function provideReplaceVars() {
		return [
			[ 'foo', 'foo' ],
			[
				"CREATE TABLE /**/foo (foo_key INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, " .
				"foo_bar TEXT, foo_name TEXT NOT NULL DEFAULT '', foo_int INTEGER, foo_int2 INTEGER );",
				"CREATE TABLE /**/foo (foo_key int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT, " .
				"foo_bar char(13), foo_name varchar(255) binary NOT NULL DEFAULT '', " .
				"foo_int tinyint ( 8 ), foo_int2 int(16) ) ENGINE=MyISAM;"
			],
			[
				"CREATE TABLE foo ( foo1 REAL, foo2 REAL, foo3 REAL );",
				"CREATE TABLE foo ( foo1 FLOAT, foo2 DOUBLE( 1,10), foo3 DOUBLE PRECISION );"
			],
			[
				"CREATE TABLE foo ( foo_binary1 BLOB, foo_binary2 BLOB );",
				"CREATE TABLE foo ( foo_binary1 binary(16), foo_binary2 varbinary(32) );"
			],
			[
				"CREATE TABLE text ( text_foo TEXT );",
				"CREATE TABLE text ( text_foo tinytext );"
			],
			[
				"CREATE TABLE foo ( foobar INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL );",
				"CREATE TABLE foo ( foobar INT PRIMARY KEY NOT NULL AUTO_INCREMENT );"
			],
			[
				"CREATE TABLE foo ( foobar INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL );",
				"CREATE TABLE foo ( foobar INT PRIMARY KEY AUTO_INCREMENT NOT NULL );"
			],
			[
				"CREATE TABLE enums( enum1 TEXT, myenum TEXT)",
				"CREATE TABLE enums( enum1 ENUM('A', 'B'), myenum ENUM ('X', 'Y'))"
			],
			[
				"ALTER TABLE foo ADD COLUMN foo_bar INTEGER DEFAULT 42",
				"ALTER TABLE foo\nADD COLUMN foo_bar int(10) unsigned DEFAULT 42"
			],
			[
				"DROP INDEX foo",
				"DROP INDEX /*i*/foo ON /*_*/bar"
			],
			[
				"DROP INDEX foo -- dropping index",
				"DROP INDEX /*i*/foo ON /*_*/bar -- dropping index"
			],
			[
				"INSERT OR IGNORE INTO foo VALUES ('bar')",
				"INSERT OR IGNORE INTO foo VALUES ('bar')"
			]
		];
	}

	/**
	 * @param string $sql
	 * @return string
	 */
	private function replaceVars( $sql ) {
		/** @var Database $wrapper */
		$platform = new SqlitePlatform( new AddQuoterMock() );
		// normalize spacing to hide implementation details
		return preg_replace( '/\s+/', ' ', $platform->replaceVars( $sql ) );
	}

	/**
	 * @dataProvider provideReplaceVars
	 */
	public function testReplaceVars( $expected, $sql ) {
		$this->assertEquals( $expected,  $this->replaceVars( $sql ) );
	}

	public function testTableName() {
		// @todo Moar!
		$platform = $this->getPlatform();
		$this->assertEquals( 'foo', $platform->tableName( 'foo' ) );
		$this->assertEquals( 'sqlite_master', $platform->tableName( 'sqlite_master' ) );
		$platform->setPrefix( 'foo_' );
		$this->assertEquals( 'sqlite_master', $platform->tableName( 'sqlite_master' ) );
		$this->assertEquals( 'foo_bar', $platform->tableName( 'bar' ) );
	}

}
