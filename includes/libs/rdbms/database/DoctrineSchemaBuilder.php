<?php

namespace Wikimedia\Rdbms;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

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

		// TODO: Find a better place to register custom types
		if ( !Type::hasType( TimestampType::TIMESTAMP ) ) {
			Type::addType( TimestampType::TIMESTAMP, TimestampType::class );
		}
		$this->platform->registerDoctrineTypeMapping( 'Timestamp', TimestampType::TIMESTAMP );

		if ( !Type::hasType( TinyIntType::TINYINT ) ) {
			Type::addType( TinyIntType::TINYINT, TinyIntType::class );
		}
		$this->platform->registerDoctrineTypeMapping( 'Tinyint', TinyIntType::TINYINT );
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
