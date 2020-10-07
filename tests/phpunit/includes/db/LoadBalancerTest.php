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
use Wikimedia\Rdbms\DBError;
use Wikimedia\Rdbms\DBReadOnlyRoleError;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\Rdbms\LoadMonitorNull;
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
	 */
	public function testWithoutReplica() {
		global $wgDBname;

		$called = false;
		$lb = new LoadBalancer( [
			// Simulate web request with DBO_TRX
			'servers' => [ $this->makeServerConfig( DBO_TRX ) ],
			'queryLogger' => MediaWiki\Logger\LoggerFactory::getInstance( 'DBQuery' ),
			'localDomain' => new DatabaseDomain( $wgDBname, null, $this->dbPrefix() ),
			'chronologyCallback' => function () use ( &$called ) {
				$called = true;
			}
		] );

		$this->assertSame( 1, $lb->getServerCount() );
		$this->assertFalse( $lb->hasReplicaServers() );
		$this->assertFalse( $lb->hasStreamingReplicaServers() );

		$this->assertTrue( $lb->haveIndex( 0 ) );
		$this->assertFalse( $lb->haveIndex( 1 ) );
		$this->assertFalse( $lb->isNonZeroLoad( 0 ) );
		$this->assertFalse( $lb->isNonZeroLoad( 1 ) );

		$ld = DatabaseDomain::newFromId( $lb->getLocalDomainID() );
		$this->assertEquals( $wgDBname, $ld->getDatabase(), 'local domain DB set' );
		$this->assertEquals( $this->dbPrefix(), $ld->getTablePrefix(), 'local domain prefix set' );
		$this->assertSame( 'my_test_wiki', $lb->resolveDomainID( 'my_test_wiki' ) );
		$this->assertSame( $ld->getId(), $lb->resolveDomainID( false ) );
		$this->assertSame( $ld->getId(), $lb->resolveDomainID( $ld ) );
		$this->assertFalse( $called );

		$dbw = $lb->getConnection( DB_MASTER );
		$this->assertTrue( $called );
		$this->assertEquals(
			$dbw::ROLE_STREAMING_MASTER, $dbw->getTopologyRole(), 'master shows as master'
		);
		$this->assertTrue( $dbw->getFlag( $dbw::DBO_TRX ), "DBO_TRX set on master" );
		$this->assertWriteAllowed( $dbw );

		$dbr = $lb->getConnection( DB_REPLICA );
		$this->assertEquals(
			$dbr::ROLE_STREAMING_MASTER, $dbr->getTopologyRole(), 'DB_REPLICA also gets the master' );
		$this->assertTrue( $dbr->getFlag( $dbw::DBO_TRX ), "DBO_TRX set on replica" );

		if ( !$lb->getServerAttributes( $lb->getWriterIndex() )[$dbw::ATTR_DB_LEVEL_LOCKING] ) {
			$dbwAuto = $lb->getConnection( DB_MASTER, [], false, $lb::CONN_TRX_AUTOCOMMIT );
			$this->assertFalse(
				$dbwAuto->getFlag( $dbw::DBO_TRX ), "No DBO_TRX with CONN_TRX_AUTOCOMMIT" );
			$this->assertTrue( $dbw->getFlag( $dbw::DBO_TRX ), "DBO_TRX still set on master" );
			$this->assertNotEquals(
				$dbw, $dbwAuto, "CONN_TRX_AUTOCOMMIT uses separate connection" );

			$dbrAuto = $lb->getConnection( DB_REPLICA, [], false, $lb::CONN_TRX_AUTOCOMMIT );
			$this->assertFalse(
				$dbrAuto->getFlag( $dbw::DBO_TRX ), "No DBO_TRX with CONN_TRX_AUTOCOMMIT" );
			$this->assertTrue( $dbr->getFlag( $dbw::DBO_TRX ), "DBO_TRX still set on replica" );
			$this->assertNotEquals(
				$dbr, $dbrAuto, "CONN_TRX_AUTOCOMMIT uses separate connection" );

			$dbwAuto2 = $lb->getConnection( DB_MASTER, [], false, $lb::CONN_TRX_AUTOCOMMIT );
			$this->assertEquals( $dbwAuto2, $dbwAuto, "CONN_TRX_AUTOCOMMIT reuses connections" );
		}

		$lb->closeAll();
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
	 */
	public function testWithReplica() {
		global $wgDBserver;

		// Simulate web request with DBO_TRX
		$lb = $this->newMultiServerLocalLoadBalancer( [], [ 'flags' => DBO_TRX ] );

		$this->assertEquals( 8, $lb->getServerCount() );
		$this->assertTrue( $lb->hasReplicaServers() );
		$this->assertTrue( $lb->hasStreamingReplicaServers() );

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

		$dbw = $lb->getConnection( DB_MASTER );
		$this->assertEquals(
			$dbw::ROLE_STREAMING_MASTER, $dbw->getTopologyRole(), 'master shows as master' );
		$this->assertEquals(
			( $wgDBserver != '' ) ? $wgDBserver : 'localhost',
			$dbw->getTopologyRootMaster(),
			'cluster master set'
		);
		$this->assertTrue( $dbw->getFlag( $dbw::DBO_TRX ), "DBO_TRX set on master" );
		$this->assertWriteAllowed( $dbw );

		$dbr = $lb->getConnection( DB_REPLICA );
		$this->assertEquals(
			$dbr::ROLE_STREAMING_REPLICA, $dbr->getTopologyRole(), 'replica shows as replica' );
		$this->assertTrue( $dbr->isReadOnly(), 'replica shows as replica' );
		$this->assertEquals(
			( $wgDBserver != '' ) ? $wgDBserver : 'localhost',
			$dbr->getTopologyRootMaster(),
			'cluster master set'
		);
		$this->assertTrue( $dbr->getFlag( $dbw::DBO_TRX ), "DBO_TRX set on replica" );
		$this->assertEquals( $dbr->getLBInfo( 'serverIndex' ), $lb->getReaderIndex() );

		if ( !$lb->getServerAttributes( $lb->getWriterIndex() )[$dbw::ATTR_DB_LEVEL_LOCKING] ) {
			$dbwAuto = $lb->getConnection( DB_MASTER, [], false, $lb::CONN_TRX_AUTOCOMMIT );
			$this->assertFalse(
				$dbwAuto->getFlag( $dbw::DBO_TRX ), "No DBO_TRX with CONN_TRX_AUTOCOMMIT" );
			$this->assertTrue( $dbw->getFlag( $dbw::DBO_TRX ), "DBO_TRX still set on master" );
			$this->assertNotEquals(
				$dbw, $dbwAuto, "CONN_TRX_AUTOCOMMIT uses separate connection" );

			$dbrAuto = $lb->getConnection( DB_REPLICA, [], false, $lb::CONN_TRX_AUTOCOMMIT );
			$this->assertFalse(
				$dbrAuto->getFlag( $dbw::DBO_TRX ), "No DBO_TRX with CONN_TRX_AUTOCOMMIT" );
			$this->assertTrue( $dbr->getFlag( $dbw::DBO_TRX ), "DBO_TRX still set on replica" );
			$this->assertNotEquals(
				$dbr, $dbrAuto, "CONN_TRX_AUTOCOMMIT uses separate connection" );

			$dbwAuto2 = $lb->getConnection( DB_MASTER, [], false, $lb::CONN_TRX_AUTOCOMMIT );
			$this->assertEquals( $dbwAuto2, $dbwAuto, "CONN_TRX_AUTOCOMMIT reuses connections" );
		}

		$lb->closeAll();
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
			// Master DB
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
					'recentchanges' => 100,
					'watchlist' => 100
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
					'logging' => 100
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
					'logging' => 100
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
			'loadMonitor' => [ 'class' => LoadMonitorNull::class ]
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

	private function assertWriteAllowed( Database $db ) {
		$table = $db->tableName( 'some_table' );
		// Trigger a transaction so that rollback() will remove all the tables.
		// Don't do this for MySQL as it auto-commits transactions for DDL
		// statements such as CREATE TABLE.
		$useAtomicSection = in_array( $db->getType(), [ 'sqlite', 'postgres' ], true );
		try {
			$db->dropTable( 'some_table' ); // clear for sanity
			$this->assertNotEquals( $db::STATUS_TRX_ERROR, $db->trxStatus() );

			if ( $useAtomicSection ) {
				$db->startAtomic( __METHOD__ );
			}
			// Use only basic SQL and trivial types for these queries for compatibility
			$this->assertNotSame(
				false,
				$db->query( "CREATE TABLE $table (id INT, time INT)", __METHOD__ ),
				"table created"
			);
			$this->assertNotEquals( $db::STATUS_TRX_ERROR, $db->trxStatus() );
			$this->assertNotSame(
				false,
				$db->query( "DELETE FROM $table WHERE id=57634126", __METHOD__ ),
				"delete query"
			);
			$this->assertNotEquals( $db::STATUS_TRX_ERROR, $db->trxStatus() );
		} finally {
			if ( !$useAtomicSection ) {
				// Drop the table to clean up, ignoring any error.
				$db->dropTable( 'some_table' );
			}
			// Rollback the atomic section for sqlite's benefit.
			$db->rollback( __METHOD__, 'flush' );
			$this->assertNotEquals( $db::STATUS_TRX_ERROR, $db->trxStatus() );
		}
	}

	/**
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getServerAttributes
	 */
	public function testServerAttributes() {
		$servers = [
			[ // master
				'dbname'      => 'my_unittest_wiki',
				'tablePrefix' => 'unittest_',
				'type'        => 'sqlite',
				'dbDirectory' => "some_directory",
				'load'        => 0
			]
		];

		$lb = new LoadBalancer( [
			'servers' => $servers,
			'localDomain' => new DatabaseDomain( 'my_unittest_wiki', null, 'unittest_' ),
			'loadMonitor' => [ 'class' => LoadMonitorNull::class ]
		] );

		$this->assertTrue( $lb->getServerAttributes( 0 )[Database::ATTR_DB_LEVEL_LOCKING] );

		$servers = [
			[ // master
				'host'        => 'db1001',
				'user'        => 'wikiuser',
				'password'    => 'none',
				'dbname'      => 'my_unittest_wiki',
				'tablePrefix' => 'unittest_',
				'type'        => 'mysql',
				'load'        => 100
			],
			[ // emulated replica
				'host'        => 'db1002',
				'user'        => 'wikiuser',
				'password'    => 'none',
				'dbname'      => 'my_unittest_wiki',
				'tablePrefix' => 'unittest_',
				'type'        => 'mysql',
				'load'        => 100
			]
		];

		$lb = new LoadBalancer( [
			'servers' => $servers,
			'localDomain' => new DatabaseDomain( 'my_unittest_wiki', null, 'unittest_' ),
			'loadMonitor' => [ 'class' => LoadMonitorNull::class ]
		] );

		$this->assertFalse( $lb->getServerAttributes( 1 )[Database::ATTR_DB_LEVEL_LOCKING] );
	}

	/**
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getConnection()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::openConnection()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getAnyOpenConnection()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getWriterIndex()
	 */
	public function testOpenConnection() {
		$lb = $this->newSingleServerLocalLoadBalancer();

		$i = $lb->getWriterIndex();
		$this->assertFalse( $lb->getAnyOpenConnection( $i ) );

		$conn1 = $lb->getConnection( $i );
		$this->assertNotEquals( null, $conn1 );
		$this->assertEquals( $conn1, $lb->getAnyOpenConnection( $i ) );
		$this->assertFalse( $conn1->getFlag( DBO_TRX ) );

		$conn2 = $lb->getConnection( $i, [], false, $lb::CONN_TRX_AUTOCOMMIT );
		$this->assertNotEquals( null, $conn2 );
		$this->assertFalse( $conn2->getFlag( DBO_TRX ) );

		if ( $lb->getServerAttributes( $i )[Database::ATTR_DB_LEVEL_LOCKING] ) {
			$this->assertFalse(
				$lb->getAnyOpenConnection( $i, $lb::CONN_TRX_AUTOCOMMIT ) );
			$this->assertEquals( $conn1,
				$lb->getConnection(
					$i, [], false, $lb::CONN_TRX_AUTOCOMMIT ), $lb::CONN_TRX_AUTOCOMMIT );
		} else {
			$this->assertEquals( $conn2,
				$lb->getAnyOpenConnection( $i, $lb::CONN_TRX_AUTOCOMMIT ) );
			$this->assertEquals( $conn2,
				$lb->getConnection( $i, [], false, $lb::CONN_TRX_AUTOCOMMIT ) );

			$conn2->startAtomic( __METHOD__ );
			try {
				$lb->getConnection( $i, [], false, $lb::CONN_TRX_AUTOCOMMIT );
				$conn2->endAtomic( __METHOD__ );
				$this->fail( "No exception thrown." );
			} catch ( DBUnexpectedError $e ) {
				$this->assertEquals(
					'Handle requested with CONN_TRX_AUTOCOMMIT yet it has a transaction',
					$e->getMessage()
				);
			}
			$conn2->endAtomic( __METHOD__ );
		}

		$lb->closeAll();
	}

	/**
	 * @covers \Wikimedia\Rdbms\LoadBalancer::openConnection()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getWriterIndex()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::forEachOpenMasterConnection()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::setTransactionListener()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::beginMasterChanges()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::finalizeMasterChanges()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::approveMasterChanges()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::commitMasterChanges()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::runMasterTransactionIdleCallbacks()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::runMasterTransactionListenerCallbacks()
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

		$conn1 = $lb->openConnection( $lb->getWriterIndex(), false );
		$conn2 = $lb->openConnection( $lb->getWriterIndex(), '' );

		$count = 0;
		$lb->forEachOpenMasterConnection( function () use ( &$count ) {
			++$count;
		} );
		$this->assertEquals( 2, $count, 'Connection handle count' );

		$tlCalls = 0;
		$lb->setTransactionListener( 'test-listener', function () use ( &$tlCalls ) {
			++$tlCalls;
		} );

		$lb->beginMasterChanges( __METHOD__ );
		$bc = array_fill_keys( [ 'a', 'b', 'c', 'd' ], 0 );
		$conn1->onTransactionPreCommitOrIdle( function () use ( &$bc, $conn1, $conn2 ) {
			$bc['a'] = 1;
			$conn2->onTransactionPreCommitOrIdle( function () use ( &$bc, $conn1, $conn2 ) {
				$bc['b'] = 1;
				$conn1->onTransactionPreCommitOrIdle( function () use ( &$bc, $conn1, $conn2 ) {
					$bc['c'] = 1;
					$conn1->onTransactionPreCommitOrIdle( function () use ( &$bc, $conn1, $conn2 ) {
						$bc['d'] = 1;
					} );
				} );
			} );
		} );
		$lb->finalizeMasterChanges();
		$lb->approveMasterChanges( [] );
		$lb->commitMasterChanges( __METHOD__ );
		$lb->runMasterTransactionIdleCallbacks();
		$lb->runMasterTransactionListenerCallbacks();

		$this->assertEquals( array_fill_keys( [ 'a', 'b', 'c', 'd' ], 1 ), $bc );
		$this->assertEquals( 2, $tlCalls );

		$tlCalls = 0;
		$lb->beginMasterChanges( __METHOD__ );
		$ac = array_fill_keys( [ 'a', 'b', 'c', 'd' ], 0 );
		$conn1->onTransactionCommitOrIdle( function () use ( &$ac, $conn1, $conn2 ) {
			$ac['a'] = 1;
			$conn2->onTransactionCommitOrIdle( function () use ( &$ac, $conn1, $conn2 ) {
				$ac['b'] = 1;
				$conn1->onTransactionCommitOrIdle( function () use ( &$ac, $conn1, $conn2 ) {
					$ac['c'] = 1;
					$conn1->onTransactionCommitOrIdle( function () use ( &$ac, $conn1, $conn2 ) {
						$ac['d'] = 1;
					} );
				} );
			} );
		} );
		$lb->finalizeMasterChanges();
		$lb->approveMasterChanges( [] );
		$lb->commitMasterChanges( __METHOD__ );
		$lb->runMasterTransactionIdleCallbacks();
		$lb->runMasterTransactionListenerCallbacks();

		$this->assertEquals( array_fill_keys( [ 'a', 'b', 'c', 'd' ], 1 ), $ac );
		$this->assertEquals( 2, $tlCalls );

		$conn1->close();
		$conn2->close();
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
		$lb->closeAll();
	}

	/**
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getConnectionRef()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getConnection()
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getConnectionRef()
	 */
	public function testDBConnRefReadsMasterAndReplicaRoles() {
		$lb = $this->newSingleServerLocalLoadBalancer();

		$rConn = $lb->getConnectionRef( DB_REPLICA );
		$wConn = $lb->getConnectionRef( DB_MASTER );
		$wConn2 = $lb->getConnectionRef( 0 );

		$v = [ 'value' => '1', '1' ];
		$sql = 'SELECT MAX(1) AS value';
		foreach ( [ $rConn, $wConn, $wConn2 ] as $conn ) {
			$conn->clearFlag( $conn::DBO_TRX );

			$res = $conn->query( $sql, __METHOD__ );
			$this->assertEquals( $v, $conn->fetchRow( $res ) );

			$res = $conn->query( $sql, __METHOD__, $conn::QUERY_REPLICA_ROLE );
			$this->assertEquals( $v, $conn->fetchRow( $res ) );
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
	public function testLazyDBConnRefWritesReplicaRoleIndex() {
		$lb = $this->newMultiServerLocalLoadBalancer();

		$rConn = $lb->getLazyConnectionRef( 1 );

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

		$this->assertEquals(
			$mainIndexPicked,
			$lbWrapper->getExistingReaderIndex( $lb::GROUP_GENERIC )
		);
		$this->assertTrue( in_array( $mainIndexPicked, [ 1, 2 ] ) );
		for ( $i = 0; $i < 300; ++$i ) {
			$rLog = $lb->getConnectionRef( DB_REPLICA, [] );
			$this->assertEquals(
				$mainIndexPicked,
				$rLog->getLBInfo( 'serverIndex' ),
				"Main index unchanged" );
		}

		$rRC = $lb->getConnectionRef( DB_REPLICA, [ 'recentchanges' ] );
		$rWL = $lb->getConnectionRef( DB_REPLICA, [ 'watchlist' ] );
		$rRCMaint = $lb->getMaintenanceConnectionRef( DB_REPLICA, [ 'recentchanges' ] );
		$rWLMaint = $lb->getMaintenanceConnectionRef( DB_REPLICA, [ 'watchlist' ] );

		$this->assertEquals( 3, $rRC->getLBInfo( 'serverIndex' ) );
		$this->assertEquals( 3, $rWL->getLBInfo( 'serverIndex' ) );
		$this->assertEquals( 3, $rRCMaint->getLBInfo( 'serverIndex' ) );
		$this->assertEquals( 3, $rWLMaint->getLBInfo( 'serverIndex' ) );

		$rLog = $lb->getConnectionRef( DB_REPLICA, [ 'logging', 'watchlist' ] );
		$logIndexPicked = $rLog->getLBInfo( 'serverIndex' );

		$this->assertEquals( $logIndexPicked, $lbWrapper->getExistingReaderIndex( 'logging' ) );
		$this->assertTrue( in_array( $logIndexPicked, [ 4, 5 ] ) );

		for ( $i = 0; $i < 300; ++$i ) {
			$rLog = $lb->getConnectionRef( DB_REPLICA, [ 'logging', 'watchlist' ] );
			$this->assertEquals(
				$logIndexPicked, $rLog->getLBInfo( 'serverIndex' ), "Index unchanged" );
		}

		$rVslow = $lb->getConnectionRef( DB_REPLICA, [ 'vslow', 'logging' ] );
		$vslowIndexPicked = $rVslow->getLBInfo( 'serverIndex' );

		$this->assertEquals( $vslowIndexPicked, $lbWrapper->getExistingReaderIndex( 'vslow' ) );
		$this->assertEquals( 6, $vslowIndexPicked );
	}

	public function testNonZeroMasterLoad() {
		$lb = $this->newMultiServerLocalLoadBalancer( [], [ 'flags' => DBO_DEFAULT ], true );
		// Make sure that no infinite loop occurs (T226678)
		$rGeneric = $lb->getConnectionRef( DB_REPLICA );
		$this->assertEquals( $lb->getWriterIndex(), $rGeneric->getLBInfo( 'serverIndex' ) );
	}

	/**
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getLazyConnectionRef
	 */
	public function testGetLazyConnectionRef() {
		$lb = $this->newMultiServerLocalLoadBalancer();

		$rMaster = $lb->getLazyConnectionRef( DB_MASTER );
		$rReplica = $lb->getLazyConnectionRef( 1 );
		$this->assertFalse( $lb->getAnyOpenConnection( 0 ) );
		$this->assertFalse( $lb->getAnyOpenConnection( 1 ) );

		$rMaster->getType();
		$rReplica->getType();
		$rMaster->getDomainID();
		$rReplica->getDomainID();
		$this->assertFalse( $lb->getAnyOpenConnection( 0 ) );
		$this->assertFalse( $lb->getAnyOpenConnection( 1 ) );

		$rMaster->query( "SELECT 1", __METHOD__ );
		$this->assertNotFalse( $lb->getAnyOpenConnection( 0 ) );

		$rReplica->query( "SELECT 1", __METHOD__ );
		$this->assertNotFalse( $lb->getAnyOpenConnection( 0 ) );
		$this->assertNotFalse( $lb->getAnyOpenConnection( 1 ) );
	}

	/**
	 * @covers LoadBalancer::setDomainAliases()
	 * @covers LoadBalancer::resolveDomainID()
	 */
	public function testSetDomainAliases() {
		$lb = $this->newMultiServerLocalLoadBalancer();
		$origDomain = $lb->getLocalDomainID();

		$this->assertEquals( $origDomain, $lb->resolveDomainID( false ) );
		$this->assertEquals( "db-prefix_", $lb->resolveDomainID( "db-prefix_" ) );

		$lb->setDomainAliases( [
			'alias-db' => 'realdb',
			'alias-db-prefix_' => 'realdb-realprefix_'
		] );

		$this->assertEquals( 'realdb', $lb->resolveDomainID( 'alias-db' ) );
		$this->assertEquals( "realdb-realprefix_", $lb->resolveDomainID( "alias-db-prefix_" ) );
	}
}
