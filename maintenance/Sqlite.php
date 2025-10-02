<?php
/**
 * Helper class for sqlite-specific scripts
 *
 * @license GPL-2.0-or-later
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
	 * @return true|string True if no error or error string in case of errors
	 */
	public static function checkSqlSyntax( $files ) {
		if ( !self::isPresent() ) {
			throw new RuntimeException( "Can't check SQL syntax: SQLite not found" );
		}
		if ( !is_array( $files ) ) {
			$files = [ $files ];
		}

		$allowedTypes = array_fill_keys( [
			'integer',
			'real',
			'text',
			'blob',
			// NULL type is omitted intentionally
		], true );

		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );
		try {
			foreach ( $files as $file ) {
				$err = $db->sourceFile( $file );
				if ( $err ) {
					return $err;
				}
			}

			$tables = $db->query( "SELECT name FROM sqlite_master WHERE type='table'", __METHOD__ );
			foreach ( $tables as $table ) {
				if ( str_starts_with( $table->name, 'sqlite_' ) ) {
					continue;
				}

				$columns = $db->query(
					'PRAGMA table_info(' . $db->addIdentifierQuotes( $table->name ) . ')',
					__METHOD__
				);
				foreach ( $columns as $col ) {
					if ( !isset( $allowedTypes[strtolower( $col->type )] ) ) {
						$db->close( __METHOD__ );

						return "Table {$table->name} has column {$col->name} with non-native type '{$col->type}'";
					}
				}
			}
		} catch ( DBError $e ) {
			return $e->getMessage();
		}
		$db->close( __METHOD__ );

		return true;
	}
}
