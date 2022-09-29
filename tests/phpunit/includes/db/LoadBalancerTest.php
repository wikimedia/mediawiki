<?php

/**
 * Holds tests for LoadBalancer MediaWiki class.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */
use PHPUnit\Framework\Constraint\StringContains;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\DBConnRef;
use Wikimedia\Rdbms\DBError;
use Wikimedia\Rdbms\DBReadOnlyRoleError;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IMaintainableDatabase;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\Rdbms\LoadMonitorNull;
use Wikimedia\Rdbms\TransactionManager;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 * @group medium
 * @covers \Wikimedia\Rdbms\LoadBalancer
 */
class LoadBalancerTest extends MediaWikiIntegrationTestCase {
	private function makeServerConfig( $flags = DBO_DEFAULT ) {
		global $wgDBserver, $wgDBname, $wgDBuser, $wgDBpassword, $wgDBtype, $wgSQLiteDataDir;

		return [
			'host' => $wgDBserver,
			'serverName' => 'testhost',
			'dbname' => $wgDBname,
			'tablePrefix' => $this->dbPrefix(),
			'user' => $wgDBuser,
			'password' => $wgDBpassword,
			'type' => $wgDBtype,
			'dbDirectory' => $wgSQLiteDataDir,
			'load' => 0,
			'flags' => $flags
		];
	}

	/**
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getConnection()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getLocalDomainID()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::resolveDomainID()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::haveIndex()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::isNonZeroLoad()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::setDomainAliases()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getClusterName()
	 */
	public function testWithoutReplica() {
		global $wgDBname;

		$called = false;
		$lb = new LoadBalancer( [
			// Simulate web request with DBO_TRX
			'servers' => [ $this->makeServerConfig( DBO_TRX ) ],
			'queryLogger' => MediaWiki\Logger\LoggerFactory::getInstance( 'DBQuery' ),
			'localDomain' => new DatabaseDomain( $wgDBname, null, $this->dbPrefix() ),
			'chronologyCallback' => static function () use ( &$called ) {
				$called = true;
			},
			'clusterName' => 'xyz'
		] );

		$this->assertSame( 1, $lb->getServerCount() );
		$this->assertFalse( $lb->hasReplicaServers() );
		$this->assertFalse( $lb->hasStreamingReplicaServers() );
		$this->assertSame( 'xyz', $lb->getClusterName() );

		$this->assertTrue( $lb->haveIndex( 0 ) );
		$this->assertFalse( $lb->haveIndex( 1 ) );
		$this->assertFalse( $lb->isNonZeroLoad( 0 ) );
		$this->assertFalse( $lb->isNonZeroLoad( 1 ) );

		$ld = DatabaseDomain::newFromId( $lb->getLocalDomainID() );
		$this->assertSame( $wgDBname, $ld->getDatabase(), 'local domain DB set' );
		$this->assertSame( $this->dbPrefix(), $ld->getTablePrefix(), 'local domain prefix set' );
		$this->assertSame( 'my_test_wiki', $lb->resolveDomainID( 'my_test_wiki' ) );
		$this->assertSame( $ld->getId(), $lb->resolveDomainID( false ) );
		$this->assertSame( $ld->getId(), $lb->resolveDomainID( $ld ) );
		$this->assertFalse( $called );

		$dbw = $lb->getConnection( DB_PRIMARY );
		$dbw->getServerName();
		$this->assertFalse( $called, "getServerName() optimized for DB_PRIMARY" );

		$dbw->ensureConnection();
		$this->assertTrue( $called );
		$this->assertSame(
			$dbw::ROLE_STREAMING_MASTER, $dbw->getTopologyRole(), 'master shows as master'
		);
		$this->assertTrue( $dbw->getFlag( $dbw::DBO_TRX ), "DBO_TRX set on master" );
		$this->assertWriteAllowed( $dbw );

		$dbr = $lb->getConnection( DB_REPLICA );
		$this->assertSame(
			$dbr::ROLE_STREAMING_MASTER, $dbr->getTopologyRole(), 'DB_REPLICA also gets the master' );
		$this->assertTrue( $dbr->getFlag( $dbw::DBO_TRX ), "DBO_TRX set on replica" );

		if ( !$lb->getServerAttributes( $lb->getWriterIndex() )[Database::ATTR_DB_LEVEL_LOCKING] ) {
			$dbwAC1 = $lb->getConnection( DB_PRIMARY, [], false, $lb::CONN_TRX_AUTOCOMMIT );
			$this->assertFalse(
				$dbwAC1->getFlag( $dbw::DBO_TRX ),
				"No DBO_TRX with CONN_TRX_AUTOCOMMIT"
			);
			$this->assertTrue( $dbw->getFlag( $dbw::DBO_TRX ), "DBO_TRX still set on master" );
			$this->assertUnsharedHandle( $dbw, $dbwAC1, "CONN_TRX_AUTOCOMMIT separate connection" );

			$dbrAC1 = $lb->getConnection( DB_REPLICA, [], false, $lb::CONN_TRX_AUTOCOMMIT );
			$this->assertFalse(
				$dbrAC1->getFlag( $dbw::DBO_TRX ),
				"No DBO_TRX with CONN_TRX_AUTOCOMMIT"
			);
			$this->assertTrue( $dbr->getFlag( $dbw::DBO_TRX ), "DBO_TRX still set on replica" );
			$this->assertUnsharedHandle( $dbr, $dbrAC1, "CONN_TRX_AUTOCOMMIT separate connection" );

			$dbwAC2 = $lb->getConnection( DB_PRIMARY, [], false, $lb::CONN_TRX_AUTOCOMMIT );
			$dbwAC2->ensureConnection();
			$this->assertSharedHandle( $dbwAC2, $dbwAC1, "CONN_TRX_AUTOCOMMIT reuses connections" );

			$dbrAC2 = $lb->getConnection( DB_REPLICA, [], false, $lb::CONN_TRX_AUTOCOMMIT );
			$dbrAC2->ensureConnection();
			$this->assertSharedHandle( $dbrAC2, $dbrAC1, "CONN_TRX_AUTOCOMMIT reuses connections" );
		}

		$lb->closeAll( __METHOD__ );
	}

	/**
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getConnection()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getReaderIndex()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getWriterIndex()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::haveIndex()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::isNonZeroLoad()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getServerName()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getServerInfo()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getServerType()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getServerAttributes()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getClusterName()
	 */
	public function testWithReplica() {
		global $wgDBserver;

		// Simulate web request with DBO_TRX
		$lb = $this->newMultiServerLocalLoadBalancer( [], [ 'flags' => DBO_TRX ] );

		$this->assertSame( 8, $lb->getServerCount() );
		$this->assertTrue( $lb->hasReplicaServers() );
		$this->assertTrue( $lb->hasStreamingReplicaServers() );
		$this->assertSame( 'main-test-cluster', $lb->getClusterName() );

		$this->assertTrue( $lb->haveIndex( 0 ) );
		$this->assertTrue( $lb->haveIndex( 1 ) );
		$this->assertFalse( $lb->isNonZeroLoad( 0 ) );
		$this->assertTrue( $lb->isNonZeroLoad( 1 ) );

		for ( $i = 0; $i < $lb->getServerCount(); ++$i ) {
			$this->assertIsString( $lb->getServerName( $i ) );
			$this->assertIsArray( $lb->getServerInfo( $i ) );
			$this->assertIsString( $lb->getServerType( $i ) );
			$this->assertIsArray( $lb->getServerAttributes( $i ) );
		}

		$dbw = $lb->getConnection( DB_PRIMARY );
		$dbw->ensureConnection();
		$wConn = TestingAccessWrapper::newFromObject( $dbw )->conn;
		$wConnWrap = TestingAccessWrapper::newFromObject( $wConn );

		$this->assertSame(
			$dbw::ROLE_STREAMING_MASTER, $dbw->getTopologyRole(), 'primary shows as primary' );
		$this->assertSame(
			( $wgDBserver != '' ) ? $wgDBserver : 'localhost',
			$wConnWrap->topologicalPrimaryConnRef->getServerName(),
			'cluster primary is set'
		);
		$this->assertTrue( $dbw->getFlag( $dbw::DBO_TRX ), "DBO_TRX set on primary" );
		$this->assertWriteAllowed( $dbw );

		$dbr = $lb->getConnection( DB_REPLICA );
		$dbr->ensureConnection();
		$rConn = TestingAccessWrapper::newFromObject( $dbr )->conn;
		$rConnWrap = TestingAccessWrapper::newFromObject( $rConn );

		$this->assertSame(
			$dbr::ROLE_STREAMING_REPLICA, $dbr->getTopologyRole(), 'replica shows as replica' );
		$this->assertTrue( $dbr->isReadOnly(), 'replica shows as replica' );
		$this->assertSame(
			( $wgDBserver != '' ) ? $wgDBserver : 'localhost',
			$rConnWrap->topologicalPrimaryConnRef->getServerName(),
			'cluster master is set'
		);
		$this->assertTrue( $dbr->getFlag( $dbw::DBO_TRX ), "DBO_TRX set on replica" );
		$this->assertSame( $dbr->getLBInfo( 'serverIndex' ), $lb->getReaderIndex() );

		if ( !$lb->getServerAttributes( $lb->getWriterIndex() )[Database::ATTR_DB_LEVEL_LOCKING] ) {
			$dbwAC1 = $lb->getConnection( DB_PRIMARY, [], false, $lb::CONN_TRX_AUTOCOMMIT );
			$this->assertFalse(
				$dbwAC1->getFlag( $dbw::DBO_TRX ),
				"No DBO_TRX with CONN_TRX_AUTOCOMMIT"
			);
			$this->assertTrue( $dbw->getFlag( $dbw::DBO_TRX ), "DBO_TRX still set on master" );
			$this->assertUnsharedHandle( $dbw, $dbwAC1, "CONN_TRX_AUTOCOMMIT separate connection" );

			$dbrAC1 = $lb->getConnection( DB_REPLICA, [], false, $lb::CONN_TRX_AUTOCOMMIT );
			$this->assertFalse(
				$dbrAC1->getFlag( $dbw::DBO_TRX ),
				"No DBO_TRX with CONN_TRX_AUTOCOMMIT"
			);
			$this->assertTrue( $dbr->getFlag( $dbw::DBO_TRX ), "DBO_TRX still set on replica" );
			$this->assertUnsharedHandle( $dbr, $dbrAC1, "CONN_TRX_AUTOCOMMIT separate connection" );

			$dbwAC2 = $lb->getConnection( DB_PRIMARY, [], false, $lb::CONN_TRX_AUTOCOMMIT );
			$dbwAC2->ensureConnection();
			$this->assertSharedHandle( $dbwAC2, $dbwAC1, "CONN_TRX_AUTOCOMMIT reuses connections" );

			$dbrAC2 = $lb->getConnection( DB_REPLICA, [], false, $lb::CONN_TRX_AUTOCOMMIT );
			$dbrAC2->ensureConnection();
			$this->assertSharedHandle( $dbrAC2, $dbrAC1, "CONN_TRX_AUTOCOMMIT reuses connections" );
		}

		$lb->closeAll( __METHOD__ );
	}

	private function assertSharedHandle( DBConnRef $connRef1, DBConnRef $connRef2, $msg ) {
		$connRef1Wrap = TestingAccessWrapper::newFromObject( $connRef1 );
		$connRef2Wrap = TestingAccessWrapper::newFromObject( $connRef2 );
		$this->assertSame( $connRef1Wrap->conn, $connRef2Wrap->conn, $msg );
	}

	private function assertUnsharedHandle( DBConnRef $connRef1, DBConnRef $connRef2, $msg ) {
		$connRef1Wrap = TestingAccessWrapper::newFromObject( $connRef1 );
		$connRef2Wrap = TestingAccessWrapper::newFromObject( $connRef2 );
		$this->assertNotSame( $connRef1Wrap->conn, $connRef2Wrap->conn, $msg );
	}

	private function newSingleServerLocalLoadBalancer() {
		global $wgDBname;

		return new LoadBalancer( [
			'servers' => [ $this->makeServerConfig() ],
			'localDomain' => new DatabaseDomain( $wgDBname, null, $this->dbPrefix() )
		] );
	}

	private function newMultiServerLocalLoadBalancer(
		$lbExtra = [], $srvExtra = [], $masterOnly = false
	) {
		global $wgDBserver, $wgDBname, $wgDBuser, $wgDBpassword, $wgDBtype, $wgSQLiteDataDir;

		$servers = [
			// Primary DB
			0 => $srvExtra + [
					'host' => $wgDBserver,
					'dbname' => $wgDBname,
					'tablePrefix' => $this->dbPrefix(),
					'user' => $wgDBuser,
					'password' => $wgDBpassword,
					'type' => $wgDBtype,
					'dbDirectory' => $wgSQLiteDataDir,
					'load' => $masterOnly ? 100 : 0,
				],
			// Main replica DBs
			1 => $srvExtra + [
					'host' => $wgDBserver,
					'dbname' => $wgDBname,
					'tablePrefix' => $this->dbPrefix(),
					'user' => $wgDBuser,
					'password' => $wgDBpassword,
					'type' => $wgDBtype,
					'dbDirectory' => $wgSQLiteDataDir,
					'load' => $masterOnly ? 0 : 100,
				],
			2 => $srvExtra + [
					'host' => $wgDBserver,
					'dbname' => $wgDBname,
					'tablePrefix' => $this->dbPrefix(),
					'user' => $wgDBuser,
					'password' => $wgDBpassword,
					'type' => $wgDBtype,
					'dbDirectory' => $wgSQLiteDataDir,
					'load' => $masterOnly ? 0 : 100,
				],
			// RC replica DBs
			3 => $srvExtra + [
					'host' => $wgDBserver,
					'dbname' => $wgDBname,
					'tablePrefix' => $this->dbPrefix(),
					'user' => $wgDBuser,
					'password' => $wgDBpassword,
					'type' => $wgDBtype,
					'dbDirectory' => $wgSQLiteDataDir,
					'load' => 0,
					'groupLoads' => [
						'foo' => 100,
						'bar' => 100
					],
				],
			// Logging replica DBs
			4 => $srvExtra + [
					'host' => $wgDBserver,
					'dbname' => $wgDBname,
					'tablePrefix' => $this->dbPrefix(),
					'user' => $wgDBuser,
					'password' => $wgDBpassword,
					'type' => $wgDBtype,
					'dbDirectory' => $wgSQLiteDataDir,
					'load' => 0,
					'groupLoads' => [
						'baz' => 100
					],
				],
			5 => $srvExtra + [
					'host' => $wgDBserver,
					'dbname' => $wgDBname,
					'tablePrefix' => $this->dbPrefix(),
					'user' => $wgDBuser,
					'password' => $wgDBpassword,
					'type' => $wgDBtype,
					'dbDirectory' => $wgSQLiteDataDir,
					'load' => 0,
					'groupLoads' => [
						'baz' => 100
					],
				],
			// Maintenance query replica DBs
			6 => $srvExtra + [
					'host' => $wgDBserver,
					'dbname' => $wgDBname,
					'tablePrefix' => $this->dbPrefix(),
					'user' => $wgDBuser,
					'password' => $wgDBpassword,
					'type' => $wgDBtype,
					'dbDirectory' => $wgSQLiteDataDir,
					'load' => 0,
					'groupLoads' => [
						'vslow' => 100
					],
				],
			// Replica DB that only has a copy of some static tables
			7 => $srvExtra + [
					'host' => $wgDBserver,
					'dbname' => $wgDBname,
					'tablePrefix' => $this->dbPrefix(),
					'user' => $wgDBuser,
					'password' => $wgDBpassword,
					'type' => $wgDBtype,
					'dbDirectory' => $wgSQLiteDataDir,
					'load' => 0,
					'groupLoads' => [
						'archive' => 100
					],
					'is static' => true
				]
		];

		return new LoadBalancer( $lbExtra + [
			'servers' => $servers,
			'localDomain' => new DatabaseDomain( $wgDBname, null, $this->dbPrefix() ),
			'queryLogger' => MediaWiki\Logger\LoggerFactory::getInstance( 'DBQuery' ),
			'loadMonitor' => [ 'class' => LoadMonitorNull::class ],
			'clusterName' => 'main-test-cluster'
		] );
	}

	private function assertWriteForbidden( Database $db ) {
		try {
			$db->delete( 'some_table', [ 'id' => 57634126 ], __METHOD__ );
			$this->fail( 'Write operation should have failed!' );
		} catch ( DBError $ex ) {
			// check that the exception message contains "Write operation"
			$constraint = new StringContains( 'Write operation' );

			if ( !$constraint->evaluate( $ex->getMessage(), '', true ) ) {
				// re-throw original error, to preserve stack trace
				throw $ex;
			}
		}
	}

	private function assertWriteAllowed( IMaintainableDatabase $db ) {
		$table = $db->tableName( 'some_table' );
		// Trigger a transaction so that rollback() will remove all the tables.
		// Don't do this for MySQL as it auto-commits transactions for DDL
		// statements such as CREATE TABLE.
		$useAtomicSection = in_array( $db->getType(), [ 'sqlite', 'postgres' ], true );
		/** @var Database $db */
		try {
			$db->dropTable( 'some_table' );
			$this->assertNotEquals( TransactionManager::STATUS_TRX_ERROR, $db->trxStatus() );

			if ( $useAtomicSection ) {
				$db->startAtomic( __METHOD__ );
			}
			// Use only basic SQL and trivial types for these queries for compatibility
			$this->assertNotFalse(
				$db->query( "CREATE TABLE $table (id INT, time INT)", __METHOD__ ),
				"table created"
			);
			$this->assertNotEquals( TransactionManager::STATUS_TRX_ERROR, $db->trxStatus() );
			$this->assertNotFalse(
				$db->query( "DELETE FROM $table WHERE id=57634126", __METHOD__ ),
				"delete query"
			);
			$this->assertNotEquals( TransactionManager::STATUS_TRX_ERROR, $db->trxStatus() );
		} finally {
			if ( !$useAtomicSection ) {
				// Drop the table to clean up, ignoring any error.
				$db->dropTable( 'some_table' );
			}
			// Rollback the atomic section for sqlite's benefit.
			$db->rollback( __METHOD__, 'flush' );
			$this->assertNotEquals( TransactionManager::STATUS_TRX_ERROR, $db->trxStatus() );
		}
	}

	/**
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getServerAttributes
	 */
	public function testServerAttributes() {
		$servers = [
			[ // master
				'dbname' => 'my_unittest_wiki',
				'tablePrefix' => self::DB_PREFIX,
				'type' => 'sqlite',
				'dbDirectory' => "some_directory",
				'load' => 0
			]
		];

		$lb = new LoadBalancer( [
			'servers' => $servers,
			'localDomain' => new DatabaseDomain( 'my_unittest_wiki', null, self::DB_PREFIX ),
			'loadMonitor' => [ 'class' => LoadMonitorNull::class ]
		] );

		$this->assertTrue( $lb->getServerAttributes( 0 )[Database::ATTR_DB_LEVEL_LOCKING] );

		$servers = [
			[ // master
				'host' => 'db1001',
				'user' => 'wikiuser',
				'password' => 'none',
				'dbname' => 'my_unittest_wiki',
				'tablePrefix' => self::DB_PREFIX,
				'type' => 'mysql',
				'load' => 100
			],
			[ // emulated replica
				'host' => 'db1002',
				'user' => 'wikiuser',
				'password' => 'none',
				'dbname' => 'my_unittest_wiki',
				'tablePrefix' => self::DB_PREFIX,
				'type' => 'mysql',
				'load' => 100
			]
		];

		$lb = new LoadBalancer( [
			'servers' => $servers,
			'localDomain' => new DatabaseDomain( 'my_unittest_wiki', null, self::DB_PREFIX ),
			'loadMonitor' => [ 'class' => LoadMonitorNull::class ]
		] );

		$this->assertFalse( $lb->getServerAttributes( 1 )[Database::ATTR_DB_LEVEL_LOCKING] );
	}

	/**
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getConnectionInternal()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getWriterIndex()
	 */
	public function testOpenConnection() {
		$lb = $this->newSingleServerLocalLoadBalancer();

		$i = $lb->getWriterIndex();
		$this->assertFalse( $lb->getAnyOpenConnection( $i ) );

		$conn1 = $lb->getConnectionInternal( $i );
		$conn1->getServerName();
		$this->assertNotEquals( null, $conn1 );
		$this->assertFalse( $conn1->getFlag( DBO_TRX ) );

		$conn2 = $lb->getConnectionInternal( $i, [], false, $lb::CONN_TRX_AUTOCOMMIT );
		$this->assertNotEquals( null, $conn2 );
		$this->assertFalse( $conn2->getFlag( DBO_TRX ) );

		if ( $lb->getServerAttributes( $i )[Database::ATTR_DB_LEVEL_LOCKING] ) {
			$this->assertFalse(
				$lb->getAnyOpenConnection( $i, $lb::CONN_TRX_AUTOCOMMIT ) );
			$this->assertSame( $conn1,
				$lb->getConnectionInternal(
					$i, [], false, $lb::CONN_TRX_AUTOCOMMIT ), $lb::CONN_TRX_AUTOCOMMIT );
		}

		$lb->closeAll( __METHOD__ );
	}

	public function testReconfigure() {
		$conf = [
			'servers' => [ $this->makeServerConfig() ],
			'clusterName' => 'A',
			'localDomain' => $this->db->getDomainID()
		];

		$lb = new LoadBalancer( $conf );
		$this->assertSame( 'A', $lb->getClusterName() );

		$con = $lb->getConnectionInternal( DB_PRIMARY );
		$ref = $lb->getConnection( DB_PRIMARY );

		$this->assertTrue( $con->isOpen() );
		$this->assertTrue( $ref->isOpen() );

		// Reconfigure the LoadBalancer
		$conf['clusterName'] = 'X';
		$lb->reconfigure( $conf );
		$this->assertSame( 'X', $lb->getClusterName() );

		// Reconfiguring should not close connections immediately.
		$this->assertTrue( $con->isOpen() );

		// Connection refs should detect the config change, close the old connection,
		// and get a new connection.
		$this->assertTrue( $ref->isOpen() );

		// The old connection should have been called by DBConnRef.
		$this->assertFalse( $con->isOpen() );
	}

	/**
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getWriterIndex()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getOpenPrimaryConnections()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::setTransactionListener()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::beginPrimaryChanges()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::finalizePrimaryChanges()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::approvePrimaryChanges()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::commitPrimaryChanges()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::flushPrimarySessions()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::runPrimaryTransactionIdleCallbacks()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::runPrimaryTransactionListenerCallbacks()
	 */
	public function testTransactionCallbackChains() {
		global $wgDBserver, $wgDBname, $wgDBuser, $wgDBpassword, $wgDBtype, $wgSQLiteDataDir;

		$servers = [
			[
				'host' => $wgDBserver,
				'dbname' => $wgDBname,
				'tablePrefix' => $this->dbPrefix(),
				'user' => $wgDBuser,
				'password' => $wgDBpassword,
				'type' => $wgDBtype,
				'dbDirectory' => $wgSQLiteDataDir,
				'load' => 0,
				'flags' => DBO_TRX // simulate a web request with DBO_TRX
			],
		];

		$lb = new LoadBalancer( [
			'servers' => $servers,
			'localDomain' => new DatabaseDomain( $wgDBname, null, $this->dbPrefix() )
		] );
		/** @var LoadBalancer $lbWrapper */
		$lbWrapper = TestingAccessWrapper::newFromObject( $lb );

		$conn1 = $lb->getConnection( $lb->getWriterIndex(), [], false );
		$count = iterator_count( $lbWrapper->getOpenPrimaryConnections() );
		$this->assertSame( 0, $count, 'Connection handle count' );
		$conn1->getServerName();
		$count = iterator_count( $lbWrapper->getOpenPrimaryConnections() );
		$this->assertSame( 0, $count, 'Connection handle count' );
		$conn1->ensureConnection();

		$conn2 = $lb->getConnection( $lb->getWriterIndex(), [], '' );
		$count = iterator_count( $lbWrapper->getOpenPrimaryConnections() );
		$this->assertSame( 1, $count, 'Connection handle count' );
		$conn2->getServerName();
		$count = iterator_count( $lbWrapper->getOpenPrimaryConnections() );
		$this->assertSame( 1, $count, 'Connection handle count' );
		$conn2->ensureConnection();

		$count = iterator_count( $lbWrapper->getOpenPrimaryConnections() );
		$this->assertSame( 2, $count, 'Connection handle count' );

		$tlCalls = 0;
		$lb->setTransactionListener( 'test-listener', static function () use ( &$tlCalls ) {
			++$tlCalls;
		} );

		$lb->beginPrimaryChanges( __METHOD__ );
		$bc = array_fill_keys( [ 'a', 'b', 'c', 'd' ], 0 );
		$conn1->onTransactionPreCommitOrIdle( static function () use ( &$bc, $conn1, $conn2 ) {
			$bc['a'] = 1;
			$conn2->onTransactionPreCommitOrIdle( static function () use ( &$bc, $conn1 ) {
				$bc['b'] = 1;
				$conn1->onTransactionPreCommitOrIdle( static function () use ( &$bc, $conn1 ) {
					$bc['c'] = 1;
					$conn1->onTransactionPreCommitOrIdle( static function () use ( &$bc ) {
						$bc['d'] = 1;
					} );
				} );
			} );
		} );
		$lb->finalizePrimaryChanges();
		$lb->approvePrimaryChanges( [] );
		$lb->commitPrimaryChanges( __METHOD__ );
		$lb->runPrimaryTransactionIdleCallbacks();
		$lb->runPrimaryTransactionListenerCallbacks();

		$this->assertSame( array_fill_keys( [ 'a', 'b', 'c', 'd' ], 1 ), $bc );
		$this->assertSame( 2, $tlCalls );

		$tlCalls = 0;
		$lb->beginPrimaryChanges( __METHOD__ );
		$ac = array_fill_keys( [ 'a', 'b', 'c', 'd' ], 0 );
		$conn1->onTransactionCommitOrIdle( static function () use ( &$ac, $conn1, $conn2 ) {
			$ac['a'] = 1;
			$conn2->onTransactionCommitOrIdle( static function () use ( &$ac, $conn1 ) {
				$ac['b'] = 1;
				$conn1->onTransactionCommitOrIdle( static function () use ( &$ac, $conn1 ) {
					$ac['c'] = 1;
					$conn1->onTransactionCommitOrIdle( static function () use ( &$ac ) {
						$ac['d'] = 1;
					} );
				} );
			} );
		} );
		$lb->finalizePrimaryChanges();
		$lb->approvePrimaryChanges( [] );
		$lb->commitPrimaryChanges( __METHOD__ );
		$lb->runPrimaryTransactionIdleCallbacks();
		$lb->runPrimaryTransactionListenerCallbacks();

		$this->assertSame( array_fill_keys( [ 'a', 'b', 'c', 'd' ], 1 ), $ac );
		$this->assertSame( 2, $tlCalls );

		$conn1->lock( 'test_lock_' . mt_rand(), __METHOD__, 0 );
		$lb->flushPrimarySessions( __METHOD__ );
		$this->assertSame( TransactionManager::STATUS_TRX_NONE, $conn1->trxStatus() );
		$this->assertSame( TransactionManager::STATUS_TRX_NONE, $conn2->trxStatus() );
	}

	/**
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getConnectionRef()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getConnection()
	 */
	public function testForbiddenWritesNoRef() {
		// Simulate web request with DBO_TRX
		$lb = $this->newMultiServerLocalLoadBalancer( [], [ 'flags' => DBO_TRX ] );

		$dbr = $lb->getConnection( DB_REPLICA );
		$this->assertTrue( $dbr->isReadOnly(), 'replica shows as replica' );
		$this->expectException( DBReadOnlyRoleError::class );
		$dbr->delete( 'some_table', [ 'id' => 57634126 ], __METHOD__ );

		// FIXME: not needed?
		$lb->closeAll( __METHOD__ );
	}

	/**
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getConnectionRef()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getConnection()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getConnectionRef()
	 */
	public function testDBConnRefReadsMasterAndReplicaRoles() {
		$lb = $this->newSingleServerLocalLoadBalancer();

		$rConn = $lb->getConnectionRef( DB_REPLICA );
		$wConn = $lb->getConnectionRef( DB_PRIMARY );
		$wConn2 = $lb->getConnectionRef( 0 );

		$v = [ 'value' => '1', '1' ];
		$sql = 'SELECT MAX(1) AS value';
		foreach ( [ $rConn, $wConn, $wConn2 ] as $conn ) {
			$conn->clearFlag( $conn::DBO_TRX );

			$res = $conn->query( $sql, __METHOD__ );
			$this->assertEquals( $v, $res->fetchRow() );

			$res = $conn->query( $sql, __METHOD__, $conn::QUERY_REPLICA_ROLE );
			$this->assertEquals( $v, $res->fetchRow() );
		}

		$wConn->getScopedLockAndFlush( 'key', __METHOD__, 1 );
		$wConn2->getScopedLockAndFlush( 'key2', __METHOD__, 1 );
	}

	/**
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getConnectionRef()
	 */
	public function testDBConnRefWritesReplicaRole() {
		$lb = $this->newSingleServerLocalLoadBalancer();

		$rConn = $lb->getConnectionRef( DB_REPLICA );

		$this->expectException( DBReadOnlyRoleError::class );
		$rConn->query( 'DELETE FROM sometesttable WHERE 1=0' );
	}

	/**
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getConnectionRef()
	 */
	public function testDBConnRefWritesReplicaRoleIndex() {
		$lb = $this->newMultiServerLocalLoadBalancer();

		$rConn = $lb->getConnectionRef( 1 );

		$this->expectException( DBReadOnlyRoleError::class );
		$rConn->query( 'DELETE FROM sometesttable WHERE 1=0' );
	}

	/**
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getConnectionRef()
	 */
	public function testDBConnRefWritesReplicaRoleInsert() {
		$lb = $this->newMultiServerLocalLoadBalancer();

		$rConn = $lb->getConnectionRef( DB_REPLICA );

		$this->expectException( DBReadOnlyRoleError::class );
		$rConn->insert( 'test', [ 't' => 1 ], __METHOD__ );
	}

	/**
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getConnection()
	 */
	public function testGetConnectionRefDefaultGroup() {
		$lb = $this->newMultiServerLocalLoadBalancer( [ 'defaultGroup' => 'vslow' ] );
		$lbWrapper = TestingAccessWrapper::newFromObject( $lb );

		$rVslow = $lb->getConnectionRef( DB_REPLICA );
		$vslowIndexPicked = $rVslow->getLBInfo( 'serverIndex' );

		$this->assertSame( $vslowIndexPicked, $lbWrapper->getExistingReaderIndex( 'vslow' ) );
	}

	/**
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getConnection()
	 */
	public function testGetConnectionRefUnknownDefaultGroup() {
		$lb = $this->newMultiServerLocalLoadBalancer( [ 'defaultGroup' => 'invalid' ] );

		$this->assertInstanceOf(
			IDatabase::class,
			$lb->getConnectionRef( DB_REPLICA )
		);
	}

	/**
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getConnection()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getMaintenanceConnectionRef()
	 */
	public function testQueryGroupIndex() {
		$lb = $this->newMultiServerLocalLoadBalancer( [ 'defaultGroup' => false ] );
		/** @var LoadBalancer $lbWrapper */
		$lbWrapper = TestingAccessWrapper::newFromObject( $lb );

		$rGeneric = $lb->getConnectionRef( DB_REPLICA );
		$mainIndexPicked = $rGeneric->getLBInfo( 'serverIndex' );

		$this->assertSame(
			$mainIndexPicked,
			$lbWrapper->getExistingReaderIndex( $lb::GROUP_GENERIC )
		);
		$this->assertContains( $mainIndexPicked, [ 1, 2 ] );
		for ( $i = 0; $i < 300; ++$i ) {
			$rLog = $lb->getConnectionRef( DB_REPLICA, [] );
			$this->assertSame(
				$mainIndexPicked,
				$rLog->getLBInfo( 'serverIndex' ),
				"Main index unchanged" );
		}

		$rRC = $lb->getConnectionRef( DB_REPLICA, [ 'foo' ] );
		$rWL = $lb->getConnectionRef( DB_REPLICA, [ 'bar' ] );
		$rRCMaint = $lb->getMaintenanceConnectionRef( DB_REPLICA, [ 'foo' ] );
		$rWLMaint = $lb->getMaintenanceConnectionRef( DB_REPLICA, [ 'bar' ] );

		$this->assertSame( 3, $rRC->getLBInfo( 'serverIndex' ) );
		$this->assertSame( 3, $rWL->getLBInfo( 'serverIndex' ) );
		$this->assertSame( 3, $rRCMaint->getLBInfo( 'serverIndex' ) );
		$this->assertSame( 3, $rWLMaint->getLBInfo( 'serverIndex' ) );

		$rLog = $lb->getConnectionRef( DB_REPLICA, [ 'baz', 'bar' ] );
		$logIndexPicked = $rLog->getLBInfo( 'serverIndex' );

		$this->assertSame( $logIndexPicked, $lbWrapper->getExistingReaderIndex( 'baz' ) );
		$this->assertContains( $logIndexPicked, [ 4, 5 ] );

		for ( $i = 0; $i < 300; ++$i ) {
			$rLog = $lb->getConnectionRef( DB_REPLICA, [ 'baz', 'bar' ] );
			$this->assertSame(
				$logIndexPicked, $rLog->getLBInfo( 'serverIndex' ), "Index unchanged" );
		}

		$rVslow = $lb->getConnectionRef( DB_REPLICA, [ 'vslow', 'baz' ] );
		$vslowIndexPicked = $rVslow->getLBInfo( 'serverIndex' );

		$this->assertSame( $vslowIndexPicked, $lbWrapper->getExistingReaderIndex( 'vslow' ) );
		$this->assertSame( 6, $vslowIndexPicked );
	}

	public function testNonZeroMasterLoad() {
		$lb = $this->newMultiServerLocalLoadBalancer( [], [ 'flags' => DBO_DEFAULT ], true );
		// Make sure that no infinite loop occurs (T226678)
		$rGeneric = $lb->getConnectionRef( DB_REPLICA );
		$this->assertSame( $lb->getWriterIndex(), $rGeneric->getLBInfo( 'serverIndex' ) );
	}

	/**
	 * @covers \Wikimedia\Rdbms\LoadBalancer::setDomainAliases()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::resolveDomainID()
	 */
	public function testSetDomainAliases() {
		$lb = $this->newMultiServerLocalLoadBalancer();
		$origDomain = $lb->getLocalDomainID();

		$this->assertSame( $origDomain, $lb->resolveDomainID( false ) );
		$this->assertSame( "db-prefix_", $lb->resolveDomainID( "db-prefix_" ) );

		$lb->setDomainAliases( [
			'alias-db' => 'realdb',
			'alias-db-prefix_' => 'realdb-realprefix_'
		] );

		$this->assertSame( 'realdb', $lb->resolveDomainID( 'alias-db' ) );
		$this->assertSame( "realdb-realprefix_", $lb->resolveDomainID( "alias-db-prefix_" ) );
	}

	/**
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getClusterName()
	 */
	public function testClusterName() {
		global $wgDBname;

		$lb1 = new LoadBalancer( [
			'servers' => [ $this->makeServerConfig() ],
			'queryLogger' => MediaWiki\Logger\LoggerFactory::getInstance( 'DBQuery' ),
			'localDomain' => new DatabaseDomain( $wgDBname, null, $this->dbPrefix() ),
			'chronologyCallback' => static function () use ( &$called ) {
				$called = true;
			},
			'clusterName' => 'xx'
		] );
		$this->assertSame( 'xx', $lb1->getClusterName() );

		$lb2 = new LoadBalancer( [
			'servers' => [ $this->makeServerConfig() ],
			'queryLogger' => MediaWiki\Logger\LoggerFactory::getInstance( 'DBQuery' ),
			'localDomain' => new DatabaseDomain( $wgDBname, null, $this->dbPrefix() ),
			'chronologyCallback' => static function () use ( &$called ) {
				$called = true;
			},
			'clusterName' => null
		] );
		$this->assertSame( 'testhost', $lb2->getClusterName() );
	}
}
