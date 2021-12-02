<?php

namespace MediaWiki\Tests\Unit\Settings\Cache;

use MediaWiki\Settings\Cache\CacheableSource;
use MediaWiki\Settings\Cache\CachedSource;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

/**
 * @covers \MediaWiki\Settings\Cache\CachedSource
 */
class CachedSourceTest extends TestCase {
	public function testLoadWithMiss() {
		$cache = $this->createMock( CacheInterface::class );
		$source = $this->createMock( CacheableSource::class );
		$cacheSource = new CachedSource( $cache, $source );

		$settings = [ 'config' => [ 'Foo' => 'value' ] ];
		$key = 'abc123';
		$ttl = 123;

		$source
			->expects( $this->once() )
			->method( 'getHashKey' )
			->willReturn( $key );

		$cache
			->expects( $this->once() )
			->method( 'get' )
			->with( $key, null )
			->willReturn( null );

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
		$cache = $this->createMock( CacheInterface::class );
		$source = $this->createMock( CacheableSource::class );
		$cacheSource = new CachedSource( $cache, $source );

		$settings = [ 'config' => [ 'Foo' => 'value' ] ];
		$key = 'abc123';

		$source
			->expects( $this->once() )
			->method( 'getHashKey' )
			->willReturn( 'abc123' );

		$cache
			->expects( $this->once() )
			->method( 'get' )
			->with( $key, null )
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
}
