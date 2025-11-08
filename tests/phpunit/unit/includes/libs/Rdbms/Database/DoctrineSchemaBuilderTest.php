<?php

namespace Wikimedia\Tests\Rdbms;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\SqlitePlatform;
use MediaWikiUnitTestCase;
use Wikimedia\Rdbms\DoctrineSchemaBuilder;
use Wikimedia\Rdbms\MWMySQLPlatform;
use Wikimedia\Rdbms\MWPostgreSqlPlatform;

class DoctrineSchemaBuilderTest extends MediaWikiUnitTestCase {

	/**
	 * @dataProvider provideTestGetResultAllTables
	 * @covers \Wikimedia\Rdbms\DoctrineSchemaBuilder
	 *
	 * @param AbstractPlatform $platform
	 * @param string $expectedFile path fragment
	 */
	public function testGetResultAllTables( $platform, $expectedFile ) {
		$basePath = dirname( __DIR__, 5 );
		$builder = new DoctrineSchemaBuilder( $platform );
		$json = file_get_contents( $basePath . '/data/db/tables.json' );
		$tables = json_decode( $json, true );

		foreach ( $tables as $table ) {
			$builder->addTable( $table );
		}

		$actual = implode( "\n", $builder->getSql() );
		$actual = preg_replace( "/\s*?(\n|$)/m", "", $actual );

		$expected = file_get_contents( $basePath . $expectedFile );
		$expected = preg_replace( "/\s*?(\n|$)/m", "", $expected );

		$this->assertSame( $expected, $actual );
	}

	public static function provideTestGetResultAllTables() {
		yield 'MySQL schema tables' => [
			new MWMySQLPlatform,
			'/data/db/mysql/tables-generated.sql',
		];

		yield 'PostgreSQL schema tables' => [
			new MWPostgreSqlPlatform,
			'/data/db/postgres/tables-generated.sql'
		];

		yield 'SQLite schema tables' => [
			new SqlitePlatform,
			'/data/db/sqlite/tables-generated.sql'
		];
	}
}
