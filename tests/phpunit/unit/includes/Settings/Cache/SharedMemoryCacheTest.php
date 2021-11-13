<?php

namespace MediaWiki\Tests\Unit\Settings\Cache;

use MediaWiki\Settings\Cache\CacheArgumentException;
use MediaWiki\Settings\Cache\SharedMemoryCache;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Settings\Cache\SharedMemoryCache
 */
class SharedMemoryCacheTest extends TestCase {
	use CacheInterfaceTestTrait;

	protected function newCache(): SharedMemoryCache {
		return new SharedMemoryCache( 'phpunit.mediawiki.settings' );
	}

	public function setUp(): void {
		if ( !SharedMemoryCache::isSupported() || !ini_get( 'apc.enable_cli' ) ) {
			$this->markTestSkipped(
				'skipped shared memory cache tests. set apc.enable_cli=on to run them'
			);
		}
	}

	public function tearDown(): void {
		if ( SharedMemoryCache::isSupported() && ini_get( 'apc.enable_cli' ) ) {
			$this->newCache()->clear();
		}
	}

	public function testSetWithZeroLengthKey() {
		$cache = $this->newCache();

		$this->expectException( CacheArgumentException::class );

		$cache->set( '', 'foo' );
	}

	public function testNamespacing() {
		$cache1 = new SharedMemoryCache( 'phpunit.mediawiki.settings.one' );
		$cache2 = new SharedMemoryCache( 'phpunit.mediawiki.settings.two' );

		$cache1->set( 'one', 'fish' );
		$cache1->set( 'two', 'fish' );
		$cache2->set( 'red', 'fish' );
		$cache2->set( 'blue', 'fish' );

		try {
			$cache1->clear();

			$this->assertFalse( $cache1->has( 'one' ) );
			$this->assertFalse( $cache1->has( 'two' ) );

			$this->assertTrue( $cache2->has( 'red' ) );
			$this->assertTrue( $cache2->has( 'blue' ) );

		} finally {
			$cache2->clear();
		}
	}
}
