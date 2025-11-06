<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\Rdbms;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Schema\Schema;

/**
 * Trait for schema spec of doctrine-based abstract schema
 * @since 1.36
 * @internal
 */
trait DoctrineAbstractSchemaTrait {

	private AbstractPlatform $platform;

	private function addTableToSchema( Schema $schema, array $schemaSpec ): Schema {
		$prefix = ( $this->platform instanceof PostgreSQLPlatform ) ? '' : '/*_*/';

		$table = $schema->createTable( $prefix . $schemaSpec['name'] );
		foreach ( $schemaSpec['columns'] as $column ) {
			$table->addColumn( $column['name'], $column['type'], $column['options'] );
		}

		foreach ( $schemaSpec['indexes'] as $index ) {
			if ( $index['unique'] === true ) {
				$table->addUniqueIndex( $index['columns'], $index['name'], $index['options'] ?? [] );
			} else {
				$table->addIndex( $index['columns'], $index['name'], $index['flags'] ?? [], $index['options'] ?? [] );
			}
		}

		if ( isset( $schemaSpec['pk'] ) && $schemaSpec['pk'] !== [] ) {
			$table->setPrimaryKey( $schemaSpec['pk'] );
		}

		if ( isset( $schemaSpec['table_options'] ) ) {
			$table->addOption( 'table_options', implode( ' ', $schemaSpec['table_options'] ) );
		} else {
			$table->addOption( 'table_options', '/*$wgDBTableOptions*/' );
		}

		return $schema;
	}
}
