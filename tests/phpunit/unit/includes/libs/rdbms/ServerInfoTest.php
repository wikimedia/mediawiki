<?php

use PHPUnit\Framework\TestCase;
use Wikimedia\Rdbms\ServerInfo;

/**
 * @covers \Wikimedia\Rdbms\ServerInfo
 */
class ServerInfoTest extends TestCase {

	use MediaWikiCoversValidator;

	private function serverConfigs(
		$extra = [], $flags = DBO_DEFAULT
	) {
		return [
			// Primary DB
			0 => $extra + [
					'host' => 'db0',
					'serverName' => 'testhost0',
					'dbname' => 'testwiki',
					'tablePrefix' => 'prefix',
					'user' => 'dbuser',
					'password' => 'verystrongpassword',
					'type' => 'mysql',
					'flags' => $flags,
					'load' => 0,
				],
			// Main replica DBs
			1 => $extra + [
					'host' => 'db1',
					'serverName' => 'testhost1',
					'dbname' => 'testwiki',
					'tablePrefix' => 'prefix',
					'user' => 'dbuser',
					'password' => 'verystrongpassword',
					'type' => 'mysql',
					'flags' => $flags,
					'load' => 100,
				],
			2 => $extra + [
					'host' => 'db2',
					'serverName' => 'testhost2',
					'dbname' => 'testwiki',
					'tablePrefix' => 'prefix',
					'user' => 'dbuser',
					'password' => 'verystrongpassword',
					'type' => 'mysql',
					'flags' => $flags,
					'load' => 100,
				],
			// RC replica DBs
			3 => $extra + [
					'host' => 'db3',
					'serverName' => 'testhost3',
					'dbname' => 'testwiki',
					'tablePrefix' => 'prefix',
					'user' => 'dbuser',
					'password' => 'verystrongpassword',
					'type' => 'mysql',
					'flags' => $flags,
					'load' => 0,
					'groupLoads' => [
						'foo' => 100,
						'bar' => 100
					],
				],
			// Logging replica DBs
			4 => $extra + [
					'host' => 'db4',
					'serverName' => 'testhost4',
					'dbname' => 'testwiki',
					'tablePrefix' => 'prefix',
					'user' => 'dbuser',
					'password' => 'verystrongpassword',
					'type' => 'mysql',
					'flags' => $flags,
					'load' => 0,
					'groupLoads' => [
						'baz' => 100
					],
				],
			5 => $extra + [
					'host' => 'db5',
					'serverName' => 'testhost5',
					'dbname' => 'testwiki',
					'tablePrefix' => 'prefix',
					'user' => 'dbuser',
					'password' => 'verystrongpassword',
					'type' => 'mysql',
					'flags' => $flags,
					'load' => 0,
					'groupLoads' => [
						'baz' => 100
					],
				],
			// Maintenance query replica DBs
			6 => $extra + [
					'host' => 'db6',
					'serverName' => 'testhost6',
					'dbname' => 'testwiki',
					'tablePrefix' => 'prefix',
					'user' => 'dbuser',
					'password' => 'verystrongpassword',
					'type' => 'mysql',
					'flags' => $flags,
					'load' => 0,
					'groupLoads' => [
						'vslow' => 100
					],
				],
			// Replica DB that only has a copy of some static tables
			7 => $extra + [
					'host' => 'db7',
					'serverName' => 'testhost7',
					'dbname' => 'testwiki',
					'tablePrefix' => 'prefix',
					'user' => 'dbuser',
					'password' => 'verystrongpassword',
					'type' => 'mysql',
					'flags' => $flags,
					'load' => 0,
					'groupLoads' => [
						'archive' => 100
					],
					'is static' => true
				]
		];
	}

	private function createInfoHolderFromArray( $servers ): ServerInfo {
		$holder = new ServerInfo();
		foreach ( $holder->normalizeServerMaps( $servers ) as $i => $server ) {
			$holder->addServer( $i, $server );
		}

		return $holder;
	}

	public function testWithoutReplica() {
		$primaryInfo = $this->serverConfigs()[0];
		$holder = $this->createInfoHolderFromArray( [ $primaryInfo ] );
		$primaryInfo['groupLoads'] = [];

		$this->assertSame( 1, $holder->getServerCount() );
		$this->assertFalse( $holder->hasReplicaServers() );
		$this->assertFalse( $holder->hasStreamingReplicaServers() );
		$this->assertTrue( $holder->hasServerIndex( 0 ) );
		$this->assertFalse( $holder->hasServerIndex( 1 ) );
		$this->assertFalse( $holder->hasServerIndex( 999 ) );
		$this->assertSame( $primaryInfo, $holder->getServerInfo( 0 ) );
		$this->assertSame( $primaryInfo, $holder->getServerInfoStrict( 0 ) );
		$this->assertSame( 'testhost0', $holder->getPrimaryServerName() );
	}

	public function testWithReplica() {
		// Simulate web request with DBO_TRX
		$holder = $this->createInfoHolderFromArray( $this->serverConfigs( [], DBO_TRX ) );

		$this->assertSame( 8, $holder->getServerCount() );
		$this->assertTrue( $holder->hasReplicaServers() );
		$this->assertTrue( $holder->hasStreamingReplicaServers() );
		$this->assertSame( 'testhost0', $holder->getPrimaryServerName() );

		for ( $i = 0; $i < $holder->getServerCount(); ++$i ) {
			$this->assertIsString( $holder->getServerName( $i ) );
			$this->assertIsArray( $holder->getServerInfo( $i ) );
			$this->assertIsString( $holder->getServerType( $i ) );
		}

		$this->assertFalse( $holder->hasServerIndex( 999 ) );
	}

	public function testNonExistentServerInfoStrict() {
		$holder = $this->createInfoHolderFromArray( $this->serverConfigs( [], DBO_TRX ) );
		$this->expectException( InvalidArgumentException::class );
		$holder->getServerInfoStrict( 999 );
	}

	public function testNonExistentFieldInfoStrict() {
		$holder = $this->createInfoHolderFromArray( $this->serverConfigs( [], DBO_TRX ) );
		$this->expectException( InvalidArgumentException::class );
		$holder->getServerInfoStrict( 0, 'non-existent-field' );
	}
}
