<?php

use MediaWiki\Tests\Unit\Libs\Rdbms\AddQuoterMock;
use MediaWiki\Tests\Unit\Libs\Rdbms\SQLPlatformTestHelper;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\DatabaseMysqli;
use Wikimedia\Rdbms\DatabasePostgres;
use Wikimedia\Rdbms\DatabaseSqlite;
use Wikimedia\Rdbms\DBReadOnlyRoleError;
use Wikimedia\Rdbms\DBTransactionStateError;
use Wikimedia\Rdbms\DBUnexpectedError;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\LBFactorySingle;
use Wikimedia\Rdbms\Platform\SQLPlatform;
use Wikimedia\Rdbms\QueryStatus;
use Wikimedia\Rdbms\TransactionManager;
use Wikimedia\RequestTimeout\CriticalSectionScope;
use Wikimedia\TestingAccessWrapper;

class DatabaseTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	/** @var DatabaseTestHelper */
	private $db;

	protected function setUp(): void {
		$this->db = new DatabaseTestHelper( __CLASS__ . '::' . $this->getName() );
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::factory
	 */
	public function testFactory() {
		$m = Database::NEW_UNCONNECTED; // no-connect mode
		$p = [
			'host' => 'localhost',
			'serverName' => 'localdb',
			'user' => 'me',
			'password' => 'myself',
			'dbname' => 'i'
		];

		$this->assertInstanceOf( DatabaseMysqli::class, Database::factory( 'mysqli', $p, $m ) );
		$this->assertInstanceOf( DatabaseMysqli::class, Database::factory( 'MySqli', $p, $m ) );
		$this->assertInstanceOf( DatabaseMysqli::class, Database::factory( 'MySQLi', $p, $m ) );
		$this->assertInstanceOf( DatabasePostgres::class, Database::factory( 'postgres', $p, $m ) );
		$this->assertInstanceOf( DatabasePostgres::class, Database::factory( 'Postgres', $p, $m ) );

		$x = $p + [ 'dbFilePath' => 'some/file.sqlite' ];
		$this->assertInstanceOf( DatabaseSqlite::class, Database::factory( 'sqlite', $x, $m ) );
		$x = $p + [ 'dbDirectory' => 'some/file' ];
		$this->assertInstanceOf( DatabaseSqlite::class, Database::factory( 'sqlite', $x, $m ) );

		$conn = Database::factory( 'sqlite', $p, $m );
		$this->assertEquals( 'localhost', $conn->getServer() );
		$this->assertEquals( 'localdb', $conn->getServerName() );
	}

	public static function provideAddQuotes() {
		return [
			[ null, 'NULL' ],
			[ 1234, "1234" ],
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
	 * @covers Wikimedia\Rdbms\Platform\SQLPlatform::tableNamesWithIndexClauseOrJOIN
	 */
	public function testTableNamesWithIndexClauseOrJOIN( $tables, $join_conds, $expect ) {
		$clause = TestingAccessWrapper::newFromObject( ( new SQLPlatformTestHelper( new AddQuoterMock() ) ) )
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
		$callback = static function ( $trigger, IDatabase $db ) use ( &$flagSet, &$called ) {
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
			static function ( $trigger, IDatabase $db ) {
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
		// about this connection being the primary connection
		$lb = $lbFactory->getMainLB();
		$conn = $lb->getConnectionInternal( $lb->getWriterIndex() );
		$this->assertSame( $db, $conn, 'Same DB instance' );
		$this->assertTrue( $db->getFlag( DBO_TRX ), 'DBO_TRX is set' );

		$called = false;
		$flagSet = null;
		$callback = static function () use ( $db, &$flagSet, &$called ) {
			$called = true;
			$flagSet = $db->getFlag( DBO_TRX );
		};

		$db->onTransactionCommitOrIdle( $callback, __METHOD__ );
		$this->assertTrue( $called, 'Called when idle if DBO_TRX is set' );
		$this->assertFalse( $flagSet, 'DBO_TRX off in callback' );
		$this->assertTrue( $db->getFlag( DBO_TRX ), 'DBO_TRX still default' );

		$called = false;
		$lbFactory->beginPrimaryChanges( __METHOD__ );
		$db->onTransactionCommitOrIdle( $callback, __METHOD__ );
		$this->assertFalse( $called, 'Not called when lb-transaction is active' );

		$lbFactory->commitPrimaryChanges( __METHOD__ );
		$this->assertTrue( $called, 'Called when lb-transaction is committed' );

		$called = false;
		$lbFactory->beginPrimaryChanges( __METHOD__ );
		$db->onTransactionCommitOrIdle( $callback, __METHOD__ );
		$this->assertFalse( $called, 'Not called when lb-transaction is active' );

		$lbFactory->rollbackPrimaryChanges( __METHOD__ );
		$this->assertFalse( $called, 'Not called when lb-transaction is rolled back' );

		$lbFactory->commitPrimaryChanges( __METHOD__ );
		$this->assertFalse( $called, 'Not called in next round commit' );

		$db->setFlag( DBO_TRX );
		try {
			$db->onTransactionCommitOrIdle( static function () {
				throw new RuntimeException( 'test' );
			} );
			$this->fail( "Exception not thrown" );
		} catch ( RuntimeException $e ) {
			$this->assertTrue( $db->getFlag( DBO_TRX ) );
		}

		$lbFactory->rollbackPrimaryChanges( __METHOD__ );
		$lbFactory->flushPrimarySessions( __METHOD__ );
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
			static function ( IDatabase $db ) use ( &$called ) {
				$called = true;
			},
			__METHOD__
		);
		$this->assertTrue( $called, 'Called when idle' );

		$db->begin( __METHOD__ );
		$called = false;
		$db->onTransactionPreCommitOrIdle(
			static function ( IDatabase $db ) use ( &$called ) {
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
		// about this connection being the primary connection
		$lb = $lbFactory->getMainLB();
		$conn = $lb->getConnectionInternal( $lb->getWriterIndex() );
		$this->assertSame( $db, $conn, 'Same DB instance' );

		$this->assertFalse( $lb->hasPrimaryChanges() );
		$this->assertTrue( $db->getFlag( DBO_TRX ), 'DBO_TRX is set' );
		$called = false;
		$callback = static function ( IDatabase $db ) use ( &$called ) {
			$called = true;
		};
		$db->onTransactionPreCommitOrIdle( $callback, __METHOD__ );
		$this->assertTrue( $called, 'Called when idle if DBO_TRX is set' );
		$called = false;
		$lbFactory->commitPrimaryChanges();
		$this->assertFalse( $called );

		$called = false;
		$lbFactory->beginPrimaryChanges( __METHOD__ );
		$db->onTransactionPreCommitOrIdle( $callback, __METHOD__ );
		$this->assertFalse( $called, 'Not called when lb-transaction is active' );
		$lbFactory->commitPrimaryChanges( __METHOD__ );
		$this->assertTrue( $called, 'Called when lb-transaction is committed' );

		$called = false;
		$lbFactory->beginPrimaryChanges( __METHOD__ );
		$db->onTransactionPreCommitOrIdle( $callback, __METHOD__ );
		$this->assertFalse( $called, 'Not called when lb-transaction is active' );

		$lbFactory->rollbackPrimaryChanges( __METHOD__ );
		$this->assertFalse( $called, 'Not called when lb-transaction is rolled back' );

		$lbFactory->commitPrimaryChanges( __METHOD__ );
		$this->assertFalse( $called, 'Not called in next round commit' );

		$lbFactory->flushPrimarySessions( __METHOD__ );
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
		$db->onTransactionResolution( static function ( $trigger, IDatabase $db ) use ( &$called ) {
			$called = true;
			$db->setFlag( DBO_TRX );
		} );
		$db->commit( __METHOD__ );
		$this->assertFalse( $db->getFlag( DBO_TRX ), 'DBO_TRX restored to default' );
		$this->assertTrue( $called, 'Callback reached' );

		$db->clearFlag( DBO_TRX );
		$db->begin( __METHOD__ );
		$called = false;
		$db->onTransactionResolution( static function ( $trigger, IDatabase $db ) use ( &$called ) {
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

		$db->setTransactionListener( 'ping', static function () use ( &$called ) {
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
	 * @param string[] $methods
	 * @return Database
	 */
	private function getMockDB( $methods = [] ) {
		static $abstractMethods = [
			'fetchAffectedRowCount',
			'closeConnection',
			'doSingleStatementQuery',
			'fieldInfo',
			'getSoftwareLink',
			'getServerVersion',
			'getType',
			'indexInfo',
			'insertId',
			'lastError',
			'lastErrno',
			'open',
			'strencode',
			'tableExists',
			'getServer'
		];
		$db = $this->getMockBuilder( Database::class )
			->disableOriginalConstructor()
			->onlyMethods( array_values( array_unique( array_merge(
				$abstractMethods,
				$methods
			) ) ) )
			->getMock();
		$wdb = TestingAccessWrapper::newFromObject( $db );
		$wdb->connLogger = new \Psr\Log\NullLogger();
		$wdb->queryLogger = new \Psr\Log\NullLogger();
		$wdb->replLogger = new \Psr\Log\NullLogger();
		$wdb->errorLogger = static function ( Throwable $e ) {
		};
		$wdb->deprecationLogger = static function ( $msg ) {
		};
		$wdb->currentDomain = DatabaseDomain::newUnspecified();
		$wdb->platform = new SQLPlatform( new AddQuoterMock() );
		// Info used for logging/errors
		$wdb->connectionParams = [
			'host' => 'localhost',
			'user' => 'testuser'
		];

		$db->method( 'getServer' )->willReturn( '*dummy*' );
		$db->setTransactionManager( new TransactionManager() );

		$qs = new QueryStatus( false, 0, '', 0 );
		$qs->res = true;
		$db->method( 'doSingleStatementQuery' )->willReturn( $qs );

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

		$db->setFlag( DBO_TRX, IDatabase::REMEMBER_PRIOR );
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

		$this->assertSame( 0, $db->trxLevel() );
		$this->assertTrue( $db->lockIsFree( 'x', __METHOD__ ) );
		$this->assertTrue( $db->lock( 'x', __METHOD__ ) );
		$this->assertFalse( $db->lockIsFree( 'x', __METHOD__ ) );
		$this->assertTrue( $db->unlock( 'x', __METHOD__ ) );
		$this->assertTrue( $db->lockIsFree( 'x', __METHOD__ ) );
		$this->assertSame( 0, $db->trxLevel() );

		$db->setFlag( DBO_TRX );
		$this->assertTrue( $db->lockIsFree( 'x', __METHOD__ ) );
		$this->assertTrue( $db->lock( 'x', __METHOD__ ) );
		$this->assertFalse( $db->lockIsFree( 'x', __METHOD__ ) );
		$this->assertTrue( $db->unlock( 'x', __METHOD__ ) );
		$this->assertTrue( $db->lockIsFree( 'x', __METHOD__ ) );
		$db->clearFlag( DBO_TRX );

		// Pending writes with DBO_TRX
		$this->assertSame( 0, $db->trxLevel() );
		$this->assertTrue( $db->lockIsFree( 'meow', __METHOD__ ) );
		$db->setFlag( DBO_TRX );
		$db->query( "DELETE FROM test WHERE t = 1" ); // trigger DBO_TRX transaction before lock
		try {
			$lock = $db->getScopedLockAndFlush( 'meow', __METHOD__, 1 );
			$this->fail( "Exception not reached" );
		} catch ( DBUnexpectedError $e ) {
			$this->assertSame( 1, $db->trxLevel(), "Transaction not committed." );
			$this->assertTrue( $db->lockIsFree( 'meow', __METHOD__ ), 'Lock not acquired' );
		}
		$db->rollback( __METHOD__, IDatabase::FLUSHING_ALL_PEERS );
		// Pending writes without DBO_TRX
		$db->clearFlag( DBO_TRX );
		$this->assertSame( 0, $db->trxLevel() );
		$this->assertTrue( $db->lockIsFree( 'meow2', __METHOD__ ) );
		$db->begin( __METHOD__ );
		$db->query( "DELETE FROM test WHERE t = 1" ); // trigger DBO_TRX transaction before lock
		try {
			$lock = $db->getScopedLockAndFlush( 'meow2', __METHOD__, 1 );
			$this->fail( "Exception not reached" );
		} catch ( DBUnexpectedError $e ) {
			$this->assertSame( 1, $db->trxLevel(), "Transaction not committed." );
			$this->assertTrue( $db->lockIsFree( 'meow2', __METHOD__ ), 'Lock not acquired' );
		}
		$db->rollback( __METHOD__ );
		// No pending writes, with DBO_TRX
		$db->setFlag( DBO_TRX );
		$this->assertSame( 0, $db->trxLevel() );
		$this->assertTrue( $db->lockIsFree( 'wuff', __METHOD__ ) );
		$db->query( "SELECT 1", __METHOD__ );
		$this->assertSame( 1, $db->trxLevel() );
		$lock = $db->getScopedLockAndFlush( 'wuff', __METHOD__, 1 );
		$this->assertSame( 0, $db->trxLevel() );
		$this->assertFalse( $db->lockIsFree( 'wuff', __METHOD__ ), 'Lock already acquired' );
		$db->rollback( __METHOD__, IDatabase::FLUSHING_ALL_PEERS );
		// No pending writes, without DBO_TRX
		$db->clearFlag( DBO_TRX );
		$this->assertSame( 0, $db->trxLevel() );
		$this->assertTrue( $db->lockIsFree( 'wuff2', __METHOD__ ) );
		$db->begin( __METHOD__ );
		try {
			$lock = $db->getScopedLockAndFlush( 'wuff2', __METHOD__, 1 );
			$this->fail( "Exception not reached" );
		} catch ( DBUnexpectedError $e ) {
			$this->assertSame( 1, $db->trxLevel(), "Transaction not committed." );
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
		$origNoBuffer = $db->getFlag( DBO_NOBUFFER );

		$origTrx
			? $db->clearFlag( DBO_TRX, $db::REMEMBER_PRIOR )
			: $db->setFlag( DBO_TRX, $db::REMEMBER_PRIOR );
		$this->assertEquals( !$origTrx, $db->getFlag( DBO_TRX ) );

		$origNoBuffer
			? $db->clearFlag( DBO_NOBUFFER, $db::REMEMBER_PRIOR )
			: $db->setFlag( DBO_NOBUFFER, $db::REMEMBER_PRIOR );
		$this->assertEquals( !$origNoBuffer, $db->getFlag( DBO_NOBUFFER ) );

		$db->restoreFlags( $db::RESTORE_INITIAL );
		$this->assertEquals( $origTrx, $db->getFlag( DBO_TRX ) );
		$this->assertEquals( $origNoBuffer, $db->getFlag( DBO_NOBUFFER ) );

		$origTrx
			? $db->clearFlag( DBO_TRX, $db::REMEMBER_PRIOR )
			: $db->setFlag( DBO_TRX, $db::REMEMBER_PRIOR );
		$origNoBuffer
			? $db->clearFlag( DBO_NOBUFFER, $db::REMEMBER_PRIOR )
			: $db->setFlag( DBO_NOBUFFER, $db::REMEMBER_PRIOR );

		$db->restoreFlags();
		$this->assertEquals( $origNoBuffer, $db->getFlag( DBO_NOBUFFER ) );
		$this->assertEquals( !$origTrx, $db->getFlag( DBO_TRX ) );

		$db->restoreFlags();
		$this->assertEquals( $origNoBuffer, $db->getFlag( DBO_NOBUFFER ) );
		$this->assertEquals( $origTrx, $db->getFlag( DBO_TRX ) );
	}

	public function provideImmutableDBOFlags() {
		return [
			[ Database::DBO_IGNORE ],
			[ Database::DBO_DEFAULT ],
			[ Database::DBO_PERSISTENT ]
		];
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::setFlag
	 * @dataProvider provideImmutableDBOFlags
	 * @param int $flag
	 */
	public function testDBOCannotSet( $flag ) {
		$db = $this->createPartialMock( DatabaseMysqli::class, [] );

		$this->expectException( DBUnexpectedError::class );
		$db->setFlag( $flag );
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::clearFlag
	 * @dataProvider provideImmutableDBOFlags
	 * @param int $flag
	 */
	public function testDBOCannotClear( $flag ) {
		$db = $this->createPartialMock( DatabaseMysqli::class, [] );

		$this->expectException( DBUnexpectedError::class );
		$db->clearFlag( $flag );
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::tablePrefix
	 * @covers Wikimedia\Rdbms\Database::dbSchema
	 */
	public function testSchemaAndPrefixMutators() {
		$ud = DatabaseDomain::newUnspecified();

		$this->assertEquals( $ud->getId(), $this->db->getDomainID() );

		$old = $this->db->tablePrefix();
		$oldDomain = $this->db->getDomainID();
		$this->assertIsString( $old, 'Prefix is string' );
		$this->assertSame( $old, $this->db->tablePrefix(), "Prefix unchanged" );
		$this->assertSame( $old, $this->db->tablePrefix( 'xxx_' ) );
		$this->assertSame( 'xxx_', $this->db->tablePrefix(), "Prefix set" );
		$this->db->tablePrefix( $old );
		$this->assertNotEquals( 'xxx_', $this->db->tablePrefix() );
		$this->assertSame( $oldDomain, $this->db->getDomainID() );

		$old = $this->db->dbSchema();
		$oldDomain = $this->db->getDomainID();
		$this->assertIsString( $old, 'Schema is string' );
		$this->assertSame( $old, $this->db->dbSchema(), "Schema unchanged" );

		$this->db->selectDB( 'y' );
		$this->assertSame( $old, $this->db->dbSchema( 'xxx' ) );
		$this->assertSame( 'xxx', $this->db->dbSchema(), "Schema set" );
		$this->db->dbSchema( $old );
		$this->assertNotEquals( 'xxx', $this->db->dbSchema() );
		$this->assertSame( "y", $this->db->getDomainID() );
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::tablePrefix
	 * @covers Wikimedia\Rdbms\Database::dbSchema
	 */
	public function testSchemaWithNoDB() {
		$ud = DatabaseDomain::newUnspecified();

		$this->assertEquals( $ud->getId(), $this->db->getDomainID() );
		$this->assertSame( '', $this->db->dbSchema() );

		$this->expectException( DBUnexpectedError::class );
		$this->db->dbSchema( 'xxx' );
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::selectDomain
	 */
	public function testSelectDomain() {
		$oldDomain = $this->db->getDomainID();
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
		$this->assertSame( $oldDomain, $this->db->getDomainID() );

		$this->db->selectDomain( 'testselectdb-schema-xxx_' );
		$this->assertSame( 'testselectdb', $this->db->getDBname() );
		$this->assertSame( 'schema', $this->db->dbSchema() );
		$this->assertSame( 'xxx_', $this->db->tablePrefix() );

		$this->db->selectDomain( $oldDomain );
		$this->assertSame( $oldDatabase, $this->db->getDBname() );
		$this->assertSame( $oldSchema, $this->db->dbSchema() );
		$this->assertSame( $oldPrefix, $this->db->tablePrefix() );
		$this->assertSame( $oldDomain, $this->db->getDomainID() );
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::getLBInfo
	 * @covers Wikimedia\Rdbms\Database::setLBInfo
	 */
	public function testGetSetLBInfo() {
		$db = $this->getMockDB();

		$this->assertEquals( [], $db->getLBInfo() );
		$this->assertNull( $db->getLBInfo( 'pringles' ) );

		$db->setLBInfo( 'soda', 'water' );
		$this->assertEquals( [ 'soda' => 'water' ], $db->getLBInfo() );
		$this->assertNull( $db->getLBInfo( 'pringles' ) );
		$this->assertEquals( 'water', $db->getLBInfo( 'soda' ) );

		$db->setLBInfo( 'basketball', 'Lebron' );
		$this->assertEquals( [ 'soda' => 'water', 'basketball' => 'Lebron' ], $db->getLBInfo() );
		$this->assertEquals( 'water', $db->getLBInfo( 'soda' ) );
		$this->assertEquals( 'Lebron', $db->getLBInfo( 'basketball' ) );

		$db->setLBInfo( 'soda', null );
		$this->assertEquals( [ 'basketball' => 'Lebron' ], $db->getLBInfo() );

		$db->setLBInfo( [ 'King' => 'James' ] );
		$this->assertNull( $db->getLBInfo( 'basketball' ) );
		$this->assertEquals( [ 'King' => 'James' ], $db->getLBInfo() );
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database::executeQuery()
	 * @covers \Wikimedia\Rdbms\Database::assertIsWritablePrimary()
	 */
	public function testShouldRejectPersistentWriteQueryOnReplicaDatabaseConnection() {
		$this->expectException( DBReadOnlyRoleError::class );
		$this->expectDeprecationMessage( 'Server is configured as a read-only replica database.' );

		$dbr = new DatabaseTestHelper(
			__CLASS__ . '::' . $this->getName(),
			[ 'topologyRole' => Database::ROLE_STREAMING_REPLICA ]
		);

		$dbr->query( "INSERT INTO test_table (a_column) VALUES ('foo');", __METHOD__ );
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database::executeQuery()
	 * @covers \Wikimedia\Rdbms\Database::assertIsWritablePrimary()
	 */
	public function testShouldAcceptTemporaryTableOperationsOnReplicaDatabaseConnection() {
		$dbr = new DatabaseTestHelper(
			__CLASS__ . '::' . $this->getName(),
			[ 'topologyRole' => Database::ROLE_STREAMING_REPLICA ]
		);

		$resCreate = $dbr->query(
			"CREATE TEMPORARY TABLE temp_test_table (temp_column int);",
			__METHOD__
		);

		$resModify = $dbr->query(
			"INSERT INTO temp_test_table (temp_column) VALUES (42);",
			__METHOD__
		);

		$this->assertInstanceOf( IResultWrapper::class, $resCreate );
		$this->assertInstanceOf( IResultWrapper::class, $resModify );
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database::executeQuery()
	 * @covers \Wikimedia\Rdbms\Database::assertIsWritablePrimary()
	 */
	public function testShouldRejectPseudoPermanentTemporaryTableOperationsOnReplicaDatabaseConnection() {
		$this->expectException( DBReadOnlyRoleError::class );
		$this->expectDeprecationMessage( 'Server is configured as a read-only replica database.' );

		$dbr = new DatabaseTestHelper(
			__CLASS__ . '::' . $this->getName(),
			[ 'topologyRole' => Database::ROLE_STREAMING_REPLICA ]
		);

		$dbr->query(
			"CREATE TEMPORARY TABLE temp_test_table (temp_column int);",
			__METHOD__,
			Database::QUERY_PSEUDO_PERMANENT
		);
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database::executeQuery()
	 * @covers \Wikimedia\Rdbms\Database::assertIsWritablePrimary()
	 */
	public function testShouldAcceptWriteQueryOnPrimaryDatabaseConnection() {
		$dbr = new DatabaseTestHelper(
			__CLASS__ . '::' . $this->getName(),
			[ 'topologyRole' => Database::ROLE_STREAMING_MASTER ]
		);

		$res = $dbr->query( "INSERT INTO test_table (a_column) VALUES ('foo');", __METHOD__ );

		$this->assertInstanceOf( IResultWrapper::class, $res );
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database::executeQuery()
	 * @covers \Wikimedia\Rdbms\Database::assertIsWritablePrimary()
	 */
	public function testShouldRejectWriteQueryOnPrimaryDatabaseConnectionWhenReplicaQueryRoleFlagIsSet() {
		$this->expectException( DBReadOnlyRoleError::class );
		$this->expectDeprecationMessage( 'Cannot write; target role is DB_REPLICA' );

		$dbr = new DatabaseTestHelper(
			__CLASS__ . '::' . $this->getName(),
			[ 'topologyRole' => Database::ROLE_STREAMING_MASTER ]
		);

		$dbr->query(
			"INSERT INTO test_table (a_column) VALUES ('foo');",
			__METHOD__,
			Database::QUERY_REPLICA_ROLE
		);
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database::commenceCriticalSection()
	 * @covers \Wikimedia\Rdbms\Database::completeCriticalSection()
	 */
	public function testCriticalSectionErrorSelect() {
		$this->expectException( DBTransactionStateError::class );

		$db = TestingAccessWrapper::newFromObject( $this->db );
		try {
			$this->corruptDbState( $db );
		} catch ( RuntimeException $e ) {
			$this->assertEquals( "Unexpected error", $e->getMessage() );
		}

		$db->query( "SELECT 1", __METHOD__ );
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database::commenceCriticalSection()
	 * @covers \Wikimedia\Rdbms\Database::completeCriticalSection()
	 */
	public function testCriticalSectionErrorRollback() {
		$db = TestingAccessWrapper::newFromObject( $this->db );
		try {
			$this->corruptDbState( $db );
		} catch ( RuntimeException $e ) {
			$this->assertEquals( "Unexpected error", $e->getMessage() );
		}

		$db->rollback( __METHOD__, IDatabase::FLUSHING_ALL_PEERS );
		$this->assertTrue( true, "No exception on ROLLBACK" );
	}

	private function corruptDbState( $db ) {
		$cs = $db->commenceCriticalSection( __METHOD__ );
		$this->assertInstanceOf( CriticalSectionScope::class, $cs );
		throw new RuntimeException( "Unexpected error" );
	}
}
