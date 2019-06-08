<?php

/**
 * The urls herein are not actually called, because we mock the return results.
 *
 * @covers MultiHttpClient
 */
class MultiHttpClientTest extends MediaWikiTestCase {
	protected $client;

	protected function setUp() {
		parent::setUp();
		$client = $this->getMockBuilder( MultiHttpClient::class )
			->setConstructorArgs( [ [] ] )
			->setMethods( [ 'isCurlEnabled' ] )->getMock();
		$client->method( 'isCurlEnabled' )->willReturn( false );
		$this->client = $client;
	}

	private function getHttpRequest( $statusValue, $statusCode, $headers = [] ) {
		$httpRequest = $this->getMockBuilder( PhpHttpRequest::class )
			->setConstructorArgs( [ '', [] ] )
			->getMock();
		$httpRequest->expects( $this->any() )
			->method( 'execute' )
			->willReturn( Status::wrap( $statusValue ) );
		$httpRequest->expects( $this->any() )
			->method( 'getResponseHeaders' )
			->willReturn( $headers );
		$httpRequest->expects( $this->any() )
				->method( 'getStatus' )
				->willReturn( $statusCode );
		return $httpRequest;
	}

	private function mockHttpRequestFactory( $httpRequest ) {
		$factory = $this->getMockBuilder( MediaWiki\Http\HttpRequestFactory::class )
			->getMock();
		$factory->expects( $this->any() )
			->method( 'create' )
			->willReturn( $httpRequest );
		return $factory;
	}

	/**
	 * Test call of a single url that should succeed
	 */
	public function testMultiHttpClientSingleSuccess() {
		// Mock success
		$httpRequest = $this->getHttpRequest( StatusValue::newGood( 200 ), 200 );
		$this->setService( 'HttpRequestFactory', $this->mockHttpRequestFactory( $httpRequest ) );

		list( $rcode, $rdesc, /* $rhdrs */, $rbody, $rerr ) = $this->client->run( [
			'method' => 'GET',
			'url' => "http://example.test",
		] );

		$this->assertEquals( 200, $rcode );
	}

	/**
	 * Test call of a single url that should not exist, and therefore fail
	 */
	public function testMultiHttpClientSingleFailure() {
		// Mock an invalid tld
		$httpRequest = $this->getHttpRequest(
			StatusValue::newFatal( 'http-invalid-url', 'http://www.example.test' ), 0 );
		$this->setService( 'HttpRequestFactory', $this->mockHttpRequestFactory( $httpRequest ) );

		list( $rcode, $rdesc, /* $rhdrs */, $rbody, $rerr ) = $this->client->run( [
			'method' => 'GET',
			'url' => "http://www.example.test",
		] );

		$failure = $rcode < 200 || $rcode >= 400;
		$this->assertTrue( $failure );
	}

	/**
	 * Test call of multiple urls that should all succeed
	 */
	public function testMultiHttpClientMultipleSuccess() {
		// Mock success
		$httpRequest = $this->getHttpRequest( StatusValue::newGood( 200 ), 200 );
		$this->setService( 'HttpRequestFactory', $this->mockHttpRequestFactory( $httpRequest ) );

		$reqs = [
			[
				'method' => 'GET',
				'url' => 'http://example.test',
			],
			[
				'method' => 'GET',
				'url' => 'https://get.test',
			],
		];
		$responses = $this->client->runMulti( $reqs );
		foreach ( $responses as $response ) {
			list( $rcode, $rdesc, /* $rhdrs */, $rbody, $rerr ) = $response['response'];
			$this->assertEquals( 200, $rcode );
		}
	}

	/**
	 * Test call of multiple urls that should all fail
	 */
	public function testMultiHttpClientMultipleFailure() {
		// Mock page not found
		$httpRequest = $this->getHttpRequest(
			StatusValue::newFatal( "http-bad-status", 404, 'Not Found' ), 404 );
		$this->setService( 'HttpRequestFactory', $this->mockHttpRequestFactory( $httpRequest ) );

		$reqs = [
			[
				'method' => 'GET',
				'url' => 'http://example.test/12345',
			],
			[
				'method' => 'GET',
				'url' => 'http://example.test/67890' ,
			]
		];
		$responses = $this->client->runMulti( $reqs );
		foreach ( $responses as $response ) {
			list( $rcode, $rdesc, /* $rhdrs */, $rbody, $rerr ) = $response['response'];
			$failure = $rcode < 200 || $rcode >= 400;
			$this->assertTrue( $failure );
		}
	}

	/**
	 * Test of response header handling
	 */
	public function testMultiHttpClientHeaders() {
		// Represenative headers for typical requests, per MWHttpRequest::getResponseHeaders()
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

		// Mock success with specific headers
		$httpRequest = $this->getHttpRequest( StatusValue::newGood( 200 ), 200, $headers );
		$this->setService( 'HttpRequestFactory', $this->mockHttpRequestFactory( $httpRequest ) );

		list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $this->client->run( [
			'method' => 'GET',
			'url' => 'http://example.test',
		] );

		$this->assertEquals( 200, $rcode );
		$this->assertEquals( count( $headers ), count( $rhdrs ) );
		foreach ( $headers as $name => $values ) {
			$value = implode( ', ', $values );
			$this->assertArrayHasKey( $name, $rhdrs );
			$this->assertEquals( $value, $rhdrs[$name] );
		}
	}
}
