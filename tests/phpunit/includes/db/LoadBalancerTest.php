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
	public function testWithoutReplica() {
		global $wgDBserver, $wgDBname, $wgDBuser, $wgDBpassword, $wgDBtype, $wgSQLiteDataDir;

		$servers = [
			[
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
		];

		$lb = new LoadBalancer( [
			'servers' => $servers,
			'localDomain' => new DatabaseDomain( $wgDBname, null, $this->dbPrefix() )
		] );

		$ld = DatabaseDomain::newFromId( $lb->getLocalDomainID() );
		$this->assertEquals( $wgDBname, $ld->getDatabase(), 'local domain DB set' );
		$this->assertEquals( $this->dbPrefix(), $ld->getTablePrefix(), 'local domain prefix set' );

		$dbw = $lb->getConnection( DB_MASTER );
		$this->assertTrue( $dbw->getLBInfo( 'master' ), 'master shows as master' );
		$this->assertTrue( $dbw->getFlag( $dbw::DBO_TRX ), "DBO_TRX set on master" );
		$this->assertWriteAllowed( $dbw );

		$dbr = $lb->getConnection( DB_REPLICA );
		$this->assertTrue( $dbr->getLBInfo( 'master' ), 'DB_REPLICA also gets the master' );
		$this->assertTrue( $dbr->getFlag( $dbw::DBO_TRX ), "DBO_TRX set on replica" );

		$dbwAuto = $lb->getConnection( DB_MASTER, [], false, $lb::CONN_TRX_AUTO );
		$this->assertFalse( $dbwAuto->getFlag( $dbw::DBO_TRX ), "No DBO_TRX with CONN_TRX_AUTO" );
		$this->assertTrue( $dbw->getFlag( $dbw::DBO_TRX ), "DBO_TRX still set on master" );
		$this->assertNotEquals( $dbw, $dbwAuto, "CONN_TRX_AUTO uses separate connection" );

		$dbrAuto = $lb->getConnection( DB_REPLICA, [], false, $lb::CONN_TRX_AUTO );
		$this->assertFalse( $dbrAuto->getFlag( $dbw::DBO_TRX ), "No DBO_TRX with CONN_TRX_AUTO" );
		$this->assertTrue( $dbr->getFlag( $dbw::DBO_TRX ), "DBO_TRX still set on replica" );
		$this->assertNotEquals( $dbr, $dbrAuto, "CONN_TRX_AUTO uses separate connection" );

		$dbwAuto2 = $lb->getConnection( DB_MASTER, [], false, $lb::CONN_TRX_AUTO );
		$this->assertEquals( $dbwAuto2, $dbwAuto, "CONN_TRX_AUTO reuses connections" );

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

		$dbwAuto = $lb->getConnection( DB_MASTER, [], false, $lb::CONN_TRX_AUTO );
		$this->assertFalse( $dbwAuto->getFlag( $dbw::DBO_TRX ), "No DBO_TRX with CONN_TRX_AUTO" );
		$this->assertTrue( $dbw->getFlag( $dbw::DBO_TRX ), "DBO_TRX still set on master" );
		$this->assertNotEquals( $dbw, $dbwAuto, "CONN_TRX_AUTO uses separate connection" );

		$dbrAuto = $lb->getConnection( DB_REPLICA, [], false, $lb::CONN_TRX_AUTO );
		$this->assertFalse( $dbrAuto->getFlag( $dbw::DBO_TRX ), "No DBO_TRX with CONN_TRX_AUTO" );
		$this->assertTrue( $dbr->getFlag( $dbw::DBO_TRX ), "DBO_TRX still set on replica" );
		$this->assertNotEquals( $dbr, $dbrAuto, "CONN_TRX_AUTO uses separate connection" );

		$dbwAuto2 = $lb->getConnection( DB_MASTER, [], false, $lb::CONN_TRX_AUTO );
		$this->assertEquals( $dbwAuto2, $dbwAuto, "CONN_TRX_AUTO reuses connections" );

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
		} finally {
			$db->rollback( __METHOD__, 'flush' );
		}
	}

	private function assertWriteAllowed( Database $db ) {
		$table = $db->tableName( 'some_table' );
		try {
			$db->dropTable( 'some_table' ); // clear for sanity
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
			$this->assertNotSame(
				false,
				$db->query( "DROP TABLE $table", __METHOD__ ),
				"table dropped"
			);
		} finally {
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
}
