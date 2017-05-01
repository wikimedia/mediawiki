<?php
/**
 * Helper class for sqlite-specific scripts
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

use Wikimedia\Rdbms\DatabaseSqlite;
use Wikimedia\Rdbms\DBError;

/**
 * This class contains code common to different SQLite-related maintenance scripts
 *
 * @ingroup Maintenance
 */
class Sqlite {

	/**
	 * Checks whether PHP has SQLite support
	 * @return bool
	 */
	public static function isPresent() {
		return extension_loaded( 'pdo_sqlite' );
	}

	/**
	 * Checks given files for correctness of SQL syntax. MySQL DDL will be converted to
	 * SQLite-compatible during processing.
	 * Will throw exceptions on SQL errors
	 * @param array|string $files
	 * @throws MWException
	 * @return bool True if no error or error string in case of errors
	 */
	public static function checkSqlSyntax( $files ) {
		if ( !Sqlite::isPresent() ) {
			throw new MWException( "Can't check SQL syntax: SQLite not found" );
		}
		if ( !is_array( $files ) ) {
			$files = [ $files ];
		}

		$allowedTypes = array_flip( [
			'integer',
			'real',
			'text',
			'blob', // NULL type is omitted intentionally
		] );

		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );
		try {
			foreach ( $files as $file ) {
				$err = $db->sourceFile( $file );
				if ( $err != true ) {
					return $err;
				}
			}

			$tables = $db->query( "SELECT name FROM sqlite_master WHERE type='table'", __METHOD__ );
			foreach ( $tables as $table ) {
				if ( strpos( $table->name, 'sqlite_' ) === 0 ) {
					continue;
				}

				$columns = $db->query( "PRAGMA table_info({$table->name})", __METHOD__ );
				foreach ( $columns as $col ) {
					if ( !isset( $allowedTypes[strtolower( $col->type )] ) ) {
						$db->close();

						return "Table {$table->name} has column {$col->name} with non-native type '{$col->type}'";
					}
				}
			}
		} catch ( DBError $e ) {
			return $e->getMessage();
		}
		$db->close();

		return true;
	}
}
