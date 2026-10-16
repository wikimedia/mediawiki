<?php

namespace Wikimedia\Tests\Rdbms\DBAL;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Platforms\SQLitePlatform;
use MediaWikiUnitTestCase;
use Wikimedia\Rdbms\DBAL\DoctrineSchemaChangeBuilder;
use Wikimedia\Rdbms\DBAL\MWPostgreSqlPlatform;

class DoctrineSchemaChangeBuilderTest extends MediaWikiUnitTestCase {

	/**
	 * @dataProvider provideTestGetResultAllTables
	 * @covers \Wikimedia\Rdbms\DBAL\DoctrineSchemaBuilder
	 *
	 * @param AbstractPlatform $platform
	 * @param string $expectedFile path fragment
	 */
	public function testGetResultAllTables( $platform, $expectedFile ) {
		$basePath = dirname( __DIR__, 6 );
		$builder = new DoctrineSchemaChangeBuilder( $platform );
		$json = file_get_contents( $basePath . '/data/db/patch-drop-ct_tag.json' );
		$schemaChange = json_decode( $json, true );

		$actual = implode( ";\n", $builder->getSchemaChangeSql( $schemaChange ) ) . ";\n";

		// sqlite hacks
		if ( $platform instanceof SQLitePlatform ) {
			// Doctrine prepends __temp__ to the table name and we set the table with
			// the schema prefix causing invalid sqlite.
			$actual = preg_replace( '/__temp__\s*\/\*_\*\//', '/*_*/__temp__', $actual );
		}

		$expected = file_get_contents( $basePath . $expectedFile );

		$this->assertSame( $expected, $actual );
	}

	public static function provideTestGetResultAllTables() {
		yield 'MySQL schema tables' => [
			new MySQLPlatform,
			'/data/db/mysql/patch-drop-ct_tag.sql'
		];

		yield 'PostgreSQL schema tables' => [
			new MWPostgreSqlPlatform,
			'/data/db/postgres/patch-drop-ct_tag.sql'
		];

		yield 'SQLite schema tables' => [
			new SQLitePlatform,
			'/data/db/sqlite/patch-drop-ct_tag.sql'
		];
	}
}
