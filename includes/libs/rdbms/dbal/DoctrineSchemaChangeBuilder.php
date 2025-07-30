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
	use DoctrineAbstractSchemaTrait;

	private AbstractPlatform $platform;

	/**
	 * A builder object that take abstract schema definition and produces sql to create the tables.
	 *
	 * @param AbstractPlatform $platform A Doctrine Platform object, Can be Mysql, Sqlite, etc.
	 */
	public function __construct( AbstractPlatform $platform ) {
		$this->platform = $platform;
	}

	private function getTableSchema( array $tableSpec ): Schema {
		if ( !$tableSpec ) {
			// Used for not yet created tables.
			return new Schema();
		}
		return $this->addTableToSchema( new Schema(), $tableSpec );
	}

	public function getSchemaChangeSql( array $schemaChangeSpec ): array {
		$comparator = new Comparator( $this->platform );
		$schemaDiff = $comparator->compareSchemas(
			$this->getTableSchema( $schemaChangeSpec['before'] ),
			$this->getTableSchema( $schemaChangeSpec['after'] )
		);
		return $this->platform->getAlterSchemaSQL( $schemaDiff );
	}
}
