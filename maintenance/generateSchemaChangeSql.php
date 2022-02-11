<?php

/**
 * Convert a JSON abstract schema change to a schema change file in the given DBMS type
 *
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
 * @ingroup Maintenance
 */

use Doctrine\SqlFormatter\NullHighlighter;
use Doctrine\SqlFormatter\SqlFormatter;
use Wikimedia\Rdbms\DoctrineSchemaBuilderFactory;

require_once __DIR__ . '/includes/SchemaMaintenance.php';

/**
 * Maintenance script to generate schema from abstract json files.
 *
 * @ingroup Maintenance
 */
class GenerateSchemaChangeSql extends SchemaMaintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Build SQL files for schema changes from abstract JSON files' );
		$this->scriptName = 'generateSchemaChangeSql.php';
	}

	protected function generateSchema( string $platform, array $schema ): string {
		$schemaChangeBuilder = ( new DoctrineSchemaBuilderFactory() )->getSchemaChangeBuilder( $platform );

		$schemaChangeSqls = $schemaChangeBuilder->getSchemaChangeSql( $schema );

		$sql = '';

		if ( $schemaChangeSqls !== [] ) {
			// Temporary
			$sql .= implode( ";\n\n", $schemaChangeSqls ) . ';';
			$sql = ( new SqlFormatter( new NullHighlighter() ) )->format( $sql );
		} else {
			$this->fatalError( 'No schema changes detected!' );
		}

		// Postgres hacks
		if ( $platform === 'postgres' ) {
			// Remove table prefixes from Postgres schema, people should not set it
			// but better safe than sorry.
			$sql = str_replace( "\n/*_*/\n", ' ', $sql );

			// MySQL goes with varbinary for collation reasons, but postgres can't
			// properly understand BYTEA type and works just fine with TEXT type
			// FIXME: This should be fixed at some point (T257755)
			$sql = str_replace( "BYTEA", 'TEXT', $sql );
		}

		// Until the linting issue is resolved
		// https://github.com/doctrine/sql-formatter/issues/53
		$sql = str_replace( "\n/*_*/\n", " /*_*/", $sql );
		$sql = str_replace( "; ", ";\n", $sql );
		$sql = preg_replace( "/\n+? +?/", ' ', $sql );
		$sql = str_replace( "/*_*/  ", "/*_*/", $sql );

		// Sqlite hacks
		if ( $platform === 'sqlite' ) {
			// Doctrine prepends __temp__ to the table name and we set the table with the schema prefix causing invalid
			// sqlite.
			$sql = preg_replace( '/__temp__\s*\/\*_\*\//', '/*_*/__temp__', $sql );
		}

		return $sql;
	}

}

$maintClass = GenerateSchemaChangeSql::class;
require_once RUN_MAINTENANCE_IF_MAIN;
