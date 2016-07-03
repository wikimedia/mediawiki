<?php

/**
 * @group WebRequest
 */
class WebRequestTest extends MediaWikiTestCase {
	protected $oldServer;

	protected function setUp() {
		parent::setUp();

		$this->oldServer = $_SERVER;
		IP::clearCaches();
	}

	protected function tearDown() {
		$_SERVER = $this->oldServer;
		IP::clearCaches();

		parent::tearDown();
	}

	/**
	 * @dataProvider provideDetectServer
	 * @covers WebRequest::detectServer
	 */
	public function testDetectServer( $expected, $input, $description ) {
		$_SERVER = $input;
		$result = WebRequest::detectServer();
		$this->assertEquals( $expected, $result, $description );
	}

	public static function provideDetectServer() {
		return [
			[
				'http://x',
				[
					'HTTP_HOST' => 'x'
				],
				'Host header'
			],
			[
				'https://x',
				[
					'HTTP_HOST' => 'x',
					'HTTPS' => 'on',
				],
				'Host header with secure'
			],
			[
				'http://x',
				[
					'HTTP_HOST' => 'x',
					'SERVER_PORT' => 80,
				],
				'Default SERVER_PORT',
			],
			[
				'http://x',
				[
					'HTTP_HOST' => 'x',
					'HTTPS' => 'off',
				],
				'Secure off'
			],
			[
				'http://y',
				[
					'SERVER_NAME' => 'y',
				],
				'Server name'
			],
			[
				'http://x',
				[
					'HTTP_HOST' => 'x',
					'SERVER_NAME' => 'y',
				],
				'Host server name precedence'
			],
			[
				'http://[::1]:81',
				[
					'HTTP_HOST' => '[::1]',
					'SERVER_NAME' => '::1',
					'SERVER_PORT' => '81',
				],
				'Apache bug 26005'
			],
			[
				'http://localhost',
				[
					'SERVER_NAME' => '[2001'
				],
				'Kind of like lighttpd per commit message in MW r83847',
			],
			[
				'http://[2a01:e35:2eb4:1::2]:777',
				[
					'SERVER_NAME' => '[2a01:e35:2eb4:1::2]:777'
				],
				'Possible lighttpd environment per bug 14977 comment 13',
			],
		];
	}

	/**
	 * @dataProvider provideGetIP
	 * @covers WebRequest::getIP
	 */
	public function testGetIP( $expected, $input, $squid, $xffList, $private, $description ) {
		$_SERVER = $input;
		$this->setMwGlobals( [
			'wgSquidServersNoPurge' => $squid,
			'wgUsePrivateIPs' => $private,
			'wgHooks' => [
				'IsTrustedProxy' => [
					function ( &$ip, &$trusted ) use ( $xffList ) {
						$trusted = $trusted || in_array( $ip, $xffList );
						return true;
					}
				]
			]
		] );

		$request = new WebRequest();
		$result = $request->getIP();
		$this->assertEquals( $expected, $result, $description );
	}

	public static function provideGetIP() {
		return [
			[
				'127.0.0.1',
				[
					'REMOTE_ADDR' => '127.0.0.1'
				],
				[],
				[],
				false,
				'Simple IPv4'
			],
			[
				'::1',
				[
					'REMOTE_ADDR' => '::1'
				],
				[],
				[],
				false,
				'Simple IPv6'
			],
			[
				'12.0.0.1',
				[
					'REMOTE_ADDR' => 'abcd:0001:002:03:4:555:6666:7777',
					'HTTP_X_FORWARDED_FOR' => '12.0.0.1, abcd:0001:002:03:4:555:6666:7777',
				],
				[ 'ABCD:1:2:3:4:555:6666:7777' ],
				[],
				false,
				'IPv6 normalisation'
			],
			[
				'12.0.0.3',
				[
					'REMOTE_ADDR' => '12.0.0.1',
					'HTTP_X_FORWARDED_FOR' => '12.0.0.3, 12.0.0.2'
				],
				[ '12.0.0.1', '12.0.0.2' ],
				[],
				false,
				'With X-Forwaded-For'
			],
			[
				'12.0.0.1',
				[
					'REMOTE_ADDR' => '12.0.0.1',
					'HTTP_X_FORWARDED_FOR' => '12.0.0.3, 12.0.0.2'
				],
				[],
				[],
				false,
				'With X-Forwaded-For and disallowed server'
			],
			[
				'12.0.0.2',
				[
					'REMOTE_ADDR' => '12.0.0.1',
					'HTTP_X_FORWARDED_FOR' => '12.0.0.3, 12.0.0.2'
				],
				[ '12.0.0.1' ],
				[],
				false,
				'With multiple X-Forwaded-For and only one allowed server'
			],
			[
				'10.0.0.3',
				[
					'REMOTE_ADDR' => '12.0.0.2',
					'HTTP_X_FORWARDED_FOR' => '10.0.0.4, 10.0.0.3, 12.0.0.2'
				],
				[ '12.0.0.1', '12.0.0.2' ],
				[],
				false,
				'With X-Forwaded-For and private IP (from cache proxy)'
			],
			[
				'10.0.0.4',
				[
					'REMOTE_ADDR' => '12.0.0.2',
					'HTTP_X_FORWARDED_FOR' => '10.0.0.4, 10.0.0.3, 12.0.0.2'
				],
				[ '12.0.0.1', '12.0.0.2', '10.0.0.3' ],
				[],
				true,
				'With X-Forwaded-For and private IP (allowed)'
			],
			[
				'10.0.0.4',
				[
					'REMOTE_ADDR' => '12.0.0.2',
					'HTTP_X_FORWARDED_FOR' => '10.0.0.4, 10.0.0.3, 12.0.0.2'
				],
				[ '12.0.0.1', '12.0.0.2' ],
				[ '10.0.0.3' ],
				true,
				'With X-Forwaded-For and private IP (allowed)'
			],
			[
				'10.0.0.3',
				[
					'REMOTE_ADDR' => '12.0.0.2',
					'HTTP_X_FORWARDED_FOR' => '10.0.0.4, 10.0.0.3, 12.0.0.2'
				],
				[ '12.0.0.1', '12.0.0.2' ],
				[ '10.0.0.3' ],
				false,
				'With X-Forwaded-For and private IP (disallowed)'
			],
			[
				'12.0.0.3',
				[
					'REMOTE_ADDR' => '12.0.0.1',
					'HTTP_X_FORWARDED_FOR' => '12.0.0.3, 12.0.0.2'
				],
				[],
				[ '12.0.0.1', '12.0.0.2' ],
				false,
				'With X-Forwaded-For'
			],
			[
				'12.0.0.2',
				[
					'REMOTE_ADDR' => '12.0.0.1',
					'HTTP_X_FORWARDED_FOR' => '12.0.0.3, 12.0.0.2'
				],
				[],
				[ '12.0.0.1' ],
				false,
				'With multiple X-Forwaded-For and only one allowed server'
			],
			[
				'12.0.0.2',
				[
					'REMOTE_ADDR' => '12.0.0.2',
					'HTTP_X_FORWARDED_FOR' => '10.0.0.3, 12.0.0.2'
				],
				[],
				[ '12.0.0.2' ],
				false,
				'With X-Forwaded-For and private IP and hook (disallowed)'
			],
			[
				'12.0.0.1',
				[
					'REMOTE_ADDR' => 'abcd:0001:002:03:4:555:6666:7777',
					'HTTP_X_FORWARDED_FOR' => '12.0.0.1, abcd:0001:002:03:4:555:6666:7777',
				],
				[ 'ABCD:1:2:3::/64' ],
				[],
				false,
				'IPv6 CIDR'
			],
			[
				'12.0.0.3',
				[
					'REMOTE_ADDR' => '12.0.0.1',
					'HTTP_X_FORWARDED_FOR' => '12.0.0.3, 12.0.0.2'
				],
				[ '12.0.0.0/24' ],
				[],
				false,
				'IPv4 CIDR'
			],
		];
	}

	/**
	 * @expectedException MWException
	 * @covers WebRequest::getIP
	 */
	public function testGetIpLackOfRemoteAddrThrowAnException() {
		// ensure that local install state doesn't interfere with test
		$this->setMwGlobals( [
			'wgSquidServersNoPurge' => [],
			'wgSquidServers' => [],
			'wgUsePrivateIPs' => false,
			'wgHooks' => [],
		] );

		$request = new WebRequest();
		# Next call throw an exception about lacking an IP
		$request->getIP();
	}

	public static function provideLanguageData() {
		return [
			[ '', [], 'Empty Accept-Language header' ],
			[ 'en', [ 'en' => 1 ], 'One language' ],
			[ 'en, ar', [ 'en' => 1, 'ar' => 1 ], 'Two languages listed in appearance order.' ],
			[
				'zh-cn,zh-tw',
				[ 'zh-cn' => 1, 'zh-tw' => 1 ],
				'Two equally prefered languages, listed in appearance order per rfc3282. Checks c9119'
			],
			[
				'es, en; q=0.5',
				[ 'es' => 1, 'en' => '0.5' ],
				'Spanish as first language and English and second'
			],
			[ 'en; q=0.5, es', [ 'es' => 1, 'en' => '0.5' ], 'Less prefered language first' ],
			[ 'fr, en; q=0.5, es', [ 'fr' => 1, 'es' => 1, 'en' => '0.5' ], 'Three languages' ],
			[ 'en; q=0.5, es', [ 'es' => 1, 'en' => '0.5' ], 'Two languages' ],
			[ 'en, zh;q=0', [ 'en' => 1 ], "It's Chinese to me" ],
			[
				'es; q=1, pt;q=0.7, it; q=0.6, de; q=0.1, ru;q=0',
				[ 'es' => '1', 'pt' => '0.7', 'it' => '0.6', 'de' => '0.1' ],
				'Preference for Romance languages'
			],
			[
				'en-gb, en-us; q=1',
				[ 'en-gb' => 1, 'en-us' => '1' ],
				'Two equally prefered English variants'
			],
		];
	}

	/**
	 * @dataProvider provideLanguageData
	 * @covers WebRequest::getAcceptLang
	 */
	public function testAcceptLang( $acceptLanguageHeader, $expectedLanguages, $description ) {
		$_SERVER = [ 'HTTP_ACCEPT_LANGUAGE' => $acceptLanguageHeader ];
		$request = new WebRequest();
		$this->assertSame( $request->getAcceptLang(), $expectedLanguages, $description );
	}
}
