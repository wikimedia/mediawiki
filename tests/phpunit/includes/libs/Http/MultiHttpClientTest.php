<?php

namespace Tests\Wikimedia\Http;

use Exception;
use Generator;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\Status\Status;
use MediaWikiIntegrationTestCase;
use MWHttpRequest;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionClass;
use StatusValue;
use Wikimedia\Http\MultiHttpClient;
use Wikimedia\Http\TelemetryHeadersInterface;
use Wikimedia\ScopedCallback;
use Wikimedia\TestingAccessWrapper;

/**
 * The urls herein are not actually called, because we mock the return results.
 *
 * @covers \Wikimedia\Http\MultiHttpClient
 */
class MultiHttpClientTest extends MediaWikiIntegrationTestCase {
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
		$factory = $this->createMock( HttpRequestFactory::class );
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

		[ $rcode, $rdesc, /* $rhdrs */, $rbody, $rerr ] = $this->createClient()->run( [
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

		[ $rcode, $rdesc, /* $rhdrs */, $rbody, $rerr ] = $this->createClient()->run( [
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
		$responses = $this->createClient()->runMulti( $reqs );
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
				'url' => 'http://example.test/67890',
			]
		];
		$responses = $this->createClient()->runMulti( $reqs );
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

		[ $rcode, $rdesc, $rhdrs, $rbody, $rerr ] = $this->createClient()->run( [
			'method' => 'GET',
			'url' => 'http://example.test',
		] );

		$this->assertSame( 200, $rcode );
		$this->assertSameSize( $headers, $rhdrs );
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
		$factory = $this->createMock( HttpRequestFactory::class );
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
		$reqs = [
			[ 'GET', 'https://example.org/path?query=string' ],
			[
				'method' => 'GET',
				'url' => 'https://example.com/path?query=another%20string',
				'headers' => [
					'header2' => 'value2'
				]
			],
		];
		$client = new MultiHttpClient( [
			'localVirtualHosts' => [ 'example.org' ],
			'localProxy' => 'http://localhost:1234',
			'headers' => [
				'header1' => 'value1'
			]
		] );
		$func->invokeArgs( $client, [ &$reqs ] );
		// Both requests have the default header added
		$this->assertSame( 'value1', $reqs[0]['headers']['header1'] );
		$this->assertSame( 'value1', $reqs[1]['headers']['header1'] );
		// Only Req #1 has an additional header
		$this->assertSame( 'value2', $reqs[1]['headers']['header2'] );
		$this->assertArrayNotHasKey( 'header2', $reqs[0]['headers'] );

		// Req #0 transformed to use reverse proxy
		$this->assertSame( 'http://localhost:1234/path?query=string', $reqs[0]['url'] );
		$this->assertSame( 'example.org', $reqs[0]['headers']['host'] );
		$this->assertFalse( $reqs[0]['proxy'] );
		// Req #1 left alone, domain doesn't match
		$this->assertSame( 'https://example.com/path?query=another%20string', $reqs[1]['url'] );
	}

	/**
	 * @dataProvider provideAssembleUrl
	 * @param array $bits
	 * @param string $expected
	 * @throws ReflectionException
	 */
	public function testAssembleUrl( array $bits, string $expected ) {
		$class = TestingAccessWrapper::newFromClass( MultiHttpClient::class );
		$this->assertSame( $expected, $class->assembleUrl( $bits ) );
	}

	public static function provideAssembleUrl(): Generator {
		$schemes = [
			'' => [],
			'http://' => [
				'scheme' => 'http',
			],
		];

		$hosts = [
			'' => [],
			'example.com' => [
				'host' => 'example.com',
			],
			'example.com:123' => [
				'host' => 'example.com',
				'port' => 123,
			],
			'id@example.com' => [
				'user' => 'id',
				'host' => 'example.com',
			],
			'id@example.com:123' => [
				'user' => 'id',
				'host' => 'example.com',
				'port' => 123,
			],
			'id:key@example.com' => [
				'user' => 'id',
				'pass' => 'key',
				'host' => 'example.com',
			],
			'id:key@example.com:123' => [
				'user' => 'id',
				'pass' => 'key',
				'host' => 'example.com',
				'port' => 123,
			],
		];

		foreach ( $schemes as $scheme => $schemeParts ) {
			foreach ( $hosts as $host => $hostParts ) {
				foreach ( [ '', '/', '/0', '/path' ] as $path ) {
					foreach ( [ '', '0', 'query' ] as $query ) {
						foreach ( [ '', '0', 'fragment' ] as $fragment ) {
							$parts = array_merge(
								$schemeParts,
								$hostParts
							);
							$url = $scheme .
								$host .
								$path;

							if ( $path !== '' ) {
								$parts['path'] = $path;
							}
							if ( $query !== '' ) {
								$parts['query'] = $query;
								$url .= '?' . $query;
							}
							if ( $fragment !== '' ) {
								$parts['fragment'] = $fragment;
								$url .= '#' . $fragment;
							}

							yield [ $parts, $url ];
						}
					}
				}
			}
		}

		yield [
			[
				'scheme' => 'http',
				'user' => 'id',
				'pass' => 'key',
				'host' => 'example.org',
				'port' => 321,
				'path' => '/over/there',
				'query' => 'name=ferret&foo=bar',
				'fragment' => 'nose',
			],
			'http://id:key@example.org:321/over/there?name=ferret&foo=bar#nose',
		];

		// Account for parse_url() on PHP >= 8 returning an empty query field for URLs ending with
		// '?' such as "http://url.with.empty.query/foo?" (T268852)
		yield [
			[
				'scheme' => 'http',
				'host' => 'url.with.empty.query',
				'path' => '/foo',
				'query' => '',
			],
			'http://url.with.empty.query/foo',
		];
	}

	public static function provideHeader() {
		// Invalid
		yield 'colon space' => [ false, [ 'Foo: X' => 'Y' ] ];
		yield 'colon' => [ false, [ 'Foo:bar' => 'X' ] ];
		yield 'two colon' => [ false, [ 'Foo:bar:baz' => 'X' ] ];
		yield 'trailing colon' => [ false, [ 'Foo:' => 'Y' ] ];
		yield 'leading colon' => [ false, [ ':Foo' => 'Y' ] ];
		// Valid
		yield 'word' => [ true, [ 'Foo' => 'X' ] ];
		yield 'dash' => [ true, [ 'Foo-baz' => 'X' ] ];
	}

	/**
	 * @dataProvider provideHeader
	 */
	public function testNormalizeIllegalHeader( bool $valid, array $headers ) {
		$class = new ReflectionClass( MultiHttpClient::class );
		$func = $class->getMethod( 'getCurlHandle' );
		$req = [
			'method' => 'GET',
			'url' => 'http://localhost:1234',
			'query' => [],
			'body' => '',
			'headers' => $headers
		];

		if ( $valid ) {
			$this->expectNotToPerformAssertions();
		} else {
			$this->expectException( Exception::class );
			$this->expectExceptionMessage( 'Header name must not contain colon-space' );
		}
		$func->invokeArgs( new MultiHttpClient( [] ), [ &$req, [
			'connTimeout' => 1,
			'reqTimeout' => 1,
		] ] );
		// TODO: Factor out curl_multi_exec so can stub that,
		// and then simply test the public runMulti() method here.
		// Or move more logic to normalizeRequests and test that.
	}

	public function testForwardsTelemetryHeaders() {
		$telemetry = $this->getMockBuilder( TelemetryHeadersInterface::class )
			->getMock();
		$telemetry->expects( $this->once() )
			->method( 'getRequestHeaders' )
			->willReturn( [ 'header1' => 'value1', 'header2' => 'value2' ] );

		// TODO: Cannot use TestingAccessWrapper here because it doesn't
		// support pass-by-reference (T287318)
		$class = new ReflectionClass( MultiHttpClient::class );
		$func = $class->getMethod( 'normalizeRequests' );
		$reqs = [
			[ 'GET', 'https://example.org/path?query=string' ],
		];
		$client = new MultiHttpClient( [
			'localVirtualHosts' => [ 'example.org' ],
			'localProxy' => 'http://localhost:1234',
			'telemetry' => $telemetry
		] );
		$func->invokeArgs( $client, [ &$reqs ] );
		$this->assertArrayHasKey( 'header1', $reqs[0]['headers'] );
		$this->assertSame( 'value1', $reqs[0]['headers']['header1'] );
		$this->assertArrayHasKey( 'header2', $reqs[0]['headers'] );
		$this->assertSame( 'value2', $reqs[0]['headers']['header2'] );
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

	/**
	 * @requires extension curl
	 */
	public function testShouldHandleConnectionLevelCurlErrors(): void {
		// Find a random local port on which nothing is listening.
		$randomPort = self::randomPort();

		$client = new MultiHttpClient( [] );
		$res = $client->run( [
			'method' => 'GET',
			'url' => "http://127.0.0.1:$randomPort",
		] );

		$this->assertStringStartsWith( '(curl error: 7)', $res['error'] );
	}

	/**
	 * @requires extension curl
	 */
	public function testShouldReturnResponses(): void {
		// Find a random local port on which nothing is listening.
		$randomPort = self::randomPort();

		// Start a mock server locally for testing.
		// phpcs:ignore MediaWiki.Usage.ForbiddenFunctions.proc_open
		$phpServerProc = proc_open(
			[ PHP_BINARY, '-S', "127.0.0.1:$randomPort" ],
			// Silence unwanted output.
			[ 1 => [ 'file', '/dev/null', 'w' ], 2 => [ 'file', '/dev/null', 'w' ] ],
			$pipes,
			__DIR__
		);
		$scope = new ScopedCallback( static function () use ( $phpServerProc ) {
			proc_terminate( $phpServerProc );
			proc_close( $phpServerProc );
		} );

		// Wait a short while for the mock server to start.
		$socket = socket_create( AF_INET, SOCK_STREAM, SOL_TCP );
		$tries = 0;
		while ( !@socket_connect( $socket, "127.0.0.1", $randomPort ) ) {
			if ( ++$tries > 10 ) {
				socket_close( $socket );
				$this->fail( 'Could not connect to PHP server' );
			}
			usleep( 100_000 );
		}
		socket_close( $socket );

		$client = new MultiHttpClient( [] );

		$reqs = [];

		for ( $i = 1; $i <= 4; $i++ ) {
			$reqs[] = [
				'method' => 'GET',
				'url' => "http://127.0.0.1:$randomPort/test-index.php?request=$i",
			];
		}

		$reqs = $client->runMulti( $reqs );

		$this->assertCount( 4, $reqs );
		$i = 1;
		foreach ( $reqs as $req ) {
			$this->assertSame( '', $req['response']['error'] );
			$this->assertSame( 200, $req['response']['code'] );
			$this->assertSame( "Response for request $i\n", $req['response']['body'] );
			$i++;
		}
	}

	/**
	 * Convenience function to obtain a random free port.
	 * @return int
	 */
	private static function randomPort(): int {
		$socket = socket_create_listen( 0 );
		socket_getsockname( $socket, $address, $randomPort );
		socket_close( $socket );

		return $randomPort;
	}
}
