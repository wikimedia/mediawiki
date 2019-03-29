<?php

use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\DatabaseMysqli;
use Wikimedia\Rdbms\LBFactorySingle;
use Wikimedia\Rdbms\TransactionProfiler;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Rdbms\DatabaseSqlite;
use Wikimedia\Rdbms\DatabasePostgres;
use Wikimedia\Rdbms\DatabaseMssql;
use Wikimedia\Rdbms\DBUnexpectedError;

class DatabaseTest extends PHPUnit\Framework\TestCase {
	/** @var DatabaseTestHelper */
	private $db;

	use MediaWikiCoversValidator;

	protected function setUp() {
		$this->db = new DatabaseTestHelper( __CLASS__ . '::' . $this->getName() );
	}

	/**
	 * @dataProvider provideAddQuotes
	 * @covers Wikimedia\Rdbms\Database::factory
	 */
	public function testFactory() {
		$m = Database::NEW_UNCONNECTED; // no-connect mode
		$p = [ 'host' => 'localhost', 'user' => 'me', 'password' => 'myself', 'dbname' => 'i' ];

		$this->assertInstanceOf( DatabaseMysqli::class, Database::factory( 'mysqli', $p, $m ) );
		$this->assertInstanceOf( DatabaseMysqli::class, Database::factory( 'MySqli', $p, $m ) );
		$this->assertInstanceOf( DatabaseMysqli::class, Database::factory( 'MySQLi', $p, $m ) );
		$this->assertInstanceOf( DatabasePostgres::class, Database::factory( 'postgres', $p, $m ) );
		$this->assertInstanceOf( DatabasePostgres::class, Database::factory( 'Postgres', $p, $m ) );

		$x = $p + [ 'port' => 10000, 'UseWindowsAuth' => false ];
		$this->assertInstanceOf( DatabaseMssql::class, Database::factory( 'mssql', $x, $m ) );

		$x = $p + [ 'dbFilePath' => 'some/file.sqlite' ];
		$this->assertInstanceOf( DatabaseSqlite::class, Database::factory( 'sqlite', $x, $m ) );
		$x = $p + [ 'dbDirectory' => 'some/file' ];
		$this->assertInstanceOf( DatabaseSqlite::class, Database::factory( 'sqlite', $x, $m ) );
	}

	public static function provideAddQuotes() {
		return [
			[ null, 'NULL' ],
			[ 1234, "'1234'" ],
			[ 1234.5678, "'1234.5678'" ],
			[ 'string', "'string'" ],
			[ 'string\'s cause trouble', "'string\'s cause trouble'" ],
		];
	}

	/**
	 * @dataProvider provideAddQuotes
	 * @covers Wikimedia\Rdbms\Database::addQuotes
	 */
	public function testAddQuotes( $input, $expected ) {
		$this->assertEquals( $expected, $this->db->addQuotes( $input ) );
	}

	public static function provideTableName() {
		// Formatting is mostly ignored since addIdentifierQuotes is abstract.
		// For testing of addIdentifierQuotes, see actual Database subclas tests.
		return [
			'local' => [
				'tablename',
				'tablename',
				'quoted',
			],
			'local-raw' => [
				'tablename',
				'tablename',
				'raw',
			],
			'shared' => [
				'sharedb.tablename',
				'tablename',
				'quoted',
				[ 'dbname' => 'sharedb', 'schema' => null, 'prefix' => '' ],
			],
			'shared-raw' => [
				'sharedb.tablename',
				'tablename',
				'raw',
				[ 'dbname' => 'sharedb', 'schema' => null, 'prefix' => '' ],
			],
			'shared-prefix' => [
				'sharedb.sh_tablename',
				'tablename',
				'quoted',
				[ 'dbname' => 'sharedb', 'schema' => null, 'prefix' => 'sh_' ],
			],
			'shared-prefix-raw' => [
				'sharedb.sh_tablename',
				'tablename',
				'raw',
				[ 'dbname' => 'sharedb', 'schema' => null, 'prefix' => 'sh_' ],
			],
			'foreign' => [
				'databasename.tablename',
				'databasename.tablename',
				'quoted',
			],
			'foreign-raw' => [
				'databasename.tablename',
				'databasename.tablename',
				'raw',
			],
		];
	}

	/**
	 * @dataProvider provideTableName
	 * @covers Wikimedia\Rdbms\Database::tableName
	 */
	public function testTableName( $expected, $table, $format, array $alias = null ) {
		if ( $alias ) {
			$this->db->setTableAliases( [ $table => $alias ] );
		}
		$this->assertEquals(
			$expected,
			$this->db->tableName( $table, $format ?: 'quoted' )
		);
	}

	public function provideTableNamesWithIndexClauseOrJOIN() {
		return [
			'one-element array' => [
				[ 'table' ], [], 'table '
			],
			'comma join' => [
				[ 'table1', 'table2' ], [], 'table1,table2 '
			],
			'real join' => [
				[ 'table1', 'table2' ],
				[ 'table2' => [ 'LEFT JOIN', 't1_id = t2_id' ] ],
				'table1 LEFT JOIN table2 ON ((t1_id = t2_id))'
			],
			'real join with multiple conditionals' => [
				[ 'table1', 'table2' ],
				[ 'table2' => [ 'LEFT JOIN', [ 't1_id = t2_id', 't2_x = \'X\'' ] ] ],
				'table1 LEFT JOIN table2 ON ((t1_id = t2_id) AND (t2_x = \'X\'))'
			],
			'join with parenthesized group' => [
				[ 'table1', 'n' => [ 'table2', 'table3' ] ],
				[
					'table3' => [ 'JOIN', 't2_id = t3_id' ],
					'n' => [ 'LEFT JOIN', 't1_id = t2_id' ],
				],
				'table1 LEFT JOIN (table2 JOIN table3 ON ((t2_id = t3_id))) ON ((t1_id = t2_id))'
			],
			'join with degenerate parenthesized group' => [
				[ 'table1', 'n' => [ 't2' => 'table2' ] ],
				[
					'n' => [ 'LEFT JOIN', 't1_id = t2_id' ],
				],
				'table1 LEFT JOIN table2 t2 ON ((t1_id = t2_id))'
			],
		];
	}

	/**
	 * @dataProvider provideTableNamesWithIndexClauseOrJOIN
	 * @covers Wikimedia\Rdbms\Database::tableNamesWithIndexClauseOrJOIN
	 */
	public function testTableNamesWithIndexClauseOrJOIN( $tables, $join_conds, $expect ) {
		$clause = TestingAccessWrapper::newFromObject( $this->db )
			->tableNamesWithIndexClauseOrJOIN( $tables, [], [], $join_conds );
		$this->assertSame( $expect, $clause );
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::onTransactionCommitOrIdle
	 * @covers Wikimedia\Rdbms\Database::runOnTransactionIdleCallbacks
	 */
	public function testTransactionIdle() {
		$db = $this->db;

		$db->clearFlag( DBO_TRX );
		$called = false;
		$flagSet = null;
		$callback = function ( $trigger, IDatabase $db ) use ( &$flagSet, &$called ) {
			$called = true;
			$flagSet = $db->getFlag( DBO_TRX );
		};

		$db->onTransactionCommitOrIdle( $callback, __METHOD__ );
		$this->assertTrue( $called, 'Callback reached' );
		$this->assertFalse( $flagSet, 'DBO_TRX off in callback' );
		$this->assertFalse( $db->getFlag( DBO_TRX ), 'DBO_TRX still default' );

		$flagSet = null;
		$called = false;
		$db->startAtomic( __METHOD__ );
		$db->onTransactionCommitOrIdle( $callback, __METHOD__ );
		$this->assertFalse( $called, 'Callback not reached during TRX' );
		$db->endAtomic( __METHOD__ );

		$this->assertTrue( $called, 'Callback reached after COMMIT' );
		$this->assertFalse( $flagSet, 'DBO_TRX off in callback' );
		$this->assertFalse( $db->getFlag( DBO_TRX ), 'DBO_TRX restored to default' );

		$db->clearFlag( DBO_TRX );
		$db->onTransactionCommitOrIdle(
			function ( $trigger, IDatabase $db ) {
				$db->setFlag( DBO_TRX );
			},
			__METHOD__
		);
		$this->assertFalse( $db->getFlag( DBO_TRX ), 'DBO_TRX restored to default' );
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::onTransactionCommitOrIdle
	 * @covers Wikimedia\Rdbms\Database::runOnTransactionIdleCallbacks
	 */
	public function testTransactionIdle_TRX() {
		$db = $this->getMockDB( [ 'isOpen', 'ping', 'getDBname' ] );
		$db->method( 'isOpen' )->willReturn( true );
		$db->method( 'ping' )->willReturn( true );
		$db->method( 'getDBname' )->willReturn( '' );
		$db->setFlag( DBO_TRX );

		$lbFactory = LBFactorySingle::newFromConnection( $db );
		// Ask for the connection so that LB sets internal state
		// about this connection being the master connection
		$lb = $lbFactory->getMainLB();
		$conn = $lb->openConnection( $lb->getWriterIndex() );
		$this->assertSame( $db, $conn, 'Same DB instance' );
		$this->assertTrue( $db->getFlag( DBO_TRX ), 'DBO_TRX is set' );

		$called = false;
		$flagSet = null;
		$callback = function () use ( $db, &$flagSet, &$called ) {
			$called = true;
			$flagSet = $db->getFlag( DBO_TRX );
		};

		$db->onTransactionCommitOrIdle( $callback, __METHOD__ );
		$this->assertTrue( $called, 'Called when idle if DBO_TRX is set' );
		$this->assertFalse( $flagSet, 'DBO_TRX off in callback' );
		$this->assertTrue( $db->getFlag( DBO_TRX ), 'DBO_TRX still default' );

		$called = false;
		$lbFactory->beginMasterChanges( __METHOD__ );
		$db->onTransactionCommitOrIdle( $callback, __METHOD__ );
		$this->assertFalse( $called, 'Not called when lb-transaction is active' );

		$lbFactory->commitMasterChanges( __METHOD__ );
		$this->assertTrue( $called, 'Called when lb-transaction is committed' );

		$called = false;
		$lbFactory->beginMasterChanges( __METHOD__ );
		$db->onTransactionCommitOrIdle( $callback, __METHOD__ );
		$this->assertFalse( $called, 'Not called when lb-transaction is active' );

		$lbFactory->rollbackMasterChanges( __METHOD__ );
		$this->assertFalse( $called, 'Not called when lb-transaction is rolled back' );

		$lbFactory->commitMasterChanges( __METHOD__ );
		$this->assertFalse( $called, 'Not called in next round commit' );

		$db->setFlag( DBO_TRX );
		try {
			$db->onTransactionCommitOrIdle( function () {
				throw new RuntimeException( 'test' );
			} );
			$this->fail( "Exception not thrown" );
		} catch ( RuntimeException $e ) {
			$this->assertTrue( $db->getFlag( DBO_TRX ) );
		}
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::onTransactionPreCommitOrIdle
	 * @covers Wikimedia\Rdbms\Database::runOnTransactionPreCommitCallbacks
	 */
	public function testTransactionPreCommitOrIdle() {
		$db = $this->getMockDB( [ 'isOpen' ] );
		$db->method( 'isOpen' )->willReturn( true );
		$db->clearFlag( DBO_TRX );

		$this->assertFalse( $db->getFlag( DBO_TRX ), 'DBO_TRX is not set' );

		$called = false;
		$db->onTransactionPreCommitOrIdle(
			function ( IDatabase $db ) use ( &$called ) {
				$called = true;
			},
			__METHOD__
		);
		$this->assertTrue( $called, 'Called when idle' );

		$db->begin( __METHOD__ );
		$called = false;
		$db->onTransactionPreCommitOrIdle(
			function ( IDatabase $db ) use ( &$called ) {
				$called = true;
			},
			__METHOD__
		);
		$this->assertFalse( $called, 'Not called when transaction is active' );
		$db->commit( __METHOD__ );
		$this->assertTrue( $called, 'Called when transaction is committed' );
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::onTransactionPreCommitOrIdle
	 * @covers Wikimedia\Rdbms\Database::runOnTransactionPreCommitCallbacks
	 */
	public function testTransactionPreCommitOrIdle_TRX() {
		$db = $this->getMockDB( [ 'isOpen', 'ping', 'getDBname' ] );
		$db->method( 'isOpen' )->willReturn( true );
		$db->method( 'ping' )->willReturn( true );
		$db->method( 'getDBname' )->willReturn( 'unittest' );
		$db->setFlag( DBO_TRX );

		$lbFactory = LBFactorySingle::newFromConnection( $db );
		// Ask for the connection so that LB sets internal state
		// about this connection being the master connection
		$lb = $lbFactory->getMainLB();
		$conn = $lb->openConnection( $lb->getWriterIndex() );
		$this->assertSame( $db, $conn, 'Same DB instance' );

		$this->assertFalse( $lb->hasMasterChanges() );
		$this->assertTrue( $db->getFlag( DBO_TRX ), 'DBO_TRX is set' );
		$called = false;
		$callback = function ( IDatabase $db ) use ( &$called ) {
			$called = true;
		};
		$db->onTransactionPreCommitOrIdle( $callback, __METHOD__ );
		$this->assertTrue( $called, 'Called when idle if DBO_TRX is set' );
		$called = false;
		$lbFactory->commitMasterChanges();
		$this->assertFalse( $called );

		$called = false;
		$lbFactory->beginMasterChanges( __METHOD__ );
		$db->onTransactionPreCommitOrIdle( $callback, __METHOD__ );
		$this->assertFalse( $called, 'Not called when lb-transaction is active' );
		$lbFactory->commitMasterChanges( __METHOD__ );
		$this->assertTrue( $called, 'Called when lb-transaction is committed' );

		$called = false;
		$lbFactory->beginMasterChanges( __METHOD__ );
		$db->onTransactionPreCommitOrIdle( $callback, __METHOD__ );
		$this->assertFalse( $called, 'Not called when lb-transaction is active' );

		$lbFactory->rollbackMasterChanges( __METHOD__ );
		$this->assertFalse( $called, 'Not called when lb-transaction is rolled back' );

		$lbFactory->commitMasterChanges( __METHOD__ );
		$this->assertFalse( $called, 'Not called in next round commit' );
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::onTransactionResolution
	 * @covers Wikimedia\Rdbms\Database::runOnTransactionIdleCallbacks
	 */
	public function testTransactionResolution() {
		$db = $this->db;

		$db->clearFlag( DBO_TRX );
		$db->begin( __METHOD__ );
		$called = false;
		$db->onTransactionResolution( function ( $trigger, IDatabase $db ) use ( &$called ) {
			$called = true;
			$db->setFlag( DBO_TRX );
		} );
		$db->commit( __METHOD__ );
		$this->assertFalse( $db->getFlag( DBO_TRX ), 'DBO_TRX restored to default' );
		$this->assertTrue( $called, 'Callback reached' );

		$db->clearFlag( DBO_TRX );
		$db->begin( __METHOD__ );
		$called = false;
		$db->onTransactionResolution( function ( $trigger, IDatabase $db ) use ( &$called ) {
			$called = true;
			$db->setFlag( DBO_TRX );
		} );
		$db->rollback( __METHOD__ );
		$this->assertFalse( $db->getFlag( DBO_TRX ), 'DBO_TRX restored to default' );
		$this->assertTrue( $called, 'Callback reached' );
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::setTransactionListener
	 */
	public function testTransactionListener() {
		$db = $this->db;

		$db->setTransactionListener( 'ping', function () use ( $db, &$called ) {
			$called = true;
		} );

		$called = false;
		$db->begin( __METHOD__ );
		$db->commit( __METHOD__ );
		$this->assertTrue( $called, 'Callback reached' );

		$called = false;
		$db->begin( __METHOD__ );
		$db->commit( __METHOD__ );
		$this->assertTrue( $called, 'Callback still reached' );

		$called = false;
		$db->begin( __METHOD__ );
		$db->rollback( __METHOD__ );
		$this->assertTrue( $called, 'Callback reached' );

		$db->setTransactionListener( 'ping', null );
		$called = false;
		$db->begin( __METHOD__ );
		$db->commit( __METHOD__ );
		$this->assertFalse( $called, 'Callback not reached' );
	}

	/**
	 * Use this mock instead of DatabaseTestHelper for cases where
	 * DatabaseTestHelper is too inflexibile due to mocking too much
	 * or being too restrictive about fname matching (e.g. for tests
	 * that assert behaviour when the name is a mismatch, we need to
	 * catch the error here instead of there).
	 *
	 * @return Database
	 */
	private function getMockDB( $methods = [] ) {
		static $abstractMethods = [
			'fetchAffectedRowCount',
			'closeConnection',
			'dataSeek',
			'doQuery',
			'fetchObject', 'fetchRow',
			'fieldInfo', 'fieldName',
			'getSoftwareLink', 'getServerVersion',
			'getType',
			'indexInfo',
			'insertId',
			'lastError', 'lastErrno',
			'numFields', 'numRows',
			'open',
			'strencode',
			'tableExists'
		];
		$db = $this->getMockBuilder( Database::class )
			->disableOriginalConstructor()
			->setMethods( array_values( array_unique( array_merge(
				$abstractMethods,
				$methods
			) ) ) )
			->getMock();
		$wdb = TestingAccessWrapper::newFromObject( $db );
		$wdb->trxProfiler = new TransactionProfiler();
		$wdb->connLogger = new \Psr\Log\NullLogger();
		$wdb->queryLogger = new \Psr\Log\NullLogger();
		$wdb->currentDomain = DatabaseDomain::newUnspecified();
		return $db;
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::flushSnapshot
	 */
	public function testFlushSnapshot() {
		$db = $this->getMockDB( [ 'isOpen' ] );
		$db->method( 'isOpen' )->willReturn( true );

		$db->flushSnapshot( __METHOD__ ); // ok
		$db->flushSnapshot( __METHOD__ ); // ok

		$db->setFlag( DBO_TRX, $db::REMEMBER_PRIOR );
		$db->query( 'SELECT 1', __METHOD__ );
		$this->assertTrue( (bool)$db->trxLevel(), "Transaction started." );
		$db->flushSnapshot( __METHOD__ ); // ok
		$db->restoreFlags( $db::RESTORE_PRIOR );

		$this->assertFalse( (bool)$db->trxLevel(), "Transaction cleared." );
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::getScopedLockAndFlush
	 * @covers Wikimedia\Rdbms\Database::lock
	 * @covers Wikimedia\Rdbms\Database::unlock
	 * @covers Wikimedia\Rdbms\Database::lockIsFree
	 */
	public function testGetScopedLock() {
		$db = $this->getMockDB( [ 'isOpen', 'getDBname' ] );
		$db->method( 'isOpen' )->willReturn( true );
		$db->method( 'getDBname' )->willReturn( 'unittest' );

		$this->assertEquals( 0, $db->trxLevel() );
		$this->assertEquals( true, $db->lockIsFree( 'x', __METHOD__ ) );
		$this->assertEquals( true, $db->lock( 'x', __METHOD__ ) );
		$this->assertEquals( false, $db->lockIsFree( 'x', __METHOD__ ) );
		$this->assertEquals( true, $db->unlock( 'x', __METHOD__ ) );
		$this->assertEquals( true, $db->lockIsFree( 'x', __METHOD__ ) );
		$this->assertEquals( 0, $db->trxLevel() );

		$db->setFlag( DBO_TRX );
		$this->assertEquals( true, $db->lockIsFree( 'x', __METHOD__ ) );
		$this->assertEquals( true, $db->lock( 'x', __METHOD__ ) );
		$this->assertEquals( false, $db->lockIsFree( 'x', __METHOD__ ) );
		$this->assertEquals( true, $db->unlock( 'x', __METHOD__ ) );
		$this->assertEquals( true, $db->lockIsFree( 'x', __METHOD__ ) );
		$db->clearFlag( DBO_TRX );

		// Pending writes with DBO_TRX
		$this->assertEquals( 0, $db->trxLevel() );
		$this->assertTrue( $db->lockIsFree( 'meow', __METHOD__ ) );
		$db->setFlag( DBO_TRX );
		$db->query( "DELETE FROM test WHERE t = 1" ); // trigger DBO_TRX transaction before lock
		try {
			$lock = $db->getScopedLockAndFlush( 'meow', __METHOD__, 1 );
			$this->fail( "Exception not reached" );
		} catch ( DBUnexpectedError $e ) {
			$this->assertEquals( 1, $db->trxLevel(), "Transaction not committed." );
			$this->assertTrue( $db->lockIsFree( 'meow', __METHOD__ ), 'Lock not acquired' );
		}
		$db->rollback( __METHOD__, IDatabase::FLUSHING_ALL_PEERS );
		// Pending writes without DBO_TRX
		$db->clearFlag( DBO_TRX );
		$this->assertEquals( 0, $db->trxLevel() );
		$this->assertTrue( $db->lockIsFree( 'meow2', __METHOD__ ) );
		$db->begin( __METHOD__ );
		$db->query( "DELETE FROM test WHERE t = 1" ); // trigger DBO_TRX transaction before lock
		try {
			$lock = $db->getScopedLockAndFlush( 'meow2', __METHOD__, 1 );
			$this->fail( "Exception not reached" );
		} catch ( DBUnexpectedError $e ) {
			$this->assertEquals( 1, $db->trxLevel(), "Transaction not committed." );
			$this->assertTrue( $db->lockIsFree( 'meow2', __METHOD__ ), 'Lock not acquired' );
		}
		$db->rollback( __METHOD__ );
		// No pending writes, with DBO_TRX
		$db->setFlag( DBO_TRX );
		$this->assertEquals( 0, $db->trxLevel() );
		$this->assertTrue( $db->lockIsFree( 'wuff', __METHOD__ ) );
		$db->query( "SELECT 1", __METHOD__ );
		$this->assertEquals( 1, $db->trxLevel() );
		$lock = $db->getScopedLockAndFlush( 'wuff', __METHOD__, 1 );
		$this->assertEquals( 0, $db->trxLevel() );
		$this->assertFalse( $db->lockIsFree( 'wuff', __METHOD__ ), 'Lock already acquired' );
		$db->rollback( __METHOD__, IDatabase::FLUSHING_ALL_PEERS );
		// No pending writes, without DBO_TRX
		$db->clearFlag( DBO_TRX );
		$this->assertEquals( 0, $db->trxLevel() );
		$this->assertTrue( $db->lockIsFree( 'wuff2', __METHOD__ ) );
		$db->begin( __METHOD__ );
		try {
			$lock = $db->getScopedLockAndFlush( 'wuff2', __METHOD__, 1 );
			$this->fail( "Exception not reached" );
		} catch ( DBUnexpectedError $e ) {
			$this->assertEquals( 1, $db->trxLevel(), "Transaction not committed." );
			$this->assertFalse( $db->lockIsFree( 'wuff2', __METHOD__ ), 'Lock not acquired' );
		}
		$db->rollback( __METHOD__ );
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::getFlag
	 * @covers Wikimedia\Rdbms\Database::setFlag
	 * @covers Wikimedia\Rdbms\Database::restoreFlags
	 */
	public function testFlagSetting() {
		$db = $this->db;
		$origTrx = $db->getFlag( DBO_TRX );
		$origSsl = $db->getFlag( DBO_SSL );

		$origTrx
			? $db->clearFlag( DBO_TRX, $db::REMEMBER_PRIOR )
			: $db->setFlag( DBO_TRX, $db::REMEMBER_PRIOR );
		$this->assertEquals( !$origTrx, $db->getFlag( DBO_TRX ) );

		$origSsl
			? $db->clearFlag( DBO_SSL, $db::REMEMBER_PRIOR )
			: $db->setFlag( DBO_SSL, $db::REMEMBER_PRIOR );
		$this->assertEquals( !$origSsl, $db->getFlag( DBO_SSL ) );

		$db->restoreFlags( $db::RESTORE_INITIAL );
		$this->assertEquals( $origTrx, $db->getFlag( DBO_TRX ) );
		$this->assertEquals( $origSsl, $db->getFlag( DBO_SSL ) );

		$origTrx
			? $db->clearFlag( DBO_TRX, $db::REMEMBER_PRIOR )
			: $db->setFlag( DBO_TRX, $db::REMEMBER_PRIOR );
		$origSsl
			? $db->clearFlag( DBO_SSL, $db::REMEMBER_PRIOR )
			: $db->setFlag( DBO_SSL, $db::REMEMBER_PRIOR );

		$db->restoreFlags();
		$this->assertEquals( $origSsl, $db->getFlag( DBO_SSL ) );
		$this->assertEquals( !$origTrx, $db->getFlag( DBO_TRX ) );

		$db->restoreFlags();
		$this->assertEquals( $origSsl, $db->getFlag( DBO_SSL ) );
		$this->assertEquals( $origTrx, $db->getFlag( DBO_TRX ) );
	}

	/**
	 * @expectedException UnexpectedValueException
	 * @covers Wikimedia\Rdbms\Database::setFlag
	 */
	public function testDBOIgnoreSet() {
		$db = $this->getMockBuilder( DatabaseMysqli::class )
			->disableOriginalConstructor()
			->setMethods( null )
			->getMock();

		$db->setFlag( Database::DBO_IGNORE );
	}

	/**
	 * @expectedException UnexpectedValueException
	 * @covers Wikimedia\Rdbms\Database::clearFlag
	 */
	public function testDBOIgnoreClear() {
		$db = $this->getMockBuilder( DatabaseMysqli::class )
			->disableOriginalConstructor()
			->setMethods( null )
			->getMock();

		$db->clearFlag( Database::DBO_IGNORE );
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::tablePrefix
	 * @covers Wikimedia\Rdbms\Database::dbSchema
	 */
	public function testSchemaAndPrefixMutators() {
		$ud = DatabaseDomain::newUnspecified();

		$this->assertEquals( $ud->getId(), $this->db->getDomainID() );

		$old = $this->db->tablePrefix();
		$oldDomain = $this->db->getDomainId();
		$this->assertInternalType( 'string', $old, 'Prefix is string' );
		$this->assertSame( $old, $this->db->tablePrefix(), "Prefix unchanged" );
		$this->assertSame( $old, $this->db->tablePrefix( 'xxx_' ) );
		$this->assertSame( 'xxx_', $this->db->tablePrefix(), "Prefix set" );
		$this->db->tablePrefix( $old );
		$this->assertNotEquals( 'xxx_', $this->db->tablePrefix() );
		$this->assertSame( $oldDomain, $this->db->getDomainId() );

		$old = $this->db->dbSchema();
		$oldDomain = $this->db->getDomainId();
		$this->assertInternalType( 'string', $old, 'Schema is string' );
		$this->assertSame( $old, $this->db->dbSchema(), "Schema unchanged" );

		$this->db->selectDB( 'y' );
		$this->assertSame( $old, $this->db->dbSchema( 'xxx' ) );
		$this->assertSame( 'xxx', $this->db->dbSchema(), "Schema set" );
		$this->db->dbSchema( $old );
		$this->assertNotEquals( 'xxx', $this->db->dbSchema() );
		$this->assertSame( "y", $this->db->getDomainId() );
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::tablePrefix
	 * @covers Wikimedia\Rdbms\Database::dbSchema
	 * @expectedException DBUnexpectedError
	 */
	public function testSchemaWithNoDB() {
		$ud = DatabaseDomain::newUnspecified();

		$this->assertEquals( $ud->getId(), $this->db->getDomainID() );
		$this->assertSame( '', $this->db->dbSchema() );

		$this->db->dbSchema( 'xxx' );
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::selectDomain
	 */
	public function testSelectDomain() {
		$oldDomain = $this->db->getDomainId();
		$oldDatabase = $this->db->getDBname();
		$oldSchema = $this->db->dbSchema();
		$oldPrefix = $this->db->tablePrefix();

		$this->db->selectDomain( 'testselectdb-xxx_' );
		$this->assertSame( 'testselectdb', $this->db->getDBname() );
		$this->assertSame( '', $this->db->dbSchema() );
		$this->assertSame( 'xxx_', $this->db->tablePrefix() );

		$this->db->selectDomain( $oldDomain );
		$this->assertSame( $oldDatabase, $this->db->getDBname() );
		$this->assertSame( $oldSchema, $this->db->dbSchema() );
		$this->assertSame( $oldPrefix, $this->db->tablePrefix() );
		$this->assertSame( $oldDomain, $this->db->getDomainId() );

		$this->db->selectDomain( 'testselectdb-schema-xxx_' );
		$this->assertSame( 'testselectdb', $this->db->getDBname() );
		$this->assertSame( 'schema', $this->db->dbSchema() );
		$this->assertSame( 'xxx_', $this->db->tablePrefix() );

		$this->db->selectDomain( $oldDomain );
		$this->assertSame( $oldDatabase, $this->db->getDBname() );
		$this->assertSame( $oldSchema, $this->db->dbSchema() );
		$this->assertSame( $oldPrefix, $this->db->tablePrefix() );
		$this->assertSame( $oldDomain, $this->db->getDomainId() );
	}

}
