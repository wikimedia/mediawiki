<?php

use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\Rdbms\DatabaseSqlite;

/**
 * DatabaseSqliteTest is already defined in mediawiki core hence the 'Rdbms' included in this
 * class name.
 * The test in core should have mediawiki specific stuff removed and the tests moved to this
 * rdbms libs test.
 */
class DatabaseSqliteRdbmsTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	/**
	 * @return MockObject|DatabaseSqlite
	 */
	private function getMockDb() {
		$db = $this->getMockBuilder( DatabaseSqlite::class )
			->disableOriginalConstructor()
			->setMethods( [ 'open', 'query', 'addQuotes' ] )
			->getMock();

		$db->expects( $this->any() )->method( 'addQuotes' )->willReturnCallback(
			function ( $s ) {
				return "'$s'";
			}
		);

		return $db;
	}

	public function provideBuildSubstring() {
		yield [ 'someField', 1, 2, 'SUBSTR(someField,1,2)' ];
		yield [ 'someField', 1, null, 'SUBSTR(someField,1)' ];
	}

	/**
	 * @covers Wikimedia\Rdbms\DatabaseSqlite::buildSubstring
	 * @dataProvider provideBuildSubstring
	 */
	public function testBuildSubstring( $input, $start, $length, $expected ) {
		$dbMock = $this->getMockDb();
		$output = $dbMock->buildSubstring( $input, $start, $length );
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
	 * @covers Wikimedia\Rdbms\DatabaseSqlite::buildSubstring
	 * @dataProvider provideBuildSubstring_invalidParams
	 */
	public function testBuildSubstring_invalidParams( $start, $length ) {
		$dbMock = $this->getMockDb();
		$this->expectException( InvalidArgumentException::class );
		$dbMock->buildSubstring( 'foo', $start, $length );
	}

	/**
	 * @dataProvider provideGreatest
	 * @covers Wikimedia\Rdbms\DatabaseSqlite::buildGreatest
	 */
	public function testBuildGreatest( $fields, $values, $sqlText ) {
		$dbMock = $this->getMockDb();
		$this->assertEquals(
			$sqlText,
			trim( $dbMock->buildGreatest( $fields, $values ) )
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
	 * @covers Wikimedia\Rdbms\DatabaseSqlite::buildLeast
	 */
	public function testBuildLeast( $fields, $values, $sqlText ) {
		$dbMock = $this->getMockDb();
		$this->assertEquals(
			$sqlText,
			trim( $dbMock->buildLeast( $fields, $values ) )
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
}
