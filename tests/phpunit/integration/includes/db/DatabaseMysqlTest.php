<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\ChangedTablesTracker;
use Wikimedia\Rdbms\DatabaseMySQL;
use Wikimedia\Rdbms\DBError;
use Wikimedia\Rdbms\DBQueryDisconnectedError;
use Wikimedia\Rdbms\DBQueryError;
use Wikimedia\Rdbms\DBQueryTimeoutError;
use Wikimedia\Rdbms\DBSessionStateError;
use Wikimedia\Rdbms\DBTransactionStateError;
use Wikimedia\Rdbms\DBUnexpectedError;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\Platform\ISQLPlatform;
use Wikimedia\Rdbms\Platform\SQLPlatform;
use Wikimedia\Rdbms\Query;
use Wikimedia\Rdbms\TransactionManager;

/**
 * @covers \Wikimedia\Rdbms\Database
 * @covers \Wikimedia\Rdbms\DatabaseMySQL
 * @covers \Wikimedia\Rdbms\Platform\MySQLPlatform
 * @group mysql
 * @group Database
 * @group medium
 * @requires extension mysqli
 */
class DatabaseMysqlTest extends \MediaWikiIntegrationTestCase {
	/** @var DatabaseMySQL */
	protected $conn;

	protected function setUp(): void {
		parent::setUp();

		$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
		if ( $lb->getServerType( 0 ) !== 'mysql' ) {
			$this->markTestSkipped( 'No MySQL $wgLBFactoryConf config detected' );
		}

		$this->conn = $this->newConnection();
		// FIXME: Tables used by this test aren't parsed correctly, see T344510.
		ChangedTablesTracker::stopTracking();
	}

	protected function tearDown(): void {
		if ( $this->conn ) {
			$this->conn->close( __METHOD__ );
			ChangedTablesTracker::startTracking();
		}

		parent::tearDown();
	}

	public function testQueryTimeout() {
		try {
			$res = $this->conn->query(
				'SET STATEMENT max_statement_time=0.001 FOR SELECT sleep(1) FROM dual',
				__METHOD__
			);
			// if the query did not time out, there should be a single row where sleep() returned 1
			$this->assertSame( 1, $res->numRows() );
			$row = $res->fetchRow();
			$this->assertSame( '1', (string)reset( $row ) );
		} catch ( DBQueryTimeoutError $e ) {
			$this->assertInstanceOf( DBQueryTimeoutError::class, $e );
		}

		$row = $this->conn->query( 'SELECT "x" AS v', __METHOD__ )->fetchObject();
		$this->assertSame( 'x', $row->v, "Still connected/usable" );
	}

	public function testConnectionKill() {
		try {
			$this->conn->query( 'KILL (SELECT connection_id())', __METHOD__ );
			$this->fail( "No DBQueryDisconnectedError caught" );
		} catch ( DBQueryDisconnectedError $e ) {
			$this->assertInstanceOf( DBQueryDisconnectedError::class, $e );
		}

		$row = $this->conn->query( 'SELECT "x" AS v', __METHOD__ )->fetchObject();
		$this->assertSame( 'x', $row->v, "Recovered" );
	}

	public function testConnectionLossQuery() {
		$row = $this->conn->query( 'SELECT connection_id() AS id', __METHOD__ )->fetchObject();
		$encId = intval( $row->id );

		$adminConn = $this->newConnection();
		$adminConn->query( "KILL $encId", __METHOD__ );

		$row = $this->conn->query( 'SELECT "x" AS v', __METHOD__ )->fetchObject();
		$this->assertSame( 'x', $row->v, "Recovered" );

		$this->conn->startAtomic( __METHOD__ );
		$this->assertSame( 1, $this->conn->trxLevel(), "Transaction exists" );
		$row = $this->conn->query( 'SELECT connection_id() AS id', __METHOD__ )->fetchObject();
		$encId = intval( $row->id );

		$adminConn->query( "KILL $encId", __METHOD__ );
		try {
			$this->conn->query( 'SELECT 1', __METHOD__ );
			$this->fail( "No DBQueryDisconnectedError caught" );
		} catch ( DBQueryDisconnectedError $e ) {
			$this->assertTrue( $this->conn->isOpen(), "Reconnected" );
			try {
				$this->conn->endAtomic( __METHOD__ );
				$this->fail( "No DBUnexpectedError caught" );
			} catch ( DBUnexpectedError $e ) {
				$this->assertInstanceOf( DBUnexpectedError::class, $e );
			}

			$this->assertSame( TransactionManager::STATUS_TRX_ERROR, $this->conn->trxStatus() );
			try {
				$this->conn->query( 'SELECT "x" AS v', __METHOD__ )->fetchObject();
				$this->fail( "No DBTransactionStateError caught" );
			} catch ( DBTransactionStateError $e ) {
				$this->assertInstanceOf( DBTransactionStateError::class, $e );
			}

			$this->assertSame( 0, $this->conn->trxLevel(), "Transaction lost" );
			$this->conn->rollback( __METHOD__ );
			$this->assertSame( 0, $this->conn->trxLevel(), "No transaction" );

			$row = $this->conn->query( 'SELECT "x" AS v', __METHOD__ )->fetchObject();
			$this->assertSame( 'x', $row->v, "Recovered" );
		}

		$this->conn->lock( 'session_lock_' . mt_rand(), __METHOD__, 0 );
		$row = $this->conn->query( 'SELECT connection_id() AS id', __METHOD__ )->fetchObject();
		$encId = intval( $row->id );
		$adminConn->query( "KILL $encId", __METHOD__ );
		try {
			$this->conn->query( 'SELECT 1', __METHOD__ );
			$this->fail( "No DBQueryDisconnectedError caught" );
		} catch ( DBQueryDisconnectedError $e ) {
			$this->assertInstanceOf( DBQueryDisconnectedError::class, $e );
		}

		$this->assertTrue( $this->conn->isOpen(), "Reconnected" );
		try {
			$this->conn->query( 'SELECT "x" AS v', __METHOD__ )->fetchObject();
			$this->fail( "No DBSessionStateError caught" );
		} catch ( DBSessionStateError $e ) {
			$this->assertInstanceOf( DBSessionStateError::class, $e );
		}

		$this->assertTrue( $this->conn->isOpen(), "Connection remains" );
		$this->conn->rollback( __METHOD__ );
		$this->conn->flushSession( __METHOD__ );

		$row = $this->conn->query( 'SELECT "x" AS v', __METHOD__ )->fetchObject();
		$this->assertSame( 'x', $row->v, "Recovered" );

		$adminConn->close( __METHOD__ );
	}

	public function testConnectionLossSnapshotFlush() {
		$fakeWriteQuery = new Query( 'SELECT 1', SQLPlatform::QUERY_CHANGE_ROWS, 'SELECT' );

		$this->conn->begin( __METHOD__, IDatabase::TRANSACTION_INTERNAL );
		$row = $this->conn->query( 'SELECT connection_id() AS id', __METHOD__ )->fetchObject();
		$encId = intval( $row->id );

		$adminConn = $this->newConnection();
		$adminConn->query( "KILL $encId", __METHOD__ );

		$this->conn->flushSnapshot( __METHOD__ );
		$this->assertSame( 0, $this->conn->trxLevel(), "Lost connection during snapshot flush" );
		$this->assertSame( TransactionManager::STATUS_TRX_NONE, $this->conn->trxStatus() );

		$this->conn->begin( __METHOD__, IDatabase::TRANSACTION_INTERNAL );
		$this->conn->query( $fakeWriteQuery, __METHOD__ );

		$row = $this->conn->query( 'SELECT connection_id() AS id', __METHOD__ )->fetchObject();
		$encId = intval( $row->id );
		$adminConn->query( "KILL $encId", __METHOD__ );

		try {
			$this->conn->query( $fakeWriteQuery, __METHOD__ );
			$this->fail( "Error thrown due to connection loss with pending writes" );
		} catch ( DBError $e ) {
			$this->assertInstanceOf( DBQueryDisconnectedError::class, $e );
			$this->assertSame( TransactionManager::STATUS_TRX_ERROR, $this->conn->trxStatus() );
		}

		try {
			$this->conn->query( 'SELECT 1', __METHOD__ );
			$this->fail( "Error thrown due to unacknowledged transaction error" );
		} catch ( DBError $e ) {
			$this->assertInstanceOf( DBTransactionStateError::class, $e );
			$this->assertSame( TransactionManager::STATUS_TRX_ERROR, $this->conn->trxStatus() );
		}

		$this->conn->flushSnapshot( __METHOD__ );
		$this->assertSame( 0, $this->conn->trxLevel(), "Snapshot flush after lost writes" );
		$this->assertSame( TransactionManager::STATUS_TRX_NONE, $this->conn->trxStatus() );

		// Get a lock outside of any transaction
		$unlocker = $this->conn->getScopedLockAndFlush( 'testing-key', __METHOD__, 0 );
		// Start transaction after getting the lock
		$this->conn->begin( __METHOD__, IDatabase::TRANSACTION_INTERNAL );

		$row = $this->conn->query( 'SELECT connection_id() AS id', __METHOD__ )->fetchObject();
		$encId = intval( $row->id );
		$adminConn->query( "KILL $encId", __METHOD__ );

		try {
			$this->conn->query( 'SELECT 1', __METHOD__ );
			$this->fail( "Error thrown due to connection loss with locks" );
		} catch ( DBError ) {
			$this->assertSame( TransactionManager::STATUS_TRX_ERROR, $this->conn->trxStatus() );
		}

		$this->conn->flushSnapshot( __METHOD__ );
		$this->assertSame( 0, $this->conn->trxLevel(), "Snapshot flush with lost lock" );

		try {
			$this->conn->query( 'SELECT 1', __METHOD__ );
			$this->fail( "Error thrown due to unacknowledged session error" );
		} catch ( DBError $e ) {
			$this->assertInstanceOf( DBSessionStateError::class, $e );
		}

		$adminConn->close( __METHOD__ );
	}

	public function testConnectionLossScopedLock() {
		$row = $this->conn->query( 'SELECT connection_id() AS id', __METHOD__ )->fetchObject();
		$encId = intval( $row->id );

		try {
			( function () use ( $encId ) {
				$unlocker = $this->conn->getScopedLockAndFlush( 'x', 'fn', 1 );

				$adminConn = $this->newConnection();
				$adminConn->query( "KILL $encId" );

				$this->conn->query( "SELECT 1" );
			} )();
			$this->fail( "Expected DBQueryDisconnectedError" );
		} catch ( DBQueryDisconnectedError $e ) {
			// This should report the explicit query that failed,
			// instead of the (later) implicit query from the $unlocker deref.
			$this->assertStringContainsString( "SELECT 1", $e->getMessage() );
		}

		$this->conn->rollback( __METHOD__ );
		$this->conn->unlock( 'x', __METHOD__ );
	}

	public function testTransactionError() {
		$this->assertSame( TransactionManager::STATUS_TRX_NONE, $this->conn->trxStatus() );

		$this->conn->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->assertSame( TransactionManager::STATUS_TRX_OK, $this->conn->trxStatus() );
		$row = $this->conn->query( 'SELECT connection_id() AS id', __METHOD__ )->fetchObject();
		$encId = intval( $row->id );

		try {
			$this->conn->lock( 'trx_lock_' . mt_rand(), __METHOD__, 0 );
			$this->assertSame( TransactionManager::STATUS_TRX_OK, $this->conn->trxStatus() );
			$this->conn->query( "SELECT invalid query()", __METHOD__ );
			$this->fail( "No DBQueryError caught" );
		} catch ( DBQueryError $e ) {
			$this->assertInstanceOf( DBQueryError::class, $e );
		}

		$this->assertSame( TransactionManager::STATUS_TRX_ERROR, $this->conn->trxStatus() );
		try {
			$this->conn->query( 'SELECT "x" AS v', __METHOD__ )->fetchObject();
			$this->fail( "No DBTransactionStateError caught" );
		} catch ( DBTransactionStateError $e ) {
			$this->assertInstanceOf( DBTransactionStateError::class, $e );
			$this->assertSame( TransactionManager::STATUS_TRX_ERROR, $this->conn->trxStatus() );
			$this->assertSame( 1, $this->conn->trxLevel(), "Transaction remains" );
			$this->assertTrue( $this->conn->isOpen(), "Connection remains" );
		}

		$adminConn = $this->newConnection();
		$adminConn->query( "KILL $encId", __METHOD__ );

		$this->assertSame( TransactionManager::STATUS_TRX_ERROR, $this->conn->trxStatus() );
		try {
			$this->conn->query( 'SELECT 1', __METHOD__ );
			$this->fail( "No DBTransactionStateError caught" );
		} catch ( DBTransactionStateError $e ) {
			$this->assertInstanceOf( DBTransactionStateError::class, $e );
		}

		$this->assertSame(
			1,
			$this->conn->trxLevel(),
			"Transaction loss not yet detected (due to STATUS_TRX_ERROR)"
		);
		$this->assertTrue(
			$this->conn->isOpen(),
			"Connection loss not detected (due to STATUS_TRX_ERROR)"
		);

		$this->conn->cancelAtomic( __METHOD__ );
		$this->assertSame( 0, $this->conn->trxLevel(), "No transaction remains" );
		$this->assertTrue( $this->conn->isOpen(), "Reconnected" );

		$row = $this->conn->query( 'SELECT "x" AS v', __METHOD__ )->fetchObject();
		$this->assertSame( 'x', $row->v, "Recovered" );

		$this->conn->rollback( __METHOD__ );

		$adminConn->close( __METHOD__ );
	}

	private function newConnection(): DatabaseMySQL {
		$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
		$dbFactory = MediaWikiServices::getInstance()->getDatabaseFactory();
		/** @var DatabaseMySQL $conn */
		$conn = $dbFactory->create(
			'mysql',
			array_merge(
				$lb->getServerInfo( 0 ),
				[
					'dbname' => null,
					'schema' => null,
					'tablePrefix' => '',
				]
			)
		);

		return $conn;
	}

	public function testInsertIdAfterInsert() {
		$dTable = $this->createDestTable();

		$rows = [ [ 'k' => 'Luca', 'v' => mt_rand( 1, 100 ), 't' => time() ] ];

		$this->conn->insert( $dTable, $rows, __METHOD__ );
		$this->assertSame( 1, $this->conn->affectedRows() );
		$this->assertSame( 1, $this->conn->insertId() );

		$this->assertSame( 1, (int)$this->conn->selectField( $dTable, 'n', [ 'k' => 'Luca' ] ) );
		$this->assertSame( 1, $this->conn->affectedRows() );
		$this->assertSame( 0, $this->conn->insertId() );

		$this->dropDestTable();
	}

	public function testInsertIdAfterInsertIgnore() {
		$dTable = $this->createDestTable();

		$rows = [ [ 'k' => 'Luca', 'v' => mt_rand( 1, 100 ), 't' => time() ] ];

		$this->conn->insert( $dTable, $rows, __METHOD__, 'IGNORE' );
		$this->assertSame( 1, $this->conn->affectedRows() );
		$this->assertSame( 1, $this->conn->insertId() );
		$this->assertSame( 1, (int)$this->conn->selectField( $dTable, 'n', [ 'k' => 'Luca' ] ) );

		$this->conn->insert( $dTable, $rows, __METHOD__, 'IGNORE' );
		$this->assertSame( 0, $this->conn->affectedRows() );
		$this->assertSame( 0, $this->conn->insertId() );

		$this->assertSame( 1, (int)$this->conn->selectField( $dTable, 'n', [ 'k' => 'Luca' ] ) );
		$this->assertSame( 1, $this->conn->affectedRows() );
		$this->assertSame( 0, $this->conn->insertId() );

		$this->dropDestTable();
	}

	public function testInsertIdAfterReplace() {
		$dTable = $this->createDestTable();

		$rows = [ [ 'k' => 'Luca', 'v' => mt_rand( 1, 100 ), 't' => time() ] ];

		$this->conn->replace( $dTable, 'k', $rows, __METHOD__ );
		$this->assertSame( 1, $this->conn->affectedRows() );
		$this->assertSame( 1, $this->conn->insertId() );
		$this->assertSame( 1, (int)$this->conn->selectField( $dTable, 'n', [ 'k' => 'Luca' ] ) );

		$this->conn->replace( $dTable, 'k', $rows, __METHOD__ );
		$this->assertSame( 1, $this->conn->affectedRows() );
		$this->assertSame( 2, $this->conn->insertId() );

		$this->assertSame( 2, (int)$this->conn->selectField( $dTable, 'n', [ 'k' => 'Luca' ] ) );
		$this->assertSame( 1, $this->conn->affectedRows() );
		$this->assertSame( 0, $this->conn->insertId() );

		$this->dropDestTable();
	}

	public function testInsertIdAfterUpsert() {
		$dTable = $this->createDestTable();

		$rows = [ [ 'k' => 'Luca', 'v' => mt_rand( 1, 100 ), 't' => time() ] ];
		$set = [
			'v = ' . $this->conn->buildExcludedValue( 'v' ),
			't = ' . $this->conn->buildExcludedValue( 't' ) . ' + 1'
		];

		$this->conn->upsert( $dTable, $rows, 'k', $set, __METHOD__ );
		$this->assertSame( 1, $this->conn->affectedRows() );
		$this->assertSame( 1, $this->conn->insertId() );
		$this->assertSame( 1, (int)$this->conn->selectField( $dTable, 'n', [ 'k' => 'Luca' ] ) );

		$this->conn->upsert( $dTable, $rows, 'k', $set, __METHOD__ );
		$this->assertSame( 1, $this->conn->affectedRows() );
		$this->assertSame( 1, $this->conn->insertId() );

		$this->assertSame( 1, (int)$this->conn->selectField( $dTable, 'n', [ 'k' => 'Luca' ] ) );
		$this->assertSame( 1, $this->conn->affectedRows() );
		$this->assertSame( 0, $this->conn->insertId() );

		$this->dropDestTable();
	}

	public function testInsertIdAfterInsertSelect() {
		$sTable = $this->createSourceTable();
		$dTable = $this->createDestTable();

		$rows = [ [ 'sk' => 'Luca', 'sv' => mt_rand( 1, 100 ), 'st' => time() ] ];
		$this->conn->insert( $sTable, $rows, __METHOD__, 'IGNORE' );
		$this->assertSame( 1, $this->conn->affectedRows() );
		$this->assertSame( 1, $this->conn->insertId() );
		$this->assertSame( 1, (int)$this->conn->selectField( $sTable, 'sn', [ 'sk' => 'Luca' ] ) );

		$this->conn->insertSelect(
			$dTable,
			$sTable,
			[ 'k' => 'sk', 'v' => 'sv', 't' => 'st' ],
			[ 'sk' => 'Luca' ],
			__METHOD__,
			'IGNORE'
		);
		$this->assertSame( 1, $this->conn->affectedRows() );
		$this->assertSame( 1, $this->conn->insertId() );

		$this->assertSame( 1, (int)$this->conn->selectField( $dTable, 'n', [ 'k' => 'Luca' ] ) );
		$this->assertSame( 1, $this->conn->affectedRows() );
		$this->assertSame( 0, $this->conn->insertId() );

		$this->dropSourceTable();
		$this->dropDestTable();
	}

	public function testInsertIdAfterInsertSelectIgnore() {
		$sTable = $this->createSourceTable();
		$dTable = $this->createDestTable();

		$rows = [ [ 'sk' => 'Luca', 'sv' => mt_rand( 1, 100 ), 'st' => time() ] ];
		$this->conn->insert( $sTable, $rows, __METHOD__, 'IGNORE' );
		$this->assertSame( 1, $this->conn->affectedRows() );
		$this->assertSame( 1, $this->conn->insertId() );
		$this->assertSame( 1, (int)$this->conn->selectField( $sTable, 'sn', [ 'sk' => 'Luca' ] ) );

		$this->conn->insertSelect(
			$dTable,
			$sTable,
			[ 'k' => 'sk', 'v' => 'sv', 't' => 'st' ],
			[ 'sk' => 'Luca' ],
			__METHOD__,
			'IGNORE'
		);
		$this->assertSame( 1, $this->conn->affectedRows() );
		$this->assertSame( 1, $this->conn->insertId() );
		$this->assertSame( 1, (int)$this->conn->selectField( $dTable, 'n', [ 'k' => 'Luca' ] ) );

		$this->conn->insertSelect(
			$dTable,
			$sTable,
			[ 'k' => 'sk', 'v' => 'sv', 't' => 'st' ],
			[ 'sk' => 'Luca' ],
			__METHOD__,
			'IGNORE'
		);
		$this->assertSame( 0, $this->conn->affectedRows() );
		$this->assertSame( 0, $this->conn->insertId() );

		$this->assertSame( 1, (int)$this->conn->selectField( $dTable, 'n', [ 'k' => 'Luca' ] ) );
		$this->assertSame( 1, $this->conn->affectedRows() );
		$this->assertSame( 0, $this->conn->insertId() );

		$this->dropSourceTable();
		$this->dropDestTable();
	}

	/**
	 * @coversNothing
	 */
	public function testAffectedRowsAfterUpdateIgnore() {
		$sTable = $this->createSourceTable();

		$rows = [ [ 'sk' => 'Luca', 'sv' => mt_rand( 1, 100 ), 'st' => time() ],
			[ 'sk' => 'Test', 'sv' => mt_rand( 1, 100 ), 'st' => time() ] ];
		$this->conn->insert( $sTable, $rows, __METHOD__, 'IGNORE' );
		$this->assertSame( 2, $this->conn->affectedRows(), 'Test inserted' );

		// Test changing something
		$this->conn->update(
			$sTable,
			[ 'sk' => 'TestRow' ],
			[ 'sk' => 'Test' ],
			__METHOD__,
			[ 'IGNORE' ]
		);
		$this->assertSame( 1, $this->conn->affectedRows(), 'Updated' );

		// Test changing nothing
		$this->conn->update(
			$sTable,
			[ 'sk' => 'TestRow' ],
			[ 'sk' => 'TestRow' ],
			__METHOD__,
			[ 'IGNORE' ]
		);
		$this->assertSame( 1, $this->conn->affectedRows(), 'Nothing changed' );

		// Test nothing found
		$this->conn->update(
			$sTable,
			[ 'sk' => 'TestExistingRow' ],
			[ 'sk' => 'TestNonexistingRow' ],
			__METHOD__,
			[ 'IGNORE' ]
		);
		$this->assertSame( 0, $this->conn->affectedRows(), 'Not found' );

		// Test key conflict on the unique sk field
		$this->conn->update(
			$sTable,
			[ 'sk' => 'TestRow' ],
			[ 'sk' => 'Luca' ],
			__METHOD__,
			[ 'IGNORE' ]
		);
		$this->assertSame( 1, $this->conn->affectedRows(), 'Key conflict, nothing changed on database' );
	}

	public function testFieldAndIndexInfo() {
		global $wgDBname;

		$this->conn->selectDomain( $wgDBname );
		$this->conn->query(
			"CREATE TEMPORARY TABLE tmp_schema_tbl (" .
			"n integer not null auto_increment, " .
			"k varchar(255), " .
			"v integer, " .
			"t integer," .
			"PRIMARY KEY (n)," .
			"UNIQUE INDEX k (k)," .
			"INDEX t (t)" .
			")"
		);

		$this->assertTrue( $this->conn->fieldExists( 'tmp_schema_tbl', 'n' ) );
		$this->assertTrue( $this->conn->fieldExists( 'tmp_schema_tbl', 'k' ) );
		$this->assertTrue( $this->conn->fieldExists( 'tmp_schema_tbl', 'v' ) );
		$this->assertTrue( $this->conn->fieldExists( 'tmp_schema_tbl', 't' ) );
		$this->assertFalse( $this->conn->fieldExists( 'tmp_schema_tbl', 'x' ) );

		$this->assertTrue( $this->conn->indexExists( 'tmp_schema_tbl', 'k' ) );
		$this->assertTrue( $this->conn->indexExists( 'tmp_schema_tbl', 't' ) );
		$this->assertFalse( $this->conn->indexExists( 'tmp_schema_tbl', 'x' ) );
		$this->assertTrue( $this->conn->indexExists( 'tmp_schema_tbl', 'PRIMARY' ) );

		$this->assertTrue( $this->conn->indexUnique( 'tmp_schema_tbl', 'k' ) );
		$this->assertFalse( $this->conn->indexUnique( 'tmp_schema_tbl', 't' ) );
		$this->assertNull( $this->conn->indexUnique( 'tmp_schema_tbl', 'x' ) );
		$this->assertTrue( $this->conn->indexUnique( 'tmp_schema_tbl', 'PRIMARY' ) );
	}

	private function createSourceTable() {
		global $wgDBname;

		$this->conn->query( "DROP TABLE IF EXISTS `$wgDBname`.`tmp_src_tbl`" );
		$this->conn->query(
			"CREATE TEMPORARY TABLE `$wgDBname`.`tmp_src_tbl` (" .
			"sn integer not null unique key auto_increment, " .
			"sk varchar(255) unique, " .
			"sv integer, " .
			"st integer" .
			")"
		);

		return "$wgDBname.tmp_src_tbl";
	}

	private function createDestTable() {
		global $wgDBname;

		$this->conn->query( "DROP TABLE IF EXISTS `$wgDBname`.`tmp_dst_tbl`" );
		$this->conn->query(
			"CREATE TEMPORARY TABLE `$wgDBname`.`tmp_dst_tbl` (" .
			"n integer not null unique key auto_increment, " .
			"k varchar(255) unique, " .
			"v integer, " .
			"t integer" .
			")"
		);

		return "$wgDBname.tmp_dst_tbl";
	}

	private function dropSourceTable() {
		global $wgDBname;

		$this->conn->query( "DROP TEMPORARY TABLE IF EXISTS `$wgDBname`.`tmp_src_tbl`" );
	}

	private function dropDestTable() {
		global $wgDBname;

		$this->conn->query( "DROP TEMPORARY TABLE IF EXISTS `$wgDBname`.`tmp_dst_tbl`" );
	}

	/**
	 * Insert a null value into a field that is not nullable using INSERT IGNORE
	 */
	public function testInsertIgnoreNull() {
		$this->expectException( DBQueryError::class );
		$this->conn->newInsertQueryBuilder()
			->insertInto( 'log_search' )
			->ignore()
			->row( [ 'ls_field' => 'test', 'ls_value' => null, 'ls_log_id' => 1 ] )
			->execute();
	}

	/**
	 * @covers \Wikimedia\Rdbms\DatabaseMySQL::query
	 * @covers \Wikimedia\Rdbms\DatabaseMySQL::dropTable
	 */
	public function testTempTableDomains() {
		global $wgDBname;

		$table = 'temp_test_tbl';

		$this->conn->selectDomain( $wgDBname . '-xxx1_' );
		$this->assertFalse( $this->conn->tableExists( $table ) );

		$query = new Query(
			"CREATE TEMPORARY TABLE " . $this->conn->tableName( $table ) .
			" (n integer not null unique key)",
			ISQLPlatform::QUERY_CHANGE_SCHEMA,
			"CREATE TEMPORARY",
			$table
		);
		$this->conn->query( $query, __METHOD__ );
		$this->assertTrue( $this->conn->tableExists( $table ) );

		$this->conn->selectDomain( $wgDBname . '-xxx2_' );
		$this->assertFalse( $this->conn->tableExists( $table ) );

		$query = new Query(
			"CREATE TEMPORARY TABLE " . $this->conn->tableName( $table ) .
			" (n integer not null unique key)",
			ISQLPlatform::QUERY_CHANGE_SCHEMA,
			"CREATE TEMPORARY",
			$table
		);
		$this->conn->query( $query, __METHOD__ );
		$this->assertTrue( $this->conn->tableExists( $table ) );

		$this->conn->selectDomain( $wgDBname . '-xxx1_' );
		$this->conn->dropTable( $table );
		$this->assertFalse( $this->conn->tableExists( $table ) );

		$this->conn->selectDomain( $wgDBname . '-xxx2_' );
		$this->conn->dropTable( $table );
		$this->assertFalse( $this->conn->tableExists( $table ) );
	}

	/**
	 * @covers \Wikimedia\Rdbms\DatabaseMySQL::query
	 * @covers \Wikimedia\Rdbms\DatabaseMySQL::dropTable
	 */
	public function testTempTableDomainsTableWithDot() {
		global $wgDBname;

		$table = '.temp_test_tbl';

		$this->conn->selectDomain( $wgDBname . '-xxx1_' );
		$this->assertFalse( $this->conn->tableExists( $table ) );

		$query = new Query(
			"CREATE TEMPORARY TABLE " . $this->conn->tableName( $table ) .
			" (n integer not null unique key)",
			ISQLPlatform::QUERY_CHANGE_SCHEMA,
			"CREATE TEMPORARY",
			$table
		);
		$this->conn->query( $query, __METHOD__ );
		$this->assertTrue( $this->conn->tableExists( $table ) );

		$this->conn->selectDomain( $wgDBname . '-xxx2_' );
		$this->assertTrue( $this->conn->tableExists( $table ) );

		$this->conn->dropTable( $table );
		$this->assertFalse( $this->conn->tableExists( $table ) );

		$this->conn->selectDomain( $wgDBname . '-xxx1_' );
		$this->assertFalse( $this->conn->tableExists( $table ) );
	}
}
