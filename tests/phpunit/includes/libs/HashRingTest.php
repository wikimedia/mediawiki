<?php

/**
 * @group HashRing
 */
class HashRingTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	/**
	 * @covers HashRing
	 */
	public function testHashRing() {
		$ring = new HashRing( [ 's1' => 1, 's2' => 1, 's3' => 2, 's4' => 2, 's5' => 2, 's6' => 3 ] );

		$locations = [];
		for ( $i = 0; $i < 20; $i++ ) {
			$locations[ "hello$i"] = $ring->getLocation( "hello$i" );
		}
		$expectedLocations = [
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
		];

		$this->assertEquals( $expectedLocations, $locations, 'Items placed at proper locations' );

		$locations = [];
		for ( $i = 0; $i < 5; $i++ ) {
			$locations[ "hello$i"] = $ring->getLocations( "hello$i", 2 );
		}

		$expectedLocations = [
			"hello0" => [ "s5", "s6" ],
			"hello1" => [ "s6", "s4" ],
			"hello2" => [ "s2", "s1" ],
			"hello3" => [ "s5", "s6" ],
			"hello4" => [ "s6", "s4" ],
		];
		$this->assertEquals( $expectedLocations, $locations, 'Items placed at proper locations' );
	}

	/**
	 * @covers HashRing
	 */
	public function testHashRing2() {
		$ring = new HashRing(
			[ 's1' => 1, 's2' => 1, 's3' => 2, 's4' => 2, 's5' => 2, 's6' => 3 ],
			HashRing::TYPE_CONSISTENT
		);

		$locations = [];
		for ( $i = 0; $i < 25; $i++ ) {
			$locations[ "hello$i"] = $ring->getLocation( "hello$i" );
		}
		$expectedLocations = [
			"hello0" => "s2",
			"hello1" => "s4",
			"hello2" => "s6",
			"hello3" => "s2",
			"hello4" => "s4",
			"hello5" => "s3",
			"hello6" => "s2",
			"hello7" => "s3",
			"hello8" => "s2",
			"hello9" => "s2",
			"hello10" => "s3",
			"hello11" => "s4",
			"hello12" => "s6",
			"hello13" => "s6",
			"hello14" => "s6",
			"hello15" => "s2",
			"hello16" => "s3",
			"hello17" => "s2",
			"hello18" => "s4",
			"hello19" => "s6",
			"hello20" => "s2",
			"hello21" => "s1",
			"hello22" => "s6",
			"hello23" => "s6",
			"hello24" => "s3"
		];

		$this->assertEquals( $expectedLocations, $locations, 'Items placed at proper locations' );

		$locations = [];
		for ( $i = 0; $i < 5; $i++ ) {
			$locations[ "hello$i"] = $ring->getLocations( "hello$i", 2 );
		}

		$expectedLocations = [
			"hello0" => [ "s2", "s3" ],
			"hello1" => [ "s4", "s5" ],
			"hello2" => [ "s6", "s5" ],
			"hello3" => [ "s2", "s3" ],
			"hello4" => [ "s4", "s5" ],
		];
		$this->assertEquals( $expectedLocations, $locations, 'Items placed at proper locations' );
	}
}
