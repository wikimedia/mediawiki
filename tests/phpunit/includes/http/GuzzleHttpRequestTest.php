<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

/**
 * class for tests of GuzzleHttpRequest
 *
 * No actual requests are made herein - all external communications are mocked
 *
 * @covers GuzzleHttpRequest
 * @covers MWHttpRequest
 */
class GuzzleHttpRequestTest extends MediaWikiIntegrationTestCase {
	/** @var int[] */
	private $timeoutOptions = [
		'timeout' => 1,
		'connectTimeout' => 1
	];

	/**
	 * Placeholder url to use for various tests.  This is never contacted, but we must use
	 * a url of valid format to avoid validation errors.
	 * @var string
	 */
	protected $exampleUrl = 'http://www.example.test';

	/**
	 * Minimal example body text
	 * @var string
	 */
	protected $exampleBodyText = 'x';

	/**
	 * For accumulating callback data for testing
	 * @var string
	 */
	protected $bodyTextReceived = '';

	/**
	 * Callback: process a chunk of the result of a HTTP request
	 *
	 * @param mixed $req
	 * @param string $buffer
	 * @return int Number of bytes handled
	 */
	public function processHttpDataChunk( $req, $buffer ) {
		$this->bodyTextReceived .= $buffer;
		return strlen( $buffer );
	}

	public function testSuccess() {
		$handler = HandlerStack::create( new MockHandler( [ new Response( 200, [
			'status' => 200,
		], $this->exampleBodyText ) ] ) );
		$r = new GuzzleHttpRequest( $this->exampleUrl,
			[ 'handler' => $handler ] + $this->timeoutOptions );
		$r->execute();

		$this->assertEquals( 200, $r->getStatus() );
		$this->assertEquals( $this->exampleBodyText, $r->getContent() );
	}

	public function testSuccessConstructorCallback() {
		$this->bodyTextReceived = '';
		$handler = HandlerStack::create( new MockHandler( [ new Response( 200, [
			'status' => 200,
		], $this->exampleBodyText ) ] ) );
		$r = new GuzzleHttpRequest( $this->exampleUrl, [
			'callback' => [ $this, 'processHttpDataChunk' ],
			'handler' => $handler,
		] + $this->timeoutOptions );
		$r->execute();

		$this->assertEquals( 200, $r->getStatus() );
		$this->assertEquals( $this->exampleBodyText, $this->bodyTextReceived );
	}

	public function testSuccessSetCallback() {
		$this->bodyTextReceived = '';
		$handler = HandlerStack::create( new MockHandler( [ new Response( 200, [
			'status' => 200,
		], $this->exampleBodyText ) ] ) );
		$r = new GuzzleHttpRequest( $this->exampleUrl, [
			'handler' => $handler,
		] + $this->timeoutOptions );
		$r->setCallback( [ $this, 'processHttpDataChunk' ] );
		$r->execute();

		$this->assertEquals( 200, $r->getStatus() );
		$this->assertEquals( $this->exampleBodyText, $this->bodyTextReceived );
	}

	/**
	 * use a callback stream to pipe the mocked response data to our callback function
	 */
	public function testSuccessSink() {
		$this->bodyTextReceived = '';
		$handler = HandlerStack::create( new MockHandler( [ new Response( 200, [
			'status' => 200,
		], $this->exampleBodyText ) ] ) );
		$r = new GuzzleHttpRequest( $this->exampleUrl, [
			'handler' => $handler,
			'sink' => new MWCallbackStream( [ $this, 'processHttpDataChunk' ] ),
		] + $this->timeoutOptions );
		$r->execute();

		$this->assertEquals( 200, $r->getStatus() );
		$this->assertEquals( $this->exampleBodyText, $this->bodyTextReceived );
	}

	public function testBadUrl() {
		$r = new GuzzleHttpRequest( '', $this->timeoutOptions );
		$s = $r->execute();
		$errorMsg = $s->getErrorsByType( 'error' )[0]['message'];

		$this->assertSame( 0, $r->getStatus() );
		$this->assertEquals( 'http-invalid-url', $errorMsg );
	}

	public function testConnectException() {
		$handler = HandlerStack::create( new MockHandler( [ new GuzzleHttp\Exception\ConnectException(
			'Mock Connection Exception', new Request( 'GET', $this->exampleUrl )
		) ] ) );
		$r = new GuzzleHttpRequest( $this->exampleUrl,
			[ 'handler' => $handler ] + $this->timeoutOptions );
		$s = $r->execute();
		$errorMsg = $s->getErrorsByType( 'error' )[0]['message'];

		$this->assertSame( 0, $r->getStatus() );
		$this->assertEquals( 'http-request-error', $errorMsg );
	}

	public function testTimeout() {
		$handler = HandlerStack::create( new MockHandler( [ new GuzzleHttp\Exception\RequestException(
			'Connection timed out', new Request( 'GET', $this->exampleUrl )
		) ] ) );
		$r = new GuzzleHttpRequest( $this->exampleUrl,
			[ 'handler' => $handler ] + $this->timeoutOptions );
		$s = $r->execute();
		$errorMsg = $s->getErrorsByType( 'error' )[0]['message'];

		$this->assertSame( 0, $r->getStatus() );
		$this->assertEquals( 'http-timed-out', $errorMsg );
	}

	public function testNotFound() {
		$handler = HandlerStack::create( new MockHandler( [ new Response( 404, [
			'status' => '404',
		] ) ] ) );
		$r = new GuzzleHttpRequest( $this->exampleUrl,
			[ 'handler' => $handler ] + $this->timeoutOptions );
		$s = $r->execute();
		$errorMsg = $s->getErrorsByType( 'error' )[0]['message'];

		$this->assertEquals( 404, $r->getStatus() );
		$this->assertEquals( 'http-bad-status', $errorMsg );
	}

	/*
	 * Test of POST requests header
	 */
	public function testPostBody() {
		$container = [];
		$history = Middleware::history( $container );
		$stack = HandlerStack::create( new MockHandler( [ new Response() ] ) );
		$stack->push( $history );
		$client = new GuzzleHttpRequest( $this->exampleUrl, [
			'method' => 'POST',
			'handler' => $stack,
			'post' => 'key=value',
		] + $this->timeoutOptions );
		$client->execute();

		$request = $container[0]['request'];
		$this->assertEquals( 'POST', $request->getMethod() );
		$this->assertEquals( 'application/x-www-form-urlencoded',
			$request->getHeader( 'Content-Type' )[0] );
	}

	/**
	 * Test POSTed multipart request body with custom content type
	 */
	public function testPostBodyContentType() {
		$container = [];
		$history = Middleware::history( $container );
		$stack = HandlerStack::create( new MockHandler( [ new Response() ] ) );
		$stack->push( $history );
		$boundary = 'boundary';
		$client = new GuzzleHttpRequest( $this->exampleUrl, [
				'method' => 'POST',
				'handler' => $stack,
				'postData' => new \GuzzleHttp\Psr7\MultipartStream( [ [
					'name' => 'a',
					'contents' => 'b'
				] ] ),
			] + $this->timeoutOptions );
		$client->setHeader( 'Content-Type', 'text/mwtest' );
		$client->execute();

		$request = $container[0]['request'];
		$this->assertEquals( 'text/mwtest',
			$request->getHeader( 'Content-Type' )[0] );
	}

	/*
	 * Test that cookies from CookieJar were sent in the outgoing request.
	 */
	public function testCookieSent() {
		$domain = wfParseUrl( $this->exampleUrl )['host'];
		$expectedCookies = [ 'cookie1' => 'value1', 'anothercookie' => 'secondvalue' ];
		$jar = new CookieJar;
		foreach ( $expectedCookies as $key => $val ) {
			$jar->setCookie( $key, $val, [ 'domain' => $domain ] );
		}

		$container = [];
		$history = Middleware::history( $container );
		$stack = HandlerStack::create( new MockHandler( [ new Response() ] ) );
		$stack->push( $history );
		$client = new GuzzleHttpRequest( $this->exampleUrl, [
			'method' => 'POST',
			'handler' => $stack,
			'post' => 'key=value',
		] + $this->timeoutOptions );
		$client->setCookieJar( $jar );
		$client->execute();

		$request = $container[0]['request'];
		$this->assertEquals( [ 'cookie1=value1; anothercookie=secondvalue' ],
			$request->getHeader( 'Cookie' ) );
	}

	/*
	 * Test that cookies returned by HTTP response were added back into the CookieJar.
	 */
	public function testCookieReceived() {
		$handler = HandlerStack::create( new MockHandler( [ new Response( 200, [
			'status' => 200,
			'Set-Cookie' => [ 'cookie1=value1', 'anothercookie=secondvalue' ]
		] ) ] ) );
		$r = new GuzzleHttpRequest( $this->exampleUrl,
			[ 'handler' => $handler ] + $this->timeoutOptions );
		$r->execute();

		$domain = wfParseUrl( $this->exampleUrl )['host'];
		$this->assertEquals( 'cookie1=value1; anothercookie=secondvalue',
			$r->getCookieJar()->serializeToHttpRequest( '/', $domain ) );
	}
}
