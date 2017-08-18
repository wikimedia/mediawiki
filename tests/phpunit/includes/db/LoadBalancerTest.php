<?php

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
 */
class LoadBalancerTest extends MediaWikiTestCase {
	public function testLBSimpleServer() {
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

		$dbr = $lb->getConnection( DB_REPLICA );
		$this->assertTrue( $dbr->getLBInfo( 'master' ), 'DB_REPLICA also gets the master' );
		$this->assertTrue( $dbw->getFlag( $dbw::DBO_TRX ), "DBO_TRX set on replica" );

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

	public function testLBSimpleServers() {
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
			[ // emulated slave
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

		$dbr = $lb->getConnection( DB_REPLICA );
		$this->assertTrue( $dbr->getLBInfo( 'replica' ), 'slave shows as slave' );
		$this->assertEquals(
			( $wgDBserver != '' ) ? $wgDBserver : 'localhost',
			$dbr->getLBInfo( 'clusterMasterHost' ),
			'cluster master set' );
		$this->assertTrue( $dbw->getFlag( $dbw::DBO_TRX ), "DBO_TRX set on replica" );

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
}
