<?php

namespace Wikimedia\Tests\ObjectCache;

use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\CachedBagOStuff;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \Wikimedia\ObjectCache\CachedBagOStuff
 * @group BagOStuff
 */
class CachedBagOStuffTest extends TestCase {

	use MediaWikiCoversValidator;

	public function testGetFromBackend() {
		$backend = new HashBagOStuff;
		$cache = new CachedBagOStuff( $backend );

		$backend->set( 'foo', 'bar' );
		$this->assertEquals( 'bar', $cache->get( 'foo' ) );

		$backend->set( 'foo', 'baz' );
		$this->assertEquals( 'bar', $cache->get( 'foo' ), 'cached' );
	}

	public function testSetAndDelete() {
		$backend = new HashBagOStuff;
		$cache = new CachedBagOStuff( $backend );

		for ( $i = 0; $i < 10; $i++ ) {
			$cache->set( "key$i", 1 );
			$this->assertSame( 1, $cache->get( "key$i" ) );
			$this->assertSame( 1, $backend->get( "key$i" ) );

			$cache->delete( "key$i" );
			$this->assertFalse( $cache->get( "key$i" ) );
			$this->assertFalse( $backend->get( "key$i" ) );
		}
	}

	public function testWriteCacheOnly() {
		$backend = new HashBagOStuff;
		$cache = new CachedBagOStuff( $backend );

		$cache->set( 'foo', 'bar', 0, CachedBagOStuff::WRITE_CACHE_ONLY );
		$this->assertEquals( 'bar', $cache->get( 'foo' ) );
		$this->assertFalse( $backend->get( 'foo' ) );

		$cache->set( 'foo', 'old' );
		$this->assertEquals( 'old', $cache->get( 'foo' ) );
		$this->assertEquals( 'old', $backend->get( 'foo' ) );

		$cache->set( 'foo', 'new', 0, CachedBagOStuff::WRITE_CACHE_ONLY );
		$this->assertEquals( 'new', $cache->get( 'foo' ) );
		$this->assertEquals( 'old', $backend->get( 'foo' ) );

		$cache->delete( 'foo', CachedBagOStuff::WRITE_CACHE_ONLY );
		$this->assertEquals( 'old', $cache->get( 'foo' ) ); // Reloaded from backend
	}

	public function testCacheBackendMisses() {
		$backend = new HashBagOStuff;
		$cache = new CachedBagOStuff( $backend );

		// First hit primes the cache with miss from the backend
		$this->assertFalse( $cache->get( 'foo' ) );

		// Change the value in the backend
		$backend->set( 'foo', true );

		// Second hit returns the cached miss
		$this->assertFalse( $cache->get( 'foo' ) );

		// But a fresh value is read from the backend
		$backend->set( 'bar', true );
		$this->assertTrue( $cache->get( 'bar' ) );
	}

	public function testExpire() {
		$backend = $this->getMockBuilder( HashBagOStuff::class )
			->onlyMethods( [ 'deleteObjectsExpiringBefore' ] )
			->getMock();
		$backend->expects( $this->once() )
			->method( 'deleteObjectsExpiringBefore' )
			->willReturn( false );

		$cache = new CachedBagOStuff( $backend );
		$cache->deleteObjectsExpiringBefore( '20110401000000' );
	}

	public function testMakeKey() {
		$backend = $this->getMockBuilder( HashBagOStuff::class )
			->setConstructorArgs( [ [ 'keyspace' => 'magic' ] ] )
			->onlyMethods( [ 'makeKey' ] )
			->getMock();
		$backend->method( 'makeKey' )
			->willReturn( 'special/logic' );

		$cache = new CachedBagOStuff( $backend );

		$this->assertSame( 'special/logic', $backend->makeKey( 'special', 'logic' ) );
		$this->assertSame(
			'magic:special:logic',
			$cache->makeKey( 'special', 'logic' ),
			"Backend keyspace used"
		);
	}

	public function testMakeGlobalKey() {
		$backend = $this->getMockBuilder( HashBagOStuff::class )
			->setConstructorArgs( [ [ 'keyspace' => 'magic' ] ] )
			->onlyMethods( [ 'makeGlobalKey' ] )
			->getMock();
		$backend->method( 'makeGlobalKey' )
			->willReturn( 'special/logic' );

		$cache = new CachedBagOStuff( $backend );

		$this->assertSame( 'special/logic', $backend->makeGlobalKey( 'special', 'logic' ) );
		$this->assertSame( 'global:special:logic', $cache->makeGlobalKey( 'special', 'logic' ) );
	}

	public function testErrorHandling() {
		$backend = new HashBagOStuff;
		$cache = new CachedBagOStuff( $backend );
		$wrapper = TestingAccessWrapper::newFromObject( $cache );
		$key = $cache->makeKey( 'test' );

		$wp = $cache->watchErrors();
		$cache->get( $key );
		$this->assertSame( BagOStuff::ERR_NONE, $cache->getLastError( $wp ) );

		$wrapper->setLastError( BagOStuff::ERR_UNREACHABLE );
		$this->assertSame( BagOStuff::ERR_UNREACHABLE, $cache->getLastError() );
		$this->assertSame( BagOStuff::ERR_UNREACHABLE, $cache->getLastError( $wp ) );

		$wp = $cache->watchErrors();
		$wrapper->setLastError( BagOStuff::ERR_UNEXPECTED );
		$wp2 = $cache->watchErrors();
		$this->assertSame( BagOStuff::ERR_UNEXPECTED, $cache->getLastError() );
		$this->assertSame( BagOStuff::ERR_UNEXPECTED, $cache->getLastError( $wp ) );
		$this->assertSame( BagOStuff::ERR_NONE, $cache->getLastError( $wp2 ) );

		$cache->get( $key );
		$this->assertSame( BagOStuff::ERR_UNEXPECTED, $cache->getLastError() );
		$this->assertSame( BagOStuff::ERR_UNEXPECTED, $cache->getLastError( $wp ) );
		$this->assertSame( BagOStuff::ERR_NONE, $cache->getLastError( $wp2 ) );
	}

	public function testWasLastGetCached() {
		$backend = new HashBagOStuff( [ 'miss' => 1 ] );
		$cache = new CachedBagOStuff( $backend );
		$cache->set( 'hit', 1 );

		$cache->get( 'foo' );
		$this->assertFalse( $cache->wasLastGetCached() );

		$cache->get( 'hit' );
		$this->assertTrue( $cache->wasLastGetCached() );

		$cache->get( 'miss' );
		$this->assertFalse( $cache->wasLastGetCached() );
	}

	public function testWasLastGetCachedThrowsDefault(): void {
		$cache = new CachedBagOStuff( new HashBagOStuff() );

		$this->expectExceptionMessage(
			'Wikimedia\ObjectCache\CachedBagOStuff::wasLastGetCached must be called immediately after get()'
		);
		$cache->wasLastGetCached();
	}

	public function testWasLastGetCachedThrows(): void {
		$cache = new CachedBagOStuff( new HashBagOStuff( [ 'miss' => 1 ] ) );
		$cache->set( 'hit', 1 );

		$cache->get( 'hit' );
		$this->assertTrue( $cache->wasLastGetCached() );

		$this->expectExceptionMessage(
			'Wikimedia\ObjectCache\CachedBagOStuff::wasLastGetCached must be called immediately after get()'
		);
		$cache->wasLastGetCached();
	}
}
