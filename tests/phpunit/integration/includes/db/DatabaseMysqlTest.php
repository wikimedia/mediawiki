<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DBQueryDisconnectedError;
use Wikimedia\Rdbms\DBQueryTimeoutError;
use Wikimedia\Rdbms\DBSessionStateError;
use Wikimedia\Rdbms\DBTransactionStateError;
use Wikimedia\Rdbms\DBUnexpectedError;
use Wikimedia\Rdbms\TransactionManager;

/**
 * @group mysql
 * @group Database
 * @group medium
 */
class DatabaseMysqlTest extends \MediaWikiIntegrationTestCase {
	/** @var DatabaseMysqlBase */
	protected $conn;

	protected function setUp(): void {
		parent::setUp();

		if ( !extension_loaded( 'mysqli' ) ) {
			$this->markTestSkipped( 'No MySQL support detected' );
		}

		$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
		if ( $lb->getServerType( 0 ) !== 'mysql' ) {
			$this->markTestSkipped( 'No MySQL $wgLBFactoryConf config detected' );
		}

		$this->conn = $this->newConnection();
	}

	/**
	 * @covers Database::query()
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
	 * @covers Database::query()
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
	 * @covers Database::query()
	 * @covers Database::rollback()
	 * @covers Database::flushSession()
	 */
	public function testConnectionLoss() {
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

		$this->conn->lock( 'session_lock', __METHOD__, 0 );
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
	 * @covers Database::query()
	 * @covers Database::cancelAtomic()
	 * @covers Database::rollback()
	 * @covers Database::flushSession()
	 */
	public function testTransactionError() {
		$this->assertSame( TransactionManager::STATUS_TRX_NONE, $this->conn->trxStatus() );

		$this->conn->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->assertSame( TransactionManager::STATUS_TRX_OK, $this->conn->trxStatus() );
		$row = $this->conn->query( 'SELECT connection_id() AS id', __METHOD__ )->fetchObject();
		$encId = intval( $row->id );

		try {
			$this->conn->lock( 'trx_lock', __METHOD__, 0 );
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
	 * @return DatabaseMysqlBase
	 */
	private function newConnection() {
		$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
		/** @var DatabaseMysqlBase $conn */
		$conn = Database::factory(
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

	public function tearDown(): void {
		$this->conn->close( __METHOD__ );

		parent::tearDown();
	}
}
