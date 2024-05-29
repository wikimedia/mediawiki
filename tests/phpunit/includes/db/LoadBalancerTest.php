<?php
/**
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

use Wikimedia\Rdbms\ChronologyProtector;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\DBConnRef;
use Wikimedia\Rdbms\DBReadOnlyRoleError;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IMaintainableDatabase;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\Rdbms\LoadMonitorNull;
use Wikimedia\Rdbms\ServerInfo;
use Wikimedia\Rdbms\TransactionManager;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 * @group medium
 * @covers \Wikimedia\Rdbms\LoadBalancer
 * @covers \Wikimedia\Rdbms\ServerInfo
 */
class LoadBalancerTest extends MediaWikiIntegrationTestCase {
	private function makeServerConfig( $flags = DBO_DEFAULT ) {
		global $wgDBserver, $wgDBport, $wgDBname, $wgDBuser, $wgDBpassword, $wgDBtype;
		global $wgSQLiteDataDir;

		return [
			'host' => $wgDBserver,
			'port' => $wgDBport,
			'serverName' => 'testhost',
			'dbname' => $wgDBname,
			'tablePrefix' => self::dbPrefix(),
			'user' => $wgDBuser,
			'password' => $wgDBpassword,
			'type' => $wgDBtype,
			'dbDirectory' => $wgSQLiteDataDir,
			'load' => 0,
			'flags' => $flags
		];
	}

	public function testWithoutReplica() {
		global $wgDBname;

		$called = false;
		$chronologyProtector = $this->createMock( ChronologyProtector::class );
		$chronologyProtector->method( 'getSessionPrimaryPos' )
			->willReturnCallback(
				static function () use ( &$called ) {
					$called = true;
				}
			);
		$lb = new LoadBalancer( [
			// Simulate web request with DBO_TRX
			'servers' => [ $this->makeServerConfig( DBO_TRX ) ],
			'logger' => MediaWiki\Logger\LoggerFactory::getInstance( 'rdbms' ),
			'localDomain' => new DatabaseDomain( $wgDBname, null, self::dbPrefix() ),
			'chronologyProtector' => $chronologyProtector,
			'clusterName' => 'xyz'
		] );

		$this->assertSame( 1, $lb->getServerCount() );
		$this->assertFalse( $lb->hasReplicaServers() );
		$this->assertFalse( $lb->hasStreamingReplicaServers() );
		$this->assertSame( 'xyz', $lb->getClusterName() );

		$ld = DatabaseDomain::newFromId( $lb->getLocalDomainID() );
		$this->assertSame( $wgDBname, $ld->getDatabase(), 'local domain DB set' );
		$this->assertSame( self::dbPrefix(), $ld->getTablePrefix(), 'local domain prefix set' );
		$this->assertSame( 'my_test_wiki', $lb->resolveDomainID( 'my_test_wiki' ) );
		$this->assertSame( $ld->getId(), $lb->resolveDomainID( false ) );
		$this->assertSame( $ld->getId(), $lb->resolveDomainID( $ld ) );
		$this->assertFalse( $called );

		$dbw = $lb->getConnection( DB_PRIMARY );
		$dbw->getServerName();
		$this->assertFalse( $called, "getServerName() optimized for DB_PRIMARY" );

		$dbw->ensureConnection();
		$this->assertFalse( $called, "Session replication pos not used with single server" );
		$this->assertTrue( $dbw->getFlag( $dbw::DBO_TRX ), "DBO_TRX set on master" );
		$this->assertWriteAllowed( $dbw );

		$dbr = $lb->getConnection( DB_REPLICA );
		$this->assertTrue( $dbr->getFlag( $dbw::DBO_TRX ), "DBO_TRX set on replica" );

		if ( !$lb->getServerAttributes( ServerInfo::WRITER_INDEX )[Database::ATTR_DB_LEVEL_LOCKING] ) {
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

	public function testWithReplica() {
		// Simulate web request with DBO_TRX
		$lb = $this->newMultiServerLocalLoadBalancer( [], [ 'flags' => DBO_TRX ] );

		$this->assertSame( 8, $lb->getServerCount() );
		$this->assertTrue( $lb->hasReplicaServers() );
		$this->assertTrue( $lb->hasStreamingReplicaServers() );
		$this->assertSame( 'main-test-cluster', $lb->getClusterName() );

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

		$this->assertTrue( $dbw->getFlag( $dbw::DBO_TRX ), "DBO_TRX set on primary" );
		$this->assertWriteAllowed( $dbw );

		$dbr = $lb->getConnection( DB_REPLICA );
		$dbr->ensureConnection();
		$rConn = TestingAccessWrapper::newFromObject( $dbr )->conn;
		$rConnWrap = TestingAccessWrapper::newFromObject( $rConn );

		$this->assertTrue( $dbr->isReadOnly(), 'replica shows as replica' );
		$this->assertTrue( $dbr->getFlag( $dbw::DBO_TRX ), "DBO_TRX set on replica" );
		$this->assertSame( $dbr->getLBInfo( 'serverIndex' ), $lb->getReaderIndex() );

		if ( !$lb->getServerAttributes( ServerInfo::WRITER_INDEX )[Database::ATTR_DB_LEVEL_LOCKING] ) {
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
			'localDomain' => new DatabaseDomain( $wgDBname, null, self::dbPrefix() ),
			'cliMode' => false
		] );
	}

	private function newMultiServerLocalLoadBalancer(
		$lbExtra = [], $srvExtra = [], $masterOnly = false
	) {
		global $wgDBserver, $wgDBport, $wgDBname, $wgDBuser, $wgDBpassword, $wgDBtype;
		global $wgSQLiteDataDir;

		$servers = [
			// Primary DB
			0 => $srvExtra + [
					'serverName' => 'db0',
					'host' => $wgDBserver,
					'port' => $wgDBport,
					'dbname' => $wgDBname,
					'tablePrefix' => self::dbPrefix(),
					'user' => $wgDBuser,
					'password' => $wgDBpassword,
					'type' => $wgDBtype,
					'dbDirectory' => $wgSQLiteDataDir,
					'load' => $masterOnly ? 100 : 0,
				],
			// Main replica DBs
			1 => $srvExtra + [
					'serverName' => 'db1',
					'host' => $wgDBserver,
					'port' => $wgDBport,
					'dbname' => $wgDBname,
					'tablePrefix' => self::dbPrefix(),
					'user' => $wgDBuser,
					'password' => $wgDBpassword,
					'type' => $wgDBtype,
					'dbDirectory' => $wgSQLiteDataDir,
					'load' => $masterOnly ? 0 : 100,
				],
			2 => $srvExtra + [
					'serverName' => 'db2',
					'host' => $wgDBserver,
					'port' => $wgDBport,
					'dbname' => $wgDBname,
					'tablePrefix' => self::dbPrefix(),
					'user' => $wgDBuser,
					'password' => $wgDBpassword,
					'type' => $wgDBtype,
					'dbDirectory' => $wgSQLiteDataDir,
					'load' => $masterOnly ? 0 : 100,
				],
			// RC replica DBs
			3 => $srvExtra + [
					'serverName' => 'db3',
					'host' => $wgDBserver,
					'port' => $wgDBport,
					'dbname' => $wgDBname,
					'tablePrefix' => self::dbPrefix(),
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
					'serverName' => 'db4',
					'host' => $wgDBserver,
					'port' => $wgDBport,
					'dbname' => $wgDBname,
					'tablePrefix' => self::dbPrefix(),
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
					'serverName' => 'db5',
					'host' => $wgDBserver,
					'port' => $wgDBport,
					'dbname' => $wgDBname,
					'tablePrefix' => self::dbPrefix(),
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
					'serverName' => 'db6',
					'host' => $wgDBserver,
					'port' => $wgDBport,
					'dbname' => $wgDBname,
					'tablePrefix' => self::dbPrefix(),
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
					'serverName' => 'db7',
					'host' => $wgDBserver,
					'port' => $wgDBport,
					'dbname' => $wgDBname,
					'tablePrefix' => self::dbPrefix(),
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
			'localDomain' => new DatabaseDomain( $wgDBname, null, self::dbPrefix() ),
			'logger' => MediaWiki\Logger\LoggerFactory::getInstance( 'rdbms' ),
			'loadMonitor' => [ 'class' => LoadMonitorNull::class ],
			'clusterName' => 'main-test-cluster'
		] );
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
				'serverName' => 'db1',
				'host' => 'db1001',
				'user' => 'wikiuser',
				'password' => 'none',
				'dbname' => 'my_unittest_wiki',
				'tablePrefix' => self::DB_PREFIX,
				'type' => 'mysql',
				'load' => 100
			],
			[ // emulated replica
				'serverName' => 'db2',
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

	public function testOpenConnection() {
		$lb = $this->newSingleServerLocalLoadBalancer();
		$i = ServerInfo::WRITER_INDEX;

		$this->assertFalse( $lb->getAnyOpenConnection( $i ) );
		$this->assertFalse( $lb->getAnyOpenConnection( $i, $lb::CONN_TRX_AUTOCOMMIT ) );

		// Get two live round-aware handles
		$raConnRef1 = $lb->getConnection( $i );
		$raConnRef1->ensureConnection();
		$raConnRef1Wrapper = TestingAccessWrapper::newFromObject( $raConnRef1 );
		$raConnRef2 = $lb->getConnection( $i );
		$raConnRef2->ensureConnection();
		$raConnRef2Wrapper = TestingAccessWrapper::newFromObject( $raConnRef2 );

		$this->assertNotNull( $raConnRef1Wrapper->conn );
		$this->assertSame( $raConnRef1Wrapper->conn, $raConnRef2Wrapper->conn );
		$this->assertTrue( $raConnRef1Wrapper->conn->getFlag( DBO_TRX ) );

		// Get two live autocommit handles
		$acConnRef1 = $lb->getConnection( $i, [], false, $lb::CONN_TRX_AUTOCOMMIT );
		$acConnRef1->ensureConnection();
		$acConnRef1Wrapper = TestingAccessWrapper::newFromObject( $acConnRef1 );
		$acConnRef2 = $lb->getConnection( $i, [], false, $lb::CONN_TRX_AUTOCOMMIT );
		$acConnRef2->ensureConnection();
		$acConnRef2Wrapper = TestingAccessWrapper::newFromObject( $acConnRef2 );

		$this->assertNotNull( $acConnRef1Wrapper->conn );
		$this->assertSame( $acConnRef1Wrapper->conn, $acConnRef2Wrapper->conn );

		$this->assertNotFalse( $lb->getAnyOpenConnection( $i ) );

		$lb->closeAll( __METHOD__ );
	}

	public function testReconfigure() {
		$serverA = $this->makeServerConfig();
		$serverA['serverName'] = 'test_one';

		$serverB = $this->makeServerConfig();
		$serverB['serverName'] = 'test_two';
		$conf = [
			'servers' => [ $serverA, $serverB ],
			'clusterName' => 'A',
			'localDomain' => $this->db->getDomainID()
		];

		$lb = new LoadBalancer( $conf );
		$this->assertSame( 2, $lb->getServerCount() );

		$con = $lb->getConnectionInternal( DB_PRIMARY );
		$ref = $lb->getConnection( DB_PRIMARY );

		$this->assertTrue( $con->isOpen() );
		$this->assertTrue( $ref->isOpen() );

		// Depool the second server
		$conf['servers'] = [ $serverA ];
		$lb->reconfigure( $conf );
		$this->assertSame( 1, $lb->getServerCount() );

		// Reconfiguring should not close connections immediately.
		$this->assertTrue( $con->isOpen() );

		// Connection refs should detect the config change, close the old connection,
		// and get a new connection.
		$this->assertTrue( $ref->isOpen() );

		// The old connection should have been called by DBConnRef.
		$this->assertFalse( $con->isOpen() );
	}

	public function testTransactionCallbackChains() {
		global $wgDBserver, $wgDBport, $wgDBname, $wgDBuser, $wgDBpassword, $wgDBtype;
		global $wgSQLiteDataDir;

		$servers = [
			[
				'host' => $wgDBserver,
				'port' => $wgDBport,
				'dbname' => $wgDBname,
				'tablePrefix' => self::dbPrefix(),
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
			'localDomain' => new DatabaseDomain( $wgDBname, null, self::dbPrefix() )
		] );
		/** @var LoadBalancer $lbWrapper */
		$lbWrapper = TestingAccessWrapper::newFromObject( $lb );

		$conn1 = $lb->getConnection( ServerInfo::WRITER_INDEX, [], false );
		$count = iterator_count( $lbWrapper->getOpenPrimaryConnections() );
		$this->assertSame( 0, $count, 'Connection handle count' );
		$conn1->getServerName();
		$count = iterator_count( $lbWrapper->getOpenPrimaryConnections() );
		$this->assertSame( 0, $count, 'Connection handle count' );
		$conn1->ensureConnection();

		$conn2 = $lb->getConnection( ServerInfo::WRITER_INDEX, [], '' );
		$count = iterator_count( $lbWrapper->getOpenPrimaryConnections() );
		$this->assertSame( 1, $count, 'Connection handle count' );
		$conn2->getServerName();
		$count = iterator_count( $lbWrapper->getOpenPrimaryConnections() );
		$this->assertSame( 1, $count, 'Connection handle count' );
		$conn2->ensureConnection();

		$count = iterator_count( $lbWrapper->getOpenPrimaryConnections() );
		$this->assertSame( 1, $count, 'Connection handle count' );

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
		$lb->approvePrimaryChanges( 0 );
		$lb->commitPrimaryChanges( __METHOD__ );
		$lb->runPrimaryTransactionIdleCallbacks();
		$lb->runPrimaryTransactionListenerCallbacks();

		$this->assertSame( array_fill_keys( [ 'a', 'b', 'c', 'd' ], 1 ), $bc );
		$this->assertSame( 1, $tlCalls );

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
		$lb->approvePrimaryChanges( 0 );
		$lb->commitPrimaryChanges( __METHOD__ );
		$lb->runPrimaryTransactionIdleCallbacks();
		$lb->runPrimaryTransactionListenerCallbacks();

		$this->assertSame( array_fill_keys( [ 'a', 'b', 'c', 'd' ], 1 ), $ac );
		$this->assertSame( 1, $tlCalls );

		$conn1->lock( 'test_lock_' . mt_rand(), __METHOD__, 0 );
		$lb->flushPrimarySessions( __METHOD__ );
		$this->assertSame( TransactionManager::STATUS_TRX_NONE, $conn1->trxStatus() );
		$this->assertSame( TransactionManager::STATUS_TRX_NONE, $conn2->trxStatus() );
	}

	public function testForbiddenWritesNoRef() {
		// Simulate web request with DBO_TRX
		$lb = $this->newMultiServerLocalLoadBalancer( [], [ 'flags' => DBO_TRX ] );

		$dbr = $lb->getConnection( DB_REPLICA );
		$this->assertTrue( $dbr->isReadOnly(), 'replica shows as replica' );
		$this->expectException( DBReadOnlyRoleError::class );
		$dbr->newDeleteQueryBuilder()
			->deleteFrom( 'some_table' )
			->where( [ 'id' => 57634126 ] )
			->caller( __METHOD__ )
			->execute();

		// FIXME: not needed?
		$lb->closeAll( __METHOD__ );
	}

	public function testDBConnRefReadsMasterAndReplicaRoles() {
		$lb = $this->newSingleServerLocalLoadBalancer();

		$rConn = $lb->getConnection( DB_REPLICA );
		$wConn = $lb->getConnection( DB_PRIMARY );
		$wConn2 = $lb->getConnection( 0 );

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

	public function testDBConnRefWritesReplicaRole() {
		$lb = $this->newSingleServerLocalLoadBalancer();

		$rConn = $lb->getConnection( DB_REPLICA );

		$this->expectException( DBReadOnlyRoleError::class );
		$rConn->query( 'DELETE FROM sometesttable WHERE 1=0' );
	}

	public function testDBConnRefWritesReplicaRoleIndex() {
		$lb = $this->newMultiServerLocalLoadBalancer();

		$rConn = $lb->getConnection( 1 );

		$this->expectException( DBReadOnlyRoleError::class );
		$rConn->query( 'DELETE FROM sometesttable WHERE 1=0' );
	}

	public function testDBConnRefWritesReplicaRoleInsert() {
		$lb = $this->newMultiServerLocalLoadBalancer();

		$rConn = $lb->getConnection( DB_REPLICA );

		$this->expectException( DBReadOnlyRoleError::class );
		$rConn->insert( 'test', [ 't' => 1 ], __METHOD__ );
	}

	public function testGetConnectionRefDefaultGroup() {
		$lb = $this->newMultiServerLocalLoadBalancer( [ 'defaultGroup' => 'vslow' ] );
		$lbWrapper = TestingAccessWrapper::newFromObject( $lb );

		$rVslow = $lb->getConnection( DB_REPLICA );
		$vslowIndexPicked = $rVslow->getLBInfo( 'serverIndex' );

		$this->assertSame( $vslowIndexPicked, $lbWrapper->getExistingReaderIndex( 'vslow' ) );
	}

	public function testGetConnectionRefUnknownDefaultGroup() {
		$lb = $this->newMultiServerLocalLoadBalancer( [ 'defaultGroup' => 'invalid' ] );

		$this->assertInstanceOf(
			IDatabase::class,
			$lb->getConnection( DB_REPLICA )
		);
	}

	public function testQueryGroupIndex() {
		$lb = $this->newMultiServerLocalLoadBalancer( [ 'defaultGroup' => false ] );
		/** @var LoadBalancer $lbWrapper */
		$lbWrapper = TestingAccessWrapper::newFromObject( $lb );

		$rGeneric = $lb->getConnection( DB_REPLICA );
		$mainIndexPicked = $rGeneric->getLBInfo( 'serverIndex' );

		$this->assertSame(
			$mainIndexPicked,
			$lbWrapper->getExistingReaderIndex( $lb::GROUP_GENERIC )
		);
		$this->assertContains( $mainIndexPicked, [ 1, 2 ] );
		for ( $i = 0; $i < 300; ++$i ) {
			$rLog = $lb->getConnection( DB_REPLICA, [] );
			$this->assertSame(
				$mainIndexPicked,
				$rLog->getLBInfo( 'serverIndex' ),
				"Main index unchanged" );
		}

		$rRC = $lb->getConnection( DB_REPLICA, [ 'foo' ] );
		$rWL = $lb->getConnection( DB_REPLICA, [ 'bar' ] );
		$rRCMaint = $lb->getMaintenanceConnectionRef( DB_REPLICA, [ 'foo' ] );
		$rWLMaint = $lb->getMaintenanceConnectionRef( DB_REPLICA, [ 'bar' ] );

		$this->assertSame( 3, $rRC->getLBInfo( 'serverIndex' ) );
		$this->assertSame( 3, $rWL->getLBInfo( 'serverIndex' ) );
		$this->assertSame( 3, $rRCMaint->getLBInfo( 'serverIndex' ) );
		$this->assertSame( 3, $rWLMaint->getLBInfo( 'serverIndex' ) );

		$rLog = $lb->getConnection( DB_REPLICA, [ 'baz', 'bar' ] );
		$logIndexPicked = $rLog->getLBInfo( 'serverIndex' );

		$this->assertSame( $logIndexPicked, $lbWrapper->getExistingReaderIndex( 'baz' ) );
		$this->assertContains( $logIndexPicked, [ 4, 5 ] );

		for ( $i = 0; $i < 300; ++$i ) {
			$rLog = $lb->getConnection( DB_REPLICA, [ 'baz', 'bar' ] );
			$this->assertSame(
				$logIndexPicked, $rLog->getLBInfo( 'serverIndex' ), "Index unchanged" );
		}

		$rVslow = $lb->getConnection( DB_REPLICA, [ 'vslow', 'baz' ] );
		$vslowIndexPicked = $rVslow->getLBInfo( 'serverIndex' );

		$this->assertSame( $vslowIndexPicked, $lbWrapper->getExistingReaderIndex( 'vslow' ) );
		$this->assertSame( 6, $vslowIndexPicked );
	}

	public function testNonZeroMasterLoad() {
		$lb = $this->newMultiServerLocalLoadBalancer( [], [ 'flags' => DBO_DEFAULT ], true );
		// Make sure that no infinite loop occurs (T226678)
		$rGeneric = $lb->getConnection( DB_REPLICA );
		$this->assertSame( ServerInfo::WRITER_INDEX, $rGeneric->getLBInfo( 'serverIndex' ) );
	}

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

	public function testClusterName() {
		global $wgDBname;
		$chronologyProtector = $this->createMock( ChronologyProtector::class );
		$lb1 = new LoadBalancer( [
			'servers' => [ $this->makeServerConfig() ],
			'logger' => MediaWiki\Logger\LoggerFactory::getInstance( 'rdbms' ),
			'localDomain' => new DatabaseDomain( $wgDBname, null, self::dbPrefix() ),
			'chronologyProtector' => $chronologyProtector,
			'clusterName' => 'xx'
		] );
		$this->assertSame( 'xx', $lb1->getClusterName() );

		$lb2 = new LoadBalancer( [
			'servers' => [ $this->makeServerConfig() ],
			'logger' => MediaWiki\Logger\LoggerFactory::getInstance( 'rdbms' ),
			'localDomain' => new DatabaseDomain( $wgDBname, null, self::dbPrefix() ),
			'chronologyProtector' => $chronologyProtector,
			'clusterName' => null
		] );
		$this->assertSame( 'testhost', $lb2->getClusterName() );
	}
}
