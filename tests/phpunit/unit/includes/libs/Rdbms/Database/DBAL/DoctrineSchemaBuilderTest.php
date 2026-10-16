<?php

namespace Wikimedia\Tests\Rdbms\DBAL;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\SQLitePlatform;
use MediaWikiUnitTestCase;
use Wikimedia\Rdbms\DBAL\DoctrineSchemaBuilder;
use Wikimedia\Rdbms\DBAL\MWMySQLPlatform;
use Wikimedia\Rdbms\DBAL\MWPostgreSqlPlatform;

class DoctrineSchemaBuilderTest extends MediaWikiUnitTestCase {

	/**
	 * @dataProvider provideTestGetResultAllTables
	 * @covers \Wikimedia\Rdbms\DBAL\DoctrineSchemaBuilder
	 *
	 * @param AbstractPlatform $platform
	 * @param string $expectedFile path fragment
	 */
	public function testGetResultAllTables( $platform, $expectedFile ) {
		$basePath = dirname( __DIR__, 6 );
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
			new SQLitePlatform,
			'/data/db/sqlite/tables-generated.sql'
		];
	}
}
