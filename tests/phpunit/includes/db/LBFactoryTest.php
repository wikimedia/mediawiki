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
 * @author Antoine Musso
 * @copyright © 2013 Antoine Musso
 * @copyright © 2013 Wikimedia Foundation Inc.
 */

use MediaWiki\WikiMap\WikiMap;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\Rdbms\ChronologyProtector;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IDatabaseForOwner;
use Wikimedia\Rdbms\IMaintainableDatabase;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\LBFactoryMulti;
use Wikimedia\Rdbms\LBFactorySimple;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\Rdbms\LoadMonitorNull;
use Wikimedia\Rdbms\MySQLPrimaryPos;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 * @covers \Wikimedia\Rdbms\ChronologyProtector
 * @covers \Wikimedia\Rdbms\DatabaseMySQL
 * @covers \Wikimedia\Rdbms\DatabasePostgres
 * @covers \Wikimedia\Rdbms\DatabaseSqlite
 * @covers \Wikimedia\Rdbms\LBFactory
 * @covers \Wikimedia\Rdbms\LBFactory
 * @covers \Wikimedia\Rdbms\LBFactoryMulti
 * @covers \Wikimedia\Rdbms\LBFactorySimple
 * @covers \Wikimedia\Rdbms\LoadBalancer
 */
class LBFactoryTest extends MediaWikiIntegrationTestCase {

	private function getPrimaryServerConfig() {
		global $wgDBserver, $wgDBport, $wgDBname, $wgDBuser, $wgDBpassword, $wgDBtype;
		global $wgSQLiteDataDir;

		return [
			'serverName'  => 'db1',
			'host'        => $wgDBserver,
			'port'        => $wgDBport,
			'dbname'      => $wgDBname,
			'user'        => $wgDBuser,
			'password'    => $wgDBpassword,
			'type'        => $wgDBtype,
			'dbDirectory' => $wgSQLiteDataDir,
			'load'        => 0,
			'flags'       => DBO_TRX // REPEATABLE-READ for consistency
		];
	}

	public function testLBFactorySimpleServer() {
		$servers = [ $this->getPrimaryServerConfig() ];
		$factory = new LBFactorySimple( [ 'servers' => $servers ] );
		$lb = $factory->getMainLB();

		$dbw = $lb->getConnection( DB_PRIMARY );
		$this->assertNotFalse( $dbw );
		$dbr = $lb->getConnection( DB_REPLICA );
		$this->assertNotFalse( $dbr );

		$this->assertSame( 'DEFAULT', $lb->getClusterName() );

		$factory->shutdown();
	}

	public function testLBFactorySimpleServers() {
		$primaryConfig = $this->getPrimaryServerConfig();
		$fakeReplica = [ 'serverName' => 'db2', 'load' => 100 ] + $primaryConfig;

		$servers = [
			$primaryConfig,
			$fakeReplica
		];

		$factory = new LBFactorySimple( [
			'servers' => $servers,
			'loadMonitor' => [ 'class' => LoadMonitorNull::class ],
		] );
		$lb = $factory->getMainLB();

		$dbw = $lb->getConnection( DB_PRIMARY );
		$this->assertNotFalse( $dbw );
		$dbr = $lb->getConnection( DB_REPLICA );
		$this->assertNotFalse( $dbr );

		$factory->shutdown();
	}

	public function testLBFactoryMultiConns() {
		$factory = $this->newLBFactoryMultiLBs();

		$this->assertSame( 's3', $factory->getMainLB()->getClusterName() );

		$lb = $factory->getMainLB();
		$dbw = $lb->getConnection( DB_PRIMARY );
		$this->assertNotFalse( $dbw );
		$dbr = $lb->getConnection( DB_REPLICA );
		$this->assertNotFalse( $dbr );

		// Destructor should trigger without round stage errors
		unset( $factory );
	}

	public function testLBFactoryMultiRoundCallbacks() {
		$called = 0;
		$countLBsFunc = static function ( LBFactoryMulti $factory ) {
			$count = 0;
			foreach ( $factory->getAllLBs() as $lb ) {
				++$count;
			}

			return $count;
		};

		$factory = $this->newLBFactoryMultiLBs();
		$this->assertSame( 0, $countLBsFunc( $factory ) );
		$dbw = $factory->getMainLB()->getConnection( DB_PRIMARY );
		$this->assertSame( 1, $countLBsFunc( $factory ) );
		// Test that LoadBalancer instances made during pre-commit callbacks in do not
		// throw DBTransactionError due to transaction ROUND_* stages being mismatched.
		$factory->beginPrimaryChanges( __METHOD__ );
		$dbw->onTransactionPreCommitOrIdle( static function () use ( $factory, &$called ) {
			++$called;
			// Trigger s1 LoadBalancer instantiation during "finalize" stage.
			// There is no s1wiki DB to select so it is not in getConnection(),
			// but this fools getMainLB() at least.
			$factory->getMainLB( 's1wiki' )->getConnection( DB_PRIMARY );
		} );
		$factory->commitPrimaryChanges( __METHOD__ );
		$this->assertSame( 1, $called );
		$this->assertEquals( 2, $countLBsFunc( $factory ) );
		$factory->shutdown();
		$factory->closeAll( __METHOD__ );

		$called = 0;
		$factory = $this->newLBFactoryMultiLBs();
		$this->assertSame( 0, $countLBsFunc( $factory ) );
		$dbw = $factory->getMainLB()->getConnection( DB_PRIMARY );
		$this->assertSame( 1, $countLBsFunc( $factory ) );
		// Test that LoadBalancer instances made during pre-commit callbacks in do not
		// throw DBTransactionError due to transaction ROUND_* stages being mismatched.hrow
		// DBTransactionError due to transaction ROUND_* stages being mismatched.
		$factory->beginPrimaryChanges( __METHOD__ );
		// phpcs:ignore MediaWiki.Usage.DbrQueryUsage.DbrQueryFound
		$dbw->query( "SELECT 1 as t", __METHOD__ );
		$dbw->onTransactionResolution( static function () use ( $factory, &$called ) {
			++$called;
			// Trigger s1 LoadBalancer instantiation during "finalize" stage.
			// There is no s1wiki DB to select so it is not in getConnection(),
			// but this fools getMainLB() at least.
			$factory->getMainLB( 's1wiki' )->getConnection( DB_PRIMARY );
		} );
		$factory->commitPrimaryChanges( __METHOD__ );
		$this->assertSame( 1, $called );
		$this->assertEquals( 2, $countLBsFunc( $factory ) );
		$factory->shutdown();
		$factory->closeAll( __METHOD__ );

		$factory = $this->newLBFactoryMultiLBs();
		$dbw = $factory->getMainLB()->getConnection( DB_PRIMARY );
		// DBTransactionError should not be thrown
		$ran = 0;
		$dbw->onTransactionPreCommitOrIdle( static function () use ( &$ran ) {
			++$ran;
		} );
		$factory->commitPrimaryChanges( __METHOD__ );
		$this->assertSame( 1, $ran );

		$factory->shutdown();
		$factory->closeAll( __METHOD__ );
	}

	public function testLBFactoryMultiRoundTransactionSnapshots() {
		$factory = $this->newLBFactoryMultiLBs();
		$dbr = $factory->getMainLB()->getConnection( DB_REPLICA );
		$dbw = $factory->getMainLB()->getConnection( DB_PRIMARY );

		$dbr->begin( __METHOD__, $dbr::TRANSACTION_INTERNAL );
		$this->assertSame( 1, $dbr->trxLevel() );
		$this->assertSame( 0, $dbw->trxLevel() );

		$factory->beginPrimaryChanges( __METHOD__ );
		$this->assertSame( 0, $dbr->trxLevel() );
		$this->assertSame( 0, $dbw->trxLevel() );

		$dbr->begin( __METHOD__, $dbr::TRANSACTION_INTERNAL );
		$dbw->begin( __METHOD__, $dbw::TRANSACTION_INTERNAL );
		$this->assertSame( 1, $dbr->trxLevel() );
		$this->assertSame( 1, $dbw->trxLevel() );

		$factory->commitPrimaryChanges( __METHOD__ );
		$this->assertSame( 0, $dbr->trxLevel() );
		$this->assertSame( 0, $dbw->trxLevel() );

		$factory->beginPrimaryChanges( __METHOD__ );
		// phpcs:ignore MediaWiki.Usage.DbrQueryUsage.DbrQueryFound
		$dbr->query( 'SELECT 1', __METHOD__ );
		$this->assertSame( 1, $dbr->trxLevel() );
		$this->assertSame( 0, $dbw->trxLevel() );

		$factory->commitPrimaryChanges( __METHOD__ );
		$this->assertSame( 0, $dbr->trxLevel() );
		$this->assertSame( 0, $dbw->trxLevel() );
	}

	private function newLBFactoryMultiLBs() {
		global $wgDBserver, $wgDBport, $wgDBname, $wgDBuser, $wgDBpassword, $wgDBtype;
		global $wgSQLiteDataDir;

		return new LBFactoryMulti( [
			'sectionsByDB' => [
				's1wiki' => 's1',
				'DEFAULT' => 's3'
			],
			'sectionLoads' => [
				's1' => [
					'test-db3' => 0,
					'test-db4' => 100,
				],
				's3' => [
					'test-db1' => 0,
					'test-db2' => 100,
				]
			],
			'serverTemplate' => [
				'port' => $wgDBport,
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

		$hasChangesFunc = static function ( $mockDB ) {
			$p = $mockDB->writesOrCallbacksPending();
			$last = $mockDB->lastDoneWrites();

			return is_float( $last ) || $p;
		};

		// (a) First HTTP request
		$m1Pos = new MySQLPrimaryPos( 'db1034-bin.000976/843431247', $now );
		$m2Pos = new MySQLPrimaryPos( 'db1064-bin.002400/794074907', $now );

		// Primary DB 1
		/** @var IDatabaseForOwner|\PHPUnit\Framework\MockObject\MockObject $mockDB1 */
		$mockDB1 = $this->createMock( IDatabaseForOwner::class );
		$mockDB1->method( 'writesOrCallbacksPending' )->willReturn( true );
		$mockDB1->method( 'lastDoneWrites' )->willReturn( $now );
		// Load balancer for primary DB 1
		$lb1 = $this->createMock( LoadBalancer::class );
		$lb1->method( 'getConnection' )->willReturn( $mockDB1 );
		$lb1->method( 'getServerCount' )->willReturn( 2 );
		$lb1->method( 'hasReplicaServers' )->willReturn( true );
		$lb1->method( 'hasStreamingReplicaServers' )->willReturn( true );
		$lb1->method( 'getAnyOpenConnection' )->willReturn( $mockDB1 );
		$lb1->method( 'hasOrMadeRecentPrimaryChanges' )->willReturnCallback(
			static function () use ( $mockDB1, $hasChangesFunc ) {
				return $hasChangesFunc( $mockDB1 );
			}
		);
		$lb1->method( 'getPrimaryPos' )->willReturn( $m1Pos );
		$lb1->method( 'getServerName' )->with( 0 )->willReturn( 'master1' );
		// Primary DB 2
		/** @var IDatabaseForOwner|\PHPUnit\Framework\MockObject\MockObject $mockDB2 */
		$mockDB2 = $this->createMock( IDatabaseForOwner::class );
		$mockDB2->method( 'writesOrCallbacksPending' )->willReturn( true );
		$mockDB2->method( 'lastDoneWrites' )->willReturn( $now );
		// Load balancer for primary DB 2
		$lb2 = $this->createMock( LoadBalancer::class );
		$lb2->method( 'getConnection' )->willReturn( $mockDB2 );
		$lb2->method( 'getServerCount' )->willReturn( 2 );
		$lb2->method( 'hasReplicaServers' )->willReturn( true );
		$lb2->method( 'hasStreamingReplicaServers' )->willReturn( true );
		$lb2->method( 'getAnyOpenConnection' )->willReturn( $mockDB2 );
		$lb2->method( 'hasOrMadeRecentPrimaryChanges' )->willReturnCallback(
			static function () use ( $mockDB2, $hasChangesFunc ) {
				return $hasChangesFunc( $mockDB2 );
			}
		);
		$lb2->method( 'getServerName' )->with( 0 )->willReturn( 'master2' );
		$lb2->method( 'getPrimaryPos' )->willReturn( $m2Pos );

		$bag = new HashBagOStuff();
		$cp = new ChronologyProtector( $bag, null, false );
		$cp->setRequestInfo( [
			'IPAddress' => '127.0.0.1',
			'UserAgent' => 'Totally-Not-Firefox',
			'ChronologyClientId' => 'random_id',
		] );

		$mockDB1->expects( $this->once() )->method( 'writesOrCallbacksPending' );
		$mockDB1->expects( $this->once() )->method( 'lastDoneWrites' );
		$mockDB2->expects( $this->once() )->method( 'writesOrCallbacksPending' );
		$mockDB2->expects( $this->once() )->method( 'lastDoneWrites' );

		// Nothing to wait for on first HTTP request start
		$sPos1 = $cp->getSessionPrimaryPos( $lb1 );
		$sPos2 = $cp->getSessionPrimaryPos( $lb2 );
		// Record positions in stash on first HTTP request end
		$cp->stageSessionPrimaryPos( $lb1 );
		$cp->stageSessionPrimaryPos( $lb2 );
		$cpIndex = null;
		$cp->persistSessionReplicationPositions( $cpIndex );

		$this->assertNull( $sPos1 );
		$this->assertNull( $sPos2 );
		$this->assertSame( 1, $cpIndex, "CP write index set" );

		// (b) Second HTTP request

		// Load balancer for primary DB 1
		$lb1 = $this->createMock( LoadBalancer::class );
		$lb1->method( 'getServerCount' )->willReturn( 2 );
		$lb1->method( 'hasReplicaServers' )->willReturn( true );
		$lb1->method( 'hasStreamingReplicaServers' )->willReturn( true );
		$lb1->method( 'getServerName' )->with( 0 )->willReturn( 'master1' );
		// Load balancer for primary DB 2
		$lb2 = $this->createMock( LoadBalancer::class );
		$lb2->method( 'getServerCount' )->willReturn( 2 );
		$lb2->method( 'hasReplicaServers' )->willReturn( true );
		$lb2->method( 'hasStreamingReplicaServers' )->willReturn( true );
		$lb2->method( 'getServerName' )->with( 0 )->willReturn( 'master2' );

		$cp = new ChronologyProtector( $bag, null, false );
		$cp->setRequestInfo(
		[
			'IPAddress' => '127.0.0.1',
			'UserAgent' => 'Totally-Not-Firefox',
			'ChronologyClientId' => 'random_id',
			'ChronologyPositionIndex' => $cpIndex
		] );

		// Get last positions to be reached on second HTTP request start
		$sPos1 = $cp->getSessionPrimaryPos( $lb1 );
		$sPos2 = $cp->getSessionPrimaryPos( $lb2 );
		// Shutdown (nothing to record)
		$cp->stageSessionPrimaryPos( $lb1 );
		$cp->stageSessionPrimaryPos( $lb2 );
		$cpIndex = null;
		$cp->persistSessionReplicationPositions( $cpIndex );

		$this->assertNotNull( $sPos1 );
		$this->assertNotNull( $sPos2 );
		$this->assertSame( $m1Pos->__toString(), $sPos1->__toString() );
		$this->assertSame( $m2Pos->__toString(), $sPos2->__toString() );
		$this->assertNull( $cpIndex, "CP write index retained" );

		$this->assertEquals( 'random_id', $cp->getClientId() );
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

	public function testNiceDomains() {
		global $wgDBname;

		if ( $this->getDb()->databasesAreIndependent() ) {
			self::markTestSkipped( "Skipping tests about selecting DBs: not applicable" );
			return;
		}

		$factory = $this->newLBFactoryMulti(
			[],
			[]
		);
		$lb = $factory->getMainLB();

		$db = $lb->getConnection( DB_PRIMARY );
		$this->assertEquals(
			WikiMap::getCurrentWikiId(),
			$db->getDomainID()
		);
		unset( $db );

		/** @var IMaintainableDatabase $db */
		$db = $lb->getConnection( DB_PRIMARY, [], $lb::DOMAIN_ANY );

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

		unset( $db );

		$factory->setLocalDomainPrefix( 'my_' );
		$db = $lb->getConnection( DB_PRIMARY ); // local domain connection

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

		$factory->closeAll( __METHOD__ );
		$factory->destroy();
	}

	public function testTrickyDomain() {
		global $wgDBname;

		if ( $this->getDb()->databasesAreIndependent() ) {
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
		$db = $lb->getConnection( DB_PRIMARY, [], $lb::DOMAIN_ANY );

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

		unset( $db );

		$factory->setLocalDomainPrefix( 'my_' );
		$db = $lb->getConnection( DB_PRIMARY, [], "$wgDBname-my_" );

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

		$factory->closeAll( __METHOD__ );
		$factory->destroy();
	}

	public function testInvalidSelectDB() {
		if ( $this->getDb()->databasesAreIndependent() ) {
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
		$db = $lb->getConnection( DB_PRIMARY, [], $lb::DOMAIN_ANY );

		$this->expectException( \Wikimedia\Rdbms\DBUnexpectedError::class );
		$db->selectDomain( 'garbagedb' );
	}

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

		// FIXME: this should probably be lower (T235311)
		$this->expectException( \Wikimedia\Rdbms\DBConnectionError::class );
		if ( !$factory->getMainLB()->getServerAttributes( 0 )[Database::ATTR_DB_IS_FILE] ) {
			$this->markTestSkipped( "Not applicable per ATTR_DB_IS_FILE" );
		}

		$this->assertNotNull( $lb->getConnectionInternal( DB_PRIMARY, [], $lb::DOMAIN_ANY ) );
	}

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

		// FIXME: this should probably be lower (T235311)
		$this->expectException( \Wikimedia\Rdbms\DBExpectedError::class );
		if ( !$lb->getConnection( DB_PRIMARY )->databasesAreIndependent() ) {
			$this->markTestSkipped( "Not applicable per databasesAreIndependent()" );
		}

		$db = $lb->getConnectionInternal( DB_PRIMARY );
		$db->selectDomain( 'garbage-db' );
	}

	public function testRedefineLocalDomain() {
		global $wgDBname;

		if ( $this->getDb()->databasesAreIndependent() ) {
			self::markTestSkipped( "Skipping tests about selecting DBs: not applicable" );
			return;
		}

		$factory = $this->newLBFactoryMulti(
			[],
			[]
		);
		$lb = $factory->getMainLB();

		$conn1 = $lb->getConnection( DB_PRIMARY );
		$this->assertEquals(
			WikiMap::getCurrentWikiId(),
			$conn1->getDomainID()
		);
		unset( $conn1 );

		$factory->redefineLocalDomain( 'somedb-prefix_' );
		$this->assertEquals( 'somedb-prefix_', $factory->getLocalDomainID() );

		$domain = new DatabaseDomain( $wgDBname, null, 'pref_' );
		$factory->redefineLocalDomain( $domain );

		/** @var LoadBalancer $lbWrapper */
		$lbWrapper = TestingAccessWrapper::newFromObject( $lb );
		$n = iterator_count( $lbWrapper->getOpenConnections() );
		$this->assertSame( 0, $n, "Connections closed" );

		$conn2 = $lb->getConnection( DB_PRIMARY );
		$this->assertEquals(
			$domain->getId(),
			$conn2->getDomainID()
		);
		unset( $conn2 );

		$factory->closeAll( __METHOD__ );
		$factory->destroy();
	}

	public function testVirtualDomains() {
		$baseOverrides = [
			'localDomain' => ( new DatabaseDomain( 'localdomain', null, '' ) )->getId(),
			'sectionLoads' => [
				'DEFAULT' => [
					'test-db1' => 1,
				],
				'shareddb' => [
					'test-db1' => 1,
				],
			],
			'externalLoads' => [
				'extension1' => [
					'test-db1' => 1,
				],
			],
			'virtualDomains' => [ 'virtualdomain1', 'virtualdomain2', 'virtualdomain3', 'virtualdomain4' ],
			'virtualDomainsMapping' => [
				'virtualdomain1' => [ 'db' => 'extdomain', 'cluster' => 'extension1' ],
				'virtualdomain2' => [ 'db' => false, 'cluster' => 'extension1' ],
				'virtualdomain3' => [ 'db' => 'shareddb' ],
			]
		];
		$factory = $this->newLBFactoryMulti( $baseOverrides );
		$db1 = $factory->getPrimaryDatabase( 'virtualdomain1' );
		$this->assertEquals(
			'extdomain',
			$db1->getDomainID()
		);
		$this->assertEquals(
			'extdomain',
			$factory->getAutoCommitPrimaryConnection( 'virtualdomain1' )->getDomainID()
		);
		$this->assertEquals(
			'extension1',
			$factory->getLoadBalancer( 'virtualdomain1' )->getClusterName()
		);

		$db2 = $factory->getPrimaryDatabase( 'virtualdomain2' );
		$this->assertEquals(
			'localdomain',
			$db2->getDomainID()
		);
		$this->assertEquals(
			'localdomain',
			$factory->getAutoCommitPrimaryConnection( 'virtualdomain2' )->getDomainID()
		);
		$this->assertEquals(
			'extension1',
			$factory->getLoadBalancer( 'virtualdomain2' )->getClusterName()
		);

		$db3 = $factory->getPrimaryDatabase( 'virtualdomain3' );
		$this->assertEquals(
			'shareddb',
			$db3->getDomainID()
		);
		$this->assertEquals(
			'shareddb',
			$factory->getAutoCommitPrimaryConnection( 'virtualdomain3' )->getDomainID()
		);
		$this->assertEquals(
			'DEFAULT',
			$factory->getLoadBalancer( 'virtualdomain3' )->getClusterName()
		);

		$db4 = $factory->getPrimaryDatabase( 'virtualdomain4' );
		$this->assertEquals(
			'localdomain',
			$db4->getDomainID()
		);
		$this->assertEquals(
			'localdomain',
			$factory->getAutoCommitPrimaryConnection( 'virtualdomain4' )->getDomainID()
		);
		$this->assertEquals(
			'DEFAULT',
			$factory->getLoadBalancer( 'virtualdomain4' )->getClusterName()
		);
	}

	private function quoteTable( IReadableDatabase $db, $table ) {
		if ( $db->getType() === 'sqlite' ) {
			return $table;
		} else {
			return $db->addIdentifierQuotes( $table );
		}
	}

	public function testReconfigureWithOneReplica() {
		$primaryConfig = $this->getPrimaryServerConfig();
		$fakeReplica = [ 'load' => 100, 'serverName' => 'replica' ] + $primaryConfig;

		$conf = [ 'servers' => [
			$primaryConfig,
			$fakeReplica
		] ];

		// Configure an LBFactory with one replica
		$factory = new LBFactorySimple( $conf );
		$lb = $factory->getMainLB();
		$this->assertSame( 2, $lb->getServerCount() );

		$con = $lb->getConnectionInternal( DB_REPLICA );
		$ref = $lb->getConnection( DB_REPLICA );

		// Call reconfigure with the same config, should have no effect
		$factory->reconfigure( $conf );
		$this->assertSame( 2, $lb->getServerCount() );
		$this->assertTrue( $con->isOpen() );
		$this->assertTrue( $ref->isOpen() );

		// Call reconfigure with empty config, should have no effect
		$factory->reconfigure( [] );
		$this->assertSame( 2, $lb->getServerCount() );
		$this->assertTrue( $con->isOpen() );
		$this->assertTrue( $ref->isOpen() );

		// Reconfigure the LBFactory to only have a single server.
		$conf['servers'] = [ $this->getPrimaryServerConfig() ];
		$factory->reconfigure( $conf );

		// The LoadBalancer should have been reconfigured automatically.
		$this->assertSame( 1, $lb->getServerCount() );

		// Reconfiguring should not close connections immediately.
		$this->assertTrue( $con->isOpen() );

		// Connection refs should detect the config change, close the old connection,
		// and get a new connection.
		$this->assertTrue( $ref->isOpen() );

		// The old connection should have been closed by DBConnRef.
		$this->assertFalse( $con->isOpen() );
	}

	public function testReconfigureWithThreeReplicas() {
		$primaryConfig = $this->getPrimaryServerConfig();
		$replica1Config = [ 'serverName' => 'db2', 'load' => 0 ] + $primaryConfig;
		$replica2Config = [ 'serverName' => 'db3', 'load' => 1 ] + $primaryConfig;
		$replica3Config = [ 'serverName' => 'db4', 'load' => 1 ] + $primaryConfig;

		$conf = [ 'servers' => [
			$primaryConfig,
			$replica1Config,
			$replica2Config,
			$replica3Config
		] ];

		// Configure an LBFactory with two replicas
		$factory = new LBFactorySimple( $conf );
		$lb = $factory->getMainLB();
		$this->assertSame( 4, $lb->getServerCount() );
		$this->assertSame( 'db1', $lb->getServerName( 0 ) );
		$this->assertSame( 'db2', $lb->getServerName( 1 ) );
		$this->assertSame( 'db3', $lb->getServerName( 2 ) );
		$this->assertSame( 'db4', $lb->getServerName( 3 ) );

		$con = $lb->getConnectionInternal( DB_REPLICA );
		$ref = $lb->getConnection( DB_REPLICA );

		// Call reconfigure with the same config, should have no effect
		$factory->reconfigure( $conf );
		$this->assertSame( 4, $lb->getServerCount() );
		$this->assertSame( 'db1', $lb->getServerName( 0 ) );
		$this->assertSame( 'db2', $lb->getServerName( 1 ) );
		$this->assertSame( 'db3', $lb->getServerName( 2 ) );
		$this->assertSame( 'db4', $lb->getServerName( 3 ) );
		$this->assertTrue( $con->isOpen() );
		$this->assertTrue( $ref->isOpen() );

		// Call reconfigure with empty config, should have no effect
		$factory->reconfigure( [] );
		$this->assertSame( 4, $lb->getServerCount() );
		$this->assertSame( 'db1', $lb->getServerName( 0 ) );
		$this->assertSame( 'db2', $lb->getServerName( 1 ) );
		$this->assertSame( 'db3', $lb->getServerName( 2 ) );
		$this->assertSame( 'db4', $lb->getServerName( 3 ) );
		$this->assertTrue( $con->isOpen() );
		$this->assertTrue( $ref->isOpen() );

		// Reconfigure the LBFactory to only have a two servers (server indexes shifted).
		$conf['servers'] = [ $primaryConfig, $replica2Config, $replica3Config ];
		$factory->reconfigure( $conf );
		// The LoadBalancer should have been reconfigured automatically.
		$this->assertSame( 3, $lb->getServerCount() );
		$this->assertSame( 'db1', $lb->getServerName( 0 ) );
		$this->assertSame( false, $lb->getServerInfo( 1 ) );
		$this->assertSame( 'db3', $lb->getServerName( 2 ) );
		$this->assertSame( 'db4', $lb->getServerName( 3 ) );
		// Reconfiguring should not close connections immediately.
		$this->assertTrue( $con->isOpen() );
		// Connection refs should detect the config change, close the old connection,
		// and get a new connection.
		$this->assertTrue( $ref->isOpen() );
		// The old connection should have been closed by DBConnRef.
		$this->assertFalse( $con->isOpen() );
	}

	public function testAutoReconfigure() {
		$primaryConfig = $this->getPrimaryServerConfig();
		$fakeReplica = [ 'load' => 100, 'serverName' => 'replica1' ] + $primaryConfig;

		$conf = [
			'servers' => [
				$primaryConfig,
				$fakeReplica
			],
		];

		// The config callback should return $conf, reflecting changes
		// made to the local variable.
		$conf['configCallback'] = static function () use ( &$conf ) {
			static $calls = 0;
			$calls++;
			if ( $calls == 1 ) {
				return $conf;
			} else {
				unset( $conf['servers'][1] );
				return $conf;
			}
		};

		// Configure an LBFactory with one replica
		$factory = new LBFactorySimple( $conf );

		$lb = $factory->getMainLB();
		$this->assertSame( 2, $lb->getServerCount() );

		$con = $lb->getConnectionInternal( DB_REPLICA );
		$ref = $lb->getConnection( DB_REPLICA );

		// Nothing changed, autoReconfigure() should do nothing.
		$factory->autoReconfigure();

		$this->assertSame( 2, $lb->getServerCount() );
		$this->assertTrue( $con->isOpen() );
		$this->assertTrue( $ref->isOpen() );

		// Now autoReconfigure() should detect the change and reconfigure all LoadBalancers.
		$factory->autoReconfigure();

		// The LoadBalancer should have been reconfigured now.
		$this->assertSame( 1, $lb->getServerCount() );

		// Reconfiguring should not close connections immediately.
		$this->assertTrue( $con->isOpen() );

		// Connection refs should detect the config change, close the old connection,
		// and get a new connection.
		$this->assertTrue( $ref->isOpen() );

		// The old connection should have been called by DBConnRef.
		$this->assertFalse( $con->isOpen() );
	}

	public function testSetWaitForReplicationListener() {
		$factory = $this->newLBFactoryMultiLBs();

		$allLBs = iterator_to_array( $factory->getAllLBs() );
		$this->assertCount( 0, $allLBs );

		$runs = 0;
		$callback = static function () use ( &$runs ) {
			++$runs;
		};
		$factory->setWaitForReplicationListener( 'test', $callback );

		$this->assertSame( 0, $runs );
		$factory->waitForReplication();
		$this->assertSame( 1, $runs );

		$factory->getMainLB();
		$allLBs = iterator_to_array( $factory->getAllLBs() );
		$this->assertCount( 1, $allLBs );
		$factory->waitForReplication();
		$this->assertSame( 2, $runs );
	}
}
