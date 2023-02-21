<?php

use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers HashBagOStuff
 * @covers MediumSpecificBagOStuff
 * @covers BagOStuff
 * @group BagOStuff
 */
class HashBagOStuffTest extends PHPUnit\Framework\TestCase {
	use MediaWikiCoversValidator;

	public function testConstruct() {
		$this->assertInstanceOf(
			HashBagOStuff::class,
			new HashBagOStuff()
		);
	}

	public function testQoS() {
		$bag = new HashBagOStuff();

		$this->assertSame(
			BagOStuff::QOS_DURABILITY_SCRIPT,
			$bag->getQoS( BagOStuff::ATTR_DURABILITY )
		);
	}

	public function testConstructBadZero() {
		$this->expectException( InvalidArgumentException::class );
		$cache = new HashBagOStuff( [ 'maxKeys' => 0 ] );
	}

	public function testConstructBadNeg() {
		$this->expectException( InvalidArgumentException::class );
		$cache = new HashBagOStuff( [ 'maxKeys' => -1 ] );
	}

	public function testConstructBadType() {
		$this->expectException( InvalidArgumentException::class );
		$cache = new HashBagOStuff( [ 'maxKeys' => 'x' ] );
	}

	public function testDelete() {
		$cache = new HashBagOStuff();
		for ( $i = 0; $i < 10; $i++ ) {
			$cache->set( "key$i", 1 );
			$this->assertSame( 1, $cache->get( "key$i" ) );
			$cache->delete( "key$i" );
			$this->assertFalse( $cache->get( "key$i" ) );
		}
	}

	public function testClear() {
		$cache = new HashBagOStuff();
		for ( $i = 0; $i < 10; $i++ ) {
			$cache->set( "key$i", 1 );
			$this->assertSame( 1, $cache->get( "key$i" ) );
		}
		$cache->clear();
		for ( $i = 0; $i < 10; $i++ ) {
			$this->assertFalse( $cache->get( "key$i" ) );
		}
	}

	public function testExpire() {
		$cache = new HashBagOStuff();
		$cacheInternal = TestingAccessWrapper::newFromObject( $cache );
		$cache->set( 'foo', 1 );
		$cache->set( 'bar', 1, 10 );
		$cache->set( 'baz', 1, -10 );

		$this->assertSame( 0, $cacheInternal->bag['foo'][$cache::KEY_EXP], 'Indefinite' );
		// 2 seconds tolerance
		$this->assertEqualsWithDelta(
			time() + 10,
			$cacheInternal->bag['bar'][$cache::KEY_EXP],
			2,
			'Future'
		);
		$this->assertEqualsWithDelta(
			time() - 10,
			$cacheInternal->bag['baz'][$cache::KEY_EXP],
			2,
			'Past'
		);

		$this->assertSame( 1, $cache->get( 'bar' ), 'Key not expired' );
		$this->assertFalse( $cache->get( 'baz' ), 'Key expired' );
	}

	/**
	 * Ensure maxKeys eviction prefers keeping new keys.
	 */
	public function testEvictionAdd() {
		$cache = new HashBagOStuff( [ 'maxKeys' => 10 ] );
		for ( $i = 0; $i < 10; $i++ ) {
			$cache->set( "key$i", 1 );
			$this->assertSame( 1, $cache->get( "key$i" ) );
		}
		for ( $i = 10; $i < 20; $i++ ) {
			$cache->set( "key$i", 1 );
			$this->assertSame( 1, $cache->get( "key$i" ) );
			$this->assertFalse( $cache->get( "key" . ( $i - 10 ) ) );
		}
	}

	/**
	 * Ensure maxKeys eviction prefers recently set keys
	 * even if the keys pre-exist.
	 */
	public function testEvictionSet() {
		$cache = new HashBagOStuff( [ 'maxKeys' => 3 ] );

		foreach ( [ 'foo', 'bar', 'baz' ] as $key ) {
			$cache->set( $key, 1 );
		}

		// Set existing key
		$cache->set( 'foo', 1 );

		// Add a 4th key (beyond the allowed maximum)
		$cache->set( 'quux', 1 );

		// Foo's life should have been extended over Bar
		foreach ( [ 'foo', 'baz', 'quux' ] as $key ) {
			$this->assertSame( 1, $cache->get( $key ), "Kept $key" );
		}
		$this->assertFalse( $cache->get( 'bar' ), 'Evicted bar' );
	}

	/**
	 * Ensure maxKeys eviction prefers recently retrieved keys (LRU).
	 */
	public function testEvictionGet() {
		$cache = new HashBagOStuff( [ 'maxKeys' => 3 ] );

		foreach ( [ 'foo', 'bar', 'baz' ] as $key ) {
			$cache->set( $key, 1 );
		}

		// Get existing key
		$cache->get( 'foo', 1 );

		// Add a 4th key (beyond the allowed maximum)
		$cache->set( 'quux', 1 );

		// Foo's life should have been extended over Bar
		foreach ( [ 'foo', 'baz', 'quux' ] as $key ) {
			$this->assertSame( 1, $cache->get( $key ), "Kept $key" );
		}
		$this->assertFalse( $cache->get( 'bar' ), 'Evicted bar' );
	}

	/**
	 * Ensure updateOpStats doesn't get confused.
	 */
	public function testUpdateOpStats() {
		$counts = [];

		$stats = $this->createMock( StatsdDataFactoryInterface::class );
		$stats->method( 'updateCount' )->willReturnCallback(
			static function ( $name, $delta ) use ( &$counts ) {
				$counts[$name] = ( $counts[$name] ?? 0 ) + $delta;
			}
		);

		$cache = new HashBagOStuff( [
			'stats' => $stats
		] );
		$cache = TestingAccessWrapper::newFromObject( $cache );

		$cache->updateOpStats(
			'frob',
			[
				// The value is the key
				$cache->makeKey( 'Foo', '123456' ),

				// The value is a tuble of ( bytes sent, bytes received )
				$cache->makeGlobalKey( 'Bar', '123456' ) => [ 5, 3 ],

				// The key is not a proper key
				'1337BABE-123456' => [ 5, 3 ],
			]
		);

		$this->assertSame( 1, $counts['objectcache.Foo.frob_call_rate'] );
		$this->assertSame( 1, $counts['objectcache.Bar.frob_call_rate'] );
		$this->assertSame( 1, $counts['objectcache.UNKNOWN.frob_call_rate'] );

		$this->assertSame( 3, $counts['objectcache.Bar.frob_bytes_read'] );
		$this->assertSame( 5, $counts['objectcache.Bar.frob_bytes_sent'] );
		$this->assertSame( 3, $counts['objectcache.UNKNOWN.frob_bytes_read'] );
		$this->assertSame( 5, $counts['objectcache.UNKNOWN.frob_bytes_sent'] );
	}
}
