<?php

namespace MediaWiki\Tests\Unit\Settings\Cache;

use HashBagOStuff;
use MediaWiki\Settings\Cache\CacheableSource;
use MediaWiki\Settings\Cache\CachedSource;
use MediaWiki\Settings\SettingsBuilderException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Settings\Cache\CachedSource
 */
class CachedSourceTest extends TestCase {
	public function testLoadWithMiss() {
		$settings = [ 'config' => [ 'Foo' => 'value' ] ];
		$hashKey = 'abc123';
		$ttl = 123;

		[ $cache, $key ] = $this->createCacheMock( $hashKey );
		$source = $this->createMock( CacheableSource::class );
		$cacheSource = new CachedSource( $cache, $source );

		$source
			->expects( $this->once() )
			->method( 'getHashKey' )
			->willReturn( $hashKey );

		$cache
			->expects( $this->once() )
			->method( 'get' )
			->with( $key )
			->willReturn( false );

		$source
			->expects( $this->atLeastOnce() )
			->method( 'getExpiryTtl' )
			->willReturn( $ttl );

		$source
			->expects( $this->once() )
			->method( 'load' )
			->willReturn( $settings );

		$cache
			->expects( $this->once() )
			->method( 'set' )
			->with(
				$key,
				$this->logicalAnd(
					$this->arrayHasKey( 'value' ),
					$this->arrayHasKey( 'expiry' ),
					$this->arrayHasKey( 'generation' ),
					$this->callback( function ( $item ) use ( $settings ) {
						$this->assertSame( $settings, $item['value'] );
						$this->assertGreaterThan( 0, $item['expiry'] );
						$this->assertGreaterThan( 0, $item['generation'] );

						return true;
					} )
				),
				$ttl
			);

		$this->assertSame( $settings, $cacheSource->load() );
	}

	public function testLoadWithHit() {
		$settings = [ 'config' => [ 'Foo' => 'value' ] ];
		$hashKey = 'abc123';

		[ $cache, $key ] = $this->createCacheMock( $hashKey );
		$source = $this->createMock( CacheableSource::class );
		$cacheSource = new CachedSource( $cache, $source );

		$source
			->expects( $this->once() )
			->method( 'getHashKey' )
			->willReturn( $hashKey );

		$cache
			->expects( $this->once() )
			->method( 'get' )
			->with( $key )
			->willReturn(
				[
					'value' => $settings,
					'expiry' => microtime( true ) + 60,
					'generation' => 1.0
				]
			);

		// Effectively disable early expiry for the test since it's
		// probabilistic and thus cannot be tested reliably without more
		// dependency injection, but at least ensure that the code path is
		// exercised.
		$source
			->expects( $this->once() )
			->method( 'getExpiryWeight' )
			->willReturn( 0.0 );

		$source
			->expects( $this->never() )
			->method( 'load' );

		$this->assertSame( $settings, $cacheSource->load() );
	}

	public function testLoadStaleOnSourceException() {
		$settings = [ 'config' => [ 'Foo' => 'value' ] ];
		$hashKey = 'abc123';
		$expired = microtime( true ) - 1;

		[ $cache, $key ] = $this->createCacheMock( $hashKey );
		$source = $this->createMock( CacheableSource::class );
		$cacheSource = new CachedSource( $cache, $source );

		$source
			->expects( $this->atLeastOnce() )
			->method( 'allowsStaleLoad' )
			->willReturn( true );

		$source
			->expects( $this->once() )
			->method( 'getHashKey' )
			->willReturn( $hashKey );

		$cache
			->expects( $this->once() )
			->method( 'get' )
			->with( $key )
			->willReturn(
				[
					'value' => $settings,
					'expiry' => $expired,
					'generation' => 1.0
				]
			);

		$source
			->expects( $this->once() )
			->method( 'load' )
			->willThrowException( new SettingsBuilderException( 'foo' ) );

		$this->assertSame( $settings, $cacheSource->load() );
	}

	public function testLoadStaleOnLockFailure() {
		$settings = [ 'config' => [ 'Foo' => 'value' ] ];
		$hashKey = 'abc123';
		$expired = microtime( true ) - 1;

		[ $cache, $key ] = $this->createCacheMock( $hashKey, [ 'lock' ] );
		$source = $this->createMock( CacheableSource::class );
		$cacheSource = new CachedSource( $cache, $source );

		$source
			->expects( $this->atLeastOnce() )
			->method( 'allowsStaleLoad' )
			->willReturn( true );

		$source
			->expects( $this->once() )
			->method( 'getHashKey' )
			->willReturn( $hashKey );

		$cache
			->expects( $this->once() )
			->method( 'get' )
			->with( $key )
			->willReturn(
				[
					'value' => $settings,
					'expiry' => $expired,
					'generation' => 1.0
				]
			);

		$cache
			->expects( $this->once() )
			->method( 'lock' )
			->willReturn( false );

		$this->assertSame( $settings, $cacheSource->load() );
	}

	/**
	 * Creates a mock interface to the cache for the given key.
	 *
	 * @param string $hashKey Source hash key.
	 * @param ?array $methods Additional methods to mock.
	 *
	 * @return array The cache mock and global cache key.
	 */
	private function createCacheMock( string $hashKey, ?array $methods = [] ): array {
		$cache = $this->createPartialMock(
			HashBagOStuff::class,
			array_merge( [ 'get', 'makeGlobalKey', 'set' ], $methods )
		);

		$key = 'global:MediaWiki\Tests\Unit\Settings\Cache\CachedSourceTest:' . $hashKey;

		$cache
			->expects( $this->once() )
			->method( 'makeGlobalKey' )
			->with( CachedSource::class, $hashKey )
			->willReturn( $key );

		return [ $cache, $key ];
	}
}
