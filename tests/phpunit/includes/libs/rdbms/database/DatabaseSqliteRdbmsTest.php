<?php

use Wikimedia\Rdbms\DatabaseSqlite;

/**
 * DatabaseSqliteTest is already defined in mediawiki core hence the 'Rdbms' included in this
 * class name.
 * The test in core should have mediawiki specific stuff removed and the tests moved to this
 * rdbms libs test.
 */
class DatabaseSqliteRdbmsTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;
	use PHPUnit4And6Compat;

	/**
	 * @return PHPUnit_Framework_MockObject_MockObject|DatabaseSqlite
	 */
	private function getMockDb() {
		return $this->getMockBuilder( DatabaseSqlite::class )
			->disableOriginalConstructor()
			->setMethods( null )
			->getMock();
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
		$this->setExpectedException( InvalidArgumentException::class );
		$dbMock->buildSubstring( 'foo', $start, $length );
	}

}
