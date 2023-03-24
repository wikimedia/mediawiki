<?php

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Wikimedia\Rdbms\DoctrineSchemaChangeBuilder;
use Wikimedia\Rdbms\MWPostgreSqlPlatform;

class DoctrineSchemaChangeBuilderTest extends MediaWikiUnitTestCase {

	/**
	 * @dataProvider provideTestGetResultAllTables
	 * @covers \Wikimedia\Rdbms\DoctrineSchemaBuilder
	 *
	 * @param AbstractPlatform $platform
	 * @param string $expectedFile path fragment
	 */
	public function testGetResultAllTables( $platform, $expectedFile ) {
		$basePath = dirname( __DIR__, 5 );
		$builder = new DoctrineSchemaChangeBuilder( $platform );
		$json = file_get_contents( $basePath . '/data/db/patch-drop-ct_tag.json' );
		$schemaChange = json_decode( $json, true );

		$actual = implode( ";\n", $builder->getSchemaChangeSql( $schemaChange ) ) . ";\n";

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

		// See gerrit 827510
		// yield 'SQLite schema tables' => [
		// 	new SqlitePlatform,
		// 	'/data/db/sqlite/patch-drop-ct_tag.sql'
		// ];
	}
}
