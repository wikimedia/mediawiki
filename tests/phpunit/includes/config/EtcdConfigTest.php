<?php

class EtcConfigTest extends PHPUnit_Framework_TestCase {

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

	private function createSimpleConfigMock( array $config ) {
		$mock = $this->createConfigMock();
		$mock->expects( $this->once() )->method( 'fetchAllFromEtcd' )
			->willReturn( [
				$config,
				null, // error
				false // retry?
			] );
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
			] );
		$config = $this->createConfigMock( [ 'cache' => $cache ] );

		$this->assertSame( 'from-cache', $config->get( 'known' ) );
	}

	/**
	 * @covers EtcdConfig::__construct
	 */
	public function testConstructCacheSpec() {
		$config = $this->createConfigMock( [ 'cache' => [
			'class' =>  HashBagOStuff::class
		] ] );
		$config->expects( $this->once() )->method( 'fetchAllFromEtcd' )
			->willReturn( [
				[ 'known' => 'from-fetch' ],
				null, // error
				false // retry?
			] );

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
			->willReturn( [ [ 'known' => 'from-fetch' ], null, false ] );

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
			->willReturn( [ null, 'Fake error', false ] );

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
			->willReturn( [ [ 'known' => 'from-fetch' ], null, false ] );

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
			->willReturn( [ null, 'Fake failure', true ] );

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
}
