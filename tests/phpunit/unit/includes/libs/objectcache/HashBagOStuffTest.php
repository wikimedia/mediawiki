<?php

namespace Wikimedia\Tests\ObjectCache;

use InvalidArgumentException;
use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \Wikimedia\ObjectCache\HashBagOStuff
 * @covers \Wikimedia\ObjectCache\MediumSpecificBagOStuff
 * @covers \Wikimedia\ObjectCache\BagOStuff
 * @group BagOStuff
 */
class HashBagOStuffTest extends TestCase {
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
		$statsHelper = StatsFactory::newUnitTestingHelper();
		$cache = new HashBagOStuff( [
			'stats' => $statsHelper->getStatsFactory(),
		] );
		$cache = TestingAccessWrapper::newFromObject( $cache );
		$cache->updateOpStats(
			'frob',
			[
				// The value is the key
				$cache->makeKey( 'Foo', '123456' ),

				// The value is a tuple of ( bytes sent, bytes received )
				$cache->makeGlobalKey( 'Bar', '123456' ) => [ 5, 3 ],

				// The key is not a proper key
				'1337BABE-123456' => [ 5, 3 ],
			]
		);

		$this->assertSame(
			[
				'mediawiki.bagostuff_call_total:1|c|#keygroup:Foo,operation:frob',
				'mediawiki.bagostuff_call_total:1|c|#keygroup:Bar,operation:frob',
				'mediawiki.bagostuff_call_total:1|c|#keygroup:UNKNOWN,operation:frob',
				'mediawiki.bagostuff_bytes_sent_total:5|c|#keygroup:Bar,operation:frob',
				'mediawiki.bagostuff_bytes_sent_total:5|c|#keygroup:UNKNOWN,operation:frob',
				'mediawiki.bagostuff_bytes_read_total:3|c|#keygroup:Bar,operation:frob',
				'mediawiki.bagostuff_bytes_read_total:3|c|#keygroup:UNKNOWN,operation:frob',
			],
			$statsHelper->consumeAllFormatted()
		);
	}
}
