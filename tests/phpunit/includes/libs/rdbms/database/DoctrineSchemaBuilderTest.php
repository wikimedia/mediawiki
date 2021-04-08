<?php

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Wikimedia\Rdbms\DoctrineSchemaBuilder;

class DoctrineSchemaBuilderTest extends \PHPUnit\Framework\TestCase {

	/**
	 * @covers \Wikimedia\Rdbms\DoctrineSchemaBuilder
	 */
	public function testGetResultAllTables() {
		$platform = new MySQLPlatform();
		$builder = new DoctrineSchemaBuilder( $platform );
		$tables = json_decode( file_get_contents( __DIR__ . '/../../../../data/db/tables.json' ), true );
		$expected = file_get_contents( __DIR__ . '/../../../../data/db/mysql/tables.sql' );
		foreach ( $tables as $table ) {
			$builder->addTable( $table );
		}

		$actual = implode( "\n", $builder->getSql() );
		$actual = preg_replace( "/\s*?(\n|$)/m", "", $actual );
		$expected = preg_replace( "/\s*?(\n|$)/m", "", $expected );

		$this->assertSame(
			$expected,
			$actual
		);
	}
}
