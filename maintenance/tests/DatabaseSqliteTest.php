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
			. "foo_name TEXT NOT NULL DEFAULT '');",
			$this->replaceVars( "CREATE TABLE /**/foo (foo_key int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
			foo_name varchar(255) binary NOT NULL DEFAULT '') ENGINE=MyISAM;" )
			);

		$this->assertEquals( "CREATE TABLE foo ( foo_binary1 BLOB, foo_binary2 BLOB );",
			$this->replaceVars( "CREATE TABLE foo ( foo_binary1 binary(16), foo_binary2 varbinary(32) );" )
			);

		$this->assertEquals( "CREATE TABLE text ( text_foo TEXT );",
			$this->replaceVars( "CREATE TABLE text ( text_foo tinytext );" ),
			'Table name changed'
			);
	}
}