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

	private $platform;

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
		return $this->addTableToSchema( new Schema(), $tableSpec );
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
