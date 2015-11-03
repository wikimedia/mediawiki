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
}
