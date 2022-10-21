<?php

use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\TestingAccessWrapper;

/**
 * The urls herein are not actually called, because we mock the return results.
 *
 * @covers MultiHttpClient
 */
class MultiHttpClientTest extends MediaWikiIntegrationTestCase {
	/** @var MultiHttpClient|MockObject */
	protected $client;

	/**
	 * @param array $options
	 * @return MultiHttpClient|MockObject
	 */
	private function createClient( $options = [] ) {
		$client = $this->getMockBuilder( MultiHttpClient::class )
			->setConstructorArgs( [ $options ] )
			->onlyMethods( [ 'isCurlEnabled' ] )->getMock();
		$client->method( 'isCurlEnabled' )->willReturn( false );
		return $client;
	}

	protected function setUp(): void {
		parent::setUp();
		$this->client = $this->createClient( [] );
	}

	private function getHttpRequest( $statusValue, $statusCode, $headers = [] ) {
		$options = [
			'timeout' => 1,
			'connectTimeout' => 1
		];
		$httpRequest = $this->getMockBuilder( MWHttpRequest::class )
			->setConstructorArgs( [ '', $options ] )
			->getMock();
		$httpRequest->method( 'execute' )
			->willReturn( Status::wrap( $statusValue ) );
		$httpRequest->method( 'getResponseHeaders' )
			->willReturn( $headers );
		$httpRequest->method( 'getStatus' )
			->willReturn( $statusCode );
		return $httpRequest;
	}

	private function mockHttpRequestFactory( $httpRequest ) {
		$factory = $this->createMock( MediaWiki\Http\HttpRequestFactory::class );
		$factory->method( 'create' )
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

		[ $rcode, $rdesc, /* $rhdrs */, $rbody, $rerr ] = $this->client->run( [
			'method' => 'GET',
			'url' => "http://example.test",
		] );

		$this->assertSame( 200, $rcode );
	}

	/**
	 * Test call of a single url that should not exist, and therefore fail
	 */
	public function testMultiHttpClientSingleFailure() {
		// Mock an invalid tld
		$httpRequest = $this->getHttpRequest(
			StatusValue::newFatal( 'http-invalid-url', 'http://www.example.test' ), 0 );
		$this->setService( 'HttpRequestFactory', $this->mockHttpRequestFactory( $httpRequest ) );

		[ $rcode, $rdesc, /* $rhdrs */, $rbody, $rerr ] = $this->client->run( [
			'method' => 'GET',
			'url' => "http://www.example.test",
		] );

		$this->assertSame( 0, $rcode );
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
			[ $rcode, $rdesc, /* $rhdrs */, $rbody, $rerr ] = $response['response'];
			$this->assertSame( 200, $rcode );
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
			[ $rcode, $rdesc, /* $rhdrs */, $rbody, $rerr ] = $response['response'];
			$this->assertSame( 404, $rcode );
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

		[ $rcode, $rdesc, $rhdrs, $rbody, $rerr ] = $this->client->run( [
			'method' => 'GET',
			'url' => 'http://example.test',
		] );

		$this->assertSame( 200, $rcode );
		$this->assertSame( count( $headers ), count( $rhdrs ) );
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
		$factory = $this->createMock( MediaWiki\Http\HttpRequestFactory::class );
		$factory->method( 'create' )
			->with(
				$url,
				$this->callback(
					static function ( $options ) use ( $expectedReqTimeout, $expectedConnTimeout ) {
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

		$this->addToAssertionCount( 1 );
	}

	public function testUseReverseProxy() {
		// TODO: Cannot use TestingAccessWrapper here because it doesn't
		// support pass-by-reference (T287318)
		$class = new ReflectionClass( MultiHttpClient::class );
		$func = $class->getMethod( 'useReverseProxy' );
		$func->setAccessible( true );
		$req = [
			'url' => 'https://example.org/path?query=string',
		];
		$func->invokeArgs( new MultiHttpClient( [] ), [ &$req, 'http://localhost:1234' ] );
		$this->assertSame( 'http://localhost:1234/path?query=string', $req['url'] );
		$this->assertSame( 'example.org', $req['headers']['Host'] );
	}

	public function testNormalizeRequests() {
		// TODO: Cannot use TestingAccessWrapper here because it doesn't
		// support pass-by-reference (T287318)
		$class = new ReflectionClass( MultiHttpClient::class );
		$func = $class->getMethod( 'normalizeRequests' );
		$func->setAccessible( true );
		$reqs = [
			[ 'GET', 'https://example.org/path?query=string' ],
			[
				'method' => 'GET',
				'url' => 'https://example.com/path?query=another%20string'
			],
		];
		$client = new MultiHttpClient( [
			'localVirtualHosts' => [ 'example.org' ],
			'localProxy' => 'http://localhost:1234',
		] );
		$func->invokeArgs( $client, [ &$reqs ] );
		// Req #0 transformed to use reverse proxy
		$this->assertSame( 'http://localhost:1234/path?query=string', $reqs[0]['url'] );
		$this->assertSame( 'example.org', $reqs[0]['headers']['host'] );
		$this->assertFalse( $reqs[0]['proxy'] );
		// Req #1 left alone, domain doesn't match
		$this->assertSame( 'https://example.com/path?query=another%20string', $reqs[1]['url'] );
	}

	public function testGetCurlMulti() {
		$cm = TestingAccessWrapper::newFromObject( new MultiHttpClient( [] ) );
		$resource = $cm->getCurlMulti( [ 'usePipelining' => true ] );
		$this->assertThat(
			$resource,
			$this->logicalOr(
				$this->isType( IsType::TYPE_RESOURCE ),
				$this->isInstanceOf( 'CurlMultiHandle' )
			)
		);
	}
}
