<?php

namespace MediaWiki\Tests\Unit\Settings\Cache;

use MediaWiki\Settings\Cache\ArrayCache;
use MediaWiki\Settings\Cache\CacheableSource;
use MediaWiki\Settings\Cache\CachedSource;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Settings\Cache\CachedSource
 */
class CachedSourceTest extends TestCase {
	public function testLoadWithMiss() {
		$cache = new ArrayCache();
		$source = $this->createMock( CacheableSource::class );
		$cacheSource = new CachedSource( $cache, $source );

		$settings = [ 'config' => [ 'Foo' => 'value' ] ];

		$source
			->expects( $this->once() )
			->method( 'getHashKey' )
			->willReturn( 'abc123' );

		$source
			->expects( $this->once() )
			->method( 'load' )
			->willReturn( $settings );

		$this->assertSame( $settings, $cacheSource->load() );
	}

	public function testLoadWithHit() {
		$cache = new ArrayCache();
		$source = $this->createMock( CacheableSource::class );
		$cacheSource = new CachedSource( $cache, $source );

		$settings = [ 'config' => [ 'Foo' => 'value' ] ];

		$cache->set( 'abc123', [ 'value' => $settings ] );

		$source
			->expects( $this->once() )
			->method( 'getHashKey' )
			->willReturn( 'abc123' );

		$source
			->expects( $this->never() )
			->method( 'load' );

		$this->assertSame( $settings, $cacheSource->load() );
	}

	public function testLoadWithNoEarlyExpiry() {
		$cache = new ArrayCache();
		$source = $this->createMock( CacheableSource::class );
		$cacheSource = new CachedSource( $cache, $source );

		$settings = [ 'config' => [ 'Foo' => 'value' ] ];

		$cache->set(
			'abc123',
			[
				'value' => $settings,
				'expiry' => microtime( true ) + 60,
				'generation' => 1.0
			]
		);

		$source
			->expects( $this->once() )
			->method( 'getHashKey' )
			->willReturn( 'abc123' );

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
