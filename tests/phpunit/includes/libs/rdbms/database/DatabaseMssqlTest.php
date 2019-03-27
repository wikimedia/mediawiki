<?php

use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DatabaseMssql;

class DatabaseMssqlTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;
	use PHPUnit4And6Compat;

	/**
	 * @return PHPUnit_Framework_MockObject_MockObject|DatabaseMssql
	 */
	private function getMockDb() {
		return $this->getMockBuilder( DatabaseMssql::class )
			->disableOriginalConstructor()
			->setMethods( null )
			->getMock();
	}

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
	 * @covers Wikimedia\Rdbms\DatabaseMssql::buildSubstring
	 * @dataProvider provideBuildSubstring_invalidParams
	 */
	public function testBuildSubstring_invalidParams( $start, $length ) {
		$mockDb = $this->getMockDb();
		$this->setExpectedException( InvalidArgumentException::class );
		$mockDb->buildSubstring( 'foo', $start, $length );
	}

	/**
	 * @covers \Wikimedia\Rdbms\DatabaseMssql::getAttributes
	 */
	public function testAttributes() {
		$this->assertTrue( DatabaseMssql::getAttributes()[Database::ATTR_SCHEMAS_AS_TABLE_GROUPS] );
	}
}
