<?php

namespace MediaWiki\Tests\Unit\Settings\Cache;

use DateInterval;
use Psr\SimpleCache\CacheInterface;

trait CacheInterfaceTestTrait {
	abstract protected function newCache(): CacheInterface;

	public function testSetAndGet() {
		$cache = $this->newCache();

		$cache->set( 'one', 'fish1' );
		$cache->set( 'two', 'fish2', 60 );
		$cache->set( 'red', 'fish3', DateInterval::createFromDateString( '@60' ) );

		$this->assertSame( 'fish1', $cache->get( 'one' ) );
		$this->assertSame( 'fish2', $cache->get( 'two' ) );
		$this->assertSame( 'fish3', $cache->get( 'red' ) );
		$this->assertSame( 'fish4', $cache->get( 'blue', 'fish4' ) );
	}

	public function testSetAndGetMultiple() {
		$cache = $this->newCache();

		$cache->setMultiple(
			[
				'one' => 'fish1',
				'two' => 'fish2',
				'red' => 'fish3',
			]
		);

		$this->assertSame(
			[
				'one' => 'fish1',
				'two' => 'fish2',
				'red' => 'fish3',
				'blue' => 'fish4',
			],
			$cache->getMultiple( [ 'one', 'two', 'red', 'blue' ], 'fish4' )
		);
	}

	public function testDeleteAndHas() {
		$cache = $this->newCache();

		$cache->set( 'red', 'fish' );
		$cache->set( 'blue', 'fish' );

		$cache->delete( 'red' );

		$this->assertFalse( $cache->has( 'red' ) );
		$this->assertTrue( $cache->has( 'blue' ) );
	}

	public function testDeleteMultiple() {
		$cache = $this->newCache();

		$cache->set( 'one', 'fish' );
		$cache->set( 'two', 'fish' );
		$cache->set( 'red', 'fish' );
		$cache->set( 'blue', 'fish' );

		$cache->deleteMultiple( [ 'two', 'red' ] );

		$this->assertTrue( $cache->has( 'one' ) );
		$this->assertFalse( $cache->has( 'two' ) );
		$this->assertFalse( $cache->has( 'red' ) );
		$this->assertTrue( $cache->has( 'blue' ) );
	}

	public function testClear() {
		$cache = $this->newCache();

		$cache->set( 'one', 'fish' );
		$cache->set( 'two', 'fish' );
		$cache->set( 'red', 'fish' );
		$cache->set( 'blue', 'fish' );

		$this->assertTrue( $cache->has( 'one' ) );
		$this->assertTrue( $cache->has( 'two' ) );
		$this->assertTrue( $cache->has( 'red' ) );
		$this->assertTrue( $cache->has( 'blue' ) );

		$cache->clear();

		$this->assertFalse( $cache->has( 'one' ) );
		$this->assertFalse( $cache->has( 'two' ) );
		$this->assertFalse( $cache->has( 'red' ) );
		$this->assertFalse( $cache->has( 'blue' ) );
	}
}
