<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\DatabaseMySQL;
use Wikimedia\Rdbms\DBQueryDisconnectedError;
use Wikimedia\Rdbms\DBQueryError;
use Wikimedia\Rdbms\DBQueryTimeoutError;
use Wikimedia\Rdbms\DBSessionStateError;
use Wikimedia\Rdbms\DBTransactionStateError;
use Wikimedia\Rdbms\DBUnexpectedError;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\TransactionManager;

/**
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
	}

	protected function tearDown(): void {
		$this->conn->close( __METHOD__ );

		parent::tearDown();
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database::query()
	 */
	public function testQueryTimeout() {
		try {
			$this->conn->query(
				'SET STATEMENT max_statement_time=0.001 FOR SELECT sleep(1) FROM dual',
				__METHOD__
			);
			$this->fail( "No DBQueryTimeoutError caught" );
		} catch ( DBQueryTimeoutError $e ) {
			$this->assertInstanceOf( DBQueryTimeoutError::class, $e );
		}

		$row = $this->conn->query( 'SELECT "x" AS v', __METHOD__ )->fetchObject();
		$this->assertSame( 'x', $row->v, "Still connected/usable" );
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database::query()
	 */
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

	/**
	 * @covers \Wikimedia\Rdbms\Database::query()
	 * @covers \Wikimedia\Rdbms\Database::rollback()
	 * @covers \Wikimedia\Rdbms\Database::flushSession()
	 */
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

	/**
	 * @covers \Wikimedia\Rdbms\Database::getScopedLockAndFlush()
	 */
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

	/**
	 * @covers \Wikimedia\Rdbms\Database::query()
	 * @covers \Wikimedia\Rdbms\Database::cancelAtomic()
	 * @covers \Wikimedia\Rdbms\Database::rollback()
	 * @covers \Wikimedia\Rdbms\Database::flushSession()
	 */
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

	/**
	 * @return DatabaseMySQL
	 */
	private function newConnection() {
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

	/**
	 * @covers \Wikimedia\Rdbms\Database::insert()
	 * @covers \Wikimedia\Rdbms\Database::insertId()
	 */
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

	/**
	 * @covers \Wikimedia\Rdbms\Database::insert()
	 * @covers \Wikimedia\Rdbms\Database::insertId()
	 */
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

	/**
	 * @covers \Wikimedia\Rdbms\Database::replace()
	 * @covers \Wikimedia\Rdbms\Database::insertId()
	 */
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

	/**
	 * @covers \Wikimedia\Rdbms\Database::upsert()
	 * @covers \Wikimedia\Rdbms\Database::insertId()
	 */
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

	/**
	 * @covers \Wikimedia\Rdbms\Database::insertSelect()
	 * @covers \Wikimedia\Rdbms\Database::insertId()
	 */
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

	/**
	 * @covers \Wikimedia\Rdbms\Database::insertSelect()
	 * @covers \Wikimedia\Rdbms\Database::insertId()
	 */
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
}
