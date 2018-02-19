<?php
/**
 * @group Cache
 */
class MapCacheLRUTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	/**
	 * @covers MapCacheLRU::newFromArray()
	 * @covers MapCacheLRU::toArray()
	 * @covers MapCacheLRU::getAllKeys()
	 * @covers MapCacheLRU::clear()
	 */
	function testArrayConversion() {
		$raw = [ 'd' => 4, 'c' => 3, 'b' => 2, 'a' => 1 ];
		$cache = MapCacheLRU::newFromArray( $raw, 3 );

		$this->assertSame( true, $cache->has( 'a' ) );
		$this->assertSame( true, $cache->has( 'b' ) );
		$this->assertSame( true, $cache->has( 'c' ) );
		$this->assertSame( 1, $cache->get( 'a' ) );
		$this->assertSame( 2, $cache->get( 'b' ) );
		$this->assertSame( 3, $cache->get( 'c' ) );

		$this->assertSame(
			[ 'a' => 1, 'b' => 2, 'c' => 3 ],
			$cache->toArray()
		);
		$this->assertSame(
			[ 'a', 'b', 'c' ],
			$cache->getAllKeys()
		);

		$cache->clear( 'a' );
		$this->assertSame(
			[ 'b' => 2, 'c' => 3 ],
			$cache->toArray()
		);

		$cache->clear();
		$this->assertSame(
			[],
			$cache->toArray()
		);
	}

	/**
	 * @covers MapCacheLRU::has()
	 * @covers MapCacheLRU::get()
	 * @covers MapCacheLRU::set()
	 */
	function testLRU() {
		$raw = [ 'a' => 1, 'b' => 2, 'c' => 3 ];
		$cache = MapCacheLRU::newFromArray( $raw, 3 );

		$this->assertSame( true, $cache->has( 'c' ) );
		$this->assertSame(
			[ 'a' => 1, 'b' => 2, 'c' => 3 ],
			$cache->toArray()
		);

		$this->assertSame( 3, $cache->get( 'c' ) );
		$this->assertSame(
			[ 'a' => 1, 'b' => 2, 'c' => 3 ],
			$cache->toArray()
		);

		$this->assertSame( 1, $cache->get( 'a' ) );
		$this->assertSame(
			[ 'b' => 2, 'c' => 3, 'a' => 1 ],
			$cache->toArray()
		);

		$cache->set( 'a', 1 );
		$this->assertSame(
			[ 'b' => 2, 'c' => 3, 'a' => 1 ],
			$cache->toArray()
		);

		$cache->set( 'b', 22 );
		$this->assertSame(
			[ 'c' => 3, 'a' => 1, 'b' => 22 ],
			$cache->toArray()
		);

		$cache->set( 'd', 4 );
		$this->assertSame(
			[ 'a' => 1, 'b' => 22, 'd' => 4 ],
			$cache->toArray()
		);

		$cache->set( 'e', 5, 0.33 );
		$this->assertSame(
			[ 'e' => 5, 'b' => 22, 'd' => 4 ],
			$cache->toArray()
		);

		$cache->set( 'f', 6, 0.66 );
		$this->assertSame(
			[ 'b' => 22, 'f' => 6, 'd' => 4 ],
			$cache->toArray()
		);

		$cache->set( 'g', 7, 0.90 );
		$this->assertSame(
			[ 'f' => 6, 'g' => 7, 'd' => 4 ],
			$cache->toArray()
		);

		$cache->set( 'g', 7, 1.0 );
		$this->assertSame(
			[ 'f' => 6, 'd' => 4, 'g' => 7 ],
			$cache->toArray()
		);
	}
}
