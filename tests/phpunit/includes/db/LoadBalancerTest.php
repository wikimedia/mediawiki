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

use Wikimedia\Rdbms\DBError;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\Rdbms\LoadMonitorNull;

/**
 * @group Database
 * @covers \Wikimedia\Rdbms\LoadBalancer
 */
class LoadBalancerTest extends MediaWikiTestCase {
	private function makeServerConfig() {
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
			'flags' => DBO_TRX // REPEATABLE-READ for consistency
		];
	}

	public function testWithoutReplica() {
		global $wgDBname;

		$called = false;
		$lb = new LoadBalancer( [
			'servers' => [ $this->makeServerConfig() ],
			'queryLogger' => MediaWiki\Logger\LoggerFactory::getInstance( 'DBQuery' ),
			'localDomain' => new DatabaseDomain( $wgDBname, null, $this->dbPrefix() ),
			'chronologyCallback' => function () use ( &$called ) {
				$called = true;
			}
		] );

		$ld = DatabaseDomain::newFromId( $lb->getLocalDomainID() );
		$this->assertEquals( $wgDBname, $ld->getDatabase(), 'local domain DB set' );
		$this->assertEquals( $this->dbPrefix(), $ld->getTablePrefix(), 'local domain prefix set' );

		$this->assertFalse( $called );
		$dbw = $lb->getConnection( DB_MASTER );
		$this->assertTrue( $called );
		$this->assertTrue( $dbw->getLBInfo( 'master' ), 'master shows as master' );
		$this->assertTrue( $dbw->getFlag( $dbw::DBO_TRX ), "DBO_TRX set on master" );
		$this->assertWriteAllowed( $dbw );

		$dbr = $lb->getConnection( DB_REPLICA );
		$this->assertTrue( $dbr->getLBInfo( 'master' ), 'DB_REPLICA also gets the master' );
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

	public function testWithReplica() {
		global $wgDBserver, $wgDBname, $wgDBuser, $wgDBpassword, $wgDBtype, $wgSQLiteDataDir;

		$servers = [
			[ // master
				'host'        => $wgDBserver,
				'dbname'      => $wgDBname,
				'tablePrefix' => $this->dbPrefix(),
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
				'tablePrefix' => $this->dbPrefix(),
				'user'        => $wgDBuser,
				'password'    => $wgDBpassword,
				'type'        => $wgDBtype,
				'dbDirectory' => $wgSQLiteDataDir,
				'load'        => 100,
				'flags'       => DBO_TRX // REPEATABLE-READ for consistency
			]
		];

		$lb = new LoadBalancer( [
			'servers' => $servers,
			'localDomain' => new DatabaseDomain( $wgDBname, null, $this->dbPrefix() ),
			'queryLogger' => MediaWiki\Logger\LoggerFactory::getInstance( 'DBQuery' ),
			'loadMonitorClass' => LoadMonitorNull::class
		] );

		$dbw = $lb->getConnection( DB_MASTER );
		$this->assertTrue( $dbw->getLBInfo( 'master' ), 'master shows as master' );
		$this->assertEquals(
			( $wgDBserver != '' ) ? $wgDBserver : 'localhost',
			$dbw->getLBInfo( 'clusterMasterHost' ),
			'cluster master set' );
		$this->assertTrue( $dbw->getFlag( $dbw::DBO_TRX ), "DBO_TRX set on master" );
		$this->assertWriteAllowed( $dbw );

		$dbr = $lb->getConnection( DB_REPLICA );
		$this->assertTrue( $dbr->getLBInfo( 'replica' ), 'replica shows as replica' );
		$this->assertEquals(
			( $wgDBserver != '' ) ? $wgDBserver : 'localhost',
			$dbr->getLBInfo( 'clusterMasterHost' ),
			'cluster master set' );
		$this->assertTrue( $dbr->getFlag( $dbw::DBO_TRX ), "DBO_TRX set on replica" );
		$this->assertWriteForbidden( $dbr );

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

	private function assertWriteForbidden( Database $db ) {
		try {
			$db->delete( 'some_table', [ 'id' => 57634126 ], __METHOD__ );
			$this->fail( 'Write operation should have failed!' );
		} catch ( DBError $ex ) {
			// check that the exception message contains "Write operation"
			$constraint = new PHPUnit_Framework_Constraint_StringContains( 'Write operation' );

			if ( !$constraint->evaluate( $ex->getMessage(), '', true ) ) {
				// re-throw original error, to preserve stack trace
				throw $ex;
			}
		}
	}

	private function assertWriteAllowed( Database $db ) {
		$table = $db->tableName( 'some_table' );
		try {
			$db->dropTable( 'some_table' ); // clear for sanity

			// Trigger DBO_TRX to create a transaction so the flush below will
			// roll everything here back in sqlite. But don't actually do the
			// code below inside an atomic section becaue MySQL and Oracle
			// auto-commit transactions for DDL statements like CREATE TABLE.
			$db->startAtomic( __METHOD__ );
			$db->endAtomic( __METHOD__ );

			// Use only basic SQL and trivial types for these queries for compatibility
			$this->assertNotSame(
				false,
				$db->query( "CREATE TABLE $table (id INT, time INT)", __METHOD__ ),
				"table created"
			);
			$this->assertNotSame(
				false,
				$db->query( "DELETE FROM $table WHERE id=57634126", __METHOD__ ),
				"delete query"
			);
		} finally {
			// Drop the table to clean up, ignoring any error.
			$db->query( "DROP TABLE $table", __METHOD__, true );
			// Rollback the DBO_TRX transaction for sqlite's benefit.
			$db->rollback( __METHOD__, 'flush' );
		}
	}

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
			'loadMonitorClass' => LoadMonitorNull::class
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
			'loadMonitorClass' => LoadMonitorNull::class
		] );

		$this->assertFalse( $lb->getServerAttributes( 1 )[Database::ATTR_DB_LEVEL_LOCKING] );
	}

	/**
	 * @covers LoadBalancer::openConnection()
	 * @covers LoadBalancer::getAnyOpenConnection()
	 */
	function testOpenConnection() {
		global $wgDBname;

		$lb = new LoadBalancer( [
			'servers' => [ $this->makeServerConfig() ],
			'localDomain' => new DatabaseDomain( $wgDBname, null, $this->dbPrefix() )
		] );

		$i = $lb->getWriterIndex();
		$this->assertEquals( null, $lb->getAnyOpenConnection( $i ) );
		$conn1 = $lb->getConnection( $i );
		$this->assertNotEquals( null, $conn1 );
		$this->assertEquals( $conn1, $lb->getAnyOpenConnection( $i ) );
		$conn2 = $lb->getConnection( $i, [], false, $lb::CONN_TRX_AUTOCOMMIT );
		$this->assertNotEquals( null, $conn2 );
		if ( $lb->getServerAttributes( $i )[Database::ATTR_DB_LEVEL_LOCKING] ) {
			$this->assertEquals( null,
				$lb->getAnyOpenConnection( $i, $lb::CONN_TRX_AUTOCOMMIT ) );
			$this->assertEquals( $conn1,
				$lb->getConnection(
					$i, [], false, $lb::CONN_TRX_AUTOCOMMIT ), $lb::CONN_TRX_AUTOCOMMIT );
		} else {
			$this->assertEquals( $conn2,
				$lb->getAnyOpenConnection( $i, $lb::CONN_TRX_AUTOCOMMIT ) );
			$this->assertEquals( $conn2,
				$lb->getConnection( $i, [], false, $lb::CONN_TRX_AUTOCOMMIT ) );
		}

		$lb->closeAll();
	}
}
