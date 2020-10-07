<?php

namespace Wikimedia\Rdbms;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\Schema;

/**
 * @experimental
 * @unstable
 */
class DoctrineSchemaBuilder implements SchemaBuilder {
	private $schema;
	private $platform;

	public const TABLE_PREFIX = '/*_*/';

	/**
	 * A builder object that take abstract schema definition and produces sql to create the tables.
	 *
	 * @param AbstractPlatform $platform A Doctrine Platform object, Can be Mysql, Sqlite, etc.
	 */
	public function __construct( AbstractPlatform $platform ) {
		$this->schema = new Schema();
		$this->platform = $platform;
	}

	/**
	 * @inheritDoc
	 */
	public function addTable( array $schema ) {
		$table = $this->schema->createTable( self::TABLE_PREFIX . $schema['name'] );
		foreach ( $schema['columns'] as $column ) {
			$table->addColumn( $column['name'], $column['type'], $column['options'] );
		}
		foreach ( $schema['indexes'] as $index ) {
			if ( $index['unique'] === true ) {
				$table->addUniqueIndex( $index['columns'], $index['name'] );
			} else {
				$table->addIndex( $index['columns'], $index['name'] );
			}
		}
		$table->setPrimaryKey( $schema['pk'] );
		$table->addOption( 'table_options', '/*$wgDBTableOptions*/' );
	}

	/**
	 * @inheritDoc
	 */
	public function getSql() {
		return $this->schema->toSql( $this->platform );
	}
}
