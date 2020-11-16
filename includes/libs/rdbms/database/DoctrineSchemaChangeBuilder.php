<?php

namespace Wikimedia\Rdbms;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\Comparator;
use Doctrine\DBAL\Schema\Schema;

/**
 * @experimental
 * @unstable
 */
class DoctrineSchemaChangeBuilder implements SchemaChangeBuilder {
	private $platform;

	public const TABLE_PREFIX = '/*_*/';

	/**
	 * A builder object that take abstract schema definition and produces sql to create the tables.
	 *
	 * @param AbstractPlatform $platform A Doctrine Platform object, Can be Mysql, Sqlite, etc.
	 */
	public function __construct( AbstractPlatform $platform ) {
		$this->platform = $platform;
	}

	/**
	 * @inheritDoc
	 */
	private function getTableSchema( array $tableSpec ): Schema {
		$schema = new Schema();
		$table = $schema->createTable( self::TABLE_PREFIX . $tableSpec['name'] );
		foreach ( $tableSpec['columns'] as $column ) {
			$table->addColumn( $column['name'], $column['type'], $column['options'] );
		}
		foreach ( $tableSpec['indexes'] as $index ) {
			if ( $index['unique'] === true ) {
				$table->addUniqueIndex( $index['columns'], $index['name'] );
			} else {
				$table->addIndex( $index['columns'], $index['name'] );
			}
		}
		$table->setPrimaryKey( $tableSpec['pk'] );
		$table->addOption( 'table_options', '/*$wgDBTableOptions*/' );

		return $schema;
	}

	public function getSchemaChangeSql( array $schemaChangeSpec ): array {
		$comparator = new Comparator();
		$schemaDiff = $comparator->compare(
			$this->getTableSchema( $schemaChangeSpec['before'] ),
			$this->getTableSchema( $schemaChangeSpec['after'] )
		);
		return $schemaDiff->toSql( $this->platform );
	}
}
