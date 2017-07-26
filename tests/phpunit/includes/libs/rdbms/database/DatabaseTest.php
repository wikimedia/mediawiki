<?php

use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LBFactorySingle;
use Wikimedia\Rdbms\TransactionProfiler;
use Wikimedia\TestingAccessWrapper;

class DatabaseTest extends PHPUnit_Framework_TestCase {

	protected function setUp() {
		$this->db = new DatabaseTestHelper( __CLASS__ . '::' . $this->getName() );
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

	/**
	 * @covers Wikimedia\Rdbms\Database::onTransactionIdle
	 * @covers Wikimedia\Rdbms\Database::runOnTransactionIdleCallbacks
	 */
	public function testTransactionIdle() {
		$db = $this->db;

		$db->setFlag( DBO_TRX );
		$called = false;
		$flagSet = null;
		$db->onTransactionIdle(
			function () use ( $db, &$flagSet, &$called ) {
				$called = true;
				$flagSet = $db->getFlag( DBO_TRX );
			},
			__METHOD__
		);
		$this->assertFalse( $flagSet, 'DBO_TRX off in callback' );
		$this->assertTrue( $db->getFlag( DBO_TRX ), 'DBO_TRX restored to default' );
		$this->assertTrue( $called, 'Callback reached' );

		$db->clearFlag( DBO_TRX );
		$flagSet = null;
		$db->onTransactionIdle(
			function () use ( $db, &$flagSet ) {
				$flagSet = $db->getFlag( DBO_TRX );
			},
			__METHOD__
		);
		$this->assertFalse( $flagSet, 'DBO_TRX off in callback' );
		$this->assertFalse( $db->getFlag( DBO_TRX ), 'DBO_TRX restored to default' );

		$db->clearFlag( DBO_TRX );
		$db->onTransactionIdle(
			function () use ( $db ) {
				$db->setFlag( DBO_TRX );
			},
			__METHOD__
		);
		$this->assertFalse( $db->getFlag( DBO_TRX ), 'DBO_TRX restored to default' );
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
			function () use ( &$called ) {
				$called = true;
			},
			__METHOD__
		);
		$this->assertTrue( $called, 'Called when idle' );

		$db->begin( __METHOD__ );
		$called = false;
		$db->onTransactionPreCommitOrIdle(
			function () use ( &$called ) {
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
		$db = $this->getMockDB( [ 'isOpen' ] );
		$db->method( 'isOpen' )->willReturn( true );
		$db->setFlag( DBO_TRX );

		$lbFactory = LBFactorySingle::newFromConnection( $db );
		// Ask for the connectin so that LB sets internal state
		// about this connection being the master connection
		$lb = $lbFactory->getMainLB();
		$conn = $lb->openConnection( $lb->getWriterIndex() );
		$this->assertSame( $db, $conn, 'Same DB instance' );
		$this->assertTrue( $db->getFlag( DBO_TRX ), 'DBO_TRX is set' );

		$called = false;
		$db->onTransactionPreCommitOrIdle(
			function () use ( &$called ) {
				$called = true;
			}
		);
		$this->assertFalse( $called, 'Not called when idle if DBO_TRX is set' );

		$lbFactory->beginMasterChanges( __METHOD__ );
		$this->assertFalse( $called, 'Not called when lb-transaction is active' );

		$lbFactory->commitMasterChanges( __METHOD__ );
		$this->assertTrue( $called, 'Called when lb-transaction is committed' );
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
		$db->onTransactionResolution( function () use ( $db, &$called ) {
			$called = true;
			$db->setFlag( DBO_TRX );
		} );
		$db->commit( __METHOD__ );
		$this->assertFalse( $db->getFlag( DBO_TRX ), 'DBO_TRX restored to default' );
		$this->assertTrue( $called, 'Callback reached' );

		$db->clearFlag( DBO_TRX );
		$db->begin( __METHOD__ );
		$called = false;
		$db->onTransactionResolution( function () use ( $db, &$called ) {
			$called = true;
			$db->setFlag( DBO_TRX );
		} );
		$db->rollback( __METHOD__, IDatabase::FLUSHING_ALL_PEERS );
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
			'affectedRows',
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

	public function testGetScopedLock() {
		$db = $this->getMockDB( [ 'isOpen' ] );
		$db->method( 'isOpen' )->willReturn( true );

		$db->setFlag( DBO_TRX );
		try {
			$this->badLockingMethodImplicit( $db );
		} catch ( RunTimeException $e ) {
			$this->assertTrue( $db->trxLevel() > 0, "Transaction not committed." );
		}
		$db->clearFlag( DBO_TRX );
		$db->rollback( __METHOD__, IDatabase::FLUSHING_ALL_PEERS );
		$this->assertTrue( $db->lockIsFree( 'meow', __METHOD__ ) );

		try {
			$this->badLockingMethodExplicit( $db );
		} catch ( RunTimeException $e ) {
			$this->assertTrue( $db->trxLevel() > 0, "Transaction not committed." );
		}
		$db->rollback( __METHOD__, IDatabase::FLUSHING_ALL_PEERS );
		$this->assertTrue( $db->lockIsFree( 'meow', __METHOD__ ) );
	}

	private function badLockingMethodImplicit( IDatabase $db ) {
		$lock = $db->getScopedLockAndFlush( 'meow', __METHOD__, 1 );
		$db->query( "SELECT 1" ); // trigger DBO_TRX
		throw new RunTimeException( "Uh oh!" );
	}

	private function badLockingMethodExplicit( IDatabase $db ) {
		$lock = $db->getScopedLockAndFlush( 'meow', __METHOD__, 1 );
		$db->begin( __METHOD__ );
		throw new RunTimeException( "Uh oh!" );
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
	 * @covers Wikimedia\Rdbms\Database::tablePrefix
	 * @covers Wikimedia\Rdbms\Database::dbSchema
	 */
	public function testMutators() {
		$old = $this->db->tablePrefix();
		$this->assertInternalType( 'string', $old, 'Prefix is string' );
		$this->assertEquals( $old, $this->db->tablePrefix(), "Prefix unchanged" );
		$this->assertEquals( $old, $this->db->tablePrefix( 'xxx' ) );
		$this->assertEquals( 'xxx', $this->db->tablePrefix(), "Prefix set" );
		$this->db->tablePrefix( $old );
		$this->assertNotEquals( 'xxx', $this->db->tablePrefix() );

		$old = $this->db->dbSchema();
		$this->assertInternalType( 'string', $old, 'Schema is string' );
		$this->assertEquals( $old, $this->db->dbSchema(), "Schema unchanged" );
		$this->assertEquals( $old, $this->db->dbSchema( 'xxx' ) );
		$this->assertEquals( 'xxx', $this->db->dbSchema(), "Schema set" );
		$this->db->dbSchema( $old );
		$this->assertNotEquals( 'xxx', $this->db->dbSchema() );
	}
}
