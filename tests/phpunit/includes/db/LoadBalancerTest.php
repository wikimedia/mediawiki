<?php

use Wikimedia\Rdbms\DBError;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;

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
 * @group Database
 * @file
 *
 * @covers \Wikimedia\Rdbms\LoadBalancer
 */
class LoadBalancerTest extends MediaWikiTestCase {
	public function testWithoutReplica() {
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

		$lb = new LoadBalancer( [
			'servers' => $servers,
			'localDomain' => wfWikiID()
		] );

		$dbw = $lb->getConnection( DB_MASTER );
		$this->assertTrue( $dbw->getLBInfo( 'master' ), 'master shows as master' );
		$this->assertTrue( $dbw->getFlag( $dbw::DBO_TRX ), "DBO_TRX set on master" );
		$this->assertWriteAllowed( $dbw );

		$dbr = $lb->getConnection( DB_REPLICA );
		$this->assertNotSame( $dbw, $dbr, 'Replica connection is not master connection' );
		$this->assertTrue( $dbr->getLBInfo( 'master' ), 'DB_REPLICA connects to master' );
		$this->assertTrue( $dbr->getLBInfo( 'noWrite' ), 'Replica connection has noWrite set' );
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

	public function testWithReplica() {
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

		$lb = new LoadBalancer( [
			'servers' => $servers,
			'localDomain' => wfWikiID(),
			'loadMonitorClass' => 'LoadMonitorNull'
		] );

		$dbw = $lb->getConnection( DB_MASTER );
		$this->assertTrue( $dbw->getLBInfo( 'master' ), 'master shows as master' );
		$this->assertEquals(
			( $wgDBserver != '' ) ? $wgDBserver : 'localhost',
			$dbw->getLBInfo( 'clusterMasterHost' ),
			'cluster master set' );
		$this->assertTrue( $dbw->getFlag( $dbw::DBO_TRX ), "DBO_TRX set on master" );
		$this->assertWriteAllowed( $dbw );

		$db0 = $lb->getConnection( $lb->getWriterIndex() );
		$this->assertSame( $dbw, $db0, 'getWriterIndex() is equivalent to DB_MASTER' );
		$this->assertTrue( $db0->getLBInfo( 'master' ), 'Writer shows as master' );
		$this->assertWriteAllowed( $db0 );

		$dbr = $lb->getConnection( DB_REPLICA );
		$this->assertNotSame( $dbw, $dbr, 'Replica connection is not master connection' );
		$this->assertTrue( $dbr->getLBInfo( 'replica' ), 'replica shows as replica' );
		$this->assertEquals(
			( $wgDBserver != '' ) ? $wgDBserver : 'localhost',
			$dbr->getLBInfo( 'clusterMasterHost' ),
			'cluster master set' );
		$this->assertTrue( $dbr->getFlag( $dbw::DBO_TRX ), "DBO_TRX set on replica" );
		$this->assertWriteForbidden( $dbr );

		$db1 = $lb->getConnection( $lb->getReaderIndex() );
		$this->assertSame( $dbr, $db1, 'getReaderIndex() is equivalent to DB_REPLICA' );
		$this->assertTrue( $db1->getLBInfo( 'replica' ), 'Reader shows as replica' );
		$this->assertWriteForbidden( $db1 );

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

	public function testWithGroups() {
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
				'flags'       => DBO_TRX, // REPEATABLE-READ for consistency
				'test-name'   => 'master',
			],
			[ // emulated replica for default group
				'host'        => $wgDBserver,
				'dbname'      => $wgDBname,
				'user'        => $wgDBuser,
				'password'    => $wgDBpassword,
				'type'        => $wgDBtype,
				'dbDirectory' => $wgSQLiteDataDir,
				'load'        => 100,
				'groupLoads'  => [ 'foo' => 0 ],
				'test-name'   => 'def-replica',
			],
			[ // emulated replica in foo group
				'host'        => $wgDBserver,
				'dbname'      => $wgDBname,
				'user'        => $wgDBuser,
				'password'    => $wgDBpassword,
				'type'        => $wgDBtype,
				'dbDirectory' => $wgSQLiteDataDir,
				'load'        => 0,
				'groupLoads'  => [ 'foo' => 100 ],
				'test-name'   => 'foo-replica',
			]
		];

		$lb = new LoadBalancer( [
			'servers' => $servers,
			'localDomain' => wfWikiID(),
			'loadMonitorClass' => 'LoadMonitorNull'
		] );

		$db = $lb->getConnection( DB_MASTER );
		$this->assertSame( 'master', $db->getLBInfo( 'test-name' ), 'DB_MASTER gets master' );

		$db = $lb->getConnection( $lb->getWriterIndex() );
		$this->assertSame( 'master', $db->getLBInfo( 'test-name' ), 'getWriterIndex get master' );

		$db = $lb->getConnection( DB_REPLICA );
		$this->assertSame( 'def-replica', $db->getLBInfo( 'test-name' ), 'DB_REPLICA get replica' );

		$db = $lb->getConnection( $lb->getReaderIndex() );
		$this->assertSame( 'def-replica', $db->getLBInfo( 'test-name' ), 'getReaderIndex get replica' );

		$db = $lb->getConnection( DB_MASTER, [ 'foo' ] );
		$this->assertSame( 'master', $db->getLBInfo( 'test-name' ), 'DB_MASTER ignores group' );

		$db = $lb->getConnection( $lb->getWriterIndex(), [ 'foo' ] );
		$this->assertSame( 'master', $db->getLBInfo( 'test-name' ), 'getWriterIndex ignores group' );

		$db = $lb->getConnection( DB_REPLICA, [ 'foo' ] );
		$this->assertSame( 'foo-replica', $db->getLBInfo( 'test-name' ), 'DB_REPLICA uses group' );

		$db = $lb->getConnection( $lb->getReaderIndex( 'foo' ) );
		$this->assertSame( 'foo-replica', $db->getLBInfo( 'test-name' ), 'getReaderIndex uses group' );

		$db = $lb->getConnection( $lb->getReaderIndex(), [ 'foo' ] );
		$this->assertSame(
			'def-replica',
			$db->getLBInfo( 'test-name' ),
			'getReaderIndex forces group'
		);

		$db = $lb->getConnection( DB_REPLICA, [ 'xyzzy' ] );
		$this->assertSame(
			'def-replica',
			$db->getLBInfo( 'test-name' ),
			'bad group falls back to default'
		);

		$lb->closeAll();
	}

	private function assertWriteForbidden( IDatabase $db ) {
		try {
			$db->delete( 'user', [ 'user_id' => 57634126 ], 'TEST' );
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

	private function assertWriteAllowed( IDatabase $db ) {
		try {
			$this->assertNotSame( false, $db->delete( 'user', [ 'user_id' => 57634126 ] ) );
		} finally {
			$db->rollback( __METHOD__, 'flush' );
		}
	}

}
