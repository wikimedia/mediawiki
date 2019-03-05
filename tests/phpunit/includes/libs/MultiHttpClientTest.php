<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

/**
 * Tests for MultiHttpClient
 *
 * The urls herein are not actually called, because we mock the return results.
 *
 * @covers MultiHttpClient
 */
class MultiHttpClientTest extends MediaWikiTestCase {
	private $successReqs = [
		[
			'method' => 'GET',
			'url' => 'http://example.test',
		],
		[
			'method' => 'GET',
			'url' => 'https://get.test',
		],
		[
			'method' => 'POST',
			'url' => 'http://example.test',
			'body' => [ 'field' => 'value' ],
		],
	];

	private $failureReqs = [
		[
			'method' => 'GET',
			'url' => 'http://example.test',
		],
		[
			'method' => 'GET',
			'url' => 'http://example.test/12345',
		],
		[
			'method' => 'POST',
			'url' => 'http://example.test',
			'body' => [ 'field' => 'value' ],
		],
	];

	private function makeHandler( array $rCodes ) {
		$queue = [];
		foreach ( $rCodes as $rCode ) {
			$queue[] = new Response( $rCode );
		}
		return HandlerStack::create( new MockHandler( $queue ) );
	}

	/**
	 * Test call of a single url that should succeed
	 */
	public function testSingleSuccess() {
		$handler = $this->makeHandler( [ 200 ] );
		$client = new MultiHttpClient( [] );

		list( $rcode, $rdesc, /* $rhdrs */, $rbody, $rerr ) = $client->run(
			$this->successReqs[0],
			[ 'handler' => $handler ] );

		$this->assertEquals( 200, $rcode );
	}

	/**
	 * Test call of a single url that should not exist, and therefore fail
	 */
	public function testSingleFailure() {
		$handler = $this->makeHandler( [ 404 ] );
		$client = new MultiHttpClient( [] );

		list( $rcode, $rdesc, /* $rhdrs */, $rbody, $rerr ) = $client->run(
			$this->failureReqs[0],
			[ 'handler' => $handler ] );

		$failure = $rcode < 200 || $rcode >= 400;
		$this->assertTrue( $failure );
	}

	/**
	 * Test call of multiple urls that should all succeed
	 */
	public function testMultipleSuccess() {
		$handler = $this->makeHandler( [ 200, 200, 200 ] );
		$client = new MultiHttpClient( [] );
		$responses = $client->runMulti( $this->successReqs, [ 'handler' => $handler ] );

		foreach ( $responses as $response ) {
			list( $rcode, $rdesc, /* $rhdrs */, $rbody, $rerr ) = $response['response'];
			$this->assertEquals( 200, $rcode );
		}
	}

	/**
	 * Test call of multiple urls that should all fail
	 */
	public function testMultipleFailure() {
		$handler = $this->makeHandler( [ 404, 404, 404 ] );
		$client = new MultiHttpClient( [] );
		$responses = $client->runMulti( $this->failureReqs, [ 'handler' => $handler ] );

		foreach ( $responses as $response ) {
			list( $rcode, $rdesc, /* $rhdrs */, $rbody, $rerr ) = $response['response'];
			$failure = $rcode < 200 || $rcode >= 400;
			$this->assertTrue( $failure );
		}
	}

	/**
	 * Test call of multiple urls, some of which should succeed and some of which should fail
	 */
	public function testMixedSuccessAndFailure() {
		$responseCodes = [ 200, 200, 200, 404, 404, 404 ];
		$handler = $this->makeHandler( $responseCodes );
		$client = new MultiHttpClient( [] );

		$responses = $client->runMulti(
			array_merge( $this->successReqs, $this->failureReqs ),
			[ 'handler' => $handler ] );

		foreach ( $responses as $index => $response ) {
			list( $rcode, $rdesc, /* $rhdrs */, $rbody, $rerr ) = $response['response'];
			$this->assertEquals( $responseCodes[$index], $rcode );
		}
	}

	/**
	 * Test of response header handling
	 */
	public function testHeaders() {
		// Representative headers for typical requests, per MWHttpRequest::getResponseHeaders()
		$headers = [
			'content-type' => [
				'text/html; charset=utf-8',
			],
			'date' => [
				'Wed, 18 Jul 2018 14:52:41 GMT',
			],
			'set-cookie' => [
				'COUNTRY=NAe6; expires=Wed, 25-Jul-2018 14:52:41 GMT; path=/; domain=.example.test',
				'LAST_NEWS=1531925562; expires=Thu, 18-Jul-2019 14:52:41 GMT; path=/; domain=.example.test',
			]
		];

		$handler = HandlerStack::create( new MockHandler( [
			new Response( 200, $headers ),
		] ) );

		$client = new MultiHttpClient( [] );

		list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $client->run( [
			'method' => 'GET',
			'url' => "http://example.test",
			],
			[ 'handler' => $handler ] );
		$this->assertEquals( 200, $rcode );

		$this->assertEquals( count( $headers ), count( $rhdrs ) );
		foreach ( $headers as $name => $values ) {
			$value = implode( ', ', $values );
			$this->assertArrayHasKey( $name, $rhdrs );
			$this->assertEquals( $value, $rhdrs[$name] );
		}
	}
}
