<?php

use Psr\Log\NullLogger;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\Rdbms\Blob;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DatabaseSqlite;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\Query;
use Wikimedia\Rdbms\ResultWrapper;
use Wikimedia\Rdbms\TransactionProfiler;

/**
 * @covers \Wikimedia\Rdbms\Database
 * @covers \Wikimedia\Rdbms\DatabaseSqlite
 * @covers \Wikimedia\Rdbms\Platform\SqlitePlatform
 * @group sqlite
 * @group Database
 * @group medium
 */
class DatabaseSqliteTest extends \MediaWikiIntegrationTestCase {
	/** @var DatabaseSqlite */
	protected $db;

	/** @var array|null */
	protected $currentTableInfo;

	protected function setUp(): void {
		parent::setUp();

		if ( !Sqlite::isPresent() ) {
			$this->markTestSkipped( 'No SQLite support detected' );
		}
		$this->db = $this->newMockDb();
	}

	/**
	 * @param string|null $version
	 * @param string|null &$sqlDump
	 * @return \PHPUnit\Framework\MockObject\MockObject|DatabaseSqlite
	 */
	private function newMockDb( $version = null, &$sqlDump = null ) {
		$mock = $this->getMockBuilder( DatabaseSqlite::class )
			->setConstructorArgs( [ [
				'dbFilePath' => ':memory:',
				'dbname' => 'Foo',
				'schema' => null,
				'host' => false,
				'user' => false,
				'password' => false,
				'tablePrefix' => '',
				'cliMode' => true,
				'agent' => 'unit-tests',
				'serverName' => null,
				'flags' => DBO_DEFAULT,
				'variables' => [ 'synchronous' => 'NORMAL', 'temp_store' => 'MEMORY' ],
				'profiler' => null,
				'topologyRole' => Database::ROLE_STREAMING_MASTER,
				'trxProfiler' => new TransactionProfiler(),
				'errorLogger' => null,
				'deprecationLogger' => new NullLogger(),
				'srvCache' => new HashBagOStuff(),
			] ] )->onlyMethods( array_merge(
				[ 'query' ],
				$version ? [ 'getServerVersion' ] : []
			) )->getMock();

		$mock->initConnection();

		$sqlDump = '';
		$mock->method( 'query' )->willReturnCallback( static function ( $sql ) use ( &$sqlDump ) {
			if ( $sql instanceof Query ) {
				$sql = $sql->getSQL();
			}
			$sqlDump .= "$sql;";

			return true;
		} );

		if ( $version ) {
			$mock->method( 'getServerVersion' )->willReturn( $version );
		}

		return $mock;
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
			[ // #5: null
				null,
				"''",
			],
		];
	}

	/**
	 * @dataProvider provideAddQuotes
	 */
	public function testAddQuotes( $value, $expected ) {
		// check quoting
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );
		$this->assertEquals( $expected, $db->addQuotes( $value ), 'string not quoted as expected' );

		// ok, quoting works as expected, now try a round trip.
		$re = $db->query( 'select ' . $db->addQuotes( $value ) );

		$this->assertInstanceOf( IResultWrapper::class, $re, 'query failed' );

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

	public function testDuplicateTableStructure() {
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );
		$db->query( 'CREATE TABLE foo(foo, barfoo)' );
		$db->query( 'CREATE INDEX index1 ON foo(foo)' );
		$db->query( 'CREATE UNIQUE INDEX index2 ON foo(barfoo)' );

		$db->duplicateTableStructure( 'foo', 'bar' );
		$this->assertEquals( 'CREATE TABLE "bar"(foo, barfoo)',
			$db->newSelectQueryBuilder()
				->select( 'sql' )
				->from( 'sqlite_master' )
				->where( [ 'name' => 'bar' ] )
				->caller( __METHOD__ )->fetchField(),
			'Normal table duplication'
		);
		$indexList = $db->query( 'PRAGMA INDEX_LIST("bar")' );
		$index = $indexList->fetchObject();
		$this->assertEquals( 'bar_index1', $index->name );
		$this->assertSame( '0', (string)$index->unique );
		$index = $indexList->fetchObject();
		$this->assertEquals( 'bar_index2', $index->name );
		$this->assertSame( '1', (string)$index->unique );

		$db->duplicateTableStructure( 'foo', 'baz', true );
		$this->assertEquals( 'CREATE TABLE "baz"(foo, barfoo)',
			$db->newSelectQueryBuilder()
				->select( 'sql' )
				->from( 'sqlite_temp_master' )
				->where( [ 'name' => 'baz' ] )
				->caller( __METHOD__ )->fetchField(),
			'Creation of temporary duplicate'
		);
		$indexList = $db->query( 'PRAGMA INDEX_LIST("baz")' );
		$index = $indexList->fetchObject();
		$this->assertEquals( 'baz_index1', $index->name );
		$this->assertSame( '0', (string)$index->unique );
		$index = $indexList->fetchObject();
		$this->assertEquals( 'baz_index2', $index->name );
		$this->assertSame( '1', (string)$index->unique );
		$this->assertSame( '0',
			(string)$db->newSelectQueryBuilder()
				->select( 'COUNT(*)' )
				->from( 'sqlite_master' )
				->where( [ 'name' => 'baz' ] )
				->caller( __METHOD__ )->fetchField(),
			'Create a temporary duplicate only'
		);
	}

	public function testDuplicateTableStructureVirtual() {
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );
		if ( $db->getFulltextSearchModule() != 'FTS3' ) {
			$this->markTestSkipped( 'FTS3 not supported, cannot create virtual tables' );
		}
		$db->query( 'CREATE VIRTUAL TABLE "foo" USING FTS3(foobar)' );

		$db->duplicateTableStructure( 'foo', 'bar' );
		$this->assertEquals( 'CREATE VIRTUAL TABLE "bar" USING FTS3(foobar)',
			$db->newSelectQueryBuilder()
				->select( 'sql' )
				->from( 'sqlite_master' )
				->where( [ 'name' => 'bar' ] )
				->caller( __METHOD__ )->fetchField(),
			'Duplication of virtual tables'
		);

		$db->duplicateTableStructure( 'foo', 'baz', true );
		$this->assertEquals( 'CREATE VIRTUAL TABLE "baz" USING FTS3(foobar)',
			$db->newSelectQueryBuilder()
				->select( 'sql' )
				->from( 'sqlite_master' )
				->where( [ 'name' => 'baz' ] )
				->caller( __METHOD__ )->fetchField(),
			"Can't create temporary virtual tables, should fall back to non-temporary duplication"
		);
	}

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

	/**
	 * @coversNothing
	 */
	public function testEntireSchema() {
		global $IP;

		$result = Sqlite::checkSqlSyntax( "$IP/sql/sqlite/tables-generated.sql" );

		$this->assertTrue( $result, $result );
	}

	public function testInsertIdType() {
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );

		$databaseCreation = $db->query( 'CREATE TABLE a ( a_1 )', __METHOD__ );
		$this->assertInstanceOf( ResultWrapper::class, $databaseCreation, "Database creation" );

		$db->insert( 'a', [ 'a_1' => 10 ], __METHOD__ );
		$this->assertIsInt( $db->insertId(), "Actual typecheck" );
		$this->assertTrue( $db->close(), "closing database" );
	}

	public function testInsertAffectedRows() {
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );
		$db->query( 'CREATE TABLE testInsertAffectedRows ( foo )', __METHOD__ );

		$db->insert(
			'testInsertAffectedRows',
			[
				[ 'foo' => 10 ],
				[ 'foo' => 12 ],
				[ 'foo' => 1555 ],
			],
			__METHOD__
		);

		$this->assertSame( 3, $db->affectedRows() );
		$this->assertTrue( $db->close(), "closing database" );
	}

	/**
	 * @coversNothing
	 */
	public function testCaseInsensitiveLike() {
		// TODO: Test this for all databases
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );
		$res = $db->query( 'SELECT "a" LIKE "A" AS a' );
		$row = $res->fetchRow();
		$this->assertFalse( (bool)$row['a'] );
	}

	public function testToString() {
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );

		$toString = (string)$db;

		$this->assertStringContainsString( 'sqlite object', $toString );
	}

	public function testsAttributes() {
		$dbFactory = $this->getServiceContainer()->getDatabaseFactory();
		$this->assertTrue( $dbFactory->attributesFromType( 'sqlite' )[Database::ATTR_DB_LEVEL_LOCKING] );
	}

	/**
	 * @param string $version
	 * @param string $table
	 * @param array $rows
	 * @param string $expectedSql
	 * @dataProvider provideNativeInserts
	 */
	public function testNativeInsertSupport( $version, $table, $rows, $expectedSql ) {
		$sqlDump = '';
		$db = $this->newMockDb( $version, $sqlDump );
		$db->query( 'CREATE TABLE a ( a_1 )', __METHOD__ );

		$sqlDump = '';
		$db->insert( $table, $rows, __METHOD__ );
		$this->assertEquals( $expectedSql, $sqlDump );
	}

	public static function provideNativeInserts() {
		return [
			[
				'3.8.0',
				'a',
				[ 'a_1' => 1 ],
				'INSERT INTO "a" (a_1) VALUES (1);'
			],
			[
				'3.8.0',
				'a',
				[
					[ 'a_1' => 2 ],
					[ 'a_1' => 3 ]
				],
				'INSERT INTO "a" (a_1) VALUES (2),(3);'
			],
		];
	}

	/**
	 * @param string $version
	 * @param string $table
	 * @param array $ukeys
	 * @param array $rows
	 * @param string $expectedSql
	 * @dataProvider provideNativeReplaces
	 */
	public function testNativeReplaceSupport( $version, $table, $ukeys, $rows, $expectedSql ) {
		$sqlDump = '';
		$db = $this->newMockDb( $version, $sqlDump );
		$db->query( 'CREATE TABLE a ( a_1 PRIMARY KEY, a_2 )', __METHOD__ );

		$sqlDump = '';
		$db->replace( $table, $ukeys, $rows, __METHOD__ );
		$this->assertEquals( $expectedSql, $sqlDump );
	}

	public static function provideNativeReplaces() {
		return [
			[
				'3.8.0',
				'a',
				[ 'a_1' ],
				[ 'a_1' => 1, 'a_2' => 'x' ],
				'REPLACE INTO "a" (a_1,a_2) VALUES (1,\'x\');'
			],
			[
				'3.8.0',
				'a',
				[ 'a_1' ],
				[
					[ 'a_1' => 2, 'a_2' => 'x' ],
					[ 'a_1' => 3, 'a_2' => 'y' ]
				],
				'REPLACE INTO "a" (a_1,a_2) VALUES (2,\'x\'),(3,\'y\');'
			],
		];
	}

	public function testInsertIdAfterInsert() {
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );
		$dTable = $this->createDestTable( $db );

		$rows = [ [ 'k' => 'Luca', 'v' => mt_rand( 1, 100 ), 't' => time() ] ];

		$db->insert( $dTable, $rows, __METHOD__ );
		$this->assertSame( 1, $db->affectedRows() );
		$this->assertSame( 1, $db->insertId() );

		$this->assertNWhereKEqualsLuca( 1, $dTable, $db );
		$this->assertSame( 0, $db->affectedRows() );
		$this->assertSame( 0, $db->insertId() );
	}

	public function testInsertIdAfterInsertIgnore() {
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );
		$dTable = $this->createDestTable( $db );

		$rows = [ [ 'k' => 'Luca', 'v' => mt_rand( 1, 100 ), 't' => time() ] ];

		$db->insert( $dTable, $rows, __METHOD__, 'IGNORE' );
		$this->assertSame( 1, $db->affectedRows() );
		$this->assertSame( 1, $db->insertId() );
		$this->assertNWhereKEqualsLuca( 1, $dTable, $db );

		$db->insert( $dTable, $rows, __METHOD__, 'IGNORE' );
		$this->assertSame( 0, $db->affectedRows() );
		$this->assertSame( 0, $db->insertId() );

		$this->assertNWhereKEqualsLuca( 1, $dTable, $db );
		$this->assertSame( 0, $db->affectedRows() );
		$this->assertSame( 0, $db->insertId() );
	}

	public function testInsertIdAfterReplace() {
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );
		$dTable = $this->createDestTable( $db );

		$rows = [ [ 'k' => 'Luca', 'v' => mt_rand( 1, 100 ), 't' => time() ] ];

		$db->replace( $dTable, 'k', $rows, __METHOD__ );
		$this->assertSame( 1, $db->affectedRows() );
		$this->assertSame( 1, $db->insertId() );
		$this->assertNWhereKEqualsLuca( 1, $dTable, $db );

		$db->replace( $dTable, 'k', $rows, __METHOD__ );
		$this->assertSame( 1, $db->affectedRows() );
		$this->assertSame( 2, $db->insertId() );

		$this->assertNWhereKEqualsLuca( 2, $dTable, $db );
		$this->assertSame( 0, $db->affectedRows() );
		$this->assertSame( 0, $db->insertId() );
	}

	public function testInsertIdAfterUpsert() {
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );
		$dTable = $this->createDestTable( $db );

		$rows = [ [ 'k' => 'Luca', 'v' => mt_rand( 1, 100 ), 't' => time() ] ];
		$otherRows = [ [ 'k' => 'Skylar', 'v' => mt_rand( 1, 100 ), 't' => time() ] ];
		$set = [
			'v = ' . $db->buildExcludedValue( 'v' ),
			't = ' . $db->buildExcludedValue( 't' ) . ' + 1'
		];

		$db->upsert( $dTable, $rows, 'k', $set, __METHOD__ );
		$this->assertSame( 1, $db->affectedRows() );
		$this->assertSame( 1, $db->insertId() );
		$this->assertNWhereKEqualsLuca( 1, $dTable, $db );

		$db->upsert( $dTable, $otherRows, 'k', $set, __METHOD__ );
		$this->assertSame( 1, $db->affectedRows() );
		$this->assertSame( 2, $db->insertId() );

		$db->upsert( $dTable, $rows, 'k', $set, __METHOD__ );
		$this->assertSame( 1, $db->affectedRows() );
		$this->assertSame( 1, $db->insertId() );

		$this->assertNWhereKEqualsLuca( 1, $dTable, $db );
		$this->assertSame( 0, $db->affectedRows() );
		$this->assertSame( 0, $db->insertId() );
	}

	public function testInsertIdAfterInsertSelect() {
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );
		$sTable = $this->createSourceTable( $db );
		$dTable = $this->createDestTable( $db );

		$rows = [ [ 'sk' => 'Luca', 'sv' => mt_rand( 1, 100 ), 'st' => time() ] ];
		$db->insert( $sTable, $rows, __METHOD__, 'IGNORE' );
		$this->assertSame( 1, $db->affectedRows() );
		$this->assertSame( 1, $db->insertId() );
		$this->assertSame( 1, (int)$db->newSelectQueryBuilder()
			->select( 'sn' )
			->from( $sTable )
			->where( [ 'sk' => 'Luca' ] )
			->fetchField() );

		$db->insertSelect(
			$dTable,
			$sTable,
			[ 'k' => 'sk', 'v' => 'sv', 't' => 'st' ],
			[ 'sk' => 'Luca' ],
			__METHOD__,
			'IGNORE'
		);
		$this->assertSame( 1, $db->affectedRows() );
		$this->assertSame( 1, $db->insertId() );

		$this->assertNWhereKEqualsLuca( 1, $dTable, $db );
		$this->assertSame( 0, $db->affectedRows() );
		$this->assertSame( 0, $db->insertId() );
	}

	public function testInsertIdAfterInsertSelectIgnore() {
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );
		$sTable = $this->createSourceTable( $db );
		$dTable = $this->createDestTable( $db );

		$rows = [ [ 'sk' => 'Luca', 'sv' => mt_rand( 1, 100 ), 'st' => time() ] ];
		$db->insert( $sTable, $rows, __METHOD__, 'IGNORE' );
		$this->assertSame( 1, $db->affectedRows() );
		$this->assertSame( 1, $db->insertId() );
		$this->assertSame( 1, (int)$db->newSelectQueryBuilder()
			->select( 'sn' )
			->from( $sTable )
			->where( [ 'sk' => 'Luca' ] )
			->fetchField() );

		$db->insertSelect(
			$dTable,
			$sTable,
			[ 'k' => 'sk', 'v' => 'sv', 't' => 'st' ],
			[ 'sk' => 'Luca' ],
			__METHOD__,
			'IGNORE'
		);
		$this->assertSame( 1, $db->affectedRows() );
		$this->assertSame( 1, $db->insertId() );
		$this->assertNWhereKEqualsLuca( 1, $dTable, $db );

		$db->insertSelect(
			$dTable,
			$sTable,
			[ 'k' => 'sk', 'v' => 'sv', 't' => 'st' ],
			[ 'sk' => 'Luca' ],
			__METHOD__,
			'IGNORE'
		);
		$this->assertSame( 0, $db->affectedRows() );
		$this->assertSame( 0, $db->insertId() );

		$this->assertNWhereKEqualsLuca( 1, $dTable, $db );
		$this->assertSame( 0, $db->affectedRows() );
		$this->assertSame( 0, $db->insertId() );
	}

	public function testFieldAndIndexInfo() {
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );
		$db->query(
			"CREATE TABLE tmp_schema_tbl (" .
			"n integer not null primary key autoincrement, " .
			"k text, " .
			"v integer, " .
			"t integer" .
			")"
		);
		$db->query( "CREATE UNIQUE INDEX tmp_schema_tbl_k ON tmp_schema_tbl (k)" );
		$db->query( "CREATE INDEX tmp_schema_tbl_t ON tmp_schema_tbl (t)" );

		$this->assertTrue( $db->fieldExists( 'tmp_schema_tbl', 'n' ) );
		$this->assertTrue( $db->fieldExists( 'tmp_schema_tbl', 'k' ) );
		$this->assertTrue( $db->fieldExists( 'tmp_schema_tbl', 'v' ) );
		$this->assertTrue( $db->fieldExists( 'tmp_schema_tbl', 't' ) );
		$this->assertFalse( $db->fieldExists( 'tmp_schema_tbl', 'x' ) );

		$this->assertTrue( $db->indexExists( 'tmp_schema_tbl', 'tmp_schema_tbl_k' ) );
		$this->assertTrue( $db->indexExists( 'tmp_schema_tbl', 'tmp_schema_tbl_t' ) );
		$this->assertFalse( $db->indexExists( 'tmp_schema_tbl', 'tmp_schema_tbl_x' ) );
		$this->assertFalse( $db->indexExists( 'tmp_schema_tbl', 'PRIMARY' ) );

		$this->assertTrue( $db->indexUnique( 'tmp_schema_tbl', 'tmp_schema_tbl_k' ) );
		$this->assertFalse( $db->indexUnique( 'tmp_schema_tbl', 'tmp_schema_tbl_t' ) );
		$this->assertNull( $db->indexUnique( 'tmp_schema_tbl', 'tmp_schema_tbl_x' ) );
		$this->assertNull( $db->indexUnique( 'tmp_schema_tbl', 'PRIMARY' ) );
	}

	private function createSourceTable( IDatabase $db ) {
		$db->query( "DROP TABLE IF EXISTS tmp_src_tbl" );
		$db->query(
			"CREATE TABLE tmp_src_tbl (" .
			"sn integer not null primary key autoincrement, " .
			"sk text, " .
			"sv integer, " .
			"st integer" .
			")"
		);
		$db->query( "CREATE UNIQUE INDEX tmp_src_tbl_sk ON tmp_src_tbl (sk)" );

		return "tmp_src_tbl";
	}

	private function createDestTable( IDatabase $db ) {
		$db->query( "DROP TABLE IF EXISTS tmp_dst_tbl" );
		$db->query(
			"CREATE TABLE tmp_dst_tbl (" .
			"n integer not null primary key autoincrement, " .
			"k text, " .
			"v integer, " .
			"t integer" .
			")"
		);
		$db->query( "CREATE UNIQUE INDEX tmp_dst_tbl_k ON tmp_dst_tbl (k)" );

		return "tmp_dst_tbl";
	}

	private function assertNWhereKEqualsLuca( $expected, $table, $db ) {
		$this->assertSame( $expected, (int)$db->newSelectQueryBuilder()
			->select( 'n' )
			->from( $table )
			->where( [ 'k' => 'Luca' ] )
			->fetchField() );
	}
}
