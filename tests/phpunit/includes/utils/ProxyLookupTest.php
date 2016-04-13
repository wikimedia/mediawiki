<?php
/**
 * Tests for ProxyLookup.
 *
 * @group IP
 */

class ProxyLookupTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();
		$this->setMwGlobals( [
			'wgSquidServers' => [ '127.0.0.1', '127.0.0.1:8080', '192.168.0.2:80' ],
			'wgSquidServersNoPurge' => [ '192.168.0.3' ],
			'wgUsePrivateIPs' => false,
		] );
	}

	/**
	 * @covers ProxyLookup::isConfiguredProxy
	 * @dataProvider provideIPsToTestConfiguredProxy
	 */
	public function testisConfiguredProxy( $expected, $input, $description ) {
		$this->assertEquals( $expected, IP::isConfiguredProxy( $input ), $description );
	}

	/**
	 * Provider for ProxyLookup::testisConfiguredProxy()
	 */
	public static function provideIPsToTestConfiguredProxy() {
		return [
			[ true, '127.0.0.1', 'IP in $wgSquidServers list without port' ],
			[ true, '192.168.0.2', 'IP in $wgSquidServers list with port' ],
			[ true, '192.168.0.3', 'IP in $wgSquidServersNoPurge list' ],
			[ false, '192.168.0.4', 'IP not in any list' ],
		];
	}
}
