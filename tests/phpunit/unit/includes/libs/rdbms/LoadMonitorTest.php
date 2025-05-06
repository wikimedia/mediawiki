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

use Psr\Log\NullLogger;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\DBConnectionError;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\LoadMonitor;
use Wikimedia\Stats\StatsFactory;

/**
 * @covers \Wikimedia\Rdbms\LoadMonitor
 */
class LoadMonitorTest extends MediaWikiUnitTestCase {
	public function testWithReplica() {
		$dbArray = $this->newMultiServerLocalLoadBalancer();
		$dbs = [];
		foreach ( $dbArray as $i => $info ) {
			$dbMock = $this->createMock( IDatabase::class );
			$dbMock->method( 'getType' )
				->willReturn( 'mysql' );
			$dbMock->method( 'query' )
				->willReturn( new FakeResultWrapper( [ [ 'pcount' => $i * 50 ] ] ) );
			$dbs[] = $dbMock;
		}
		$lbMock = $this->createMock( ILoadBalancer::class );
		$lbMock->method( 'getServerInfo' )
			->willReturnCallback( static function ( $i ) use ( $dbArray ) {
				return $dbArray[$i] ?? false;
			} );
		$lbMock->method( 'getServerName' )
			->willReturnCallback( static function ( $i ) use ( $dbArray ) {
				return $dbArray[$i]['serverName'] ?? false;
			} );

		$lbMock->method( 'getServerConnection' )
			->willReturnCallback( static function ( $i ) use ( $dbs ) {
				return $dbs[$i];
			} );

		$lbMock->method( 'getClusterName' )
			->willReturn( 'test-cluster' );

		$wanMock = new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );

		$loadMonitor = new LoadMonitor(
			$lbMock,
			new HashBagOStuff(),
			$wanMock,
			new NullLogger(),
			StatsFactory::newNull(),
			[]
		);

		$weights = [ 0, 100, 100, 100, 100 ];
		$loadMonitor->scaleLoads( $weights );
		$this->assertSame( [ 0, 130, 111, 91, 72 ], $weights );
	}

	public function testCircuitBreaking() {
		$dbArray = $this->newMultiServerLocalLoadBalancer();
		$dbs = [];
		foreach ( $dbArray as $i => $info ) {
			$dbMock = $this->createMock( IDatabase::class );
			$dbMock->method( 'getType' )
				->willReturn( 'mysql' );
			$dbMock->method( 'query' )
				->willReturn( new FakeResultWrapper( [ [ 'pcount' => $i * 500 ] ] ) );
			$dbs[] = $dbMock;
		}
		$lbMock = $this->createMock( ILoadBalancer::class );
		$lbMock->method( 'getServerInfo' )
			->willReturnCallback( static function ( $i ) use ( $dbArray ) {
				return $dbArray[$i] ?? false;
			} );
		$lbMock->method( 'getServerName' )
			->willReturnCallback( static function ( $i ) use ( $dbArray ) {
				return $dbArray[$i]['serverName'] ?? false;
			} );

		$lbMock->method( 'getServerConnection' )
			->willReturnCallback( static function ( $i ) use ( $dbs ) {
				return $dbs[$i];
			} );

		$lbMock->method( 'getClusterName' )
			->willReturn( 'test-cluster' );

		$wanMock = new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );

		$loadMonitor = new LoadMonitor(
			$lbMock,
			new HashBagOStuff(),
			$wanMock,
			new NullLogger(),
			StatsFactory::newNull(),
			[ 'maxConnCount' => 400 ]
		);

		$weights = [ 0, 100, 100, 100, 100 ];
		$this->expectException( DBConnectionError::class );
		$loadMonitor->scaleLoads( $weights );
	}

	private function newMultiServerLocalLoadBalancer() {
		$DBserver = 'localhost';
		$DBport = '3306';
		$DBname = 'wikidb';
		$DBuser = 'wikiuser';
		$DBpassword = 'wikidbpassword';
		$DBtype = 'mysql';
		$prefix = '';

		return [
			// Primary DB
			0 => [
				'serverName' => 'db0',
				'host' => $DBserver,
				'port' => $DBport,
				'dbname' => $DBname,
				'tablePrefix' => $prefix,
				'user' => $DBuser,
				'password' => $DBpassword,
				'type' => $DBtype,
				'flags' => DBO_TRX,
				'load' => 0,
			],
			1 => [
				'serverName' => 'db1',
				'host' => $DBserver,
				'port' => $DBport,
				'dbname' => $DBname,
				'tablePrefix' => $prefix,
				'user' => $DBuser,
				'password' => $DBpassword,
				'type' => $DBtype,
				'flags' => DBO_TRX,
				'load' => 100,
			],
			2 => [
				'serverName' => 'db2',
				'host' => $DBserver,
				'port' => $DBport,
				'dbname' => $DBname,
				'tablePrefix' => $prefix,
				'user' => $DBuser,
				'password' => $DBpassword,
				'type' => $DBtype,
				'flags' => DBO_TRX,
				'load' => 100,
			],
			3 => [
				'serverName' => 'db3',
				'host' => $DBserver,
				'port' => $DBport,
				'dbname' => $DBname,
				'tablePrefix' => $prefix,
				'user' => $DBuser,
				'password' => $DBpassword,
				'type' => $DBtype,
				'load' => 100,
				'flags' => DBO_TRX,
			],
			4 => [
				'serverName' => 'db4',
				'host' => $DBserver,
				'port' => $DBport,
				'dbname' => $DBname,
				'tablePrefix' => $prefix,
				'user' => $DBuser,
				'password' => $DBpassword,
				'type' => $DBtype,
				'load' => 100,
				'flags' => DBO_TRX,
			],
		];
	}
}
