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

	const TABLE_PREFIX = '/*_*/';

	public function __construct( AbstractPlatform $platform ) {
		$this->schema = new Schema();
		$this->platform = $platform;
	}

	public function addTable( array $schema ) {
		$table = $this->schema->createTable( self::TABLE_PREFIX . $schema['name'] );
		foreach ( $schema['columns'] as $column ) {
			$table->addColumn( $column[0], $column[1], $column[2] );
		}
		foreach ( $schema['indexes'] as $index ) {
			if ( $index['unique'] === true ) {
				$table->addUniqueIndex( $index[1], $index[0] );
			} else {
				$table->addIndex( $index[1], $index[0] );
			}
		}
		$table->setPrimaryKey( $schema['pk'] );
		$table->addOption( 'table_options', '' );
	}

	public function getSql() {
		return $this->schema->toSql( $this->platform );
	}
}
