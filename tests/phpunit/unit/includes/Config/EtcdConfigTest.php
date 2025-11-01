<?php

use MediaWiki\Config\ConfigException;
use MediaWiki\Config\EtcdConfig;
use Wikimedia\Http\MultiHttpClient;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Config\EtcdConfig
 */
class EtcdConfigTest extends MediaWikiUnitTestCase {

	private function createConfigMock( array $options = [], ?array $methods = null ) {
		return $this->getMockBuilder( EtcdConfig::class )
			->setConstructorArgs( [ $options + [
				'host' => 'etcd-tcp.example.net',
				'directory' => '/',
				'timeout' => 0.1,
			] ] )
			->onlyMethods( $methods ?? [ 'fetchAllFromEtcd' ] )
			->getMock();
	}

	private static function createEtcdResponse( array $response ) {
		$baseResponse = [
			'config' => null,
			'error' => null,
			'retry' => false,
			'modifiedIndex' => 0,
		];
		return array_merge( $baseResponse, $response );
	}

	private function createSimpleConfigMock( array $config, $index = 0 ) {
		$mock = $this->createConfigMock();
		$mock->expects( $this->once() )->method( 'fetchAllFromEtcd' )
			->willReturn( self::createEtcdResponse( [
				'config' => $config,
				'modifiedIndex' => $index,
			] ) );
		return $mock;
	}

	private function createCallableMock() {
		return $this
			->getMockBuilder( \stdClass::class )
			->addMethods( [ '__invoke' ] )
			->getMock();
	}

	public function testHasKnown() {
		$config = $this->createSimpleConfigMock( [
			'known' => 'value'
		] );
		$this->assertSame( true, $config->has( 'known' ) );
	}

	public function testGetKnown() {
		$config = $this->createSimpleConfigMock( [
			'known' => 'value'
		] );
		$this->assertSame( 'value', $config->get( 'known' ) );
	}

	public function testHasUnknown() {
		$config = $this->createSimpleConfigMock( [
			'known' => 'value'
		] );
		$this->assertSame( false, $config->has( 'unknown' ) );
	}

	public function testGetUnknown() {
		$config = $this->createSimpleConfigMock( [
			'known' => 'value'
		] );
		$this->expectException( ConfigException::class );
		$config->get( 'unknown' );
	}

	public function testGetModifiedIndex() {
		$config = $this->createSimpleConfigMock(
			[ 'some' => 'value' ],
			123
		);
		$this->assertSame( 123, $config->getModifiedIndex() );
	}

	public function testConstructCacheObj() {
		$cache = $this->getMockBuilder( HashBagOStuff::class )
			->onlyMethods( [ 'get' ] )
			->getMock();
		$cache->expects( $this->once() )->method( 'get' )
			->willReturn( [
				'config' => [ 'known' => 'from-cache' ],
				'expires' => INF,
				'modifiedIndex' => 123
			] );
		$config = $this->createConfigMock( [ 'cache' => $cache ] );

		$this->assertSame( 'from-cache', $config->get( 'known' ) );
	}

	public function testConstructCacheSpec() {
		$config = $this->createConfigMock( [ 'cache' => [
			'class' => HashBagOStuff::class
		] ] );
		$config->expects( $this->once() )->method( 'fetchAllFromEtcd' )
			->willReturn( self::createEtcdResponse(
				[ 'config' => [ 'known' => 'from-fetch' ], ] ) );

		$this->assertSame( 'from-fetch', $config->get( 'known' ) );
	}

	public static function provideScenario() {
		$miss = false;
		$hit = [
			'config' => [ 'mykey' => 'from-cache' ],
			'expires' => INF,
			'modifiedIndex' => 0,
		];
		$stale = [
			'config' => [ 'mykey' => 'from-cache-expired' ],
			'expires' => -INF,
			'modifiedIndex' => 0,
		];

		yield 'Cache miss' => [ 'from-fetch', [
			'cache' => $miss,
			'lock' => 'acquired',
			'backend' => 'success',
		] ];

		yield 'Cache miss with backend error' => [ 'error', [
			'cache' => $miss,
			'lock' => 'acquired',
			'backend' => 'error',
		] ];

		yield 'Cache miss with retry after backend error' => [ 'from-cache', [
			'cache' => [ $miss, $hit ],
			'lock' => 'acquired',
			'backend' => 'error-may-retry',
		] ];

		yield 'Cache hit after lock fail and cache retry' => [ 'from-cache', [
			// misses cache first time
			// the other process holding the lock populates the value
			// hits cache on retry
			'cache' => [ $miss, $hit ],
			'lock' => 'fail',
			'backend' => 'never',
		] ];

		yield 'Cache hit' => [ 'from-cache', [
			'cache' => $hit,
			'lock' => 'never',
			'backend' => 'never',
		] ];

		yield 'Cache expired' => [ 'from-fetch', [
			'cache' => $stale,
			'lock' => 'acquired',
			'backend' => 'success',
		] ];

		yield 'Cache expired with retry after backend failure' => [ 'from-cache-expired', [
			'cache' => $stale,
			'lock' => 'acquired',
			'backend' => 'error-may-retry',
		] ];

		yield 'Cache expired without retry after backend failure' => [ 'from-cache-expired', [
			'cache' => $stale,
			'lock' => 'acquired',
			'backend' => 'error',
		] ];

		yield 'Cache expired with lock failure' => [ 'from-cache-expired', [
			'cache' => $stale,
			'lock' => 'fail',
			'backend' => 'never',
		] ];
	}

	/**
	 * @dataProvider provideScenario
	 */
	public function testScenario( string $expect, array $scenario ) {
		// Create cache mock
		$cache = $this->getMockBuilder( HashBagOStuff::class )
			->onlyMethods( [ 'get', 'lock' ] )
			->getMock();
		if ( is_array( $scenario['cache'] ) && array_is_list( $scenario['cache'] ) ) {
			$cache->expects( $this->exactly( count( $scenario['cache'] ) ) )->method( 'get' )
				->willReturnOnConsecutiveCalls(
					...$scenario['cache']
				);
		} else {
			$cache->expects( $this->any() )->method( 'get' )
				->willReturn( $scenario['cache'] );
		}
		if ( $scenario['lock'] === 'acquired' ) {
			$cache->expects( $this->once() )->method( 'lock' )
				->willReturn( true );
		} elseif ( $scenario['lock'] === 'fail' ) {
			$cache->expects( $this->once() )->method( 'lock' )
				->willReturn( false );
		} else {
			// lock=never
			$cache->expects( $this->never() )->method( 'lock' );
		}

		// Create config mock
		$mock = $this->createConfigMock( [
			'cache' => $cache,
		] );
		if ( $scenario['backend'] === 'success' ) {
			$mock->expects( $this->once() )->method( 'fetchAllFromEtcd' )
				->willReturn(
					self::createEtcdResponse( [ 'config' => [ 'mykey' => 'from-fetch' ] ] )
				);
		} elseif ( $scenario['backend'] === 'error' ) {
			$mock->expects( $this->once() )->method( 'fetchAllFromEtcd' )
				->willReturn( self::createEtcdResponse( [ 'error' => 'Fake error' ] ) );
		} elseif ( $scenario['backend'] === 'error-may-retry' ) {
			$mock->expects( $this->once() )->method( 'fetchAllFromEtcd' )
				->willReturn( self::createEtcdResponse( [ 'error' => 'Fake error', 'retry' => true ] ) );
		} elseif ( $scenario['backend'] === 'never' ) {
			$mock->expects( $this->never() )->method( 'fetchAllFromEtcd' );
		}

		if ( $expect === 'error' ) {
			$this->expectException( ConfigException::class );
			@$mock->get( 'mykey' );
		} else {
			$this->assertSame( $expect, @$mock->get( 'mykey' ) );
		}
	}

	public function testLoadProcessCacheHit() {
		// Create cache mock
		$cache = $this->getMockBuilder( HashBagOStuff::class )
			->onlyMethods( [ 'get', 'lock' ] )
			->getMock();
		$cache->expects( $this->once() )->method( 'get' )
			// .. hits cache
			->willReturn( [
				'config' => [ 'known' => 'from-cache' ],
				'expires' => INF,
				'modifiedIndex' => 0,
			] );
		$cache->expects( $this->never() )->method( 'lock' );

		// Create config mock
		$mock = $this->createConfigMock( [
			'cache' => $cache,
		] );
		$mock->expects( $this->never() )->method( 'fetchAllFromEtcd' );

		$this->assertSame( 'from-cache', $mock->get( 'known' ), 'Cache hit' );
		$this->assertSame( 'from-cache', $mock->get( 'known' ), 'Process cache hit' );
	}

	public function testLoadCacheExpiredLockWarning() {
		// Create cache mock
		$cache = $this->getMockBuilder( HashBagOStuff::class )
			->onlyMethods( [ 'get', 'lock' ] )
			->getMock();
		$cache->expects( $this->once() )->method( 'get' )
			// .. hits cache (expired value)
			->willReturn( [
				'config' => [ 'known' => 'from-cache-expired' ],
				'expires' => -INF,
				'modifiedIndex' => 0,
			] );
		// .. misses lock
		$cache->expects( $this->once() )->method( 'lock' )
			->willReturn( false );

		// Create config mock
		$mock = $this->createConfigMock( [
			'cache' => $cache,
		] );
		$mock->expects( $this->never() )->method( 'fetchAllFromEtcd' );

		$this->expectPHPError(
			E_USER_NOTICE,
			static function () use ( $mock ) {
				$mock->get( 'known' );
			},
			'using stale data: lost lock'
		);
	}

	public static function provideFetchFromServer() {
		return [
			'200 OK - Success' => [
				'http' => [
					'code' => 200,
					'reason' => 'OK',
					'headers' => [],
					'body' => json_encode( [ 'node' => [ 'nodes' => [
						[
							'key' => '/example/foo',
							'value' => json_encode( [ 'val' => true ] ),
							'modifiedIndex' => 123
						],
					] ] ] ),
					'error' => '',
				],
				'expect' => self::createEtcdResponse( [
					'config' => [ 'foo' => true ], // data
					'modifiedIndex' => 123
				] ),
			],
			'200 OK - Empty dir' => [
				'http' => [
					'code' => 200,
					'reason' => 'OK',
					'headers' => [],
					'body' => json_encode( [ 'node' => [ 'nodes' => [
						[
							'key' => '/example/foo',
							'value' => json_encode( [ 'val' => true ] ),
							'modifiedIndex' => 123
						],
						[
							'key' => '/example/sub',
							'dir' => true,
							'modifiedIndex' => 234,
							'nodes' => [],
						],
						[
							'key' => '/example/bar',
							'value' => json_encode( [ 'val' => false ] ),
							'modifiedIndex' => 125
						],
					] ] ] ),
					'error' => '',
				],
				'expect' => self::createEtcdResponse( [
					'config' => [ 'foo' => true, 'bar' => false ], // data
					'modifiedIndex' => 125 // largest modified index
				] ),
			],
			'200 OK - Recursive' => [
				'http' => [
					'code' => 200,
					'reason' => 'OK',
					'headers' => [],
					'body' => json_encode( [ 'node' => [ 'nodes' => [
						[
							'key' => '/example/a',
							'dir' => true,
							'modifiedIndex' => 124,
							'nodes' => [
								[
									'key' => 'b',
									'value' => json_encode( [ 'val' => true ] ),
									'modifiedIndex' => 123,

								],
								[
									'key' => 'c',
									'value' => json_encode( [ 'val' => false ] ),
									'modifiedIndex' => 123,
								],
							],
						],
					] ] ] ),
					'error' => '',
				],
				'expect' => self::createEtcdResponse( [
					'config' => [ 'a/b' => true, 'a/c' => false ], // data
					'modifiedIndex' => 123 // largest modified index
				] ),
			],
			'200 OK - Missing nodes at second level' => [
				'http' => [
					'code' => 200,
					'reason' => 'OK',
					'headers' => [],
					'body' => json_encode( [ 'node' => [ 'nodes' => [
						[
							'key' => '/example/a',
							'dir' => true,
							'modifiedIndex' => 0,
						],
					] ] ] ),
					'error' => '',
				],
				'expect' => self::createEtcdResponse( [
					'error' => "Unexpected JSON response in dir 'a'; missing 'nodes' list.",
				] ),
			],
			'200 OK - Directory with non-array "nodes" key' => [
				'http' => [
					'code' => 200,
					'reason' => 'OK',
					'headers' => [],
					'body' => json_encode( [ 'node' => [ 'nodes' => [
						[
							'key' => '/example/a',
							'dir' => true,
							'nodes' => 'not an array'
						],
					] ] ] ),
					'error' => '',
				],
				'expect' => self::createEtcdResponse( [
					'error' => "Unexpected JSON response in dir 'a'; 'nodes' is not an array.",
				] ),
			],
			'200 OK - Correctly encoded garbage response' => [
				'http' => [
					'code' => 200,
					'reason' => 'OK',
					'headers' => [],
					'body' => json_encode( [ 'foo' => 'bar' ] ),
					'error' => '',
				],
				'expect' => self::createEtcdResponse( [
					'error' => "Unexpected JSON response: Missing or invalid node at top level.",
				] ),
			],
			'200 OK - Bad value' => [
				'http' => [
					'code' => 200,
					'reason' => 'OK',
					'headers' => [],
					'body' => json_encode( [ 'node' => [ 'nodes' => [
						[
							'key' => '/example/foo',
							'value' => ';"broken{value',
							'modifiedIndex' => 123,
						]
					] ] ] ),
					'error' => '',
				],
				'expect' => self::createEtcdResponse( [
					'error' => "Failed to parse value for 'foo'.",
				] ),
			],
			'200 OK - Empty node list' => [
				'http' => [
					'code' => 200,
					'reason' => 'OK',
					'headers' => [],
					'body' => '{"node":{"nodes":[], "modifiedIndex": 12 }}',
					'error' => '',
				],
				'expect' => self::createEtcdResponse( [
					'config' => [], // data
				] ),
			],
			'200 OK - Invalid JSON' => [
				'http' => [
					'code' => 200,
					'reason' => 'OK',
					'headers' => [ 'content-length' => 0 ],
					'body' => '',
					'error' => '(curl error: no status set)',
				],
				'expect' => self::createEtcdResponse( [
					'error' => "Error unserializing JSON response.",
				] ),
			],
			'404 Not Found' => [
				'http' => [
					'code' => 404,
					'reason' => 'Not Found',
					'headers' => [ 'content-length' => 0 ],
					'body' => '',
					'error' => '',
				],
				'expect' => self::createEtcdResponse( [
					'error' => 'HTTP 404 (Not Found)',
				] ),
			],
			'400 Bad Request - custom error' => [
				'http' => [
					'code' => 400,
					'reason' => 'Bad Request',
					'headers' => [ 'content-length' => 0 ],
					'body' => '',
					'error' => 'No good reason',
				],
				'expect' => self::createEtcdResponse( [
					'error' => 'No good reason',
					'retry' => true, // retry
				] ),
			],
		];
	}

	/**
	 * @covers \MediaWiki\Config\EtcdConfigParseError
	 * @dataProvider provideFetchFromServer
	 */
	public function testFetchFromServer( array $httpResponse, array $expected ) {
		$http = $this->createMock( MultiHttpClient::class );
		$http->expects( $this->once() )->method( 'run' )
			->willReturn( array_values( $httpResponse ) );

		$conf = $this->createMock( EtcdConfig::class );
		// Access for protected member and method
		$conf = TestingAccessWrapper::newFromObject( $conf );
		$conf->http = $http;

		$this->assertSame(
			$expected,
			$conf->fetchAllFromEtcdServer( 'etcd-tcp.example.net' )
		);
	}

	public function testFetchFromServerWithoutPort() {
		$conf = $this->createMock( EtcdConfig::class );

		$http = $this->createMock( MultiHttpClient::class );

		$conf = TestingAccessWrapper::newFromObject( $conf );
		$conf->protocol = 'https';
		$conf->http = $http;

		$http
			->expects( $this->once() )
			->method( 'run' )
			->with(
				$this->logicalAnd(
					$this->arrayHasKey( 'url' ),
					$this->callback( function ( $request ) {
						$this->assertStringStartsWith(
							'https://etcd.example/',
							$request['url']
						);
						return true;
					} )
				)
			);

		$conf->fetchAllFromEtcdServer( 'etcd.example' );
	}

	public function testFetchFromServerWithPort() {
		$conf = $this->createMock( EtcdConfig::class );

		$http = $this->createMock( MultiHttpClient::class );

		$conf = TestingAccessWrapper::newFromObject( $conf );
		$conf->protocol = 'https';
		$conf->http = $http;

		$http
			->expects( $this->once() )
			->method( 'run' )
			->with(
				$this->logicalAnd(
					$this->arrayHasKey( 'url' ),
					$this->callback( function ( $request ) {
						$this->assertStringStartsWith(
							'https://etcd.example:4001/',
							$request['url']
						);
						return true;
					} )
				)
			);

		$conf->fetchAllFromEtcdServer( 'etcd.example', 4001 );
	}

	public function testServiceDiscovery() {
		$conf = $this->createConfigMock(
			[ 'host' => 'an.example' ],
			[ 'fetchAllFromEtcdServer' ]
		);
		$conf = TestingAccessWrapper::newFromObject( $conf );

		$conf->dsd = TestingAccessWrapper::newFromObject( $conf->dsd );
		$conf->dsd->resolver = $this->createCallableMock();
		$conf->dsd->resolver
			->expects( $this->once() )
			->method( '__invoke' )
			->with( '_etcd._tcp.an.example' )
			->willReturn( [
				[
					'target' => 'etcd-target.an.example',
					'port' => '2379',
					'pri' => '1',
					'weight' => '1',
				],
			] );

		$conf->expects( $this->once() )
			->method( 'fetchAllFromEtcdServer' )
			->with( 'etcd-target.an.example', 2379 )
			->willReturn( self::createEtcdResponse( [ 'foo' => true ] ) );

		$conf->fetchAllFromEtcd();
	}

	public function testServiceDiscoverySrvRecordAsHost() {
		$conf = $this->createConfigMock(
			[ 'host' => '_etcd-client-ssl._tcp.an.example' ],
			[ 'fetchAllFromEtcdServer' ]
		);
		$conf = TestingAccessWrapper::newFromObject( $conf );

		$conf->dsd = TestingAccessWrapper::newFromObject( $conf->dsd );
		$conf->dsd->resolver = $this->createCallableMock();
		$conf->dsd->resolver
			->expects( $this->once() )
			->method( '__invoke' )
			->with( '_etcd-client-ssl._tcp.an.example' )
			->willReturn( [
				[
					'target' => 'etcd-target.an.example',
					'port' => '2379',
					'pri' => '1',
					'weight' => '1',
				],
			] );

		$conf->expects( $this->once() )
			->method( 'fetchAllFromEtcdServer' )
			->with( 'etcd-target.an.example', 2379 )
			->willReturn( self::createEtcdResponse( [ 'foo' => true ] ) );

		$conf->fetchAllFromEtcd();
	}
}
