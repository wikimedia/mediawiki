<?php

/**
 * @group WebRequest
 */
class WebRequestTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();

		$this->oldServer = $_SERVER;
		$this->oldWgRequest = $GLOBALS['wgRequest'];
		$this->oldWgServer = $GLOBALS['wgServer'];
	}

	protected function tearDown() {
		$_SERVER = $this->oldServer;
		$GLOBALS['wgRequest'] = $this->oldWgRequest;
		$GLOBALS['wgServer'] = $this->oldWgServer;

		parent::tearDown();
	}

	/**
	 * @dataProvider provideDetectServer
	 * @covers WebRequest::detectServer
	 * @covers WebRequest::detectProtocol
	 */
	public function testDetectServer( $expected, $input, $description ) {
		$this->setMwGlobals( 'wgAssumeProxiesUseDefaultProtocolPorts', true );

		$this->setServerVars( $input );
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
				'https://x',
				[
					'HTTP_HOST' => 'x',
					'HTTP_X_FORWARDED_PROTO' => 'https',
				],
				'Forwarded HTTPS'
			],
			[
				'https://x',
				[
					'HTTP_HOST' => 'x',
					'HTTPS' => 'off',
					'SERVER_PORT' => '81',
					'HTTP_X_FORWARDED_PROTO' => 'https',
				],
				'Forwarded HTTPS'
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
	 * @param array $data Request data
	 * @param array $config
	 *  - float 'requestTime': Mock value for `$_SERVER['REQUEST_TIME_FLOAT']`.
	 * @return WebRequest
	 */
	protected function mockWebRequest( array $data = [], array $config = [] ) {
		// Cannot use PHPUnit getMockBuilder() as it does not support
		// overriding protected properties afterwards
		$reflection = new ReflectionClass( WebRequest::class );
		$req = $reflection->newInstanceWithoutConstructor();

		$prop = $reflection->getProperty( 'data' );
		$prop->setAccessible( true );
		$prop->setValue( $req, $data );

		if ( isset( $config['requestTime'] ) ) {
			$prop = $reflection->getProperty( 'requestTime' );
			$prop->setAccessible( true );
			$prop->setValue( $req, $config['requestTime'] );
		}

		return $req;
	}

	/**
	 * @covers WebRequest::getElapsedTime
	 */
	public function testGetElapsedTime() {
		$now = microtime( true ) - 10.0;
		$req = $this->mockWebRequest( [], [ 'requestTime' => $now ] );
		$this->assertGreaterThanOrEqual( 10.0, $req->getElapsedTime() );
		// Catch common errors, but don't fail on slow hardware or VMs (T199764).
		$this->assertEquals( 10.0, $req->getElapsedTime(), '', 60.0 );
	}

	/**
	 * @covers WebRequest::getVal
	 * @covers WebRequest::getGPCVal
	 * @covers WebRequest::normalizeUnicode
	 */
	public function testGetValNormal() {
		// Assert that WebRequest normalises GPC data using UtfNormal\Validator
		$input = "a \x00 null";
		$normal = "a \xef\xbf\xbd null";
		$req = $this->mockWebRequest( [ 'x' => $input, 'y' => [ $input, $input ] ] );
		$this->assertSame( $normal, $req->getVal( 'x' ) );
		$this->assertNotSame( $input, $req->getVal( 'x' ) );
		$this->assertSame( [ $normal, $normal ], $req->getArray( 'y' ) );
	}

	/**
	 * @covers WebRequest::getVal
	 * @covers WebRequest::getGPCVal
	 */
	public function testGetVal() {
		$req = $this->mockWebRequest( [ 'x' => 'Value', 'y' => [ 'a' ], 'crlf' => "A\r\nb" ] );
		$this->assertSame( 'Value', $req->getVal( 'x' ), 'Simple value' );
		$this->assertSame( null, $req->getVal( 'z' ), 'Not found' );
		$this->assertSame( null, $req->getVal( 'y' ), 'Array is ignored' );
		$this->assertSame( "A\r\nb", $req->getVal( 'crlf' ), 'CRLF' );
	}

	/**
	 * @covers WebRequest::getRawVal
	 */
	public function testGetRawVal() {
		$req = $this->mockWebRequest( [
			'x' => 'Value',
			'y' => [ 'a' ],
			'crlf' => "A\r\nb"
		] );
		$this->assertSame( 'Value', $req->getRawVal( 'x' ) );
		$this->assertSame( null, $req->getRawVal( 'z' ), 'Not found' );
		$this->assertSame( null, $req->getRawVal( 'y' ), 'Array is ignored' );
		$this->assertSame( "A\r\nb", $req->getRawVal( 'crlf' ), 'CRLF' );
	}

	/**
	 * @covers WebRequest::getArray
	 */
	public function testGetArray() {
		$req = $this->mockWebRequest( [ 'x' => 'Value', 'y' => [ 'a', 'b' ] ] );
		$this->assertSame( [ 'Value' ], $req->getArray( 'x' ), 'Value becomes array' );
		$this->assertSame( null, $req->getArray( 'z' ), 'Not found' );
		$this->assertSame( [ 'a', 'b' ], $req->getArray( 'y' ) );
	}

	/**
	 * @covers WebRequest::getIntArray
	 */
	public function testGetIntArray() {
		$req = $this->mockWebRequest( [ 'x' => [ 'Value' ], 'y' => [ '0', '4.2', '-2' ] ] );
		$this->assertSame( [ 0 ], $req->getIntArray( 'x' ), 'Text becomes 0' );
		$this->assertSame( null, $req->getIntArray( 'z' ), 'Not found' );
		$this->assertSame( [ 0, 4, -2 ], $req->getIntArray( 'y' ) );
	}

	/**
	 * @covers WebRequest::getInt
	 */
	public function testGetInt() {
		$req = $this->mockWebRequest( [
			'x' => 'Value',
			'y' => [ 'a' ],
			'zero' => '0',
			'answer' => '4.2',
			'neg' => '-2',
		] );
		$this->assertSame( 0, $req->getInt( 'x' ), 'Text' );
		$this->assertSame( 0, $req->getInt( 'y' ), 'Array' );
		$this->assertSame( 0, $req->getInt( 'z' ), 'Not found' );
		$this->assertSame( 0, $req->getInt( 'zero' ) );
		$this->assertSame( 4, $req->getInt( 'answer' ) );
		$this->assertSame( -2, $req->getInt( 'neg' ) );
	}

	/**
	 * @covers WebRequest::getIntOrNull
	 */
	public function testGetIntOrNull() {
		$req = $this->mockWebRequest( [
			'x' => 'Value',
			'y' => [ 'a' ],
			'zero' => '0',
			'answer' => '4.2',
			'neg' => '-2',
		] );
		$this->assertSame( null, $req->getIntOrNull( 'x' ), 'Text' );
		$this->assertSame( null, $req->getIntOrNull( 'y' ), 'Array' );
		$this->assertSame( null, $req->getIntOrNull( 'z' ), 'Not found' );
		$this->assertSame( 0, $req->getIntOrNull( 'zero' ) );
		$this->assertSame( 4, $req->getIntOrNull( 'answer' ) );
		$this->assertSame( -2, $req->getIntOrNull( 'neg' ) );
	}

	/**
	 * @covers WebRequest::getFloat
	 */
	public function testGetFloat() {
		$req = $this->mockWebRequest( [
			'x' => 'Value',
			'y' => [ 'a' ],
			'zero' => '0',
			'answer' => '4.2',
			'neg' => '-2',
		] );
		$this->assertSame( 0.0, $req->getFloat( 'x' ), 'Text' );
		$this->assertSame( 0.0, $req->getFloat( 'y' ), 'Array' );
		$this->assertSame( 0.0, $req->getFloat( 'z' ), 'Not found' );
		$this->assertSame( 0.0, $req->getFloat( 'zero' ) );
		$this->assertSame( 4.2, $req->getFloat( 'answer' ) );
		$this->assertSame( -2.0, $req->getFloat( 'neg' ) );
	}

	/**
	 * @covers WebRequest::getBool
	 */
	public function testGetBool() {
		$req = $this->mockWebRequest( [
			'x' => 'Value',
			'y' => [ 'a' ],
			'zero' => '0',
			'f' => 'false',
			't' => 'true',
		] );
		$this->assertSame( true, $req->getBool( 'x' ), 'Text' );
		$this->assertSame( false, $req->getBool( 'y' ), 'Array' );
		$this->assertSame( false, $req->getBool( 'z' ), 'Not found' );
		$this->assertSame( false, $req->getBool( 'zero' ) );
		$this->assertSame( true, $req->getBool( 'f' ) );
		$this->assertSame( true, $req->getBool( 't' ) );
	}

	public static function provideFuzzyBool() {
		return [
			[ 'Text', true ],
			[ '', false, '(empty string)' ],
			[ '0', false ],
			[ '1', true ],
			[ 'false', false ],
			[ 'true', true ],
			[ 'False', false ],
			[ 'True', true ],
			[ 'FALSE', false ],
			[ 'TRUE', true ],
		];
	}

	/**
	 * @dataProvider provideFuzzyBool
	 * @covers WebRequest::getFuzzyBool
	 */
	public function testGetFuzzyBool( $value, $expected, $message = null ) {
		$req = $this->mockWebRequest( [ 'x' => $value ] );
		$this->assertSame( $expected, $req->getFuzzyBool( 'x' ), $message ?: "Value: '$value'" );
	}

	/**
	 * @covers WebRequest::getFuzzyBool
	 */
	public function testGetFuzzyBoolDefault() {
		$req = $this->mockWebRequest();
		$this->assertSame( false, $req->getFuzzyBool( 'z' ), 'Not found' );
	}

	/**
	 * @covers WebRequest::getCheck
	 */
	public function testGetCheck() {
		$req = $this->mockWebRequest( [ 'x' => 'Value', 'zero' => '0' ] );
		$this->assertSame( false, $req->getCheck( 'z' ), 'Not found' );
		$this->assertSame( true, $req->getCheck( 'x' ), 'Text' );
		$this->assertSame( true, $req->getCheck( 'zero' ) );
	}

	/**
	 * @covers WebRequest::getText
	 */
	public function testGetText() {
		// Avoid FauxRequest (overrides getText)
		$req = $this->mockWebRequest( [ 'crlf' => "Va\r\nlue" ] );
		$this->assertSame( "Va\nlue", $req->getText( 'crlf' ), 'CR stripped' );
	}

	/**
	 * @covers WebRequest::getValues
	 */
	public function testGetValues() {
		$values = [ 'x' => 'Value', 'y' => '' ];
		// Avoid FauxRequest (overrides getValues)
		$req = $this->mockWebRequest( $values );
		$this->assertSame( $values, $req->getValues() );
		$this->assertSame( [ 'x' => 'Value' ], $req->getValues( 'x' ), 'Specific keys' );
	}

	/**
	 * @covers WebRequest::getValueNames
	 */
	public function testGetValueNames() {
		$req = $this->mockWebRequest( [ 'x' => 'Value', 'y' => '' ] );
		$this->assertSame( [ 'x', 'y' ], $req->getValueNames() );
		$this->assertSame( [ 'x' ], $req->getValueNames( [ 'y' ] ), 'Exclude keys' );
	}

	/**
	 * @covers WebRequest
	 */
	public function testGetFullRequestURL() {
		// Stub this for wfGetServerUrl()
		$GLOBALS['wgServer'] = '//wiki.test';
		$req = $this->getMock( WebRequest::class, [ 'getRequestURL', 'getProtocol' ] );
		$req->method( 'getRequestURL' )->willReturn( '/path' );
		$req->method( 'getProtocol' )->willReturn( 'https' );

		$this->assertSame(
			'https://wiki.test/path',
			$req->getFullRequestURL()
		);
	}

	/**
	 * @dataProvider provideGetIP
	 * @covers WebRequest::getIP
	 */
	public function testGetIP( $expected, $input, $cdn, $xffList, $private, $description ) {
		$this->setServerVars( $input );
		$this->setMwGlobals( [
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

		$this->setService( 'ProxyLookup', new ProxyLookup( [], $cdn ) );

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
			'wgCdnServers' => [],
			'wgCdnServersNoPurge' => [],
			'wgUsePrivateIPs' => false,
			'wgHooks' => [],
		] );
		$this->setService( 'ProxyLookup', new ProxyLookup( [], [] ) );

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
			[ '_', [], 'Invalid input' ],
		];
	}

	/**
	 * @dataProvider provideLanguageData
	 * @covers WebRequest::getAcceptLang
	 */
	public function testAcceptLang( $acceptLanguageHeader, $expectedLanguages, $description ) {
		$this->setServerVars( [ 'HTTP_ACCEPT_LANGUAGE' => $acceptLanguageHeader ] );
		$request = new WebRequest();
		$this->assertSame( $request->getAcceptLang(), $expectedLanguages, $description );
	}

	protected function setServerVars( $vars ) {
		// Don't remove vars which should be available in all SAPI.
		if ( !isset( $vars['REQUEST_TIME_FLOAT'] ) ) {
			$vars['REQUEST_TIME_FLOAT'] = $_SERVER['REQUEST_TIME_FLOAT'];
		}
		if ( !isset( $vars['REQUEST_TIME'] ) ) {
			$vars['REQUEST_TIME'] = $_SERVER['REQUEST_TIME'];
		}
		$_SERVER = $vars;
	}
}
