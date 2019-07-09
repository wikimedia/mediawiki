<?php

use Wikimedia\TestingAccessWrapper;

class EtcdConfigTest extends MediaWikiUnitTestCase {

	private function createConfigMock( array $options = [] ) {
		return $this->getMockBuilder( EtcdConfig::class )
			->setConstructorArgs( [ $options + [
				'host' => 'etcd-tcp.example.net',
				'directory' => '/',
				'timeout' => 0.1,
			] ] )
			->setMethods( [ 'fetchAllFromEtcd' ] )
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

	/**
	 * @covers EtcdConfig::has
	 */
	public function testHasKnown() {
		$config = $this->createSimpleConfigMock( [
			'known' => 'value'
		] );
		$this->assertSame( true, $config->has( 'known' ) );
	}

	/**
	 * @covers EtcdConfig::__construct
	 * @covers EtcdConfig::get
	 */
	public function testGetKnown() {
		$config = $this->createSimpleConfigMock( [
			'known' => 'value'
		] );
		$this->assertSame( 'value', $config->get( 'known' ) );
	}

	/**
	 * @covers EtcdConfig::has
	 */
	public function testHasUnknown() {
		$config = $this->createSimpleConfigMock( [
			'known' => 'value'
		] );
		$this->assertSame( false, $config->has( 'unknown' ) );
	}

	/**
	 * @covers EtcdConfig::get
	 */
	public function testGetUnknown() {
		$config = $this->createSimpleConfigMock( [
			'known' => 'value'
		] );
		$this->setExpectedException( ConfigException::class );
		$config->get( 'unknown' );
	}

	/**
	 * @covers EtcdConfig::getModifiedIndex
	 */
	public function testGetModifiedIndex() {
		$config = $this->createSimpleConfigMock(
			[ 'some' => 'value' ],
			123
		);
		$this->assertSame( 123, $config->getModifiedIndex() );
	}

	/**
	 * @covers EtcdConfig::__construct
	 */
	public function testConstructCacheObj() {
		$cache = $this->getMockBuilder( HashBagOStuff::class )
			->setMethods( [ 'get' ] )
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

	/**
	 * @covers EtcdConfig::__construct
	 */
	public function testConstructCacheSpec() {
		$config = $this->createConfigMock( [ 'cache' => [
			'class' => HashBagOStuff::class
		] ] );
		$config->expects( $this->once() )->method( 'fetchAllFromEtcd' )
			->willReturn( self::createEtcdResponse(
				[ 'config' => [ 'known' => 'from-fetch' ], ] ) );

		$this->assertSame( 'from-fetch', $config->get( 'known' ) );
	}

	/**
	 * Test matrix
	 *
	 * - [x] Cache miss
	 *       Result: Fetched value
	 *       > cache miss | gets lock | backend succeeds
	 *
	 * - [x] Cache miss with backend error
	 *       Result: ConfigException
	 *       > cache miss | gets lock | backend error (no retry)
	 *
	 * - [x] Cache hit after retry
	 *       Result: Cached value (populated by process holding lock)
	 *       > cache miss | no lock | cache retry
	 *
	 * - [x] Cache hit
	 *       Result: Cached value
	 *       > cache hit
	 *
	 * - [x] Process cache hit
	 *       Result: Cached value
	 *       > process cache hit
	 *
	 * - [x] Cache expired
	 *       Result: Fetched value
	 *       > cache expired | gets lock | backend succeeds
	 *
	 * - [x] Cache expired with backend failure
	 *       Result: Cached value (stale)
	 *       > cache expired | gets lock | backend fails (allows retry)
	 *
	 * - [x] Cache expired and no lock
	 *       Result: Cached value (stale)
	 *       > cache expired | no lock
	 *
	 * Other notable scenarios:
	 *
	 * - [ ] Cache miss with backend retry
	 *       Result: Fetched value
	 *       > cache expired | gets lock | backend failure (allows retry)
	 */

	/**
	 * @covers EtcdConfig::load
	 */
	public function testLoadCacheMiss() {
		// Create cache mock
		$cache = $this->getMockBuilder( HashBagOStuff::class )
			->setMethods( [ 'get', 'lock' ] )
			->getMock();
		// .. misses cache
		$cache->expects( $this->once() )->method( 'get' )
			->willReturn( false );
		// .. gets lock
		$cache->expects( $this->once() )->method( 'lock' )
			->willReturn( true );

		// Create config mock
		$mock = $this->createConfigMock( [
			'cache' => $cache,
		] );
		$mock->expects( $this->once() )->method( 'fetchAllFromEtcd' )
			->willReturn(
				self::createEtcdResponse( [ 'config' => [ 'known' => 'from-fetch' ] ] ) );

		$this->assertSame( 'from-fetch', $mock->get( 'known' ) );
	}

	/**
	 * @covers EtcdConfig::load
	 */
	public function testLoadCacheMissBackendError() {
		// Create cache mock
		$cache = $this->getMockBuilder( HashBagOStuff::class )
			->setMethods( [ 'get', 'lock' ] )
			->getMock();
		// .. misses cache
		$cache->expects( $this->once() )->method( 'get' )
			->willReturn( false );
		// .. gets lock
		$cache->expects( $this->once() )->method( 'lock' )
			->willReturn( true );

		// Create config mock
		$mock = $this->createConfigMock( [
			'cache' => $cache,
		] );
		$mock->expects( $this->once() )->method( 'fetchAllFromEtcd' )
			->willReturn( self::createEtcdResponse( [ 'error' => 'Fake error', ] ) );

		$this->setExpectedException( ConfigException::class );
		$mock->get( 'key' );
	}

	/**
	 * @covers EtcdConfig::load
	 */
	public function testLoadCacheMissWithoutLock() {
		// Create cache mock
		$cache = $this->getMockBuilder( HashBagOStuff::class )
			->setMethods( [ 'get', 'lock' ] )
			->getMock();
		$cache->expects( $this->exactly( 2 ) )->method( 'get' )
			->will( $this->onConsecutiveCalls(
				// .. misses cache first time
				false,
				// .. hits cache on retry
				[
					'config' => [ 'known' => 'from-cache' ],
					'expires' => INF,
					'modifiedIndex' => 123
				]
			) );
		// .. misses lock
		$cache->expects( $this->once() )->method( 'lock' )
			->willReturn( false );

		// Create config mock
		$mock = $this->createConfigMock( [
			'cache' => $cache,
		] );
		$mock->expects( $this->never() )->method( 'fetchAllFromEtcd' );

		$this->assertSame( 'from-cache', $mock->get( 'known' ) );
	}

	/**
	 * @covers EtcdConfig::load
	 */
	public function testLoadCacheHit() {
		// Create cache mock
		$cache = $this->getMockBuilder( HashBagOStuff::class )
			->setMethods( [ 'get', 'lock' ] )
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

		$this->assertSame( 'from-cache', $mock->get( 'known' ) );
	}

	/**
	 * @covers EtcdConfig::load
	 */
	public function testLoadProcessCacheHit() {
		// Create cache mock
		$cache = $this->getMockBuilder( HashBagOStuff::class )
			->setMethods( [ 'get', 'lock' ] )
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

	/**
	 * @covers EtcdConfig::load
	 */
	public function testLoadCacheExpiredLockFetchSucceeded() {
		// Create cache mock
		$cache = $this->getMockBuilder( HashBagOStuff::class )
			->setMethods( [ 'get', 'lock' ] )
			->getMock();
		$cache->expects( $this->once() )->method( 'get' )->willReturn(
			// .. stale cache
			[
				'config' => [ 'known' => 'from-cache-expired' ],
				'expires' => -INF,
				'modifiedIndex' => 0,
			]
		);
		// .. gets lock
		$cache->expects( $this->once() )->method( 'lock' )
			->willReturn( true );

		// Create config mock
		$mock = $this->createConfigMock( [
			'cache' => $cache,
		] );
		$mock->expects( $this->once() )->method( 'fetchAllFromEtcd' )
			->willReturn( self::createEtcdResponse( [ 'config' => [ 'known' => 'from-fetch' ] ] ) );

		$this->assertSame( 'from-fetch', $mock->get( 'known' ) );
	}

	/**
	 * @covers EtcdConfig::load
	 */
	public function testLoadCacheExpiredLockFetchFails() {
		// Create cache mock
		$cache = $this->getMockBuilder( HashBagOStuff::class )
			->setMethods( [ 'get', 'lock' ] )
			->getMock();
		$cache->expects( $this->once() )->method( 'get' )->willReturn(
			// .. stale cache
			[
				'config' => [ 'known' => 'from-cache-expired' ],
				'expires' => -INF,
				'modifiedIndex' => 0,
			]
		);
		// .. gets lock
		$cache->expects( $this->once() )->method( 'lock' )
			->willReturn( true );

		// Create config mock
		$mock = $this->createConfigMock( [
			'cache' => $cache,
		] );
		$mock->expects( $this->once() )->method( 'fetchAllFromEtcd' )
			->willReturn( self::createEtcdResponse( [ 'error' => 'Fake failure', 'retry' => true ] ) );

		$this->assertSame( 'from-cache-expired', $mock->get( 'known' ) );
	}

	/**
	 * @covers EtcdConfig::load
	 */
	public function testLoadCacheExpiredNoLock() {
		// Create cache mock
		$cache = $this->getMockBuilder( HashBagOStuff::class )
			->setMethods( [ 'get', 'lock' ] )
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

		$this->assertSame( 'from-cache-expired', $mock->get( 'known' ) );
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
	 * @covers EtcdConfig::fetchAllFromEtcdServer
	 * @covers EtcdConfig::unserialize
	 * @covers EtcdConfig::parseResponse
	 * @covers EtcdConfig::parseDirectory
	 * @covers EtcdConfigParseError
	 * @dataProvider provideFetchFromServer
	 */
	public function testFetchFromServer( array $httpResponse, array $expected ) {
		$http = $this->getMockBuilder( MultiHttpClient::class )
			->disableOriginalConstructor()
			->getMock();
		$http->expects( $this->once() )->method( 'run' )
			->willReturn( array_values( $httpResponse ) );

		$conf = $this->getMockBuilder( EtcdConfig::class )
			->disableOriginalConstructor()
			->getMock();
		// Access for protected member and method
		$conf = TestingAccessWrapper::newFromObject( $conf );
		$conf->http = $http;

		$this->assertSame(
			$expected,
			$conf->fetchAllFromEtcdServer( 'etcd-tcp.example.net' )
		);
	}
}
