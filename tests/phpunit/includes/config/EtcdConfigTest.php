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
		$mock->expects( $this->once() )
			->method( 'fetchAllFromEtcd' )
			->willReturn( [
				$config,
				null, // error
				false // retry?
			] );
		return $mock;
	}

	/**
	 * @covers EtcdConfig::has
	 * @covers EtcdConfig::get
	 */
	public function testKnownKey() {
		$config = $this->createSimpleConfigMock( [
			'known' => 'value'
		] );

		$this->assertSame(
			true,
			$config->has( 'known' ),
			'Has key'
		);

		$this->assertSame(
			'value',
			$config->get( 'known' ),
			'Get key'
		);
	}

	/**
	 * @covers EtcdConfig::has
	 */
	public function testHasUnknown() {
		$config = $this->createSimpleConfigMock( [
			'known' => 'value'
		] );

		$this->assertSame(
			false,
			$config->has( 'unknown' ),
			'Has key'
		);
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

	public static function provideLoadScenarios() {
		// Format:
		// - cache: true, false, or "expired"
		// - lock: true, or false
		// - backend: "success", "fail", "retry"
		// - retry: true, or false (Whether a retry should succeed)
		// - expected:
		//   - "fresh" Fetched from a backend.
		//   - "fresh-retried" Fetched from a backend after retry.
		//   - "cache" Fetched from cache (not expired).
		//   - "stale-cache" Fetched from cache but known stale.
		return [
			// cache miss, gets lock
			[ [
				'cache' => false, 'lock' => true, 'backend' => 'success',
				'expected' => 'fresh',
			] ],
			[ [
				'cache' => false, 'lock' => true, 'backend' => 'fail',
				'expected' => false,
			] ],
			[ [
				'cache' => false, 'lock' => true, 'backend' => 'retry', 'retry' => true,
				'expected' => 'fresh-retried',
			] ],
			[ [
				'cache' => false, 'lock' => true, 'backend' => 'retry', 'retry' => false,
				'expected' => false,
			] ],
			// cache miss, doesn't get lock
			[ [
				'cache' => false, 'lock' => false, 'retry' => true,
				// Populated by another process after retry
				'expected' => 'cache',
			] ],
			[ [
				'cache' => false, 'lock' => false, 'retry' => false,
				'expected' => false,
			] ],
			// cache hit
			[ [
				'cache' => true,
				'expected' => 'cache',
			] ],
			// cache expired, gets lock
			[ [
				'cache' => 'expired', 'lock' => true, 'backend' => 'success',
				'expected' => 'fresh',
			] ],
			[ [
				'cache' => 'expired', 'lock' => true, 'backend' => 'fail',
				'expected' => false,
			] ],
			// cache expired, doesn't get lock
			[ [
				'cache' => 'expired', 'lock' => false,
				'expected' => 'cache-stale',
			] ],
		];
	}

	/**
	 * @dataProvider provideLoadScenarios
	 */
	public function testLoadScenarios( array $case ) {
		$case += [ 'lock' => false, 'backend' => null ];

		$cache = $this->getMockBuilder( HashBagOStuff::class )
			->setConstructorArgs( [] )
			->setMethods( [ 'get', 'lock' ] )
			->getMock();
		$cacheKey = $cache->makeKey( 'variable', sha1( '/' ) );

		$cacheValue = [
			'config' => [ 'key' => 'cache' ],
			'expires' => time() * 2,
		];
		$cacheStaleValue = [
			'config' => [ 'key' => 'cache-stale' ],
			'expires' => 1,
		];

		// Prepare lock
		$cache->expects( $this->any() )
			->method( 'lock' )
			->willReturn( $case['lock'] );


		// Prepare cache
		if ( $case['cache'] === true ) {
			$firstCache = $cacheValue;
		} elseif ( $case['cache'] === 'expired' ) {
			$firstCache = $cacheStaleValue;
		} else {
			$firstCache = false;
		}
		if ( $case['lock'] === false && $case['cache'] === false && $case['retry'] === true ) {
			$cache->expects( $this->exactly( 2 ) )
				->method( 'get' )
				->will( $this->onConsecutiveCalls(
					$firstCache,
					$cacheValue
				) );
		} else {
			$cache->expects( $this->any() )
				->method( 'get' )
				->willReturn( $firstCache );
		}

		// Create mock
		$mock = $this->createConfigMock( [
			'cache' => $cache,
		] );

		// Set up mock backend
		if ( $case['backend'] === null ) {
			$mock->expects( $this->never() )
				->method( 'fetchAllFromEtcd' );
		} else {
			if ( $case['backend'] === 'success' ) {
				$fetch = [ [ 'key' => 'fresh' ], null, false ];
				$mock->expects( $this->once() )
					->method( 'fetchAllFromEtcd' )
					->willReturn( $fetch );
			} elseif ( $case['backend'] === 'fail' ) {
				$fetch = [ null, 'Fake failure', false ];
				$mock->expects( $this->once() )
					->method( 'fetchAllFromEtcd' )
					->willReturn( $fetch );
			} elseif ( $case['backend'] === 'retry' ) {
				$firstFetch = [ null, 'Fake retryable failure', true ];
				if ( $case['retry'] === true ) {
					$secondFetch = [ [ 'key' => 'fresh-retried' ], null, false ];
				} else {
					$secondFetch = $firstFetch;
				}
				$mock->expects( $this->atLeastOnce() )
					->method( 'fetchAllFromEtcd' )
					->will( $this->onConsecutiveCalls(
						$firstFetch,
						$secondFetch
					) );
			}
		}

		if ( $case['expected'] == false ) {
			$this->setExpectedException( ConfigException::class );
			$mock->get( 'key' );
		} else {
			$this->assertSame(
				$case['expected'],
				$mock->get( 'key' ),
				'Get key'
			);
		}
	}
}
