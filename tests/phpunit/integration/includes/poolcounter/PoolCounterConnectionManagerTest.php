<?php

use MediaWiki\PoolCounter\PoolCounterConnectionManager;

/**
 * @covers PoolCounterConnectionManagerTest
 * @group Database
 */
class PoolCounterConnectionManagerTest extends MediaWikiIntegrationTestCase {

	public static function provideServersConfig() {
		// supplied hostname, expected host, expected port
		return [
			'Correct IPv4' => [
				'127.0.0.1', '127.0.0.1', 7531
			],
			'Bracketless IPv6' => [
				'::1', '[::1]', 7531
			],
			'Bracketed IPv6' => [
				'[::1]', '[::1]', 7531
			],
			'IPv4 with port' => [
				'127.0.0.1:123', '127.0.0.1', 123
			],
			'IPv6 with port' => [
				'[::1]:123', '[::1]', 123,
			],
		];
	}

	/**
	 * Tests whether the hostname supplied is correct. Tests ipv4 and ipv6.
	 *
	 * @covers \MediaWiki\PoolCounter\PoolCounterConnectionManager::get
	 * @dataProvider provideServersConfig
	 */
	public function testGetServersConfig( $suppliedHostname, $expectedHost, $expectedPort ) {
		$pcm = new PoolCounterConnectionManager( [ 'servers' => [ $suppliedHostname ] ] );
		$pcm->get( 'test' );

		$this->assertEquals( $expectedHost, $pcm->host );
		$this->assertSame( $expectedPort, $pcm->port );
	}

}
