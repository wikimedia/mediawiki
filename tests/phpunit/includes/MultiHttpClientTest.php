<?php

use PHPUnit\Framework\MockObject\MockObject;

/**
 * The urls herein are not actually called, because we mock the return results.
 *
 * @covers MultiHttpClient
 */
class MultiHttpClientTest extends MediaWikiIntegrationTestCase {
	/** @var MultiHttpClient|MockObject */
	protected $client;

	/** @return MultiHttpClient|MockObject */
	private function createClient( $options = [] ) {
		$client = $this->getMockBuilder( MultiHttpClient::class )
			->setConstructorArgs( [ $options ] )
			->setMethods( [ 'isCurlEnabled' ] )->getMock();
		$client->method( 'isCurlEnabled' )->willReturn( false );
		return $client;
	}

	protected function setUp() : void {
		parent::setUp();
		$this->client = $this->createClient( [] );
	}

	private function getHttpRequest( $statusValue, $statusCode, $headers = [] ) {
		$options = [
			'timeout' => 1,
			'connectTimeout' => 1
		];
		$httpRequest = $this->getMockBuilder( PhpHttpRequest::class )
			->setConstructorArgs( [ '', $options ] )
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
			->disableOriginalConstructor()
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

	public static function provideMultiHttpTimeout() {
		return [
			'default 10/30' => [
				[],
				[],
				10,
				30
			],
			'constructor override' => [
				[ 'connTimeout' => 2, 'reqTimeout' => 3 ],
				[],
				2,
				3
			],
			'run override' => [
				[],
				[ 'connTimeout' => 2, 'reqTimeout' => 3 ],
				2,
				3
			],
			'constructor max option limits default' => [
				[ 'maxConnTimeout' => 2, 'maxReqTimeout' => 3 ],
				[],
				2,
				3
			],
			'constructor max option limits regular constructor option' => [
				[
					'maxConnTimeout' => 2,
					'maxReqTimeout' => 3,
					'connTimeout' => 100,
					'reqTimeout' => 100
				],
				[],
				2,
				3
			],
			'constructor max option greater than regular constructor option' => [
				[
					'maxConnTimeout' => 2,
					'maxReqTimeout' => 3,
					'connTimeout' => 1,
					'reqTimeout' => 1
				],
				[],
				1,
				1
			],
			'constructor max option limits run option' => [
				[
					'maxConnTimeout' => 2,
					'maxReqTimeout' => 3,
				],
				[
					'connTimeout' => 100,
					'reqTimeout' => 100
				],
				2,
				3
			],
		];
	}

	/**
	 * Test of timeout parameter handling
	 * @dataProvider provideMultiHttpTimeout
	 */
	public function testMultiHttpTimeout( $createOptions, $runOptions,
		$expectedConnTimeout, $expectedReqTimeout
	) {
		$url = 'http://www.example.test';
		$httpRequest = $this->getHttpRequest( StatusValue::newGood( 200 ), 200 );
		$factory = $this->getMockBuilder( MediaWiki\Http\HttpRequestFactory::class )
			->disableOriginalConstructor()
			->getMock();
		$factory->expects( $this->any() )
			->method( 'create' )
			->with(
				$url,
				$this->callback(
					function ( $options ) use ( $expectedReqTimeout, $expectedConnTimeout ) {
						return $options['timeout'] === $expectedReqTimeout
							&& $options['connectTimeout'] === $expectedConnTimeout;
					}
				)
			)
			->willReturn( $httpRequest );
		$this->setService( 'HttpRequestFactory', $factory );

		$client = $this->createClient( $createOptions );

		$client->run(
			[ 'method' => 'GET', 'url' => $url ],
			$runOptions
		);

		$this->assertTrue( true );
	}
}
