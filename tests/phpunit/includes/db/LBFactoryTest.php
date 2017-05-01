<?php

use Wikimedia\Rdbms\LBFactorySimple;
use Wikimedia\Rdbms\LBFactoryMulti;
use Wikimedia\Rdbms\ChronologyProtector;
use Wikimedia\Rdbms\MySQLMasterPos;

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
 * @group Database
 * @file
 * @author Antoine Musso
 * @copyright © 2013 Antoine Musso
 * @copyright © 2013 Wikimedia Foundation Inc.
 */
class LBFactoryTest extends MediaWikiTestCase {

	/**
	 * @dataProvider getLBFactoryClassProvider
	 */
	public function testGetLBFactoryClass( $expected, $deprecated ) {
		$mockDB = $this->getMockBuilder( 'DatabaseMysqli' )
			->disableOriginalConstructor()
			->getMock();

		$config = [
			'class'          => $deprecated,
			'connection'     => $mockDB,
			# Various other parameters required:
			'sectionsByDB'   => [],
			'sectionLoads'   => [],
			'serverTemplate' => [],
		];

		$this->hideDeprecated( '$wgLBFactoryConf must be updated. See RELEASE-NOTES for details' );
		$result = MWLBFactory::getLBFactoryClass( $config );

		$this->assertEquals( $expected, $result );
	}

	public function getLBFactoryClassProvider() {
		return [
			# Format: new class, old class
			[ Wikimedia\Rdbms\LBFactorySimple::class, 'LBFactory_Simple' ],
			[ Wikimedia\Rdbms\LBFactorySingle::class, 'LBFactory_Single' ],
			[ Wikimedia\Rdbms\LBFactoryMulti::class, 'LBFactory_Multi' ],
			[ Wikimedia\Rdbms\LBFactorySimple::class, 'LBFactorySimple' ],
			[ Wikimedia\Rdbms\LBFactorySingle::class, 'LBFactorySingle' ],
			[ Wikimedia\Rdbms\LBFactoryMulti::class, 'LBFactoryMulti' ],
		];
	}

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
		$this->assertTrue( $dbw->getLBInfo( 'master' ), 'master shows as master' );

		$dbr = $lb->getConnection( DB_SLAVE );
		$this->assertTrue( $dbr->getLBInfo( 'master' ), 'DB_SLAVE also gets the master' );

		$factory->shutdown();
		$lb->closeAll();
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

		$factory = new LBFactorySimple( [
			'servers' => $servers,
			'loadMonitorClass' => 'LoadMonitorNull'
		] );
		$lb = $factory->getMainLB();

		$dbw = $lb->getConnection( DB_MASTER );
		$this->assertTrue( $dbw->getLBInfo( 'master' ), 'master shows as master' );
		$this->assertEquals(
			( $wgDBserver != '' ) ? $wgDBserver : 'localhost',
			$dbw->getLBInfo( 'clusterMasterHost' ),
			'cluster master set' );

		$dbr = $lb->getConnection( DB_SLAVE );
		$this->assertTrue( $dbr->getLBInfo( 'replica' ), 'slave shows as slave' );
		$this->assertEquals(
			( $wgDBserver != '' ) ? $wgDBserver : 'localhost',
			$dbr->getLBInfo( 'clusterMasterHost' ),
			'cluster master set' );

		$factory->shutdown();
		$lb->closeAll();
	}

	public function testLBFactoryMulti() {
		global $wgDBserver, $wgDBname, $wgDBuser, $wgDBpassword, $wgDBtype, $wgSQLiteDataDir;

		$factory = new LBFactoryMulti( [
			'sectionsByDB' => [],
			'sectionLoads' => [
				'DEFAULT' => [
					'test-db1' => 0,
					'test-db2' => 100,
				],
			],
			'serverTemplate' => [
				'dbname'      => $wgDBname,
				'user'        => $wgDBuser,
				'password'    => $wgDBpassword,
				'type'        => $wgDBtype,
				'dbDirectory' => $wgSQLiteDataDir,
				'flags'       => DBO_DEFAULT
			],
			'hostsByName' => [
				'test-db1'  => $wgDBserver,
				'test-db2'  => $wgDBserver
			],
			'loadMonitorClass' => 'LoadMonitorNull'
		] );
		$lb = $factory->getMainLB();

		$dbw = $lb->getConnection( DB_MASTER );
		$this->assertTrue( $dbw->getLBInfo( 'master' ), 'master shows as master' );

		$dbr = $lb->getConnection( DB_SLAVE );
		$this->assertTrue( $dbr->getLBInfo( 'replica' ), 'slave shows as slave' );

		$factory->shutdown();
		$lb->closeAll();
	}

	public function testChronologyProtector() {
		// (a) First HTTP request
		$mPos = new MySQLMasterPos( 'db1034-bin.000976', '843431247' );

		$now = microtime( true );
		$mockDB = $this->getMockBuilder( 'DatabaseMysqli' )
			->disableOriginalConstructor()
			->getMock();
		$mockDB->method( 'writesOrCallbacksPending' )->willReturn( true );
		$mockDB->method( 'lastDoneWrites' )->willReturn( $now );
		$mockDB->method( 'getMasterPos' )->willReturn( $mPos );

		$lb = $this->getMockBuilder( 'LoadBalancer' )
			->disableOriginalConstructor()
			->getMock();
		$lb->method( 'getConnection' )->willReturn( $mockDB );
		$lb->method( 'getServerCount' )->willReturn( 2 );
		$lb->method( 'parentInfo' )->willReturn( [ 'id' => "main-DEFAULT" ] );
		$lb->method( 'getAnyOpenConnection' )->willReturn( $mockDB );
		$lb->method( 'hasOrMadeRecentMasterChanges' )->will( $this->returnCallback(
				function () use ( $mockDB ) {
					$p = 0;
					$p |= call_user_func( [ $mockDB, 'writesOrCallbacksPending' ] );
					$p |= call_user_func( [ $mockDB, 'lastDoneWrites' ] );

					return (bool)$p;
				}
			) );
		$lb->method( 'getMasterPos' )->willReturn( $mPos );

		$bag = new HashBagOStuff();
		$cp = new ChronologyProtector(
			$bag,
			[
				'ip' => '127.0.0.1',
				'agent' => "Totally-Not-FireFox"
			]
		);

		$mockDB->expects( $this->exactly( 2 ) )->method( 'writesOrCallbacksPending' );
		$mockDB->expects( $this->exactly( 2 ) )->method( 'lastDoneWrites' );

		// Nothing to wait for
		$cp->initLB( $lb );
		// Record in stash
		$cp->shutdownLB( $lb );
		$cp->shutdown();

		// (b) Second HTTP request
		$cp = new ChronologyProtector(
			$bag,
			[
				'ip' => '127.0.0.1',
				'agent' => "Totally-Not-FireFox"
			]
		);

		$lb->expects( $this->once() )
			->method( 'waitFor' )->with( $this->equalTo( $mPos ) );

		// Wait
		$cp->initLB( $lb );
		// Record in stash
		$cp->shutdownLB( $lb );
		$cp->shutdown();
	}

	private function newLBFactoryMulti( array $baseOverride = [], array $serverOverride = [] ) {
		global $wgDBserver, $wgDBuser, $wgDBpassword, $wgDBname, $wgDBtype, $wgSQLiteDataDir;

		return new LBFactoryMulti( $baseOverride + [
			'sectionsByDB' => [],
			'sectionLoads' => [
				'DEFAULT' => [
					'test-db1' => 1,
				],
			],
			'serverTemplate' => $serverOverride + [
				'dbname' => $wgDBname,
				'user' => $wgDBuser,
				'password' => $wgDBpassword,
				'type' => $wgDBtype,
				'dbDirectory' => $wgSQLiteDataDir,
				'flags' => DBO_DEFAULT
			],
			'hostsByName' => [
				'test-db1' => $wgDBserver,
			],
			'loadMonitorClass' => 'LoadMonitorNull',
			'localDomain' => wfWikiID()
		] );
	}

	public function testNiceDomains() {
		global $wgDBname, $wgDBtype;

		if ( $wgDBtype === 'sqlite' ) {
			$tmpDir = $this->getNewTempDirectory();
			$dbPath = "$tmpDir/unit_test_db.sqlite";
			file_put_contents( $dbPath, '' );
			$tempFsFile = new TempFSFile( $dbPath );
			$tempFsFile->autocollect();
		} else {
			$dbPath = null;
		}

		$factory = $this->newLBFactoryMulti(
			[],
			[ 'dbFilePath' => $dbPath ]
		);
		$lb = $factory->getMainLB();

		if ( $wgDBtype !== 'sqlite' ) {
			$db = $lb->getConnectionRef( DB_MASTER );
			$this->assertEquals(
				$wgDBname,
				$db->getDomainID()
			);
			unset( $db );
		}

		/** @var Database $db */
		$db = $lb->getConnection( DB_MASTER, [], '' );
		$lb->reuseConnection( $db ); // don't care

		$this->assertEquals(
			'',
			$db->getDomainID()
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

		$factory->setDomainPrefix( 'my_' );
		$this->assertEquals(
			'',
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

	public function testTrickyDomain() {
		global $wgDBtype;

		if ( $wgDBtype === 'sqlite' ) {
			$tmpDir = $this->getNewTempDirectory();
			$dbPath = "$tmpDir/unit_test_db.sqlite";
			file_put_contents( $dbPath, '' );
			$tempFsFile = new TempFSFile( $dbPath );
			$tempFsFile->autocollect();
		} else {
			$dbPath = null;
		}

		$dbname = 'unittest-domain';
		$factory = $this->newLBFactoryMulti(
			[ 'localDomain' => $dbname ],
			[ 'dbname' => $dbname, 'dbFilePath' => $dbPath ]
		);
		$lb = $factory->getMainLB();
		/** @var Database $db */
		$db = $lb->getConnection( DB_MASTER, [], '' );
		$lb->reuseConnection( $db ); // don't care

		$this->assertEquals(
			'',
			$db->getDomainID()
		);

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

		$factory->setDomainPrefix( 'my_' );

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

		\MediaWiki\suppressWarnings();
		$this->assertFalse( $db->selectDB( 'garbage-db' ) );
		\MediaWiki\restoreWarnings();

		$this->assertEquals(
			$this->quoteTable( $db, 'garbage-db' ) . '.' . $this->quoteTable( $db, 'page' ),
			$db->tableName( 'garbage-db.page' ),
			"Correct full table name"
		);

		$factory->closeAll();
		$factory->destroy();
	}

	private function quoteTable( Database $db, $table ) {
		if ( $db->getType() === 'sqlite' ) {
			return $table;
		} else {
			return $db->addIdentifierQuotes( $table );
		}
	}
}
