<?php

/**
 * @group BagOStuff
 */
class CachedBagOStuffTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	/**
	 * @covers CachedBagOStuff::__construct
	 * @covers CachedBagOStuff::get
	 */
	public function testGetFromBackend() {
		$backend = new HashBagOStuff;
		$cache = new CachedBagOStuff( $backend );

		$backend->set( 'foo', 'bar' );
		$this->assertEquals( 'bar', $cache->get( 'foo' ) );

		$backend->set( 'foo', 'baz' );
		$this->assertEquals( 'bar', $cache->get( 'foo' ), 'cached' );
	}

	/**
	 * @covers CachedBagOStuff::set
	 * @covers CachedBagOStuff::delete
	 */
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

	/**
	 * @covers CachedBagOStuff::set
	 * @covers CachedBagOStuff::delete
	 */
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

	/**
	 * @covers CachedBagOStuff::get
	 */
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

	/**
	 * @covers CachedBagOStuff::deleteObjectsExpiringBefore
	 */
	public function testExpire() {
		$backend = $this->getMockBuilder( HashBagOStuff::class )
			->setMethods( [ 'deleteObjectsExpiringBefore' ] )
			->getMock();
		$backend->expects( $this->once() )
			->method( 'deleteObjectsExpiringBefore' )
			->willReturn( false );

		$cache = new CachedBagOStuff( $backend );
		$cache->deleteObjectsExpiringBefore( '20110401000000' );
	}

	/**
	 * @covers CachedBagOStuff::makeKey
	 */
	public function testMakeKey() {
		$backend = $this->getMockBuilder( HashBagOStuff::class )
			->setConstructorArgs( [ [ 'keyspace' => 'magic' ] ] )
			->setMethods( [ 'makeKey' ] )
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

	/**
	 * @covers CachedBagOStuff::makeGlobalKey
	 */
	public function testMakeGlobalKey() {
		$backend = $this->getMockBuilder( HashBagOStuff::class )
			->setConstructorArgs( [ [ 'keyspace' => 'magic' ] ] )
			->setMethods( [ 'makeGlobalKey' ] )
			->getMock();
		$backend->method( 'makeGlobalKey' )
			->willReturn( 'special/logic' );

		$cache = new CachedBagOStuff( $backend );

		$this->assertSame( 'special/logic', $backend->makeGlobalKey( 'special', 'logic' ) );
		$this->assertSame( 'global:special:logic', $cache->makeGlobalKey( 'special', 'logic' ) );
	}
}
