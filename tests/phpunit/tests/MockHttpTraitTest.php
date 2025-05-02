<?php

use PHPUnit\Framework\AssertionFailedError;
use Psr\Http\Message\ResponseInterface;

/**
 * @covers \MockHttpTrait
 * @group MediaWikiIntegrationTestCaseTest
 */
class MockHttpTraitTest extends MediaWikiIntegrationTestCase {
	use MockHttpTrait;

	public function testInstallMockHttpPreventsRequests() {
		$this->installMockHttp();

		try {
			$this->getServiceContainer()->getHttpRequestFactory()
				->create( 'http://example.com' );
			$pass = false;
		} catch ( AssertionFailedError $ex ) {
			$pass = true;
		}

		$this->assertTrue( $pass, 'HTTP request prevented' );
	}

	public function testMakeMockHttpRequestFactoryPreventsRequests() {
		try {
			$this->makeMockHttpRequestFactory()->create( 'http://example.com' );
			$pass = false;
		} catch ( AssertionFailedError $ex ) {
			$pass = true;
		}

		$this->assertTrue( $pass, 'HTTP request prevented' );
	}

	public static function provideFactoryRequestData() {
		yield 'a request object' => [ [ 'request-string' => 'Hello World' ], 'Hello World' ];

		yield 'just a string' => [ 'Hello World', 'Hello World' ];

		yield 'a closure returning a request' => [
			[
				'request-closure' => 'Hello World',
			],
			'Hello World'
		];

		yield 'a list of requests' => [
			[
				'request-array' => [
					'Hello World',
					'Yadda Yadda',
				]
			],
			'Hello World'
		];
	}

	private function prepareFactoryRequest( $requestSpec ) {
		if ( isset( $requestSpec['request-string'] ) ) {
			return $this->makeFakeHttpRequest( $requestSpec['request-string'] );
		}
		if ( isset( $requestSpec['request-array'] ) ) {
			$request = [];
			foreach ( $requestSpec['request-array'] as $v ) {
				$request[] = $this->makeFakeHttpRequest( $v );
			}
			return $request;
		}
		if ( isset( $requestSpec['request-closure'] ) ) {
			$value = $requestSpec['request-closure'];
			return function () use ( $value ) {
				return $this->makeFakeHttpRequest( $value );
			};
		}
		return $requestSpec;
	}

	/**
	 * @dataProvider provideFactoryRequestData
	 */
	public function testInstallMockHttpEmulatesRequests( $requestSpec, $expected ) {
		$request = $this->prepareFactoryRequest( $requestSpec );
		$this->installMockHttp( $request );

		$data = $this->getServiceContainer()->getHttpRequestFactory()
			->get( 'http://example.com' );

		$this->assertSame( $expected, $data );
	}

	/**
	 * @dataProvider provideFactoryRequestData
	 */
	public function testMakeMockHttpRequestFactoryEmulatesRequests( $requestSpec, $expected ) {
		$request = $this->prepareFactoryRequest( $requestSpec );
		$data = $this->makeMockHttpRequestFactory( $request )
			->get( 'http://example.com' );

		$this->assertSame( $expected, $data );
	}

	public function testFakeHttpRequestEmulatesRequests() {
		$client = $this->makeFakeHttpRequest( 'Hello World' );

		$called = 0;
		$callback = function ( $resource, $buffer ) use ( &$called ) {
			$this->assertSame( 'Hello World', $buffer );
			$called++;
			return strlen( $buffer );
		};

		$client->setCallback( $callback );
		$client->execute();

		$this->assertSame( 1, $called, 'Callback was called' );
		$this->assertSame( 'Hello World', $client->getContent() );
	}

	public static function provideMultiRequestData() {
		yield [
			[
				'a' => [ 'url' => 'http://a.example.com', ],
				[ 'url' => 'http://b.example.com', ],
				[ 'url' => 'http://c.example.com', ],
			],
			[
				'a' => 'Hello World',
				[
					'code' => 404,
					'body' => 'not found'
				],
				[
					'response' => [
						'code' => 0,
						'error' => 'timeout'
					]
				]
			],
			[
				'a' => [
					'url' => 'http://a.example.com',
					'response' => [
						// specifics
						'body' => 'Hello World',

						// defaults
						'code' => 200,
						'reason' => '',
						'headers' => [],
						'error' => '',

						// numeric keys
						0 => 200, // code
						1 => '', // reason
						2 => [], // headers
						3 => 'Hello World', // body
						4 => '', // error
					],
				],
				[
					'url' => 'http://b.example.com',
					'response' => [
						// specifics
						'code' => 404,
						'body' => 'not found',

						// defaults
						'reason' => '',
						'headers' => [],
						'error' => '',

						// numeric keys
						0 => 404, // code
						1 => '', // reason
						2 => [], // headers
						3 => 'not found', // body
						4 => '', // error
					],
				],
				[
					'url' => 'http://c.example.com',
					'response' => [
						// specifics
						'code' => 0,
						'error' => 'timeout',

						// defaults
						'reason' => '',
						'headers' => [],
						'body' => '',

						// numeric keys
						0 => 0, // code
						1 => '', // reason
						2 => [], // headers
						3 => '', // body
						4 => 'timeout', // error
					],
				],
			]
		];
	}

	/**
	 * @dataProvider provideMultiRequestData
	 */
	public function testFakeHttpMultiClientEmulatesRequests( $requests, $responses, $expected ) {
		$client = $this->makeFakeHttpMultiClient( $responses );

		$data = $client->runMulti( $requests );
		$this->assertSame( $expected, $data );
	}

	/**
	 * @dataProvider provideMultiRequestData
	 */
	public function testInstallMockHttpEmulatesMultiClient( $requests, $responses, $expected ) {
		$client = $this->makeFakeHttpMultiClient( $responses );
		$this->installMockHttp( $client );

		$client = $this->getServiceContainer()->getHttpRequestFactory()
			->createMultiClient();

		$data = $client->runMulti( $requests );
		$this->assertSame( $expected, $data );
	}

	public function testConsecutiveRequests() {
		$responses = [
			$this->makeFakeHttpRequest( 'a' ),
			$this->makeFakeHttpRequest( 'b' ),
		];
		$this->installMockHttp( $responses );
		$clientFactory = $this->getServiceContainer()->getHttpRequestFactory();
		$this->assertSame( 'a', $clientFactory->get( 'http://example.com' ) );
		$this->assertSame( 'b', $clientFactory->get( 'http://example.com' ) );
		try {
			$clientFactory->get( 'http://example.com' );
			$pass = false;
		} catch ( Error $ex ) {
			$pass = true;
			$this->assertSame( 'Call to a member function execute() on null', $ex->getMessage() );
		}

		$this->assertTrue( $pass, 'Can not consume more requests as defined.' );
	}

	/**
	 * @dataProvider provideMultiRequestData
	 */
	public function testMakeMockHttpRequestFactoryEmulatesMultiClient(
		$requests, $responses, $expected
	) {
		$client = $this->makeFakeHttpMultiClient( $responses );
		$client = $this->makeMockHttpRequestFactory( $client )
			->createMultiClient();

		$data = $client->runMulti( $requests );
		$this->assertSame( $expected, $data );
	}

	/**
	 * @dataProvider provideMultiRequestData
	 */
	public function testMakeMockHttpRequestFactoryEmulatesMultipleMultiClients(
		$requests, $responses, $expected
	) {
		$clients = [
			$this->makeFakeHttpMultiClient( $responses ),
			$this->makeFakeHttpMultiClient( $responses ),
		];
		$factory = $this->makeMockHttpRequestFactory( $clients );
		foreach ( $clients as $unused ) {
			$client = $factory->createMultiClient();
			$data = $client->runMulti( $requests );
			$this->assertSame( $expected, $data );
		}
	}

	public static function provideGuzzleClientData() {
		yield [
			'Hello Wörld',
			new GuzzleHttp\Psr7\Response( 200, [], 'Hello Wörld' ),
		];
		yield [
			new GuzzleHttp\Psr7\Response( 404, [ 'Test' => 'hi' ], 'nope' ),
			new GuzzleHttp\Psr7\Response( 404, [ 'Test' => 'hi' ], 'nope' ),
		];
	}

	/**
	 * @dataProvider provideGuzzleClientData
	 */
	public function testFakeGuzzleClientEmulatesRequests( $response, $expected ) {
		$client = $this->makeFakeGuzzleClient( $response );

		$this->assertGuzzleResponse( $expected, $client->request( 'TEST', 'http://b.example.com' ) );
		$this->assertGuzzleResponse( $expected, $client->get( 'http://b.example.com' ) );
		$this->assertGuzzleResponse( $expected, $client->put( 'http://b.example.com' ) );
		$this->assertGuzzleResponse( $expected, $client->post( 'http://b.example.com' ) );
	}

	/**
	 * @dataProvider provideGuzzleClientData
	 */
	public function testInstallMockHttpEmulatesGuzzleClient( $response, $expected ) {
		$client = $this->makeFakeGuzzleClient( $response );
		$this->installMockHttp( $client );

		$client = $this->getServiceContainer()->getHttpRequestFactory()
			->createGuzzleClient();

		$this->assertGuzzleResponse( $expected, $client->request( 'TEST', 'http://b.example.com' ) );
		$this->assertGuzzleResponse( $expected, $client->get( 'http://b.example.com' ) );
		$this->assertGuzzleResponse( $expected, $client->put( 'http://b.example.com' ) );
		$this->assertGuzzleResponse( $expected, $client->post( 'http://b.example.com' ) );
	}

	/**
	 * @dataProvider provideGuzzleClientData
	 */
	public function testMakeMockHttpRequestFactoryEmulatesGuzzleClient( $response, $expected ) {
		$client = $this->makeFakeGuzzleClient( $response );
		$client = $this->makeMockHttpRequestFactory( $client )
			->createGuzzleClient();

		$this->assertGuzzleResponse( $expected, $client->request( 'TEST', 'http://b.example.com' ) );
		$this->assertGuzzleResponse( $expected, $client->get( 'http://b.example.com' ) );
		$this->assertGuzzleResponse( $expected, $client->put( 'http://b.example.com' ) );
		$this->assertGuzzleResponse( $expected, $client->post( 'http://b.example.com' ) );
	}

	/**
	 * @dataProvider provideGuzzleClientData
	 */
	public function testMakeMockHttpRequestFactoryEmulatesMultipleGuzzleRequests( $response, $expected ) {
		$fakeClients = [
			$this->makeFakeGuzzleClient( $response ),
			$this->makeFakeGuzzleClient( $response ),
		];
		$factory = $this->makeMockHttpRequestFactory( $fakeClients );
		foreach ( $fakeClients as $unused ) {
			$client = $factory->createGuzzleClient();
			$this->assertGuzzleResponse( $expected, $client->request( 'TEST', 'http://b.example.com' ) );
			$this->assertGuzzleResponse( $expected, $client->get( 'http://b.example.com' ) );
			$this->assertGuzzleResponse( $expected, $client->put( 'http://b.example.com' ) );
			$this->assertGuzzleResponse( $expected, $client->post( 'http://b.example.com' ) );
		}
	}

	/**
	 * @param ResponseInterface $expected
	 * @param ResponseInterface $actual
	 */
	private function assertGuzzleResponse( $expected, ResponseInterface $actual ) {
		$this->assertSame( $expected->getStatusCode(), $actual->getStatusCode(), 'Status' );
		$this->assertSame( $expected->getHeaders(), $actual->getHeaders(), 'Headers' );
		$this->assertSame( strval( $expected->getBody() ), strval( $actual->getBody() ), 'Body' );
	}

}
