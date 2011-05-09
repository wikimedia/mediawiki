<?php

class MockDatabaseSqlite extends DatabaseSqliteStandalone {
	var $lastQuery;

	function __construct( ) {
		parent::__construct( ':memory:' );
	}

	function query( $sql, $fname = '', $tempIgnore = false ) {
		$this->lastQuery = $sql;
		return true;
	}

	function replaceVars( $s ) {
		return parent::replaceVars( $s );
	}
}

/**
 * @group sqlite
 */
class DatabaseSqliteTest extends MediaWikiTestCase {
	var $db;

	public function setUp() {
		if ( !Sqlite::isPresent() ) {
			$this->markTestSkipped( 'No SQLite support detected' );
		}
		$this->db = new MockDatabaseSqlite();
		if ( version_compare( $this->db->getServerVersion(), '3.6.0', '<' ) ) {
			$this->markTestSkipped( "SQLite at least 3.6 required, {$this->db->getServerVersion()} found" );
		}
	}

	private function replaceVars( $sql ) {
		// normalize spacing to hide implementation details
		return preg_replace( '/\s+/', ' ', $this->db->replaceVars( $sql ) );
	}

	public function testReplaceVars() {
		$this->assertEquals( 'foo', $this->replaceVars( 'foo' ), "Don't break anything accidentally" );

		$this->assertEquals( "CREATE TABLE /**/foo (foo_key INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, "
			. "foo_bar TEXT, foo_name TEXT NOT NULL DEFAULT '', foo_int INTEGER, foo_int2 INTEGER );",
			$this->replaceVars( "CREATE TABLE /**/foo (foo_key int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
			foo_bar char(13), foo_name varchar(255) binary NOT NULL DEFAULT '', foo_int tinyint ( 8 ), foo_int2 int(16) ) ENGINE=MyISAM;" )
			);

		$this->assertEquals( "CREATE TABLE foo ( foo1 REAL, foo2 REAL, foo3 REAL );",
			$this->replaceVars( "CREATE TABLE foo ( foo1 FLOAT, foo2 DOUBLE( 1,10), foo3 DOUBLE PRECISION );" )
			);

		$this->assertEquals( "CREATE TABLE foo ( foo_binary1 BLOB, foo_binary2 BLOB );",
			$this->replaceVars( "CREATE TABLE foo ( foo_binary1 binary(16), foo_binary2 varbinary(32) );" )
			);

		$this->assertEquals( "CREATE TABLE text ( text_foo TEXT );",
			$this->replaceVars( "CREATE TABLE text ( text_foo tinytext );" ),
			'Table name changed'
			);

		$this->assertEquals( "CREATE TABLE foo ( foobar INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL );",
			$this->replaceVars("CREATE TABLE foo ( foobar INT PRIMARY KEY NOT NULL AUTO_INCREMENT );" )
			);
		$this->assertEquals( "CREATE TABLE foo ( foobar INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL );",
			$this->replaceVars("CREATE TABLE foo ( foobar INT PRIMARY KEY AUTO_INCREMENT NOT NULL );" )
			);

		$this->assertEquals( "CREATE TABLE enums( enum1 TEXT, myenum TEXT)",
			$this->replaceVars( "CREATE TABLE enums( enum1 ENUM('A', 'B'), myenum ENUM ('X', 'Y'))" )
			);

		$this->assertEquals( "ALTER TABLE foo ADD COLUMN foo_bar INTEGER DEFAULT 42",
			$this->replaceVars( "ALTER TABLE foo\nADD COLUMN foo_bar int(10) unsigned DEFAULT 42" )
			);
	}

	public function testTableName() {
		// @todo Moar!
		$db = new DatabaseSqliteStandalone( ':memory:' );
		$this->assertEquals( 'foo', $db->tableName( 'foo' ) );
		$this->assertEquals( 'sqlite_master', $db->tableName( 'sqlite_master' ) );
		$db->tablePrefix( 'foo' );
		$this->assertEquals( 'sqlite_master', $db->tableName( 'sqlite_master' ) );
		$this->assertEquals( 'foobar', $db->tableName( 'bar' ) );
	}
	
	public function testDuplicateTableStructure() {
		$db = new DatabaseSqliteStandalone( ':memory:' );
		$db->query( 'CREATE TABLE foo(foo, barfoo)' );

		$db->duplicateTableStructure( 'foo', 'bar' );
		$this->assertEquals( 'CREATE TABLE "bar"(foo, barfoo)',
			$db->selectField( 'sqlite_master', 'sql', array( 'name' => 'bar' ) ),
			'Normal table duplication'
		);

		$db->duplicateTableStructure( 'foo', 'baz', true );
		$this->assertEquals( 'CREATE TABLE "baz"(foo, barfoo)',
			$db->selectField( 'sqlite_temp_master', 'sql', array( 'name' => 'baz' ) ),
			'Creation of temporary duplicate'
		);
		$this->assertEquals( 0,
			$db->selectField( 'sqlite_master', 'COUNT(*)', array( 'name' => 'baz' ) ),
			'Create a temporary duplicate only'
		);
	}
	
	public function testDuplicateTableStructureVirtual() {
		$db = new DatabaseSqliteStandalone( ':memory:' );
		if ( $db->getFulltextSearchModule() != 'FTS3' ) {
			$this->markTestSkipped( 'FTS3 not supported, cannot create virtual tables' );
		}
		$db->query( 'CREATE VIRTUAL TABLE "foo" USING FTS3(foobar)' );

		$db->duplicateTableStructure( 'foo', 'bar' );
		$this->assertEquals( 'CREATE VIRTUAL TABLE "bar" USING FTS3(foobar)',
			$db->selectField( 'sqlite_master', 'sql', array( 'name' => 'bar' ) ),
			'Duplication of virtual tables'
		);

		$db->duplicateTableStructure( 'foo', 'baz', true );
		$this->assertEquals( 'CREATE VIRTUAL TABLE "baz" USING FTS3(foobar)',
			$db->selectField( 'sqlite_master', 'sql', array( 'name' => 'baz' ) ),
			"Can't create temporary virtual tables, should fall back to non-temporary duplication"
		);
	}

	public function testEntireSchema() {
		global $IP;

		$result = Sqlite::checkSqlSyntax( "$IP/maintenance/tables.sql" );
		if ( $result !== true ) {
			$this->fail( $result );
		}
	}

	/**
	 * Runs upgrades of older databases and compares results with current schema
	 * @todo: currently only checks list of tables
	 */
	public function testUpgrades() {
		global $IP, $wgVersion;

		// Versions tested
		$versions = array(
			//'1.13', disabled for now, was totally screwed up
			// SQLite wasn't included in 1.14
			'1.15',
			'1.16',
			'1.17',
		);

		// Mismatches for these columns we can safely ignore
		$ignoredColumns = array(
			'user_newtalk.user_last_timestamp', // r84185
		);
			
		$currentDB = new DatabaseSqliteStandalone( ':memory:' );
		$currentDB->sourceFile( "$IP/maintenance/tables.sql" );
		$currentTables = $this->getTables( $currentDB );
		sort( $currentTables );

		foreach ( $versions as $version ) {
			$versions = "upgrading from $version to $wgVersion";
			$db = $this->prepareDB( $version );
			$tables = $this->getTables( $db );
			$this->assertEquals( $currentTables, $tables, "Different tables $versions" );
			foreach ( $tables as $table ) {
				$currentCols = $this->getColumns( $currentDB, $table );
				$cols = $this->getColumns( $db, $table );
				$this->assertEquals(
					array_keys( $currentCols ),
					array_keys( $cols ),
					"Mismatching columns for table $table $versions"
				);
				foreach ( $currentCols as $name => $column ) {
					$fullName = "$table.$name";
					$this->assertEquals(
						(bool)$column->pk,
						(bool)$cols[$name]->pk,
						"PRIMARY KEY status does not match for column $fullName $versions"
					);
					if ( !in_array( $fullName, $ignoredColumns ) ) {
						$this->assertEquals(
							(bool)$column->notnull,
							(bool)$cols[$name]->notnull,
							"NOT NULL status does not match for column $fullName $versions"
						);
						$this->assertEquals(
							$column->dflt_value,
							$cols[$name]->dflt_value,
							"Default values does not match for column $fullName $versions"
						);
					}
				}
			}
			$db->close();
		}
	}

	private function prepareDB( $version ) {
		static $maint = null;
		if ( $maint === null ) {
			$maint = new FakeMaintenance();
			$maint->loadParamsAndArgs( null, array( 'quiet' => 1 ) );
		}
		
		$db = new DatabaseSqliteStandalone( ':memory:' );
		$db->sourceFile( dirname( __FILE__ ) . "/sqlite/tables-$version.sql" );
		$updater = DatabaseUpdater::newForDB( $db, false, $maint );
		$updater->doUpdates( array( 'core' ) );
		return $db;
	}

	private function getTables( $db ) {
		$list = array_flip( $db->listTables() );
		$excluded = array(
			'math', // moved out of core in 1.18
			'searchindex',
			'searchindex_content',
			'searchindex_segments',
			'searchindex_segdir',
			// FTS4 ready!!1
			'searchindex_docsize',
			'searchindex_stat',
		);
		foreach ( $excluded as $t ) {
			unset( $list[$t] );
		}
		$list = array_flip( $list );
		sort( $list );
		return $list;
	}

	private function getColumns( $db, $table ) {
		$cols = array();
		$res = $db->query( "PRAGMA table_info($table)" );
		$this->assertNotNull( $res );
		foreach ( $res as $col ) {
			$cols[$col->name] = $col;
		}
		ksort( $cols );
		return $cols;
	}
}
