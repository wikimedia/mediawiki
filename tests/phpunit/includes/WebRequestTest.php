<?php

class WebRequestTest extends MediaWikiTestCase {
	/**
	 * @dataProvider provideDetectServer
	 */
	function testDetectServer( $expected, $input, $description ) {
		$oldServer = $_SERVER;
		$_SERVER = $input;
		$result = WebRequest::detectServer();
		$_SERVER = $oldServer;
		$this->assertEquals( $expected, $result, $description );
	}

	function provideDetectServer() {
		return array(
			array(
				'http://x',
				array(
					'HTTP_HOST' => 'x'
				),
				'Host header'
			),
			array(
				'https://x',
				array(
					'HTTP_HOST' => 'x',
					'HTTPS' => 'on',
				),
				'Host header with secure'
			),
			array(
				'http://x',
				array(
					'HTTP_HOST' => 'x',
					'SERVER_PORT' => 80,
				),
				'Default SERVER_PORT',
			),
			array(
				'http://x',
				array(
					'HTTP_HOST' => 'x',
					'HTTPS' => 'off',
				),
				'Secure off'
			),
			array(
				'http://y',
				array(
					'SERVER_NAME' => 'y',
				),
				'Server name'
			),
			array(
				'http://x',
				array(
					'HTTP_HOST' => 'x',
					'SERVER_NAME' => 'y',
				),
				'Host server name precedence'
			),
			array(
				'http://[::1]:81',
				array(
					'HTTP_HOST' => '[::1]',
					'SERVER_NAME' => '::1',
					'SERVER_PORT' => '81',
				),
				'Apache bug 26005'
			),
			array(
				'http://localhost',
				array(
					'SERVER_NAME' => '[2001'
				),
				'Kind of like lighttpd per commit message in MW r83847',
			),
			array(
				'http://[2a01:e35:2eb4:1::2]:777',
				array(
					'SERVER_NAME' => '[2a01:e35:2eb4:1::2]:777'
				),
				'Possible lighttpd environment per bug 14977 comment 13',
			),
		);
	}

	/**
	 * @dataProvider provideGetIP
	 */
	function testGetIP( $expected, $input, $squid, $private, $description ) {
		global $wgSquidServersNoPurge, $wgUsePrivateIPs;
		$oldServer = $_SERVER;
		$_SERVER = $input;
		$wgSquidServersNoPurge = $squid;
		$wgUsePrivateIPs = $private;
		$request = new WebRequest();
		$result = $request->getIP();
		$_SERVER = $oldServer;
		$this->assertEquals( $expected, $result, $description );
	}

	function provideGetIP() {
		return array(
			array(
				'127.0.0.1',
				array(
					'REMOTE_ADDR' => '127.0.0.1'
				),
				array(),
				false,
				'Simple IPv4'
			),
			array(
				'::1',
				array(
					'REMOTE_ADDR' => '::1'
				),
				array(),
				false,
				'Simple IPv6'
			),
			array(
				'12.0.0.3',
				array(
					'REMOTE_ADDR' => '12.0.0.1',
					'HTTP_X_FORWARDED_FOR' => '12.0.0.3, 12.0.0.2'
				),
				array( '12.0.0.1', '12.0.0.2' ),
				false,
				'With X-Forwaded-For'
			),
			array(
				'12.0.0.1',
				array(
					'REMOTE_ADDR' => '12.0.0.1',
					'HTTP_X_FORWARDED_FOR' => '12.0.0.3, 12.0.0.2'
				),
				array(),
				false,
				'With X-Forwaded-For and disallowed server'
			),
			array(
				'12.0.0.2',
				array(
					'REMOTE_ADDR' => '12.0.0.1',
					'HTTP_X_FORWARDED_FOR' => '12.0.0.3, 12.0.0.2'
				),
				array( '12.0.0.1' ),
				false,
				'With multiple X-Forwaded-For and only one allowed server'
			),
			array(
				'12.0.0.2',
				array(
					'REMOTE_ADDR' => '12.0.0.2',
					'HTTP_X_FORWARDED_FOR' => '10.0.0.3, 12.0.0.2'
				),
				array( '12.0.0.1', '12.0.0.2' ),
				false,
				'With X-Forwaded-For and private IP'
			),
			array(
				'10.0.0.3',
				array(
					'REMOTE_ADDR' => '12.0.0.2',
					'HTTP_X_FORWARDED_FOR' => '10.0.0.3, 12.0.0.2'
				),
				array( '12.0.0.1', '12.0.0.2' ),
				true,
				'With X-Forwaded-For and private IP (allowed)'
			),
		);
	}

	/**
	 * @expectedException MWException
	 */
	function testGetIpLackOfRemoteAddrThrowAnException() {
		$request = new WebRequest();
		# Next call throw an exception about lacking an IP
		$request->getIP();
	}
}
