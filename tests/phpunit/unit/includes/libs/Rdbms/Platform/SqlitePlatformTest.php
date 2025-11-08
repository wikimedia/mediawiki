<?php

namespace Wikimedia\Tests\Rdbms;

use InvalidArgumentException;
use MediaWiki\Tests\Unit\Libs\Rdbms\AddQuoterMock;
use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\DBLanguageError;
use Wikimedia\Rdbms\Platform\SqlitePlatform;

/**
 * @covers \Wikimedia\Rdbms\Platform\SqlitePlatform
 */
class SqlitePlatformTest extends TestCase {

	use MediaWikiCoversValidator;

	private SqlitePlatform $platform;

	protected function setUp(): void {
		parent::setUp();
		$this->platform = new SqlitePlatform(
			new AddQuoterMock(),
			null,
			new DatabaseDomain( null, null, '' )
		);
	}

	public static function provideBuildSubstring() {
		yield [ 'someField', 1, 2, 'SUBSTR(someField,1,2)' ];
		yield [ 'someField', 1, null, 'SUBSTR(someField,1)' ];
	}

	/**
	 * @dataProvider provideBuildSubstring
	 */
	public function testBuildSubstring( $input, $start, $length, $expected ) {
		$output = $this->platform->buildSubstring( $input, $start, $length );
		$this->assertSame( $expected, $output );
	}

	public static function provideBuildSubstring_invalidParams() {
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
		$this->platform->buildSubstring( 'foo', $start, $length );
	}

	/**
	 * @dataProvider provideGreatest
	 */
	public function testBuildGreatest( $fields, $values, $sqlText ) {
		$this->assertEquals(
			$sqlText,
			trim( $this->platform->buildGreatest( $fields, $values ) )
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
			trim( $this->platform->buildLeast( $fields, $values ) )
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

	public function testTableName() {
		// @todo Moar!
		$platform = $this->platform;
		$this->assertEquals( '"foo"', $platform->tableName( 'foo' ) );
		$this->assertEquals( 'sqlite_master', $platform->tableName( 'sqlite_master' ) );
		$platform->setPrefix( 'foo_' );
		$this->assertEquals( 'sqlite_master', $platform->tableName( 'sqlite_master' ) );
		$this->assertEquals( '"foo_bar"', $platform->tableName( 'bar' ) );
	}

	public static function provideTableIdentifiers() {
		// No DB name set
		yield [
			'table',
			new DatabaseDomain( null, null, '' ),
			[ null, 'table' ],
			null
		];
		yield [
			'database.table',
			new DatabaseDomain( null, null, '' ),
			[ 'database', 'table' ],
			null
		];
		yield [
			'database.schema.table',
			new DatabaseDomain( null, null, '' ),
			[ 'database', 'table' ],
			DBLanguageError::class
		];
		yield [
			'"database"."schema"."table"',
			new DatabaseDomain( null, null, '' ),
			[ 'database', 'table' ],
			DBLanguageError::class
		];
		yield [
			'"database"."table"',
			new DatabaseDomain( null, null, '' ),
			[ 'database', 'table' ],
			null
		];
		// DB name set
		yield [
			'table',
			new DatabaseDomain( 'database', null, '' ),
			[ 'database', 'table' ],
			null
		];
		yield [
			'database.table',
			new DatabaseDomain( 'database', null, '' ),
			[ 'database', 'table' ],
			null
		];
		yield [
			'database.schema.table',
			new DatabaseDomain( 'database', null, '' ),
			[ 'database', 'table' ],
			DBLanguageError::class
		];
		yield [
			'"database"."schema"."table"',
			new DatabaseDomain( 'database', null, '' ),
			[ 'database', 'table' ],
			DBLanguageError::class
		];
		yield [
			'"database"."table"',
			new DatabaseDomain( 'database', null, '' ),
			[ 'database', 'table' ],
			null
		];
	}

	/**
	 * @dataProvider provideTableIdentifiers
	 */
	public function testGetDatabaseAndTableIdentifiers(
		$tableName,
		$domain,
		$expectedIdentifiers,
		$expectedException
	) {
		$platform = new SqlitePlatform( new AddQuoterMock(), null, $domain );

		if ( $expectedException !== null ) {
			$this->expectException( DBLanguageError::class );
			$platform->getDatabaseAndTableIdentifier( $tableName );
		} else {
			$this->assertSame(
				$expectedIdentifiers,
				$platform->getDatabaseAndTableIdentifier( $tableName )
			);
		}
	}
}
