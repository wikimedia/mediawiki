<?php

/**
 * @group HashRing
 */
class HashRingTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	/**
	 * @covers HashRing
	 */
	public function testHashRingSimple1() {
		$map = [ 's1' => 3, 's2' => 10, 's3' => 2, 's4' => 10, 's5' => 2, 's6' => 3 ];
		$ring = new HashRing( $map, 'md5' );
		$serialized = serialize( $ring );
		$ringRemade = unserialize( $serialized );

		$expectedLocations = [
			"hello0" => "s4",
			"hello1" => "s4",
			"hello2" => "s2",
			"hello3" => "s6",
			"hello4" => "s4",
			"hello5" => "s5"
		];

		foreach ( [ $ring, $ringRemade ] as $testRing ) {
			$locations = [];
			for ( $i = 0; $i < 6; $i++ ) {
				$locations["hello$i"] = $testRing->getLocation( "hello$i" );
			}
			$this->assertEquals(
				$expectedLocations, $locations, 'Items placed at proper locations' );
		}
	}

	/**
	 * @covers HashRing
	 */
	public function testHashRingSimple2() {
		$map = [ 's1' => 3, 's2' => 10, 's3' => 2, 's4' => 10, 's5' => 2, 's6' => 3 ];
		$ring = HashRing::newConsistent( $map, 'sha1' );

		$locations = [];
		for ( $i = 0; $i <= 5; $i++ ) {
			$locations[ "hello$i"] = $ring->getLocations( "hello$i", 2 );
		}

		$expectedLocations = [
			"hello0" => [ "s2", "s4" ],
			"hello1" => [ "s4", "s5" ],
			"hello2" => [ "s2", "s5" ],
			"hello3" => [ "s1", "s4" ],
			"hello4" => [ "s4", "s5" ],
			"hello5" => [ "s3", "s2" ],
		];
		$this->assertEquals( $expectedLocations, $locations, 'Items placed at proper locations' );

		$this->assertEquals( $map, $ring->getLocationWeights(), 'Normalized location weights' );

		$ring = HashRing::newConsistent( [ 'x' => 200, 'y' => 0 ] );
		$this->assertEquals(
			[ 'x' => 100 ], $ring->getLocationWeights(), 'Normalized location weights' );
	}

	/**
	 * @covers HashRing
	 */
	public function testHashRing2() {
		// SHA-1 based and weighted
		$ring = new HashRing(
			[ 's1' => 1, 's2' => 1, 's3' => 2, 's4' => 2, 's5' => 2, 's6' => 3 ],
			'sha1'
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

	/**
	 * @covers HashRing
	 * @dataProvider providor_getHashLocationWeights
	 */
	public function testHashRingRatios( $locations, $expected ) {
		$ring = new HashRing( $locations, 'whirlpool' );

		$locationStats = array_fill_keys( array_keys( $locations ), 0 );
		for ( $i=0; $i < 2000; ++$i ) {
			++$locationStats[$ring->getLocation( "key-$i" )];
		}
		$this->assertEquals( $expected, $locationStats );
	}

	public static function providor_getHashLocationWeights() {
		return [
			[
				[ 'big' => 10, 'medium' => 5, 'small' => 1 ],
				[ 'big' => 887, 'medium' => 879, 'small' => 234 ]
			],
			[
				[ 'big' => 10, 'small1' => 1, 'small2' => 1 ],
				[ 'big' => 850, 'small1' => 646, 'small2' => 504 ]
			],
			[
				[ 'big1' => 10, 'small' => 1, 'big2' => 10 ],
				[ 'big1' => 672, 'small' => 64, 'big2' => 1264 ]
			]
		];
	}
	/**
	 * @covers HashRing
	 * @dataProvider providor_getHashLocationWeights2
	 */
	public function testHashRingRatios2( $locations, $expected ) {
		$ring = new HashRing( $locations, 'sha1' );
		$locationStats = array_fill_keys( array_keys( $locations ), 0 );
		for ( $i=0; $i < 1000; ++$i ) {
			foreach ( $ring->getLocations( "key-$i", 3 ) as $location ) {
				++$locationStats[$location];
			}
		}
		$this->assertEquals( $expected, $locationStats );
	}

	public static function providor_getHashLocationWeights2() {
		return [
			[
				[ 'big1' => 10, 'big2' => 10, 'big3' => 10, 'small1' => 1, 'small2' => 1 ],
				[ 'big1' => 938, 'big2' => 824, 'big3' => 998, 'small1' => 180, 'small2' => 60 ]
			],
			[
				[ 'big1' => 10, 'big2' => 10, 'small1' => 1, 'small2' => 1 ],
				[ 'big1' => 1000, 'big2' => 830, 'small1' => 250, 'small2' => 920 ]
			]
		];
	}
}
