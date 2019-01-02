<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;

/**
 * class for tests of GuzzleHttpRequest
 *
 * No actual requests are made herein - all external communications are mocked
 *
 * @covers GuzzleHttpRequest
 * @covers MWHttpRequest
 */
class GuzzleHttpRequestTest extends MediaWikiTestCase {
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
		$r = new GuzzleHttpRequest( $this->exampleUrl, [ 'handler' => $handler ] );
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
		] );
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
		] );
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
		] );
		$r->execute();

		$this->assertEquals( 200, $r->getStatus() );
		$this->assertEquals( $this->exampleBodyText, $this->bodyTextReceived );
	}

	public function testBadUrl() {
		$r = new GuzzleHttpRequest( '' );
		$s = $r->execute();
		$errorMsg = $s->getErrorsByType( 'error' )[0]['message'];

		$this->assertEquals( 0, $r->getStatus() );
		$this->assertEquals( 'http-invalid-url', $errorMsg );
	}

	public function testConnectException() {
		$handler = HandlerStack::create( new MockHandler( [ new GuzzleHttp\Exception\ConnectException(
			'Mock Connection Exception', new Request( 'GET', $this->exampleUrl )
		) ] ) );
		$r = new GuzzleHttpRequest( $this->exampleUrl, [ 'handler' => $handler ] );
		$s = $r->execute();
		$errorMsg = $s->getErrorsByType( 'error' )[0]['message'];

		$this->assertEquals( 0, $r->getStatus() );
		$this->assertEquals( 'http-request-error', $errorMsg );
	}

	public function testTimeout() {
		$handler = HandlerStack::create( new MockHandler( [ new GuzzleHttp\Exception\RequestException(
			'Connection timed out', new Request( 'GET', $this->exampleUrl )
		) ] ) );
		$r = new GuzzleHttpRequest( $this->exampleUrl, [ 'handler' => $handler ] );
		$s = $r->execute();
		$errorMsg = $s->getErrorsByType( 'error' )[0]['message'];

		$this->assertEquals( 0, $r->getStatus() );
		$this->assertEquals( 'http-timed-out', $errorMsg );
	}

	public function testNotFound() {
		$handler = HandlerStack::create( new MockHandler( [ new Response( 404, [
			'status' => '404',
		] ) ] ) );
		$r = new GuzzleHttpRequest( $this->exampleUrl, [ 'handler' => $handler ] );
		$s = $r->execute();
		$errorMsg = $s->getErrorsByType( 'error' )[0]['message'];

		$this->assertEquals( 404, $r->getStatus() );
		$this->assertEquals( 'http-bad-status', $errorMsg );
	}
}
