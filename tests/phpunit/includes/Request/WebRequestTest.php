<?php

use MediaWiki\Exception\MWException;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\ProxyLookup;
use MediaWiki\Request\WebRequest;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;

/**
 * @covers \MediaWiki\Request\WebRequest
 *
 * @group WebRequest
 */
class WebRequestTest extends MediaWikiIntegrationTestCase {
	private const INTERNAL_SERVER = 'http://wiki.site';

	/** @var array */
	private $oldServer;

	/** @var array */
	private $oldCookies;

	protected function setUp(): void {
		parent::setUp();

		$this->oldServer = $_SERVER;
		$this->oldCookies = $_COOKIE;
	}

	protected function tearDown(): void {
		$_SERVER = $this->oldServer;
		$_COOKIE = $this->oldCookies;

		parent::tearDown();
	}

	/**
	 * @dataProvider provideGetCookie
	 *
	 * @param mixed $expected Expected value
	 * @param array $cookies Cookies to set in $_COOKIE
	 * @param string $prefix Cookie prefix to use when retrieving the cookie
	 * @param string $cookieName Cookie name to retrieve
	 * @param mixed $defaultValue Default value to use when the cookie is not found
	 */
	public function testGetCookie( $expected, $cookies, $prefix, $cookieName, $defaultValue ) {
		$_COOKIE = $cookies;
		$request = new WebRequest();

		$actual = $defaultValue !== null ?
			$request->getCookie( $cookieName, $prefix, $defaultValue ) :
			$request->getCookie( $cookieName, $prefix );

		$this->assertSame( $expected, $actual );
	}

	public static function provideGetCookie() {
		yield 'no cookies with no default override' => [ null, [], '', 'test', null ];
		yield 'no cookies with explicit default override' => [ false, [], '', 'test', false ];

		yield 'missing cookie with no default override' => [ null, [ 'other' => 'bar' ], '', 'test', null ];
		yield 'missing cookie with explicit default override' => [ false, [ 'other' => 'bar' ], '', 'test', false ];

		yield 'cookie not matching prefix with no default override' => [
			null, [ 'test' => 'bar' ], 'prefix', 'test', null
		];
		yield 'cookie not matching prefix with explicit default override' => [
			false, [ 'test' => 'bar' ], 'prefix', 'test', false
		];

		// T363980
		yield 'cookie with array value with no default override' => [ null, [ 'test' => [ 'bar' ] ], '', 'test', null ];
		yield 'cookie with array value explicit default override' => [ false, [ 'test' => [ 'bar' ] ], '', 'test', false ];

		yield 'valid cookie' => [ 'value', [ 'test' => 'value' ], '', 'test', null ];
		yield 'valid cookie with prefix' => [ 'value', [ 'prefixtest' => 'value' ], 'prefix', 'test', null ];
		yield 'mangled cookie name' => [ 'value', [ 'test_mangled' => 'value' ], '', 'test.mangled', null ];
		yield 'mangled cookie name with prefix' => [
			'value', [ 'prefix_partstest_mangled' => 'value' ], 'prefix.parts', 'test.mangled', null
		];
	}

	/**
	 * @dataProvider provideDetectServer
	 */
	public function testDetectServer( $expected, $input, $description ) {
		$this->setServerVars( $input );
		$result = WebRequest::detectServer( true );
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
					'HTTP_HOST' => 'x:80',
				],
				'Host header with port'
			],
			[
				'http://x',
				[
					'HTTP_HOST' => 'x',
					'SERVER_PORT' => 80,
				],
				'Default SERVER_PORT as int',
			],
			[
				'http://x',
				[
					'HTTP_HOST' => 'x',
					'SERVER_PORT' => '80',
				],
				'Default SERVER_PORT as string',
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

	public function testGetElapsedTime() {
		$now = microtime( true ) - 10.0;
		$req = $this->mockWebRequest( [], [ 'requestTime' => $now ] );
		$this->assertGreaterThanOrEqual( 10.0, $req->getElapsedTime() );
		// Catch common errors, but don't fail on slow hardware or VMs (T199764).
		$this->assertEqualsWithDelta( 10.0, $req->getElapsedTime(), 60.0 );
	}

	public function testGetValNormal() {
		// Assert that WebRequest normalises GPC data using UtfNormal\Validator
		$input = "a \x00 null";
		$normal = "a \xef\xbf\xbd null";
		$req = $this->mockWebRequest( [ 'x' => $input, 'y' => [ $input, $input ] ] );
		$this->assertSame( $normal, $req->getVal( 'x' ) );
		$this->assertNotSame( $input, $req->getVal( 'x' ) );
		$this->assertSame( [ $normal, $normal ], $req->getArray( 'y' ) );
	}

	public function testGetVal() {
		$req = $this->mockWebRequest( [ 'x' => 'Value', 'y' => [ 'a' ], 'crlf' => "A\r\nb" ] );
		$this->assertSame( 'Value', $req->getVal( 'x' ), 'Simple value' );
		$this->assertNull( $req->getVal( 'z' ), 'Not found' );
		$this->assertNull( $req->getVal( 'y' ), 'Array is ignored' );
		$this->assertSame( "A\r\nb", $req->getVal( 'crlf' ), 'CRLF' );
	}

	public function testGetRawVal() {
		$req = $this->mockWebRequest( [
			'x' => 'Value',
			'y' => [ 'a' ],
			'crlf' => "A\r\nb"
		] );
		$this->assertSame( 'Value', $req->getRawVal( 'x' ) );
		$this->assertNull( $req->getRawVal( 'z' ), 'Not found' );
		$this->assertNull( $req->getRawVal( 'y' ), 'Array is ignored' );
		$this->assertSame( "A\r\nb", $req->getRawVal( 'crlf' ), 'CRLF' );
	}

	public function testGetArray() {
		$req = $this->mockWebRequest( [ 'x' => 'Value', 'y' => [ 'a', 'b' ] ] );
		$this->assertSame( [ 'Value' ], $req->getArray( 'x' ), 'Value becomes array' );
		$this->assertNull( $req->getArray( 'z' ), 'Not found' );
		$this->assertSame( [ 'a', 'b' ], $req->getArray( 'y' ) );
	}

	public function testGetIntArray() {
		$req = $this->mockWebRequest( [ 'x' => [ 'Value' ], 'y' => [ '0', '4.2', '-2' ] ] );
		$this->assertSame( [ 0 ], $req->getIntArray( 'x' ), 'Text becomes 0' );
		$this->assertNull( $req->getIntArray( 'z' ), 'Not found' );
		$this->assertSame( [ 0, 4, -2 ], $req->getIntArray( 'y' ) );
	}

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

	public function testGetIntOrNull() {
		$req = $this->mockWebRequest( [
			'x' => 'Value',
			'y' => [ 'a' ],
			'zero' => '0',
			'answer' => '4.2',
			'neg' => '-2',
		] );
		$this->assertNull( $req->getIntOrNull( 'x' ), 'Text' );
		$this->assertNull( $req->getIntOrNull( 'y' ), 'Array' );
		$this->assertNull( $req->getIntOrNull( 'z' ), 'Not found' );
		$this->assertSame( 0, $req->getIntOrNull( 'zero' ) );
		$this->assertSame( 4, $req->getIntOrNull( 'answer' ) );
		$this->assertSame( -2, $req->getIntOrNull( 'neg' ) );
	}

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

	public function testGetBool() {
		$req = $this->mockWebRequest( [
			'x' => 'Value',
			'y' => [ 'a' ],
			'zero' => '0',
			'f' => 'false',
			't' => 'true',
		] );
		$this->assertTrue( $req->getBool( 'x' ), 'Text' );
		$this->assertFalse( $req->getBool( 'y' ), 'Array' );
		$this->assertFalse( $req->getBool( 'z' ), 'Not found' );
		$this->assertFalse( $req->getBool( 'zero' ) );
		$this->assertTrue( $req->getBool( 'f' ) );
		$this->assertTrue( $req->getBool( 't' ) );
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
	 */
	public function testGetFuzzyBool( $value, $expected, $message = null ) {
		$req = $this->mockWebRequest( [ 'x' => $value ] );
		$this->assertSame( $expected, $req->getFuzzyBool( 'x' ), $message ?: "Value: '$value'" );
	}

	public function testGetFuzzyBoolDefault() {
		$req = $this->mockWebRequest();
		$this->assertFalse( $req->getFuzzyBool( 'z' ), 'Not found' );
	}

	public function testGetFuzzyBoolDefaultTrue() {
		$req = $this->mockWebRequest();
		$this->assertTrue( $req->getFuzzyBool( 'z', true ), 'Not found, default true' );
	}

	public function testGetCheck() {
		$req = $this->mockWebRequest( [ 'x' => 'Value', 'zero' => '0' ] );
		$this->assertFalse( $req->getCheck( 'z' ), 'Not found' );
		$this->assertTrue( $req->getCheck( 'x' ), 'Text' );
		$this->assertTrue( $req->getCheck( 'zero' ) );
	}

	public function testGetText() {
		// Avoid MediaWiki\Request\FauxRequest (overrides getText)
		$req = $this->mockWebRequest( [ 'crlf' => "Va\r\nlue" ] );
		$this->assertSame( "Va\nlue", $req->getText( 'crlf' ), 'CR stripped' );
	}

	public function testGetValues() {
		$values = [ 'x' => 'Value', 'y' => '' ];
		$req = $this->mockWebRequest( $values );
		$this->assertSame( $values, $req->getValues() );
		$this->assertSame( [ 'x' => 'Value' ], $req->getValues( 'x' ), 'Specific keys' );
	}

	public function testGetValueNames() {
		$req = $this->mockWebRequest( [ 'x' => 'Value', 'y' => '' ] );
		$this->assertSame( [ 'x', 'y' ], $req->getValueNames() );
		$this->assertSame( [ 'x' ], $req->getValueNames( [ 'y' ] ), 'Exclude keys' );
	}

	public function testGetFullRequestURL() {
		$this->overrideConfigValue( MainConfigNames::Server, '//wiki.test' );
		$req = $this->getMockBuilder( WebRequest::class )
			->onlyMethods( [ 'getRequestURL', 'getProtocol' ] )
			->getMock();
		$req->method( 'getRequestURL' )->willReturn( '/path' );
		$req->method( 'getProtocol' )->willReturn( 'https' );

		$this->assertSame(
			'https://wiki.test/path',
			$req->getFullRequestURL()
		);
	}

	/**
	 * @dataProvider provideGetIP
	 */
	public function testGetIP( $expected, $input, $cdn, $xffList, $private, $description ) {
		$this->setServerVars( $input );
		$this->overrideConfigValue( MainConfigNames::UsePrivateIPs, $private );

		$hookContainer = $this->createHookContainer( [
			'IsTrustedProxy' => static function ( &$ip, &$trusted ) use ( $xffList ) {
				$trusted = $trusted || in_array( $ip, $xffList );
				return true;
			}
		] );
		$this->setService( 'ProxyLookup', new ProxyLookup( [], $cdn, $hookContainer ) );

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

	public function testGetIpLackOfRemoteAddrThrowAnException() {
		// ensure that local install state doesn't interfere with test
		$this->overrideConfigValues( [
			MainConfigNames::CdnServers => [],
			MainConfigNames::CdnServersNoPurge => [],
			MainConfigNames::UsePrivateIPs => false,
		] );

		$hookContainer = $this->createHookContainer();
		$this->setService( 'ProxyLookup', new ProxyLookup( [], [], $hookContainer ) );

		$request = new WebRequest();
		# Next call should throw an exception about lacking an IP
		$this->expectException( MWException::class );
		$request->getIP();
	}

	public static function provideLanguageData() {
		return [
			[ '', [], 'Empty Accept-Language header' ],
			[ 'en', [ 'en' => 1.0 ], 'One language' ],
			[ 'en;q=', [ 'en' => 1.0 ], 'Empty q= defaults to 1' ],
			[ 'en;q=0, de;q=0. pt;q=0.0 it;q=0.0000', [], 'Zeros to be skipped' ],
			[ 'EN;Q=1.0009', [ 'en' => 1.000 ], 'Limited to max. 3 decimal places' ],
			[ 'en, ar', [ 'en' => 1.0, 'ar' => 1.0 ], 'Two languages listed in appearance order.' ],
			[
				'zh-cn,zh-tw',
				[ 'zh-cn' => 1.0, 'zh-tw' => 1.0 ],
				'Two equally prefered languages, listed in appearance order per rfc3282. Checks c9119'
			],
			[
				'es, en; q=0.5',
				[ 'es' => 1.0, 'en' => 0.5 ],
				'Spanish as first language and English and second'
			],
			[ 'en; q=0.5, es', [ 'es' => 1.0, 'en' => 0.5 ], 'Less prefered language first' ],
			[ 'fr, en; q=0.5, es', [ 'fr' => 1.0, 'es' => 1.0, 'en' => 0.5 ], 'Three languages' ],
			[ 'en; q=0.5, es', [ 'es' => 1.0, 'en' => 0.5 ], 'Two languages' ],
			[ 'en, zh;q=0', [ 'en' => 1.0 ], "It's Chinese to me" ],
			[
				'es; q=1, pt;q=0.7, it; q=0.6, de; q=0.1, ru;q=0',
				[ 'es' => 1.0, 'pt' => 0.7, 'it' => 0.6, 'de' => 0.1 ],
				'Preference for Romance languages'
			],
			[
				'en-gb, en-us; q=1',
				[ 'en-gb' => 1.0, 'en-us' => 1.0 ],
				'Two equally prefered English variants'
			],
			[ '_', [], 'Invalid input' ],
		];
	}

	/**
	 * @dataProvider provideLanguageData
	 */
	public function testAcceptLang( $acceptLanguageHeader, array $expectedLanguages, $description ) {
		$this->setServerVars( [ 'HTTP_ACCEPT_LANGUAGE' => $acceptLanguageHeader ] );
		$request = new WebRequest();
		$this->assertSame( $expectedLanguages, $request->getAcceptLang(), $description );
	}

	public function testGetHeaderCanYieldSpecialCgiHeaders() {
		$contentType = 'application/json; charset=utf-8';
		$contentLength = '4711';
		$contentMd5 = 'rL0Y20zC+Fzt72VPzMSk2A==';
		$this->setServerVars( [
			'HTTP_CONTENT_TYPE' => $contentType,
			'HTTP_CONTENT_LENGTH' => $contentLength,
			'HTTP_CONTENT_MD5' => $contentMd5,
		] );
		$request = new WebRequest();
		$this->assertSame( $request->getHeader( 'Content-Type' ), $contentType );
		$this->assertSame( $request->getHeader( 'Content-Length' ), $contentLength );
		$this->assertSame( $request->getHeader( 'Content-Md5' ), $contentMd5 );
	}

	public function testGetHeaderKeyIsCaseInsensitive() {
		$cacheControl = 'private, must-revalidate, max-age=0';
		$this->setServerVars( [ 'HTTP_CACHE_CONTROL' => $cacheControl ] );
		$request = new WebRequest();
		$this->assertSame( $request->getHeader( 'Cache-Control' ), $cacheControl );
		$this->assertSame( $request->getHeader( 'cache-control' ), $cacheControl );
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

	/**
	 * @dataProvider provideMatchURLForCDN
	 */
	public function testMatchURLForCDN( $url, $cdnUrls, $matchOrder, $expected ) {
		$this->setServerVars( [ 'REQUEST_URI' => $url ] );
		$this->overrideConfigValues( [
			MainConfigNames::InternalServer => self::INTERNAL_SERVER,
			MainConfigNames::CdnMatchParameterOrder => $matchOrder,
		] );
		$request = new WebRequest();
		$this->assertEquals( $expected, $request->matchURLForCDN( $cdnUrls ) );
	}

	public static function provideMatchURLForCDN() {
		$cdnUrls = [
			self::INTERNAL_SERVER . '/Title',
			self::INTERNAL_SERVER . '/w/index.php?title=Title&action=history',
		];
		return [
			[ self::INTERNAL_SERVER . '/Title', $cdnUrls, /* matchOrder= */ false, true ],
			[ self::INTERNAL_SERVER . '/Title', $cdnUrls, /* matchOrder= */ true, true ],
			[ self::INTERNAL_SERVER . '/Foo', $cdnUrls, /* matchOrder= */ false, false ],
			[ self::INTERNAL_SERVER . '/Foo', $cdnUrls, /* matchOrder= */ true, false ],
			[ self::INTERNAL_SERVER . '/Thing', $cdnUrls, /* matchOrder= */ false, false ],
			[ self::INTERNAL_SERVER . '/Thing', $cdnUrls, /* matchOrder= */ true, false ],
			[ self::INTERNAL_SERVER . '/w/index.php?action=history&title=Foo', $cdnUrls, /* matchOrder= */ false, false ],
			[ self::INTERNAL_SERVER . '/w/index.php?action=history&title=Foo', $cdnUrls, /* matchOrder= */ true, false ],
			[ self::INTERNAL_SERVER . '/w/index.php?title=Thing&action=history', $cdnUrls, /* matchOrder= */ false, false ],
			[ self::INTERNAL_SERVER . '/w/index.php?action=history&title=Thing', $cdnUrls, /* matchOrder= */ true, false ],
			[ self::INTERNAL_SERVER . '/w/index.php?action=history&title=Title', $cdnUrls, /* matchOrder= */ false, true ],
			[ self::INTERNAL_SERVER . '/w/index.php?action=history&title=Title', $cdnUrls, /* matchOrder= */ true, false ],
		];
	}

	/**
	 * @dataProvider provideRequestPathSuffix
	 *
	 * @param string $basePath
	 * @param string $requestUrl
	 * @param string|false $expected
	 */
	public function testRequestPathSuffix( string $basePath, string $requestUrl, $expected ) {
		$suffix = WebRequest::getRequestPathSuffix( $basePath, $requestUrl );
		$this->assertSame( $expected, $suffix );
	}

	public static function provideRequestPathSuffix() {
		yield [
			'/w/index.php',
			'/w/index.php/Hello',
			'Hello'
		];
		yield [
			'/w/index.php',
			'/w/index.php/Hello?x=y',
			'Hello'
		];
		yield [
			'/wiki/',
			'/w/index.php/Hello?x=y',
			false
		];
	}

	/**
	 * @dataProvider provideGetSecurityLogContext
	 */
	public function testGetSecurityLogContext( ?UserIdentity $user ) {
		$request = new WebRequest();
		$this->setServerVars( [ 'REMOTE_ADDR' => '127.0.0.1' ] );
		$this->setTemporaryHook(
			'GetSecurityLogContext',
			function ( array $info, array &$context ) use ( $request, $user ) {
				$this->assertSame( $request, $info['request'] );
				$this->assertSame( $user, $info['user'] );
				$context['foo'] = 'bar';
			}
		);

		$context = $request->getSecurityLogContext( $user );
		$this->assertSame( '127.0.0.1', $context['clientIp'] );
		if ( $user ) {
			$this->assertSame( $user->getName(), $context['user'] );
		} else {
			$this->assertArrayNotHasKey( 'user', $context );
		}
		$this->assertSame( 'bar', $context['foo'] );

		$this->setTemporaryHook( 'GetSecurityLogContext', fn () => $this->fail( 'should be cached' ) );
		$request->getSecurityLogContext( $user );

		$this->setTemporaryHook(
			'GetSecurityLogContext',
			static function ( array $info, array &$context ) use ( $request, $user ) {
				$context['foo'] = 'bar2';
			}
		);
		$context2 = $request->getSecurityLogContext( new UserIdentityValue( 0, 'DifferentTestUser' ) );
		// different cache entry for different user
		$this->assertSame( 'DifferentTestUser', $context2['user'] );
	}

	public static function provideGetSecurityLogContext() {
		return [
			[ null ],
			[ new UserIdentityValue( 0, 'TestUser' ) ],
		];
	}
}
