<?php

namespace Wikimedia\Rdbms;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\Schema;

/**
 * @experimental
 * @unstable
 */
class DoctrineSchemaBuilder implements SchemaBuilder {
	use DoctrineAbstractSchemaTrait;

	private Schema $schema;
	private AbstractPlatform $platform;

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
		$this->addTableToSchema( $this->schema, $schema );
	}

	/**
	 * @inheritDoc
	 */
	public function getSql() {
		return $this->schema->toSql( $this->platform );
	}

}
