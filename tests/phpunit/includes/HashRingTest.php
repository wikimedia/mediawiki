<?php

/**
 * @group HashRing
 */
class HashRingTest extends MediaWikiTestCase {
	public function testHashRing() {
		$ring = new HashRing( array( 's1' => 1, 's2' => 1, 's3' => 2, 's4' => 2, 's5' => 2, 's6' => 3 ) );

		$locations = array();
		for ( $i = 0; $i < 20; $i++ ) {
			$locations[ "hello$i"] = $ring->getLocation( "hello$i" );
		}
		$expectedLocations = array(
			"hello0" => "s5",
			"hello1" => "s6",
			"hello2" => "s2",
			"hello3" => "s5",
			"hello4" => "s6",
			"hello5" => "s4",
			"hello6" => "s5",
			"hello7" => "s4",
			"hello8" => "s5",
			"hello9" => "s5",
			"hello10" => "s3",
			"hello11" => "s6",
			"hello12" => "s1",
			"hello13" => "s3",
			"hello14" => "s3",
			"hello15" => "s5",
			"hello16" => "s4",
			"hello17" => "s6",
			"hello18" => "s6",
			"hello19" => "s3"
		);

		$this->assertEquals( $expectedLocations, $locations, 'Items placed at proper locations' );

		$locations = array();
		for ( $i = 0; $i < 5; $i++ ) {
			$locations[ "hello$i"] = $ring->getLocations( "hello$i", 2 );
		}

		$expectedLocations = array(
			"hello0" => array( "s5", "s6" ),
			"hello1" => array( "s6", "s4" ),
			"hello2" => array( "s2", "s1" ),
			"hello3" => array( "s5", "s6" ),
			"hello4" => array( "s6", "s4" ),
		);
		$this->assertEquals( $expectedLocations, $locations, 'Items placed at proper locations' );
	}
}
