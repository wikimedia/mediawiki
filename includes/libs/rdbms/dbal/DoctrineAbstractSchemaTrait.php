<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */
namespace Wikimedia\Rdbms;

use Doctrine\DBAL\Schema\Schema;

/**
 * Trait for schema spec of doctrine-based abstract schema
 * @since 1.36
 * @internal
 */
trait DoctrineAbstractSchemaTrait {

	private $platform;

	private function addTableToSchema( Schema $schema, array $schemaSpec ) {
		$prefix = $this->platform->getName() === 'postgresql' ? '' : '/*_*/';

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
