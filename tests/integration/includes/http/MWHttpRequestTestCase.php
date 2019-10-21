<?php

use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\MediaWikiServices;
use Wikimedia\TestingAccessWrapper;

abstract class MWHttpRequestTestCase extends PHPUnit\Framework\TestCase {
	protected static $httpEngine;
	protected $oldHttpEngine;

	/** @var HttpRequestFactory */
	private $factory;

	public function setUp() {
		parent::setUp();
		$this->oldHttpEngine = Http::$httpEngine;
		Http::$httpEngine = static::$httpEngine;

		$this->factory = MediaWikiServices::getInstance()->getHttpRequestFactory();

		try {
			$request = $factory->create( 'null:' );
		} catch ( RuntimeException $e ) {
			$this->markTestSkipped( static::$httpEngine . ' engine not supported' );
		}

		if ( static::$httpEngine === 'php' ) {
			$this->assertInstanceOf( PhpHttpRequest::class, $request );
		} else {
			$this->assertInstanceOf( CurlHttpRequest::class, $request );
		}
	}

	public function tearDown() {
		parent::tearDown();
		Http::$httpEngine = $this->oldHttpEngine;
	}

	// --------------------

	public function testIsRedirect() {
		$request = $this->factory->create( 'http://httpbin.org/get' );
		$status = $request->execute();
		$this->assertTrue( $status->isGood() );
		$this->assertFalse( $request->isRedirect() );

		$request = $this->factory->create( 'http://httpbin.org/redirect/1' );
		$status = $request->execute();
		$this->assertTrue( $status->isGood() );
		$this->assertTrue( $request->isRedirect() );
	}

	public function testgetFinalUrl() {
		$request = $this->factory->create( 'http://httpbin.org/redirect/3' );
		if ( !$request->canFollowRedirects() ) {
			$this->markTestSkipped( 'cannot follow redirects' );
		}
		$status = $request->execute();
		$this->assertTrue( $status->isGood() );
		$this->assertNotSame( 'http://httpbin.org/get', $request->getFinalUrl() );

		$request = $this->factory->create( 'http://httpbin.org/redirect/3', [ 'followRedirects'
			=> true ] );
		$status = $request->execute();
		$this->assertTrue( $status->isGood() );
		$this->assertSame( 'http://httpbin.org/get', $request->getFinalUrl() );
		$this->assertResponseFieldValue( 'url', 'http://httpbin.org/get', $request );

		$request = $this->factory->create( 'http://httpbin.org/redirect/3', [ 'followRedirects'
		=> true ] );
		$status = $request->execute();
		$this->assertTrue( $status->isGood() );
		$this->assertSame( 'http://httpbin.org/get', $request->getFinalUrl() );
		$this->assertResponseFieldValue( 'url', 'http://httpbin.org/get', $request );

		if ( static::$httpEngine === 'curl' ) {
			$this->markTestIncomplete( 'maxRedirects seems to be ignored by CurlHttpRequest' );
			return;
		}

		$request = $this->factory->create( 'http://httpbin.org/redirect/3', [ 'followRedirects'
		=> true, 'maxRedirects' => 1 ] );
		$status = $request->execute();
		$this->assertTrue( $status->isGood() );
		$this->assertNotSame( 'http://httpbin.org/get', $request->getFinalUrl() );
	}

	public function testSetCookie() {
		$request = $this->factory->create( 'http://httpbin.org/cookies' );
		$request->setCookie( 'foo', 'bar' );
		$request->setCookie( 'foo2', 'bar2', [ 'domain' => 'example.com' ] );
		$status = $request->execute();
		$this->assertTrue( $status->isGood() );
		$this->assertResponseFieldValue( 'cookies', [ 'foo' => 'bar' ], $request );
	}

	public function testSetCookieJar() {
		$request = $this->factory->create( 'http://httpbin.org/cookies' );
		$cookieJar = new CookieJar();
		$cookieJar->setCookie( 'foo', 'bar', [ 'domain' => 'httpbin.org' ] );
		$cookieJar->setCookie( 'foo2', 'bar2', [ 'domain' => 'example.com' ] );
		$request->setCookieJar( $cookieJar );
		$status = $request->execute();
		$this->assertTrue( $status->isGood() );
		$this->assertResponseFieldValue( 'cookies', [ 'foo' => 'bar' ], $request );

		$request = $this->factory->create( 'http://httpbin.org/cookies/set?foo=bar' );
		$cookieJar = new CookieJar();
		$request->setCookieJar( $cookieJar );
		$status = $request->execute();
		$this->assertTrue( $status->isGood() );
		$this->assertHasCookie( 'foo', 'bar', $request->getCookieJar() );

		$this->markTestIncomplete( 'CookieJar does not handle deletion' );

		// $request = $this->factory->create( 'http://httpbin.org/cookies/delete?foo' );
		// $cookieJar = new CookieJar();
		// $cookieJar->setCookie( 'foo', 'bar', [ 'domain' => 'httpbin.org' ] );
		// $cookieJar->setCookie( 'foo2', 'bar2', [ 'domain' => 'httpbin.org' ] );
		// $request->setCookieJar( $cookieJar );
		// $status = $request->execute();
		// $this->assertTrue( $status->isGood() );
		// $this->assertNotHasCookie( 'foo', $request->getCookieJar() );
		// $this->assertHasCookie( 'foo2', 'bar2', $request->getCookieJar() );
	}

	public function testGetResponseHeaders() {
		$request = $this->factory->create( 'http://httpbin.org/response-headers?Foo=bar' );
		$status = $request->execute();
		$this->assertTrue( $status->isGood() );
		$headers = array_change_key_case( $request->getResponseHeaders(), CASE_LOWER );
		$this->assertArrayHasKey( 'foo', $headers );
		$this->assertSame( $request->getResponseHeader( 'Foo' ), 'bar' );
	}

	public function testSetHeader() {
		$request = $this->factory->create( 'http://httpbin.org/headers' );
		$request->setHeader( 'Foo', 'bar' );
		$status = $request->execute();
		$this->assertTrue( $status->isGood() );
		$this->assertResponseFieldValue( [ 'headers', 'Foo' ], 'bar', $request );
	}

	public function testGetStatus() {
		$request = $this->factory->create( 'http://httpbin.org/status/418' );
		$status = $request->execute();
		$this->assertFalse( $status->isOK() );
		$this->assertSame( $request->getStatus(), 418 );
	}

	public function testSetUserAgent() {
		$request = $this->factory->create( 'http://httpbin.org/user-agent' );
		$request->setUserAgent( 'foo' );
		$status = $request->execute();
		$this->assertTrue( $status->isGood() );
		$this->assertResponseFieldValue( 'user-agent', 'foo', $request );
	}

	public function testSetData() {
		$request = $this->factory->create( 'http://httpbin.org/post', [ 'method' => 'POST' ] );
		$request->setData( [ 'foo' => 'bar', 'foo2' => 'bar2' ] );
		$status = $request->execute();
		$this->assertTrue( $status->isGood() );
		$this->assertResponseFieldValue( 'form', [ 'foo' => 'bar', 'foo2' => 'bar2' ], $request );
	}

	public function testSetCallback() {
		if ( static::$httpEngine === 'php' ) {
			$this->markTestIncomplete( 'PhpHttpRequest does not use setCallback()' );
			return;
		}

		$request = $this->factory->create( 'http://httpbin.org/ip' );
		$data = '';
		$request->setCallback( function ( $fh, $content ) use ( &$data ) {
			$data .= $content;
			return strlen( $content );
		} );
		$status = $request->execute();
		$this->assertTrue( $status->isGood() );
		$data = json_decode( $data, true );
		$this->assertInternalType( 'array', $data );
		$this->assertArrayHasKey( 'origin', $data );
	}

	public function testBasicAuthentication() {
		$request = $this->factory->create( 'http://httpbin.org/basic-auth/user/pass', [
			'username' => 'user',
			'password' => 'pass',
		] );
		$status = $request->execute();
		$this->assertTrue( $status->isGood() );
		$this->assertResponseFieldValue( 'authenticated', true, $request );

		$request = $this->factory->create( 'http://httpbin.org/basic-auth/user/pass', [
			'username' => 'user',
			'password' => 'wrongpass',
		] );
		$status = $request->execute();
		$this->assertFalse( $status->isOK() );
		$this->assertSame( 401, $request->getStatus() );
	}

	public function testFactoryDefaults() {
		$request = $this->factory->create( 'http://acme.test' );
		$this->assertInstanceOf( MWHttpRequest::class, $request );
	}

	// --------------------

	/**
	 * Verifies that the request was successful, returned valid JSON and the given field of that
	 * JSON data is as expected.
	 * @param string|string[] $key Path to the data in the response object
	 * @param mixed $expectedValue
	 * @param MWHttpRequest $response
	 */
	protected function assertResponseFieldValue( $key, $expectedValue, MWHttpRequest $response ) {
		$this->assertSame( 200, $response->getStatus(), 'response status is not 200' );
		$data = json_decode( $response->getContent(), true );
		$this->assertInternalType( 'array', $data, 'response is not JSON' );
		$keyPath = '';
		foreach ( (array)$key as $keySegment ) {
			$keyPath .= ( $keyPath ? '.' : '' ) . $keySegment;
			$this->assertArrayHasKey( $keySegment, $data, $keyPath . ' not found' );
			$data = $data[$keySegment];
		}
		$this->assertSame( $expectedValue, $data );
	}

	/**
	 * Asserts that the cookie jar has the given cookie with the given value.
	 * @param string $expectedName Cookie name
	 * @param string $expectedValue Cookie value
	 * @param CookieJar $cookieJar
	 */
	protected function assertHasCookie( $expectedName, $expectedValue, CookieJar $cookieJar ) {
		$cookieJar = TestingAccessWrapper::newFromObject( $cookieJar );
		$cookies = array_change_key_case( $cookieJar->cookie, CASE_LOWER );
		$this->assertArrayHasKey( strtolower( $expectedName ), $cookies );
		$cookie = TestingAccessWrapper::newFromObject(
			$cookies[strtolower( $expectedName )] );
		$this->assertSame( $expectedValue, $cookie->value );
	}

	/**
	 * Asserts that the cookie jar does not have the given cookie.
	 * @param string $name Cookie name
	 * @param CookieJar $cookieJar
	 */
	protected function assertNotHasCookie( $name, CookieJar $cookieJar ) {
		$cookieJar = TestingAccessWrapper::newFromObject( $cookieJar );
		$this->assertArrayNotHasKey( strtolower( $name ),
			array_change_key_case( $cookieJar->cookie, CASE_LOWER ) );
	}

	public static function provideRelativeRedirects() {
		return [
			[
				'location' => [ 'http://newsite/file.ext', '/newfile.ext' ],
				'final' => 'http://newsite/newfile.ext',
				'Relative file path Location: interpreted as full URL'
			],
			[
				'location' => [ 'https://oldsite/file.ext' ],
				'final' => 'https://oldsite/file.ext',
				'Location to the HTTPS version of the site'
			],
			[
				'location' => [
					'/anotherfile.ext',
					'http://anotherfile/hoster.ext',
					'https://anotherfile/hoster.ext'
				],
				'final' => 'https://anotherfile/hoster.ext',
				'Relative file path Location: should keep the latest host and scheme!'
			],
			[
				'location' => [ '/anotherfile.ext' ],
				'final' => 'http://oldsite/anotherfile.ext',
				'Relative Location without domain '
			],
			[
				'location' => null,
				'final' => 'http://oldsite/file.ext',
				'No Location (no redirect) '
			],
		];
	}

	/**
	 * @dataProvider provideRelativeRedirects
	 * @covers MWHttpRequest::getFinalUrl
	 */
	public function testRelativeRedirections( $location, $final, $message = null ) {
		$h = $this->factory->create( 'http://oldsite/file.ext', [], __METHOD__ );
		$h = TestingAccessWrapper::newFromObject( $h );

		// Forge a Location header
		$h->respHeaders['location'] = $location;

		// Verify it correctly fixes the Location
		$this->assertEquals( $final, $h->getFinalUrl(), $message );
	}

}
