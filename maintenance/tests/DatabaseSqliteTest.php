<?php

class MockDatabaseSqlite extends DatabaseSqliteStandalone {
	var $lastQuery;

	function __construct( ) {
		parent::__construct( '' );
	}

	function query( $sql, $fname = '', $tempIgnore = false ) {
		$this->lastQuery = $sql;
		return true;
	}

	function replaceVars( $s ) {
		return parent::replaceVars( $s );
	}
}

class DatabaseSqliteTest extends PHPUnit_Framework_TestCase {
	var $db;

	function setup() {
		if ( !extension_loaded( 'pdo_sqlite' ) ) {
			$this->markTestIncomplete( 'No SQLite support detected' );
		}
		$this->db = new MockDatabaseSqlite();
	}

	function replaceVars( $sql ) {
		// normalize spacing to hide implementation details
		return preg_replace( '/\s+/', ' ', $this->db->replaceVars( $sql ) );
	}

	function testReplaceVars() {
		$this->assertEquals( 'foo', $this->replaceVars( 'foo' ), "Don't break anything accidentally" );

		$this->assertEquals( "CREATE TABLE /**/foo (foo_key INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, "
			. "foo_name TEXT NOT NULL DEFAULT '', foo_int INTEGER, foo_int2 INTEGER );",
			$this->replaceVars( "CREATE TABLE /**/foo (foo_key int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
			foo_name varchar(255) binary NOT NULL DEFAULT '', foo_int tinyint( 8 ), foo_int2 int(16) ) ENGINE=MyISAM;" )
			);

		$this->assertEquals( "CREATE TABLE foo ( foo_binary1 BLOB, foo_binary2 BLOB );",
			$this->replaceVars( "CREATE TABLE foo ( foo_binary1 binary(16), foo_binary2 varbinary(32) );" )
			);

		$this->assertEquals( "CREATE TABLE text ( text_foo TEXT );",
			$this->replaceVars( "CREATE TABLE text ( text_foo tinytext );" ),
			'Table name changed'
			);

		$this->assertEquals( "CREATE TABLE enums( enum1 TEXT, myenum TEXT)",
			$this->replaceVars( "CREATE TABLE enums( enum1 ENUM('A', 'B'), myenum ENUM ('X', 'Y'))" )
			);

		$this->assertEquals( "ALTER TABLE foo ADD COLUMN foo_bar INTEGER DEFAULT 42",
			$this->replaceVars( "ALTER TABLE foo\nADD COLUMN foo_bar int(10) unsigned DEFAULT 42" )
			);
	}

	function testEntireSchema() {
		global $IP;

		$allowedTypes = array_flip( array(
			'integer',
			'real',
			'text',
			'blob', // NULL type is omitted intentionally
		) );

		$db = new DatabaseSqliteStandalone( ':memory:' );
		$db->sourceFile( "$IP/maintenance/tables.sql" );

		$tables = $db->query( "SELECT name FROM sqlite_master WHERE type='table'", __METHOD__ );
		foreach ( $tables as $table ) {
			if ( strpos( $table->name, 'sqlite_' ) === 0 ) continue;

			$columns = $db->query( "PRAGMA table_info({$table->name})", __METHOD__ );
			foreach ( $columns as $col ) {
				if ( !isset( $allowedTypes[strtolower( $col->type )] ) ) {
					$this->fail( "Table {$table->name} has column {$col->name} with non-native type '{$col->type}'" );
				}
			}
		}
		$db->close();
	}
}