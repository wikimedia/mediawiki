<?php

namespace Wikimedia\Tests\WRStats;

use PHPUnit\Framework\TestCase;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\WRStats\BagOStuffStatsStore;
use Wikimedia\WRStats\EntityKey;
use Wikimedia\WRStats\GlobalEntityKey;
use Wikimedia\WRStats\LocalEntityKey;

/**
 * @covers \Wikimedia\WRStats\BagOStuffStatsStore
 */
class BagOStuffStatsStoreTest extends TestCase {

	/**
	 * @var HashBagOStuff
	 */
	private $cache;

	/**
	 * @var float
	 */
	private $mockTime = 1000000.0;

	public function setUp(): void {
		parent::setUp();
		$this->cache = new HashBagOStuff();
		$this->cache->setMockTime( $this->mockTime );
	}

	private function tickMockTime( $time ) {
		$this->mockTime += $time;
		$this->cache->setMockTime( $this->mockTime );
	}

	private function getStatsStore() {
		return new BagOStuffStatsStore( $this->cache );
	}

	public static function provideMakeKey() {
		yield [ [ 'prefix' ], [ 'internals' ], new LocalEntityKey( [ 'key' ] ), 'local:prefix:internals:key' ];
		yield [ [ 'prefix' ], [ 'internals' ], new GlobalEntityKey( [ 'key' ] ), 'global:prefix:internals:key' ];
		yield [ [ 'p', 'q' ], [ 'i', 'j' ], new GlobalEntityKey( [ 'k', 'h' ] ), 'global:p:q:i:j:k:h' ];
	}

	/**
	 * @param array $prefix
	 * @param array $internals
	 * @param EntityKey $entity
	 * @param string $expected
	 *
	 * @dataProvider provideMakeKey
	 */
	public function testMakeKey( $prefix, $internals, $entity, $expected ) {
		$store = $this->getStatsStore();
		$this->assertSame(
			$expected,
			$store->makeKey(
				$prefix,
				$internals,
				$entity
			)
		);
	}

	public function testIncrAndExpiry() {
		$store = $this->getStatsStore();

		$store->incr( [ 'a' => 1, 'b' => 2 ], 10 );

		$this->tickMockTime( 2 );

		$store->incr( [ 'b' => 1, 'c' => 1 ], 10 );

		$values = $store->query( [ 'a', 'b', 'c' ] );
		$this->assertSame( 1, $values['a'] );
		$this->assertSame( 3, $values['b'] );
		$this->assertSame( 1, $values['c'] );

		$this->tickMockTime( 9 );

		// The TTL is counted from the time the value was first set,
		// not the time it was last updated. So the entries
		// for a and b should have expired now.
		$values = $store->query( [ 'a', 'b', 'c' ] );
		$this->assertArrayNotHasKey( 'a', $values );
		$this->assertArrayNotHasKey( 'b', $values );
		$this->assertSame( 1, $values['c'] );
	}

	public function testDelete() {
		$store = $this->getStatsStore();

		$store->incr( [ 'a' => 1, 'b' => 2 ], 10 );

		$store->delete( [ 'b' ] );

		$values = $store->query( [ 'a', 'b' ] );
		$this->assertSame( 1, $values['a'] );
		$this->assertArrayNotHasKey( 'b', $values );
	}

}
