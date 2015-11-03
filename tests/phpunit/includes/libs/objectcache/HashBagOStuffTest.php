<?php

/**
 * @group BagOStuff
 */
class HashBagOStuffTest extends PHPUnit_Framework_TestCase {

	public function testEvictionOrder() {
		$cache = new HashBagOStuff( array( 'maxKeys' => 10 ) );
		for ( $i = 0; $i < 10; $i++ ) {
			$cache->set( "key$i", 1 );
			$this->assertEquals( 1, $cache->get( "key$i" ) );
		}
		for ( $i = 10; $i < 20; $i++ ) {
			$cache->set( "key$i", 1 );
			$this->assertEquals( 1, $cache->get( "key$i" ) );
			$this->assertEquals( false, $cache->get( "key" . $i - 10 ) );
		}
	}

	public function testExpire() {
		$cache = new HashBagOStuff();
		$cacheInternal = TestingAccessWrapper::newFromObject( $cache );
		$cache->set( 'foo', 1 );
		$cache->set( 'bar', 1, 10 );
		$cache->set( 'baz', 1, -10 );

		$this->assertEquals( 0, $cacheInternal->bag['foo'][$cache::KEY_EXP], 'Indefinite' );
		// 2 seconds tolerance
		$this->assertEquals( time() + 10, $cacheInternal->bag['bar'][$cache::KEY_EXP], 'Future', 2 );
		$this->assertEquals( time() - 10, $cacheInternal->bag['baz'][$cache::KEY_EXP], 'Past', 2 );

		$this->assertEquals( 1, $cache->get( 'bar' ), 'Key not expired' );
		$this->assertEquals( false, $cache->get( 'baz' ), 'Key expired' );
	}

	public function testKeyOrder() {
		$cache = new HashBagOStuff( array( 'maxKeys' => 3 ) );

		foreach ( array( 'foo', 'bar', 'baz' ) as $key ) {
			$cache->set( $key, 1 );
		}

		// Set existing key
		$cache->set( 'foo', 1 );

		// Add a 4th key (beyond the allowed maximum)
		$cache->set( 'quux', 1 );

		// Foo's life should have been extended over Bar
		foreach ( array( 'foo', 'baz', 'quux' ) as $key ) {
			$this->assertEquals( 1, $cache->get( $key ), "Kept $key" );
		}
		$this->assertEquals( false, $cache->get( 'bar' ), 'Evicted bar' );
	}
}
