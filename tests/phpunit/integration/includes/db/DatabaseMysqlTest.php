<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DatabaseMysqlBase;
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
	/** @var DatabaseMysqlBase */
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
	 * @covers \Wikimedia\Rdbms\Database::queryMulti()
	 * @covers \Wikimedia\Rdbms\Database::rollback()
	 * @covers \Wikimedia\Rdbms\Database::flushSession()
	 */
	public function testConnectionLossQueryMulti() {
		$row = $this->conn->query( 'SELECT connection_id() AS id', __METHOD__ )->fetchObject();
		$encId = intval( $row->id );

		$adminConn = $this->newConnection();
		$adminConn->query( "KILL $encId", __METHOD__ );

		$qsById = $this->conn->queryMulti(
			[ 'SELECT "x" AS v', 'SELECT "y" AS v', 'SELECT "z" AS v' ],
			__METHOD__
		);
		$row1 = $qsById[0]->res->fetchObject();
		$row2 = $qsById[1]->res->fetchObject();
		$row3 = $qsById[2]->res->fetchObject();
		$this->assertSame( 'x', $row1->v, "Recovered" );
		$this->assertSame( 'y', $row2->v, "Recovered" );
		$this->assertSame( 'z', $row3->v, "Recovered" );

		$this->conn->startAtomic( __METHOD__ );
		$this->assertSame( 1, $this->conn->trxLevel(), "Transaction exists" );
		$row = $this->conn->query( 'SELECT connection_id() AS id', __METHOD__ )->fetchObject();
		$encId = intval( $row->id );

		$adminConn->query( "KILL $encId", __METHOD__ );
		try {
			$this->conn->queryMulti( [ 'SELECT 1', 'SELECT 2' ], __METHOD__ );
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
				$this->conn->queryMulti( [ 'SELECT "x" AS w', 'SELECT "y" AS w' ], __METHOD__ );
				$this->fail( "No DBTransactionStateError caught" );
			} catch ( DBTransactionStateError $e ) {
				$this->assertInstanceOf( DBTransactionStateError::class, $e );
			}

			$this->assertSame( 0, $this->conn->trxLevel(), "Transaction lost" );
			$this->conn->rollback( __METHOD__ );
			$this->assertSame( 0, $this->conn->trxLevel(), "No transaction" );

			$qsById = $this->conn->queryMulti( [ 'SELECT "x" AS z', 'SELECT "y" AS z' ], __METHOD__ );
			$row1 = $qsById[0]->res->fetchObject();
			$row2 = $qsById[1]->res->fetchObject();
			$this->assertSame( 'x', $row1->z, "Recovered" );
			$this->assertSame( 'y', $row2->z, "Recovered" );
		}

		$this->conn->lock( 'session_lock_' . mt_rand(), __METHOD__, 0 );
		$row = $this->conn->query( 'SELECT connection_id() AS id', __METHOD__ )->fetchObject();
		$encId = intval( $row->id );
		$adminConn->query( "KILL $encId", __METHOD__ );
		try {
			$this->conn->queryMulti( [ 'SELECT 1', 'SELECT 2' ], __METHOD__ );
			$this->fail( "No DBQueryDisconnectedError caught" );
		} catch ( DBQueryDisconnectedError $e ) {
			$this->assertInstanceOf( DBQueryDisconnectedError::class, $e );
		}

		$this->assertTrue( $this->conn->isOpen(), "Reconnected" );
		try {
			$this->conn->queryMulti( [ 'SELECT "x" AS z', 'SELECT "y" AS z' ], __METHOD__ );
			$this->fail( "No DBSessionStateError caught" );
		} catch ( DBSessionStateError $e ) {
			$this->assertInstanceOf( DBSessionStateError::class, $e );
		}

		$this->assertTrue( $this->conn->isOpen(), "Connection remains" );
		$this->conn->rollback( __METHOD__ );
		$this->conn->flushSession( __METHOD__ );

		$qsById = $this->conn->queryMulti( [ 'SELECT "x" AS z', 'SELECT "y" AS z' ], __METHOD__ );
		$row1 = $qsById[0]->res->fetchObject();
		$row2 = $qsById[1]->res->fetchObject();
		$this->assertSame( 'x', $row1->z, "Recovered" );
		$this->assertSame( 'y', $row2->z, "Recovered" );

		$adminConn->close( __METHOD__ );
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
	 * @return DatabaseMysqlBase
	 */
	private function newConnection() {
		$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
		$dbFactory = MediaWikiServices::getInstance()->getDatabaseFactory();
		/** @var DatabaseMysqlBase $conn */
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
	 * @dataProvider provideQueryMulti
	 * @covers Wikimedia\Rdbms\Database::queryMulti
	 */
	public function testQueryMulti(
		array $sqls,
		int $flags,
		string $summarySql,
		?array $resMap,
		?array $exception
	) {
		$row = $this->conn->query( "SELECT 3 as test", __METHOD__ )->fetchObject();
		$this->assertNotFalse( $row );
		$this->assertSame( '3', $row->test );

		if ( is_array( $resMap ) ) {
			$qsMap = $this->conn->queryMulti( $sqls, __METHOD__, $flags, $summarySql );

			reset( $resMap );
			foreach ( $qsMap as $qs ) {
				if ( is_iterable( $qs->res ) ) {
					$this->assertArrayEquals( current( $resMap ), iterator_to_array( $qs->res ) );
				} else {
					$this->assertSame( current( $resMap ), $qs->res );
				}
				next( $resMap );
			}
		} else {
			[ $class, $message, $code ] = $exception;

			try {
				$this->conn->queryMulti( $sqls, __METHOD__, $flags, $summarySql );
				$this->fail( "No DBError thrown" );
			} catch ( DBError $e ) {
				$this->assertInstanceOf( $class, $e );
				$this->assertStringContainsString( $message, $e->getMessage() );
				$this->assertSame( $code, $e->errno );
			}
		}
	}

	public static function provideQueryMulti() {
		return [
			[
				[
					'SELECT 1 AS v, 2 AS x',
					'(SELECT 1 AS v) UNION ALL (SELECT 2 AS v) UNION ALL (SELECT 3 AS v)',
					'SELECT IS_FREE_LOCK("unused_lock") AS free',
					'SELECT RELEASE_LOCK("unused_lock") AS released'
				],
				Database::QUERY_IGNORE_DBO_TRX,
				'COMPOSITE QUERY 1',
				[
					[
						(object)[ 'v' => '1', 'x' => '2' ]
					],
					[
						(object)[ 'v' => '1' ],
						(object)[ 'v' => '2' ],
						(object)[ 'v' => '3' ]
					],
					[
						(object)[ 'free' => '1' ]
					],
					[
						(object)[ 'released' => null ]
					]
				],
				null
			],
			[
				[
					'SELECT 1 AS v, 2 AS x',
					'SELECT UNION PARSE_ERROR ()',
					'SELECT IS_FREE_LOCK("unused_lock") AS free',
					'SELECT RELEASE_LOCK("unused_lock") AS released'
				],
				0,
				'COMPOSITE QUERY 2',
				null,
				[ DBQueryError::class, 'You have an error in your SQL syntax', 1064 ]
			],
			[
				[
					'SELECT UNION PARSE_ERROR ()',
					'SELECT 1 AS v, 2 AS x',
					'SELECT IS_FREE_LOCK("unused_lock") AS free',
					'SELECT RELEASE_LOCK("unused_lock") AS released'
				],
				0,
				'COMPOSITE QUERY 3',
				null,
				[ DBQueryError::class, 'You have an error in your SQL syntax', 1064 ]
			],
			[
				[
					'SELECT 1 AS v, 2 AS x',
					'SELECT IS_FREE_LOCK("unused_lock") AS free',
					'SELECT RELEASE_LOCK("unused_lock") AS released',
					'SELECT UNION PARSE_ERROR ()',
				],
				0,
				'COMPOSITE QUERY 4',
				null,
				[ DBQueryError::class, 'You have an error in your SQL syntax', 1064 ]
			],
			[
				[],
				0,
				'COMPOSITE QUERY 5',
				[],
				null
			],
			[
				[
					'SELECT 1 AS v, 2 AS x',
					'SELECT UNION PARSE_ERROR ()',
					'SELECT IS_FREE_LOCK("unused_lock") AS free',
					'SELECT RELEASE_LOCK("unused_lock") AS released'
				],
				Database::QUERY_SILENCE_ERRORS,
				'COMPOSITE QUERY 2I',
				[
					[
						(object)[ 'v' => '1', 'x' => '2' ]
					],
					false,
					false,
					false
				],
				null
			],
			[
				[
					'SELECT UNION PARSE_ERROR IGNORE ()',
					'SELECT 1 AS v, 2 AS x',
					'SELECT IS_FREE_LOCK("unused_lock") AS free',
					'SELECT RELEASE_LOCK("unused_lock") AS released'
				],
				Database::QUERY_SILENCE_ERRORS,
				'COMPOSITE QUERY 3I',
				[
					false,
					false,
					false,
					false
				],
				null
			],
			[
				[
					'SELECT 1 AS v, 2 AS x',
					'SELECT IS_FREE_LOCK("unused_lock") AS free',
					'SELECT RELEASE_LOCK("unused_lock") AS released',
					'SELECT UNION PARSE_ERROR ()',
				],
				Database::QUERY_SILENCE_ERRORS,
				'COMPOSITE QUERY 4I',
				[
					[
						(object)[ 'v' => '1', 'x' => '2' ]
					],
					[
						(object)[ 'free' => '1' ]
					],
					[
						(object)[ 'released' => null ]
					],
					false
				],
				null
			],
			[
				[],
				Database::QUERY_SILENCE_ERRORS,
				'COMPOSITE QUERY 5I',
				[],
				null
			]
		];
	}

}
