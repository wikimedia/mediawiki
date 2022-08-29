<?php

use Psr\Log\NullLogger;
use Wikimedia\Rdbms\Blob;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DatabaseSqlite;
use Wikimedia\Rdbms\ResultWrapper;
use Wikimedia\Rdbms\TransactionProfiler;

/**
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
		if ( version_compare( $this->db->getServerVersion(), '3.6.0', '<' ) ) {
			$this->markTestSkipped( "SQLite at least 3.6 required, {$this->db->getServerVersion()} found" );
		}
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
				'connLogger' => new NullLogger(),
				'queryLogger' => new NullLogger(),
				'replLogger' => new NullLogger(),
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
	 * @dataProvider provideAddQuotes()
	 * @covers \Wikimedia\Rdbms\DatabaseSqlite::addQuotes
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
	 * @covers \Wikimedia\Rdbms\DatabaseSqlite::duplicateTableStructure
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
		$index = $indexList->fetchObject();
		$this->assertEquals( 'bar_index1', $index->name );
		$this->assertSame( '0', (string)$index->unique );
		$index = $indexList->fetchObject();
		$this->assertEquals( 'bar_index2', $index->name );
		$this->assertSame( '1', (string)$index->unique );

		$db->duplicateTableStructure( 'foo', 'baz', true );
		$this->assertEquals( 'CREATE TABLE "baz"(foo, barfoo)',
			$db->selectField( 'sqlite_temp_master', 'sql', [ 'name' => 'baz' ] ),
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
			(string)$db->selectField( 'sqlite_master', 'COUNT(*)', [ 'name' => 'baz' ] ),
			'Create a temporary duplicate only'
		);
	}

	/**
	 * @covers \Wikimedia\Rdbms\DatabaseSqlite::duplicateTableStructure
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
	 * @covers \Wikimedia\Rdbms\DatabaseSqlite::deleteJoin
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

	/**
	 * @coversNothing
	 */
	public function testEntireSchema() {
		global $IP;

		$result = Sqlite::checkSqlSyntax( "$IP/maintenance/sqlite/tables-generated.sql" );

		$this->assertTrue( $result, $result );
	}

	/**
	 * @covers \Wikimedia\Rdbms\DatabaseSqlite::insertId
	 */
	public function testInsertIdType() {
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );

		$databaseCreation = $db->query( 'CREATE TABLE a ( a_1 )', __METHOD__ );
		$this->assertInstanceOf( ResultWrapper::class, $databaseCreation, "Database creation" );

		$insertion = $db->insert( 'a', [ 'a_1' => 10 ], __METHOD__ );
		$this->assertTrue( $insertion, "Insertion worked" );

		$this->assertIsInt( $db->insertId(), "Actual typecheck" );
		$this->assertTrue( $db->close(), "closing database" );
	}

	/**
	 * @covers \Wikimedia\Rdbms\DatabaseSqlite::insert
	 */
	public function testInsertAffectedRows() {
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );
		$db->query( 'CREATE TABLE testInsertAffectedRows ( foo )', __METHOD__ );

		$insertion = $db->insert(
			'testInsertAffectedRows',
			[
				[ 'foo' => 10 ],
				[ 'foo' => 12 ],
				[ 'foo' => 1555 ],
			],
			__METHOD__
		);
		$this->assertTrue( $insertion, "Insertion worked" );

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

	/**
	 * @covers \Wikimedia\Rdbms\DatabaseSqlite::__toString
	 */
	public function testToString() {
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );

		$toString = (string)$db;

		$this->assertStringContainsString( 'sqlite object', $toString );
	}

	/**
	 * @covers \Wikimedia\Rdbms\DatabaseSqlite::getAttributes()
	 */
	public function testsAttributes() {
		$attributes = Database::attributesFromType( 'sqlite' );
		$this->assertTrue( $attributes[Database::ATTR_DB_LEVEL_LOCKING] );
	}

	/**
	 * @covers \Wikimedia\Rdbms\DatabaseSqlite::insert()
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

	public function provideNativeInserts() {
		return [
			[
				'3.8.0',
				'a',
				[ 'a_1' => 1 ],
				'INSERT INTO a (a_1) VALUES (1);'
			],
			[
				'3.8.0',
				'a',
				[
					[ 'a_1' => 2 ],
					[ 'a_1' => 3 ]
				],
				'INSERT INTO a (a_1) VALUES (2),(3);'
			],
		];
	}

	/**
	 * @covers \Wikimedia\Rdbms\DatabaseSqlite::replace()
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

	public function provideNativeReplaces() {
		return [
			[
				'3.8.0',
				'a',
				[ 'a_1' ],
				[ 'a_1' => 1, 'a_2' => 'x' ],
				'REPLACE INTO a (a_1,a_2) VALUES (1,\'x\');'
			],
			[
				'3.8.0',
				'a',
				[ 'a_1' ],
				[
					[ 'a_1' => 2, 'a_2' => 'x' ],
					[ 'a_1' => 3, 'a_2' => 'y' ]
				],
				'REPLACE INTO a (a_1,a_2) VALUES (2,\'x\'),(3,\'y\');'
			],
		];
	}
}
