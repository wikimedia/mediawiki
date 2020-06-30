<?php
/**
 * Holds tests for LBFactory abstract MediaWiki class.
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
 * @author Antoine Musso
 * @copyright © 2013 Antoine Musso
 * @copyright © 2013 Wikimedia Foundation Inc.
 */

use Wikimedia\AtEase\AtEase;
use Wikimedia\Rdbms\ChronologyProtector;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IMaintainableDatabase;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\LBFactoryMulti;
use Wikimedia\Rdbms\LBFactorySimple;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\Rdbms\LoadMonitorNull;
use Wikimedia\Rdbms\MySQLMasterPos;

/**
 * @group Database
 * @covers \Wikimedia\Rdbms\LBFactory
 * @covers \Wikimedia\Rdbms\LBFactorySimple
 * @covers \Wikimedia\Rdbms\LBFactoryMulti
 */
class LBFactoryTest extends MediaWikiIntegrationTestCase {
	/**
	 * @covers \Wikimedia\Rdbms\LBFactory::getLocalDomainID()
	 * @covers \Wikimedia\Rdbms\LBFactory::resolveDomainID()
	 */
	public function testLBFactorySimpleServer() {
		global $wgDBserver, $wgDBname, $wgDBuser, $wgDBpassword, $wgDBtype, $wgSQLiteDataDir;

		$servers = [
			[
				'host'        => $wgDBserver,
				'dbname'      => $wgDBname,
				'user'        => $wgDBuser,
				'password'    => $wgDBpassword,
				'type'        => $wgDBtype,
				'dbDirectory' => $wgSQLiteDataDir,
				'load'        => 0,
				'flags'       => DBO_TRX // REPEATABLE-READ for consistency
			],
		];

		$factory = new LBFactorySimple( [ 'servers' => $servers ] );
		$lb = $factory->getMainLB();

		$dbw = $lb->getConnection( DB_MASTER );
		$this->assertEquals(
			$dbw::ROLE_STREAMING_MASTER, $dbw->getTopologyRole(), 'master shows as master' );

		$dbr = $lb->getConnection( DB_REPLICA );
		$this->assertEquals(
			$dbr::ROLE_STREAMING_MASTER, $dbw->getTopologyRole(), 'replica shows as replica' );

		$this->assertSame( 'my_test_wiki', $factory->resolveDomainID( 'my_test_wiki' ) );
		$this->assertSame( $factory->getLocalDomainID(), $factory->resolveDomainID( false ) );

		$factory->shutdown();
	}

	public function testLBFactorySimpleServers() {
		global $wgDBserver, $wgDBname, $wgDBuser, $wgDBpassword, $wgDBtype, $wgSQLiteDataDir;

		$servers = [
			[ // master
				'host'        => $wgDBserver,
				'dbname'      => $wgDBname,
				'user'        => $wgDBuser,
				'password'    => $wgDBpassword,
				'type'        => $wgDBtype,
				'dbDirectory' => $wgSQLiteDataDir,
				'load'        => 0,
				'flags'       => DBO_TRX // REPEATABLE-READ for consistency
			],
			[ // emulated replica
				'host'        => $wgDBserver,
				'dbname'      => $wgDBname,
				'user'        => $wgDBuser,
				'password'    => $wgDBpassword,
				'type'        => $wgDBtype,
				'dbDirectory' => $wgSQLiteDataDir,
				'load'        => 100,
				'flags'       => DBO_TRX // REPEATABLE-READ for consistency
			]
		];

		$factory = new LBFactorySimple( [
			'servers' => $servers,
			'loadMonitor' => [ 'class' => LoadMonitorNull::class ],
		] );
		$lb = $factory->getMainLB();

		$dbw = $lb->getConnection( DB_MASTER );
		$this->assertEquals(
			$dbw::ROLE_STREAMING_MASTER, $dbw->getTopologyRole(), 'master shows as master' );
		$this->assertEquals(
			( $wgDBserver != '' ) ? $wgDBserver : 'localhost',
			$dbw->getTopologyRootMaster(),
			'cluster master set' );

		$dbr = $lb->getConnection( DB_REPLICA );
		$this->assertEquals(
			$dbr::ROLE_STREAMING_REPLICA, $dbr->getTopologyRole(), 'replica shows as replica' );

		$this->assertEquals(
			( $wgDBserver != '' ) ? $wgDBserver : 'localhost',
			$dbr->getTopologyRootMaster(),
			'cluster master set'
		);

		$factory->shutdown();
	}

	public function testLBFactoryMultiConns() {
		$factory = $this->newLBFactoryMultiLBs();

		$dbw = $factory->getMainLB()->getConnection( DB_MASTER );
		$this->assertEquals(
			$dbw::ROLE_STREAMING_MASTER, $dbw->getTopologyRole(), 'master shows as master' );

		$dbr = $factory->getMainLB()->getConnection( DB_REPLICA );
		$this->assertEquals(
			$dbr::ROLE_STREAMING_REPLICA, $dbr->getTopologyRole(), 'replica shows as replica' );

		// Destructor should trigger without round stage errors
		unset( $factory );
	}

	public function testLBFactoryMultiRoundCallbacks() {
		$called = 0;
		$countLBsFunc = function ( LBFactoryMulti $factory ) {
			$count = 0;
			$factory->forEachLB( function () use ( &$count ) {
				++$count;
			} );

			return $count;
		};

		$factory = $this->newLBFactoryMultiLBs();
		$this->assertSame( 0, $countLBsFunc( $factory ) );
		$dbw = $factory->getMainLB()->getConnection( DB_MASTER );
		$this->assertSame( 1, $countLBsFunc( $factory ) );
		// Test that LoadBalancer instances made during pre-commit callbacks in do not
		// throw DBTransactionError due to transaction ROUND_* stages being mismatched.
		$factory->beginMasterChanges( __METHOD__ );
		$dbw->onTransactionPreCommitOrIdle( function () use ( $factory, &$called ) {
			++$called;
			// Trigger s1 LoadBalancer instantiation during "finalize" stage.
			// There is no s1wiki DB to select so it is not in getConnection(),
			// but this fools getMainLB() at least.
			$factory->getMainLB( 's1wiki' )->getConnection( DB_MASTER );
		} );
		$factory->commitMasterChanges( __METHOD__ );
		$this->assertSame( 1, $called );
		$this->assertEquals( 2, $countLBsFunc( $factory ) );
		$factory->shutdown();
		$factory->closeAll();

		$called = 0;
		$factory = $this->newLBFactoryMultiLBs();
		$this->assertSame( 0, $countLBsFunc( $factory ) );
		$dbw = $factory->getMainLB()->getConnection( DB_MASTER );
		$this->assertSame( 1, $countLBsFunc( $factory ) );
		// Test that LoadBalancer instances made during pre-commit callbacks in do not
		// throw DBTransactionError due to transaction ROUND_* stages being mismatched.hrow
		// DBTransactionError due to transaction ROUND_* stages being mismatched.
		$factory->beginMasterChanges( __METHOD__ );
		$dbw->query( "SELECT 1 as t", __METHOD__ );
		$dbw->onTransactionResolution( function () use ( $factory, &$called ) {
			++$called;
			// Trigger s1 LoadBalancer instantiation during "finalize" stage.
			// There is no s1wiki DB to select so it is not in getConnection(),
			// but this fools getMainLB() at least.
			$factory->getMainLB( 's1wiki' )->getConnection( DB_MASTER );
		} );
		$factory->commitMasterChanges( __METHOD__ );
		$this->assertSame( 1, $called );
		$this->assertEquals( 2, $countLBsFunc( $factory ) );
		$factory->shutdown();
		$factory->closeAll();

		$factory = $this->newLBFactoryMultiLBs();
		$dbw = $factory->getMainLB()->getConnection( DB_MASTER );
		// DBTransactionError should not be thrown
		$ran = 0;
		$dbw->onTransactionPreCommitOrIdle( function () use ( &$ran ) {
			++$ran;
		} );
		$factory->commitAll( __METHOD__ );
		$this->assertSame( 1, $ran );

		$factory->shutdown();
		$factory->closeAll();
	}

	private function newLBFactoryMultiLBs() {
		global $wgDBserver, $wgDBname, $wgDBuser, $wgDBpassword, $wgDBtype, $wgSQLiteDataDir;

		return new LBFactoryMulti( [
			'sectionsByDB' => [
				's1wiki' => 's1',
			],
			'sectionLoads' => [
				's1' => [
					'test-db3' => 0,
					'test-db4' => 100,
				],
				'DEFAULT' => [
					'test-db1' => 0,
					'test-db2' => 100,
				]
			],
			'serverTemplate' => [
				'dbname' => $wgDBname,
				'user' => $wgDBuser,
				'password' => $wgDBpassword,
				'type' => $wgDBtype,
				'dbDirectory' => $wgSQLiteDataDir,
				'flags' => DBO_DEFAULT
			],
			'hostsByName' => [
				'test-db1' => $wgDBserver,
				'test-db2' => $wgDBserver,
				'test-db3' => $wgDBserver,
				'test-db4' => $wgDBserver
			],
			'loadMonitor' => [ 'class' => LoadMonitorNull::class ],
		] );
	}

	/**
	 * @covers \Wikimedia\Rdbms\ChronologyProtector
	 */
	public function testChronologyProtector() {
		$now = microtime( true );

		// (a) First HTTP request
		$m1Pos = new MySQLMasterPos( 'db1034-bin.000976/843431247', $now );
		$m2Pos = new MySQLMasterPos( 'db1064-bin.002400/794074907', $now );

		// Master DB 1
		/** @var IDatabase|\PHPUnit\Framework\MockObject\MockObject $mockDB1 */
		$mockDB1 = $this->getMockBuilder( IDatabase::class )
			->disableOriginalConstructor()
			->getMock();
		$mockDB1->method( 'writesOrCallbacksPending' )->willReturn( true );
		$mockDB1->method( 'lastDoneWrites' )->willReturn( $now );
		$mockDB1->method( 'getMasterPos' )->willReturn( $m1Pos );
		// Load balancer for master DB 1
		$lb1 = $this->getMockBuilder( LoadBalancer::class )
			->disableOriginalConstructor()
			->getMock();
		$lb1->method( 'getConnection' )->willReturn( $mockDB1 );
		$lb1->method( 'getServerCount' )->willReturn( 2 );
		$lb1->method( 'hasReplicaServers' )->willReturn( true );
		$lb1->method( 'hasStreamingReplicaServers' )->willReturn( true );
		$lb1->method( 'getAnyOpenConnection' )->willReturn( $mockDB1 );
		$lb1->method( 'hasOrMadeRecentMasterChanges' )->will( $this->returnCallback(
				function () use ( $mockDB1 ) {
					$p = 0;
					$p |= $mockDB1->writesOrCallbacksPending();
					$p |= $mockDB1->lastDoneWrites();

					return (bool)$p;
				}
			) );
		$lb1->method( 'getMasterPos' )->willReturn( $m1Pos );
		$lb1->method( 'getReplicaResumePos' )->willReturn( $m1Pos );
		$lb1->method( 'getServerName' )->with( 0 )->willReturn( 'master1' );
		// Master DB 2
		/** @var IDatabase|\PHPUnit\Framework\MockObject\MockObject $mockDB2 */
		$mockDB2 = $this->getMockBuilder( IDatabase::class )
			->disableOriginalConstructor()
			->getMock();
		$mockDB2->method( 'writesOrCallbacksPending' )->willReturn( true );
		$mockDB2->method( 'lastDoneWrites' )->willReturn( $now );
		$mockDB2->method( 'getMasterPos' )->willReturn( $m2Pos );
		// Load balancer for master DB 2
		$lb2 = $this->getMockBuilder( LoadBalancer::class )
			->disableOriginalConstructor()
			->getMock();
		$lb2->method( 'getConnection' )->willReturn( $mockDB2 );
		$lb2->method( 'getServerCount' )->willReturn( 2 );
		$lb2->method( 'hasReplicaServers' )->willReturn( true );
		$lb2->method( 'hasStreamingReplicaServers' )->willReturn( true );
		$lb2->method( 'getAnyOpenConnection' )->willReturn( $mockDB2 );
		$lb2->method( 'hasOrMadeRecentMasterChanges' )->will( $this->returnCallback(
			function () use ( $mockDB2 ) {
				$p = 0;
				$p |= $mockDB2->writesOrCallbacksPending();
				$p |= $mockDB2->lastDoneWrites();

				return (bool)$p;
			}
		) );
		$lb2->method( 'getMasterPos' )->willReturn( $m2Pos );
		$lb2->method( 'getReplicaResumePos' )->willReturn( $m2Pos );
		$lb2->method( 'getServerName' )->with( 0 )->willReturn( 'master2' );

		$bag = new HashBagOStuff();
		$cp = new ChronologyProtector(
			$bag,
			[
				'ip' => '127.0.0.1',
				'agent' => "Totally-Not-FireFox"
			],
			null
		);

		$mockDB1->expects( $this->exactly( 1 ) )->method( 'writesOrCallbacksPending' );
		$mockDB1->expects( $this->exactly( 1 ) )->method( 'lastDoneWrites' );
		$mockDB2->expects( $this->exactly( 1 ) )->method( 'writesOrCallbacksPending' );
		$mockDB2->expects( $this->exactly( 1 ) )->method( 'lastDoneWrites' );

		// Nothing to wait for on first HTTP request start
		$cp->applySessionReplicationPosition( $lb1 );
		$cp->applySessionReplicationPosition( $lb2 );
		// Record positions in stash on first HTTP request end
		$cp->storeSessionReplicationPosition( $lb1 );
		$cp->storeSessionReplicationPosition( $lb2 );
		$cpIndex = null;
		$cp->shutdown( null, 'sync', $cpIndex );

		$this->assertSame( 1, $cpIndex, "CP write index set" );

		// (b) Second HTTP request

		// Load balancer for master DB 1
		$lb1 = $this->getMockBuilder( LoadBalancer::class )
			->disableOriginalConstructor()
			->getMock();
		$lb1->method( 'getServerCount' )->willReturn( 2 );
		$lb1->method( 'hasReplicaServers' )->willReturn( true );
		$lb1->method( 'hasStreamingReplicaServers' )->willReturn( true );
		$lb1->method( 'getServerName' )->with( 0 )->willReturn( 'master1' );
		$lb1->expects( $this->once() )
			->method( 'waitFor' )->with( $this->equalTo( $m1Pos ) );
		// Load balancer for master DB 2
		$lb2 = $this->getMockBuilder( LoadBalancer::class )
			->disableOriginalConstructor()
			->getMock();
		$lb2->method( 'getServerCount' )->willReturn( 2 );
		$lb2->method( 'hasReplicaServers' )->willReturn( true );
		$lb2->method( 'hasStreamingReplicaServers' )->willReturn( true );
		$lb2->method( 'getServerName' )->with( 0 )->willReturn( 'master2' );
		$lb2->expects( $this->once() )
			->method( 'waitFor' )->with( $this->equalTo( $m2Pos ) );

		$cp = new ChronologyProtector(
			$bag,
			[
				'ip' => '127.0.0.1',
				'agent' => "Totally-Not-FireFox"
			],
			$cpIndex
		);

		// Wait for last positions to be reached on second HTTP request start
		$cp->applySessionReplicationPosition( $lb1 );
		$cp->applySessionReplicationPosition( $lb2 );
		// Shutdown (nothing to record)
		$cp->storeSessionReplicationPosition( $lb1 );
		$cp->storeSessionReplicationPosition( $lb2 );
		$cpIndex = null;
		$cp->shutdown( null, 'sync', $cpIndex );

		$this->assertNull( $cpIndex, "CP write index retained" );

		$this->assertEquals( '45e93a9c215c031d38b7c42d8e4700ca', $cp->getClientId() );
	}

	private function newLBFactoryMulti( array $baseOverride = [], array $serverOverride = [] ) {
		global $wgDBserver, $wgDBuser, $wgDBpassword, $wgDBname, $wgDBprefix, $wgDBtype;
		global $wgSQLiteDataDir;

		return new LBFactoryMulti( $baseOverride + [
			'sectionsByDB' => [],
			'sectionLoads' => [
				'DEFAULT' => [
					'test-db1' => 1,
				],
			],
			'serverTemplate' => $serverOverride + [
				'dbname' => $wgDBname,
				'tablePrefix' => $wgDBprefix,
				'user' => $wgDBuser,
				'password' => $wgDBpassword,
				'type' => $wgDBtype,
				'dbDirectory' => $wgSQLiteDataDir,
				'flags' => DBO_DEFAULT
			],
			'hostsByName' => [
				'test-db1' => $wgDBserver,
			],
			'loadMonitor' => [ 'class' => LoadMonitorNull::class ],
			'localDomain' => new DatabaseDomain( $wgDBname, null, $wgDBprefix ),
			'agent' => 'MW-UNIT-TESTS'
		] );
	}

	/**
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getConnection
	 * @covers \Wikimedia\Rdbms\DatabaseMysqlBase::doSelectDomain
	 * @covers \Wikimedia\Rdbms\DatabaseMysqlBase::selectDB
	 */
	public function testNiceDomains() {
		global $wgDBname;

		if ( wfGetDB( DB_MASTER )->databasesAreIndependent() ) {
			self::markTestSkipped( "Skipping tests about selecting DBs: not applicable" );
			return;
		}

		$factory = $this->newLBFactoryMulti(
			[],
			[]
		);
		$lb = $factory->getMainLB();

		$db = $lb->getConnectionRef( DB_MASTER );
		$this->assertEquals(
			wfWikiID(),
			$db->getDomainID()
		);
		unset( $db );

		/** @var IMaintainableDatabase $db */
		$db = $lb->getConnection( DB_MASTER, [], $lb::DOMAIN_ANY );

		$this->assertSame(
			'',
			$db->getDomainID(),
			'Null domain ID handle used'
		);
		$this->assertNull(
			$db->getDBname(),
			'Null domain ID handle used'
		);
		$this->assertSame(
			'',
			$db->tablePrefix(),
			'Main domain ID handle used; prefix is empty though'
		);
		$this->assertEquals(
			$this->quoteTable( $db, 'page' ),
			$db->tableName( 'page' ),
			"Correct full table name"
		);
		$this->assertEquals(
			$this->quoteTable( $db, $wgDBname ) . '.' . $this->quoteTable( $db, 'page' ),
			$db->tableName( "$wgDBname.page" ),
			"Correct full table name"
		);
		$this->assertEquals(
			$this->quoteTable( $db, 'nice_db' ) . '.' . $this->quoteTable( $db, 'page' ),
			$db->tableName( 'nice_db.page' ),
			"Correct full table name"
		);

		$lb->reuseConnection( $db ); // don't care

		$db = $lb->getConnection( DB_MASTER ); // local domain connection
		$factory->setLocalDomainPrefix( 'my_' );

		$this->assertEquals( $wgDBname, $db->getDBname() );
		$this->assertEquals(
			"$wgDBname-my_",
			$db->getDomainID()
		);
		$this->assertEquals(
			$this->quoteTable( $db, 'my_page' ),
			$db->tableName( 'page' ),
			"Correct full table name"
		);
		$this->assertEquals(
			$this->quoteTable( $db, 'other_nice_db' ) . '.' . $this->quoteTable( $db, 'page' ),
			$db->tableName( 'other_nice_db.page' ),
			"Correct full table name"
		);

		$factory->closeAll();
		$factory->destroy();
	}

	/**
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getConnection
	 * @covers \Wikimedia\Rdbms\DatabaseMysqlBase::doSelectDomain
	 * @covers \Wikimedia\Rdbms\DatabaseMysqlBase::selectDB
	 */
	public function testTrickyDomain() {
		global $wgDBname;

		if ( wfGetDB( DB_MASTER )->databasesAreIndependent() ) {
			self::markTestSkipped( "Skipping tests about selecting DBs: not applicable" );
			return;
		}

		$dbname = 'unittest-domain'; // explodes if DB is selected
		$factory = $this->newLBFactoryMulti(
			[ 'localDomain' => ( new DatabaseDomain( $dbname, null, '' ) )->getId() ],
			[
				'dbname' => 'do_not_select_me' // explodes if DB is selected
			]
		);
		$lb = $factory->getMainLB();
		/** @var IMaintainableDatabase $db */
		$db = $lb->getConnection( DB_MASTER, [], $lb::DOMAIN_ANY );

		$this->assertSame( '', $db->getDomainID(), "Null domain used" );

		$this->assertEquals(
			$this->quoteTable( $db, 'page' ),
			$db->tableName( 'page' ),
			"Correct full table name"
		);

		$this->assertEquals(
			$this->quoteTable( $db, $dbname ) . '.' . $this->quoteTable( $db, 'page' ),
			$db->tableName( "$dbname.page" ),
			"Correct full table name"
		);

		$this->assertEquals(
			$this->quoteTable( $db, 'nice_db' ) . '.' . $this->quoteTable( $db, 'page' ),
			$db->tableName( 'nice_db.page' ),
			"Correct full table name"
		);

		$lb->reuseConnection( $db ); // don't care

		$factory->setLocalDomainPrefix( 'my_' );
		$db = $lb->getConnection( DB_MASTER, [], "$wgDBname-my_" );

		$this->assertEquals(
			$this->quoteTable( $db, 'my_page' ),
			$db->tableName( 'page' ),
			"Correct full table name"
		);
		$this->assertEquals(
			$this->quoteTable( $db, 'other_nice_db' ) . '.' . $this->quoteTable( $db, 'page' ),
			$db->tableName( 'other_nice_db.page' ),
			"Correct full table name"
		);
		$this->assertEquals(
			$this->quoteTable( $db, 'garbage-db' ) . '.' . $this->quoteTable( $db, 'page' ),
			$db->tableName( 'garbage-db.page' ),
			"Correct full table name"
		);

		$lb->reuseConnection( $db ); // don't care

		$factory->closeAll();
		$factory->destroy();
	}

	/**
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getConnection
	 * @covers \Wikimedia\Rdbms\DatabaseMysqlBase::doSelectDomain
	 * @covers \Wikimedia\Rdbms\DatabaseMysqlBase::selectDB
	 */
	public function testInvalidSelectDB() {
		if ( wfGetDB( DB_MASTER )->databasesAreIndependent() ) {
			$this->markTestSkipped( "Not applicable per databasesAreIndependent()" );
		}

		$dbname = 'unittest-domain'; // explodes if DB is selected
		$factory = $this->newLBFactoryMulti(
			[ 'localDomain' => ( new DatabaseDomain( $dbname, null, '' ) )->getId() ],
			[
				'dbname' => 'do_not_select_me' // explodes if DB is selected
			]
		);
		$lb = $factory->getMainLB();
		/** @var IDatabase $db */
		$db = $lb->getConnection( DB_MASTER, [], $lb::DOMAIN_ANY );

		AtEase::suppressWarnings();
		try {
			$this->assertFalse( $db->selectDomain( 'garbagedb' ) );
			$this->fail( "No error thrown." );
		} catch ( \Wikimedia\Rdbms\DBQueryError $e ) {
			$this->assertRegExp( '/[\'"]garbagedb[\'"]/', $e->getMessage() );
		}
		AtEase::restoreWarnings();
	}

	/**
	 * @covers \Wikimedia\Rdbms\DatabaseSqlite::selectDB
	 * @covers \Wikimedia\Rdbms\DatabasePostgres::selectDB
	 */
	public function testInvalidSelectDBIndependent() {
		$dbname = 'unittest-domain'; // explodes if DB is selected
		$factory = $this->newLBFactoryMulti(
			[ 'localDomain' => ( new DatabaseDomain( $dbname, null, '' ) )->getId() ],
			[
				// Explodes with SQLite and Postgres during open/USE
				'dbname' => 'bad_dir/do_not_select_me'
			]
		);
		$lb = $factory->getMainLB();

		// FIXME: this should probaly be lower (T235311)
		$this->expectException( \Wikimedia\Rdbms\DBConnectionError::class );
		if ( !$factory->getMainLB()->getServerAttributes( 0 )[Database::ATTR_DB_IS_FILE] ) {
			$this->markTestSkipped( "Not applicable per ATTR_DB_IS_FILE" );
		}

		/** @var IDatabase $db */
		$this->assertNotNull( $lb->getConnection( DB_MASTER, [], $lb::DOMAIN_ANY ) );
	}

	/**
	 * @covers \Wikimedia\Rdbms\DatabaseSqlite::selectDB
	 * @covers \Wikimedia\Rdbms\DatabasePostgres::selectDB
	 */
	public function testInvalidSelectDBIndependent2() {
		$dbname = 'unittest-domain'; // explodes if DB is selected
		$factory = $this->newLBFactoryMulti(
			[ 'localDomain' => ( new DatabaseDomain( $dbname, null, '' ) )->getId() ],
			[
				// Explodes with SQLite and Postgres during open/USE
				'dbname' => 'bad_dir/do_not_select_me'
			]
		);
		$lb = $factory->getMainLB();

		// FIXME: this should probaly be lower (T235311)
		$this->expectException( \Wikimedia\Rdbms\DBExpectedError::class );
		if ( !$lb->getConnection( DB_MASTER )->databasesAreIndependent() ) {
			$this->markTestSkipped( "Not applicable per databasesAreIndependent()" );
		}

		$db = $lb->getConnection( DB_MASTER );
		$db->selectDomain( 'garbage-db' );
	}

	/**
	 * @covers \Wikimedia\Rdbms\LoadBalancer::getConnection
	 * @covers \Wikimedia\Rdbms\LoadBalancer::redefineLocalDomain
	 * @covers \Wikimedia\Rdbms\DatabaseMysqlBase::selectDB
	 */
	public function testRedefineLocalDomain() {
		global $wgDBname;

		if ( wfGetDB( DB_MASTER )->databasesAreIndependent() ) {
			self::markTestSkipped( "Skipping tests about selecting DBs: not applicable" );
			return;
		}

		$factory = $this->newLBFactoryMulti(
			[],
			[]
		);
		$lb = $factory->getMainLB();

		$conn1 = $lb->getConnectionRef( DB_MASTER );
		$this->assertEquals(
			wfWikiID(),
			$conn1->getDomainID()
		);
		unset( $conn1 );

		$factory->redefineLocalDomain( 'somedb-prefix_' );
		$this->assertEquals( 'somedb-prefix_', $factory->getLocalDomainID() );

		$domain = new DatabaseDomain( $wgDBname, null, 'pref_' );
		$factory->redefineLocalDomain( $domain );

		$n = 0;
		$lb->forEachOpenConnection( function () use ( &$n ) {
			++$n;
		} );
		$this->assertSame( 0, $n, "Connections closed" );

		$conn2 = $lb->getConnectionRef( DB_MASTER );
		$this->assertEquals(
			$domain->getId(),
			$conn2->getDomainID()
		);
		unset( $conn2 );

		$factory->closeAll();
		$factory->destroy();
	}

	private function quoteTable( IDatabase $db, $table ) {
		if ( $db->getType() === 'sqlite' ) {
			return $table;
		} else {
			return $db->addIdentifierQuotes( $table );
		}
	}

	/**
	 * @covers \Wikimedia\Rdbms\LBFactory::makeCookieValueFromCPIndex()
	 * @covers \Wikimedia\Rdbms\LBFactory::getCPInfoFromCookieValue()
	 */
	public function testCPPosIndexCookieValues() {
		$time = 1526522031;
		$agentId = md5( 'Ramsey\'s Loyal Presa Canario' );

		$this->assertEquals(
			'3@542#c47dcfb0566e7d7bc110a6128a45c93a',
			LBFactory::makeCookieValueFromCPIndex( 3, 542, $agentId )
		);

		$lbFactory = $this->newLBFactoryMulti();
		$lbFactory->setRequestInfo( [ 'IPAddress' => '10.64.24.52', 'UserAgent' => 'meow' ] );
		$this->assertEquals(
			'1@542#c47dcfb0566e7d7bc110a6128a45c93a',
			LBFactory::makeCookieValueFromCPIndex( 1, 542, $agentId )
		);

		$this->assertSame(
			null,
			LBFactory::getCPInfoFromCookieValue( "5#$agentId", $time - 10 )['index'],
			'No time set'
		);
		$this->assertSame(
			null,
			LBFactory::getCPInfoFromCookieValue( "5@$time", $time - 10 )['index'],
			'No agent set'
		);
		$this->assertSame(
			null,
			LBFactory::getCPInfoFromCookieValue( "0@$time#$agentId", $time - 10 )['index'],
			'Bad index'
		);

		$this->assertSame(
			2,
			LBFactory::getCPInfoFromCookieValue( "2@$time#$agentId", $time - 10 )['index'],
			'Fresh'
		);
		$this->assertSame(
			2,
			LBFactory::getCPInfoFromCookieValue( "2@$time#$agentId", $time + 9 - 10 )['index'],
			'Almost stale'
		);
		$this->assertSame(
			null,
			LBFactory::getCPInfoFromCookieValue( "0@$time#$agentId", $time + 9 - 10 )['index'],
			'Almost stale; bad index'
		);
		$this->assertSame(
			null,
			LBFactory::getCPInfoFromCookieValue( "2@$time#$agentId", $time + 11 - 10 )['index'],
			'Stale'
		);

		$this->assertSame(
			$agentId,
			LBFactory::getCPInfoFromCookieValue( "5@$time#$agentId", $time - 10 )['clientId'],
			'Live (client ID)'
		);
		$this->assertSame(
			null,
			LBFactory::getCPInfoFromCookieValue( "5@$time#$agentId", $time + 11 - 10 )['clientId'],
			'Stale (client ID)'
		);
	}

	/**
	 * @covers \Wikimedia\Rdbms\LBFactory::setDomainAliases()
	 * @covers \Wikimedia\Rdbms\LBFactory::resolveDomainID()
	 */
	public function testSetDomainAliases() {
		$lb = $this->newLBFactoryMulti();
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

	/**
	 * @covers \Wikimedia\Rdbms\ChronologyProtector
	 * @covers \Wikimedia\Rdbms\LBFactory
	 */
	public function testGetChronologyProtectorTouched() {
		$store = new HashBagOStuff;
		$lbFactory = $this->newLBFactoryMulti( [
			'memStash' => $store
		] );
		$lbFactory->setRequestInfo( [ 'ChronologyClientId' => 'ii' ] );
		$key = $store->makeGlobalKey( ChronologyProtector::class,
			'mtime', 'ii', 'test-db1' );
		$store->set( $key, 2 );
		$touched = $lbFactory->getChronologyProtectorTouched();
		$this->assertEquals( 2, $touched );
	}
}
