<?php

namespace MediaWiki\Tests\Unit\Settings\Cache;

use BagOStuff;
use MediaWiki\Settings\Cache\CacheableSource;
use MediaWiki\Settings\Cache\CachedSource;
use MediaWiki\Settings\SettingsBuilderException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Settings\Cache\CachedSource
 */
class CachedSourceTest extends TestCase {
	public function testLoadWithMiss() {
		$cache = $this->createMock( BagOStuff::class );
		$source = $this->createMock( CacheableSource::class );
		$cacheSource = new CachedSource( $cache, $source );

		$settings = [ 'config' => [ 'Foo' => 'value' ] ];
		$hashKey = 'abc123';
		$key = 'global:MediaWiki\Tests\Unit\Settings\Cache\CachedSourceTest:' . $hashKey;
		$ttl = 123;

		$source
			->expects( $this->once() )
			->method( 'getHashKey' )
			->willReturn( $hashKey );

		$cache
			->expects( $this->once() )
			->method( 'makeGlobalKey' )
			->with( 'MediaWiki\Settings\Cache\CachedSource', $hashKey )
			->willReturn( $key );

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
		$cache = $this->createMock( BagOStuff::class );
		$source = $this->createMock( CacheableSource::class );
		$cacheSource = new CachedSource( $cache, $source );

		$settings = [ 'config' => [ 'Foo' => 'value' ] ];
		$hashKey = 'abc123';
		$key = 'global:MediaWiki\Tests\Unit\Settings\Cache\CachedSourceTest:' . $hashKey;

		$source
			->expects( $this->once() )
			->method( 'getHashKey' )
			->willReturn( $hashKey );

		$cache
			->expects( $this->once() )
			->method( 'makeGlobalKey' )
			->with( 'MediaWiki\Settings\Cache\CachedSource', $hashKey )
			->willReturn( $key );

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

	public function testLoadStale() {
		$cache = $this->createMock( BagOStuff::class );
		$source = $this->createMock( CacheableSource::class );
		$cacheSource = new CachedSource( $cache, $source );

		$settings = [ 'config' => [ 'Foo' => 'value' ] ];
		$hashKey = 'abc123';
		$key = 'global:MediaWiki\Tests\Unit\Settings\Cache\CachedSourceTest:' . $hashKey;
		$expired = microtime( true ) - 1;

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
			->method( 'makeGlobalKey' )
			->with( 'MediaWiki\Settings\Cache\CachedSource', $hashKey )
			->willReturn( $key );

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
			->will( $this->throwException( new SettingsBuilderException( 'foo' ) ) );

		$this->assertSame( $settings, $cacheSource->load() );
	}
}
