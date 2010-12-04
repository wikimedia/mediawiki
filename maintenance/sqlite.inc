<?php

/**
 * This class contains code common to different SQLite-related maintenance scripts
 */
class Sqlite {

	/**
	 * Checks whether PHP has SQLite support
	 * @return bool
	 */
	public static function isPresent() {
		wfSuppressWarnings();
		$compiled = wfDl( 'pdo_sqlite' );
		wfRestoreWarnings();
		return $compiled;
	}

	/**
	 * Checks given files for correctness of SQL syntax. MySQL DDL will be converted to
	 * SQLite-compatible during processing.
	 * Will throw exceptions on SQL errors
	 * @return mixed true if no error or error string in case of errors
	 */
	public static function checkSqlSyntax( $files ) {
		if ( !Sqlite::isPresent() ) {
			throw new MWException( "Can't check SQL syntax: SQLite not found" );
		}
		if ( !is_array( $files ) ) {
			$files = array( $files );
		}

		$allowedTypes = array_flip( array(
			'integer',
			'real',
			'text',
			'blob', // NULL type is omitted intentionally
		) );

		$db = new DatabaseSqliteStandalone( ':memory:' );
		try {
			foreach ( $files as $file ) {
				$err = $db->sourceFile( $file );
				if ( $err != true ) {
					return $err;
				}
			}

			$tables = $db->query( "SELECT name FROM sqlite_master WHERE type='table'", __METHOD__ );
			foreach ( $tables as $table ) {
				if ( strpos( $table->name, 'sqlite_' ) === 0 ) continue;

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
 };