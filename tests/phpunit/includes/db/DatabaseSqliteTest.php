<?php

class DatabaseSqliteMock extends DatabaseSqlite {
	private $lastQuery;

	public static function newInstance( array $p = [] ) {
		$p['dbFilePath'] = ':memory:';
		$p['schema'] = false;

		return DatabaseBase::factory( 'SqliteMock', $p );
	}

	function query( $sql, $fname = '', $tempIgnore = false ) {
		$this->lastQuery = $sql;

		return true;
	}

	/**
	 * Override parent visibility to public
	 */
	public function replaceVars( $s ) {
		return parent::replaceVars( $s );
	}
}

/**
 * @group sqlite
 * @group Database
 * @group medium
 */
class DatabaseSqliteTest extends MediaWikiTestCase {
	/** @var DatabaseSqliteMock */
	protected $db;

	protected function setUp() {
		parent::setUp();

		if ( !Sqlite::isPresent() ) {
			$this->markTestSkipped( 'No SQLite support detected' );
		}
		$this->db = DatabaseSqliteMock::newInstance();
		if ( version_compare( $this->db->getServerVersion(), '3.6.0', '<' ) ) {
			$this->markTestSkipped( "SQLite at least 3.6 required, {$this->db->getServerVersion()} found" );
		}
	}

	private function replaceVars( $sql ) {
		// normalize spacing to hide implementation details
		return preg_replace( '/\s+/', ' ', $this->db->replaceVars( $sql ) );
	}

	private function assertResultIs( $expected, $res ) {
		$this->assertNotNull( $res );
		$i = 0;
		foreach ( $res as $row ) {
			foreach ( $expected[$i] as $key => $value ) {
				$this->assertTrue( isset( $row->$key ) );
				$this->assertEquals( $value, $row->$key );
			}
			$i++;
		}
		$this->assertEquals( count( $expected ), $i, 'Unexpected number of rows' );
	}

	public static function provideAddQuotes() {
		return [
			[ // #0: empty
				'', "''"
			],
			[ // #1: simple
				'foo bar', "'foo bar'"
			],
			[ // #2: including quote
				'foo\'bar', "'foo''bar'"
			],
			// #3: including \0 (must be represented as hex, per https://bugs.php.net/bug.php?id=63419)
			[
				"x\0y",
				"x'780079'",
			],
			[ // #4: blob object (must be represented as hex)
				new Blob( "hello" ),
				"x'68656c6c6f'",
			],
		];
	}

	/**
	 * @dataProvider provideAddQuotes()
	 * @covers DatabaseSqlite::addQuotes
	 */
	public function testAddQuotes( $value, $expected ) {
		// check quoting
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );
		$this->assertEquals( $expected, $db->addQuotes( $value ), 'string not quoted as expected' );

		// ok, quoting works as expected, now try a round trip.
		$re = $db->query( 'select ' . $db->addQuotes( $value ) );

		$this->assertTrue( $re !== false, 'query failed' );

		$row = $re->fetchRow();
		if ( $row ) {
			if ( $value instanceof Blob ) {
				$value = $value->fetch();
			}

			$this->assertEquals( $value, $row[0], 'string mangled by the database' );
		} else {
			$this->fail( 'query returned no result' );
		}
	}

	/**
	 * @covers DatabaseSqlite::replaceVars
	 */
	public function testReplaceVars() {
		$this->assertEquals( 'foo', $this->replaceVars( 'foo' ), "Don't break anything accidentally" );

		$this->assertEquals(
			"CREATE TABLE /**/foo (foo_key INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, "
				. "foo_bar TEXT, foo_name TEXT NOT NULL DEFAULT '', foo_int INTEGER, foo_int2 INTEGER );",
			$this->replaceVars(
				"CREATE TABLE /**/foo (foo_key int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT, "
				. "foo_bar char(13), foo_name varchar(255) binary NOT NULL DEFAULT '', "
				. "foo_int tinyint ( 8 ), foo_int2 int(16) ) ENGINE=MyISAM;"
			)
		);

		$this->assertEquals(
			"CREATE TABLE foo ( foo1 REAL, foo2 REAL, foo3 REAL );",
			$this->replaceVars(
				"CREATE TABLE foo ( foo1 FLOAT, foo2 DOUBLE( 1,10), foo3 DOUBLE PRECISION );"
			)
		);

		$this->assertEquals( "CREATE TABLE foo ( foo_binary1 BLOB, foo_binary2 BLOB );",
			$this->replaceVars( "CREATE TABLE foo ( foo_binary1 binary(16), foo_binary2 varbinary(32) );" )
		);

		$this->assertEquals( "CREATE TABLE text ( text_foo TEXT );",
			$this->replaceVars( "CREATE TABLE text ( text_foo tinytext );" ),
			'Table name changed'
		);

		$this->assertEquals( "CREATE TABLE foo ( foobar INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL );",
			$this->replaceVars( "CREATE TABLE foo ( foobar INT PRIMARY KEY NOT NULL AUTO_INCREMENT );" )
		);
		$this->assertEquals( "CREATE TABLE foo ( foobar INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL );",
			$this->replaceVars( "CREATE TABLE foo ( foobar INT PRIMARY KEY AUTO_INCREMENT NOT NULL );" )
		);

		$this->assertEquals( "CREATE TABLE enums( enum1 TEXT, myenum TEXT)",
			$this->replaceVars( "CREATE TABLE enums( enum1 ENUM('A', 'B'), myenum ENUM ('X', 'Y'))" )
		);

		$this->assertEquals( "ALTER TABLE foo ADD COLUMN foo_bar INTEGER DEFAULT 42",
			$this->replaceVars( "ALTER TABLE foo\nADD COLUMN foo_bar int(10) unsigned DEFAULT 42" )
		);

		$this->assertEquals( "DROP INDEX foo",
			$this->replaceVars( "DROP INDEX /*i*/foo ON /*_*/bar" )
		);

		$this->assertEquals( "DROP INDEX foo -- dropping index",
			$this->replaceVars( "DROP INDEX /*i*/foo ON /*_*/bar -- dropping index" )
		);
		$this->assertEquals( "INSERT OR IGNORE INTO foo VALUES ('bar')",
			$this->replaceVars( "INSERT OR IGNORE INTO foo VALUES ('bar')" )
		);
	}

	/**
	 * @covers DatabaseSqlite::tableName
	 */
	public function testTableName() {
		// @todo Moar!
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );
		$this->assertEquals( 'foo', $db->tableName( 'foo' ) );
		$this->assertEquals( 'sqlite_master', $db->tableName( 'sqlite_master' ) );
		$db->tablePrefix( 'foo' );
		$this->assertEquals( 'sqlite_master', $db->tableName( 'sqlite_master' ) );
		$this->assertEquals( 'foobar', $db->tableName( 'bar' ) );
	}

	/**
	 * @covers DatabaseSqlite::duplicateTableStructure
	 */
	public function testDuplicateTableStructure() {
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );
		$db->query( 'CREATE TABLE foo(foo, barfoo)' );
		$db->query( 'CREATE INDEX index1 ON foo(foo)' );
		$db->query( 'CREATE UNIQUE INDEX index2 ON foo(barfoo)' );

		$db->duplicateTableStructure( 'foo', 'bar' );
		$this->assertEquals( 'CREATE TABLE "bar"(foo, barfoo)',
			$db->selectField( 'sqlite_master', 'sql', [ 'name' => 'bar' ] ),
			'Normal table duplication'
		);
		$indexList = $db->query( 'PRAGMA INDEX_LIST("bar")' );
		$index = $indexList->next();
		$this->assertEquals( 'bar_index1', $index->name );
		$this->assertEquals( '0', $index->unique );
		$index = $indexList->next();
		$this->assertEquals( 'bar_index2', $index->name );
		$this->assertEquals( '1', $index->unique );

		$db->duplicateTableStructure( 'foo', 'baz', true );
		$this->assertEquals( 'CREATE TABLE "baz"(foo, barfoo)',
			$db->selectField( 'sqlite_temp_master', 'sql', [ 'name' => 'baz' ] ),
			'Creation of temporary duplicate'
		);
		$indexList = $db->query( 'PRAGMA INDEX_LIST("baz")' );
		$index = $indexList->next();
		$this->assertEquals( 'baz_index1', $index->name );
		$this->assertEquals( '0', $index->unique );
		$index = $indexList->next();
		$this->assertEquals( 'baz_index2', $index->name );
		$this->assertEquals( '1', $index->unique );
		$this->assertEquals( 0,
			$db->selectField( 'sqlite_master', 'COUNT(*)', [ 'name' => 'baz' ] ),
			'Create a temporary duplicate only'
		);
	}

	/**
	 * @covers DatabaseSqlite::duplicateTableStructure
	 */
	public function testDuplicateTableStructureVirtual() {
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );
		if ( $db->getFulltextSearchModule() != 'FTS3' ) {
			$this->markTestSkipped( 'FTS3 not supported, cannot create virtual tables' );
		}
		$db->query( 'CREATE VIRTUAL TABLE "foo" USING FTS3(foobar)' );

		$db->duplicateTableStructure( 'foo', 'bar' );
		$this->assertEquals( 'CREATE VIRTUAL TABLE "bar" USING FTS3(foobar)',
			$db->selectField( 'sqlite_master', 'sql', [ 'name' => 'bar' ] ),
			'Duplication of virtual tables'
		);

		$db->duplicateTableStructure( 'foo', 'baz', true );
		$this->assertEquals( 'CREATE VIRTUAL TABLE "baz" USING FTS3(foobar)',
			$db->selectField( 'sqlite_master', 'sql', [ 'name' => 'baz' ] ),
			"Can't create temporary virtual tables, should fall back to non-temporary duplication"
		);
	}

	/**
	 * @covers DatabaseSqlite::deleteJoin
	 */
	public function testDeleteJoin() {
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );
		$db->query( 'CREATE TABLE a (a_1)', __METHOD__ );
		$db->query( 'CREATE TABLE b (b_1, b_2)', __METHOD__ );
		$db->insert( 'a', [
				[ 'a_1' => 1 ],
				[ 'a_1' => 2 ],
				[ 'a_1' => 3 ],
			],
			__METHOD__
		);
		$db->insert( 'b', [
				[ 'b_1' => 2, 'b_2' => 'a' ],
				[ 'b_1' => 3, 'b_2' => 'b' ],
			],
			__METHOD__
		);
		$db->deleteJoin( 'a', 'b', 'a_1', 'b_1', [ 'b_2' => 'a' ], __METHOD__ );
		$res = $db->query( "SELECT * FROM a", __METHOD__ );
		$this->assertResultIs( [
				[ 'a_1' => 1 ],
				[ 'a_1' => 3 ],
			],
			$res
		);
	}

	public function testEntireSchema() {
		global $IP;

		$result = Sqlite::checkSqlSyntax( "$IP/maintenance/tables.sql" );
		if ( $result !== true ) {
			$this->fail( $result );
		}
		$this->assertTrue( true ); // avoid test being marked as incomplete due to lack of assertions
	}

	/**
	 * Runs upgrades of older databases and compares results with current schema
	 * @todo Currently only checks list of tables
	 */
	public function testUpgrades() {
		global $IP, $wgVersion, $wgProfiler;

		// Versions tested
		$versions = [
			// '1.13', disabled for now, was totally screwed up
			// SQLite wasn't included in 1.14
			'1.15',
			'1.16',
			'1.17',
			'1.18',
		];

		// Mismatches for these columns we can safely ignore
		$ignoredColumns = [
			'user_newtalk.user_last_timestamp', // r84185
		];

		$currentDB = DatabaseSqlite::newStandaloneInstance( ':memory:' );
		$currentDB->sourceFile( "$IP/maintenance/tables.sql" );

		$profileToDb = false;
		if ( isset( $wgProfiler['output'] ) ) {
			$out = $wgProfiler['output'];
			if ( $out === 'db' ) {
				$profileToDb = true;
			} elseif ( is_array( $out ) && in_array( 'db', $out ) ) {
				$profileToDb = true;
			}
		}

		if ( $profileToDb ) {
			$currentDB->sourceFile( "$IP/maintenance/sqlite/archives/patch-profiling.sql" );
		}
		$currentTables = $this->getTables( $currentDB );
		sort( $currentTables );

		foreach ( $versions as $version ) {
			$versions = "upgrading from $version to $wgVersion";
			$db = $this->prepareTestDB( $version );
			$tables = $this->getTables( $db );
			$this->assertEquals( $currentTables, $tables, "Different tables $versions" );
			foreach ( $tables as $table ) {
				$currentCols = $this->getColumns( $currentDB, $table );
				$cols = $this->getColumns( $db, $table );
				$this->assertEquals(
					array_keys( $currentCols ),
					array_keys( $cols ),
					"Mismatching columns for table \"$table\" $versions"
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
				$currentIndexes = $this->getIndexes( $currentDB, $table );
				$indexes = $this->getIndexes( $db, $table );
				$this->assertEquals(
					array_keys( $currentIndexes ),
					array_keys( $indexes ),
					"mismatching indexes for table \"$table\" $versions"
				);
			}
			$db->close();
		}
	}

	/**
	 * @covers DatabaseSqlite::insertId
	 */
	public function testInsertIdType() {
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );

		$databaseCreation = $db->query( 'CREATE TABLE a ( a_1 )', __METHOD__ );
		$this->assertInstanceOf( 'ResultWrapper', $databaseCreation, "Database creation" );

		$insertion = $db->insert( 'a', [ 'a_1' => 10 ], __METHOD__ );
		$this->assertTrue( $insertion, "Insertion worked" );

		$this->assertInternalType( 'integer', $db->insertId(), "Actual typecheck" );
		$this->assertTrue( $db->close(), "closing database" );
	}

	private function prepareTestDB( $version ) {
		static $maint = null;
		if ( $maint === null ) {
			$maint = new FakeMaintenance();
			$maint->loadParamsAndArgs( null, [ 'quiet' => 1 ] );
		}

		global $IP;
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );
		$db->sourceFile( "$IP/tests/phpunit/data/db/sqlite/tables-$version.sql" );
		$updater = DatabaseUpdater::newForDB( $db, false, $maint );
		$updater->doUpdates( [ 'core' ] );

		return $db;
	}

	private function getTables( $db ) {
		$list = array_flip( $db->listTables() );
		$excluded = [
			'external_user', // removed from core in 1.22
			'math', // moved out of core in 1.18
			'trackbacks', // removed from core in 1.19
			'searchindex',
			'searchindex_content',
			'searchindex_segments',
			'searchindex_segdir',
			// FTS4 ready!!1
			'searchindex_docsize',
			'searchindex_stat',
		];
		foreach ( $excluded as $t ) {
			unset( $list[$t] );
		}
		$list = array_flip( $list );
		sort( $list );

		return $list;
	}

	private function getColumns( $db, $table ) {
		$cols = [];
		$res = $db->query( "PRAGMA table_info($table)" );
		$this->assertNotNull( $res );
		foreach ( $res as $col ) {
			$cols[$col->name] = $col;
		}
		ksort( $cols );

		return $cols;
	}

	private function getIndexes( $db, $table ) {
		$indexes = [];
		$res = $db->query( "PRAGMA index_list($table)" );
		$this->assertNotNull( $res );
		foreach ( $res as $index ) {
			$res2 = $db->query( "PRAGMA index_info({$index->name})" );
			$this->assertNotNull( $res2 );
			$index->columns = [];
			foreach ( $res2 as $col ) {
				$index->columns[] = $col;
			}
			$indexes[$index->name] = $index;
		}
		ksort( $indexes );

		return $indexes;
	}

	public function testCaseInsensitiveLike() {
		// TODO: Test this for all databases
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );
		$res = $db->query( 'SELECT "a" LIKE "A" AS a' );
		$row = $res->fetchRow();
		$this->assertFalse( (bool)$row['a'] );
	}

	/**
	 * @covers DatabaseSqlite::numFields
	 */
	public function testNumFields() {
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );

		$databaseCreation = $db->query( 'CREATE TABLE a ( a_1 )', __METHOD__ );
		$this->assertInstanceOf( 'ResultWrapper', $databaseCreation, "Failed to create table a" );
		$res = $db->select( 'a', '*' );
		$this->assertEquals( 0, $db->numFields( $res ), "expects to get 0 fields for an empty table" );
		$insertion = $db->insert( 'a', [ 'a_1' => 10 ], __METHOD__ );
		$this->assertTrue( $insertion, "Insertion failed" );
		$res = $db->select( 'a', '*' );
		$this->assertEquals( 1, $db->numFields( $res ), "wrong number of fields" );

		$this->assertTrue( $db->close(), "closing database" );
	}

	public function testToString() {
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );

		$toString = (string)$db;

		$this->assertContains( 'SQLite ', $toString );
	}
}
