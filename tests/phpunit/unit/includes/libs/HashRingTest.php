<?php

/**
 * @group HashRing
 * @covers HashRing
 */
class HashRingTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	public function testHashRingSerialize() {
		$map = [ 's1' => 3, 's2' => 10, 's3' => 2, 's4' => 10, 's5' => 2, 's6' => 3 ];
		$ring = new HashRing( $map, 'md5' );

		$serialized = serialize( $ring );
		$ringRemade = unserialize( $serialized );

		for ( $i = 0; $i < 100; $i++ ) {
			$this->assertEquals(
				$ring->getLocation( "hello$i" ),
				$ringRemade->getLocation( "hello$i" ),
				'Items placed at proper locations'
			);
		}
	}

	public function testHashRingSingleLocation() {
		// SHA-1 based and weighted
		$ring = new HashRing( [ 's1' => 1 ], 'sha1' );

		$this->assertEquals(
			[ 's1' => 1 ],
			$ring->getLocationWeights(),
			'Normalized location weights'
		);

		for ( $i = 0; $i < 5; $i++ ) {
			$this->assertEquals(
				's1',
				$ring->getLocation( "hello$i" ),
				'Items placed at proper locations'
			);
			$this->assertEquals(
				[ 's1' ],
				$ring->getLocations( "hello$i", 2 ),
				'Items placed at proper locations'
			);
		}

		$this->assertEquals( [], $ring->getLocations( "helloX", 0 ), "Limit of 0" );
	}

	public function testHashRingMapping() {
		// SHA-1 based and weighted
		$ring = new HashRing(
			[ 's1' => 1, 's2' => 1, 's3' => 2, 's4' => 2, 's5' => 2, 's6' => 3, 's7' => 0 ],
			'sha1'
		);

		$this->assertEquals(
			[ 's1' => 1, 's2' => 1, 's3' => 2, 's4' => 2, 's5' => 2, 's6' => 3 ],
			$ring->getLocationWeights(),
			'Normalized location weights'
		);

		$locations = [];
		for ( $i = 0; $i < 25; $i++ ) {
			$locations[ "hello$i"] = $ring->getLocation( "hello$i" );
		}
		$expectedLocations = [
			"hello0" => "s4",
			"hello1" => "s6",
			"hello2" => "s3",
			"hello3" => "s6",
			"hello4" => "s6",
			"hello5" => "s4",
			"hello6" => "s3",
			"hello7" => "s4",
			"hello8" => "s3",
			"hello9" => "s3",
			"hello10" => "s3",
			"hello11" => "s5",
			"hello12" => "s4",
			"hello13" => "s5",
			"hello14" => "s2",
			"hello15" => "s5",
			"hello16" => "s6",
			"hello17" => "s5",
			"hello18" => "s1",
			"hello19" => "s1",
			"hello20" => "s6",
			"hello21" => "s5",
			"hello22" => "s3",
			"hello23" => "s4",
			"hello24" => "s1"
		];
		$this->assertEquals( $expectedLocations, $locations, 'Items placed at proper locations' );

		$locations = [];
		for ( $i = 0; $i < 5; $i++ ) {
			$locations[ "hello$i"] = $ring->getLocations( "hello$i", 2 );
		}

		$expectedLocations = [
			"hello0" => [ "s4", "s5" ],
			"hello1" => [ "s6", "s5" ],
			"hello2" => [ "s3", "s1" ],
			"hello3" => [ "s6", "s5" ],
			"hello4" => [ "s6", "s3" ],
		];
		$this->assertEquals( $expectedLocations, $locations, 'Items placed at proper locations' );
	}

	/**
	 * @dataProvider providor_getHashLocationWeights
	 */
	public function testHashRingRatios( $locations, $expectedHits ) {
		$ring = new HashRing( $locations, 'whirlpool' );

		$locationStats = array_fill_keys( array_keys( $locations ), 0 );
		for ( $i = 0; $i < 10000; ++$i ) {
			++$locationStats[$ring->getLocation( "key-$i" )];
		}
		$this->assertEquals( $expectedHits, $locationStats );
	}

	public static function providor_getHashLocationWeights() {
		return [
			[
				[ 'big' => 10, 'medium' => 5, 'small' => 1 ],
				[ 'big' => 6037, 'medium' => 3314, 'small' => 649 ]
			]
		];
	}

	/**
	 * @dataProvider providor_getHashLocationWeights2
	 */
	public function testHashRingRatios2( $locations, $expected ) {
		$ring = new HashRing( $locations, 'sha1' );
		$locationStats = array_fill_keys( array_keys( $locations ), 0 );
		for ( $i = 0; $i < 1000; ++$i ) {
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
				[ 'big1' => 929, 'big2' => 899, 'big3' => 887, 'small1' => 143, 'small2' => 142 ]
			]
		];
	}

	public function testHashRingEjection() {
		$map = [ 's1' => 5, 's2' => 5, 's3' => 10, 's4' => 10, 's5' => 5, 's6' => 5 ];
		$ring = new HashRing( $map, 'md5' );

		$ring->ejectFromLiveRing( 's3', 30 );
		$ring->ejectFromLiveRing( 's6', 15 );

		$this->assertEquals(
			[ 's1' => 5, 's2' => 5, 's4' => 10, 's5' => 5 ],
			$ring->getLiveLocationWeights(),
			'Live location weights'
		);

		for ( $i = 0; $i < 100; ++$i ) {
			$key = "key-$i";

			$this->assertNotEquals( 's3', $ring->getLiveLocation( $key ), 'ejected' );
			$this->assertNotEquals( 's6', $ring->getLiveLocation( $key ), 'ejected' );

			if ( !in_array( $ring->getLocation( $key ), [ 's3', 's6' ], true ) ) {
				$this->assertEquals(
					$ring->getLocation( $key ),
					$ring->getLiveLocation( $key ),
					"Live ring otherwise matches (#$i)"
				);
				$this->assertEquals(
					$ring->getLocations( $key, 1 ),
					$ring->getLiveLocations( $key, 1 ),
					"Live ring otherwise matches (#$i)"
				);
			}
		}
	}

	public function testHashRingCollision() {
		$ring1 = new HashRing( [ 0 => 1, 6497 => 1 ] );
		$ring2 = new HashRing( [ 6497 => 1, 0 => 1 ] );

		for ( $i = 0; $i < 100; ++$i ) {
			$this->assertEquals( $ring1->getLocation( $i ), $ring2->getLocation( $i ) );
		}
	}

	public function testHashRingKetamaMode() {
		// Same as https://github.com/RJ/ketama/blob/master/ketama.servers
		$map = [
			'10.0.1.1:11211' => 600,
			'10.0.1.2:11211' => 300,
			'10.0.1.3:11211' => 200,
			'10.0.1.4:11211' => 350,
			'10.0.1.5:11211' => 1000,
			'10.0.1.6:11211' => 800,
			'10.0.1.7:11211' => 950,
			'10.0.1.8:11211' => 100
		];
		$ring = new HashRing( $map, 'md5' );
		$wrapper = \Wikimedia\TestingAccessWrapper::newFromObject( $ring );

		$ketama_test = function ( $count ) use ( $wrapper ) {
			$baseRing = $wrapper->baseRing;

			$lines = [];
			for ( $key = 0; $key < $count; ++$key ) {
				$location = $wrapper->getLocation( $key );

				$itemPos = $wrapper->getItemPosition( $key );
				$nodeIndex = $wrapper->findNodeIndexForPosition( $itemPos, $baseRing );
				$nodePos = $baseRing[$nodeIndex][HashRing::KEY_POS];

				$lines[] = sprintf( "%u %u %s\n", $itemPos, $nodePos, $location );
			}

			return "\n" . implode( '', $lines );
		};

		// Known correct values generated from C code:
		// https://github.com/RJ/ketama/blob/master/libketama/ketama_test.c
		$expected = <<<EOT

2216742351 2217271743 10.0.1.1:11211
943901380 949045552 10.0.1.5:11211
2373066440 2374693370 10.0.1.6:11211
2127088620 2130338203 10.0.1.6:11211
2046197672 2051996197 10.0.1.7:11211
2134629092 2135172435 10.0.1.1:11211
470382870 472541453 10.0.1.7:11211
1608782991 1609789509 10.0.1.3:11211
2516119753 2520092206 10.0.1.2:11211
3465331781 3466294492 10.0.1.4:11211
1749342675 1753760600 10.0.1.5:11211
1136464485 1137779711 10.0.1.1:11211
3620997826 3621580689 10.0.1.7:11211
283385029 285581365 10.0.1.6:11211
2300818346 2302165654 10.0.1.5:11211
2132603803 2134614475 10.0.1.8:11211
2962705863 2969767984 10.0.1.2:11211
786427760 786565633 10.0.1.5:11211
4095887727 4096760944 10.0.1.6:11211
2906459679 2906987515 10.0.1.6:11211
137884056 138922607 10.0.1.4:11211
81549628 82491298 10.0.1.6:11211
3530020790 3530525869 10.0.1.6:11211
4231817527 4234960467 10.0.1.7:11211
2011099423 2014738083 10.0.1.7:11211
107620750 120968799 10.0.1.6:11211
3979113294 3981926993 10.0.1.4:11211
273671938 276355738 10.0.1.4:11211
4032816947 4033300359 10.0.1.5:11211
464234862 466093615 10.0.1.1:11211
3007059764 3007671127 10.0.1.5:11211
542337729 542491760 10.0.1.7:11211
4040385635 4044064727 10.0.1.5:11211
3319802648 3320661601 10.0.1.7:11211
1032153571 1035085391 10.0.1.1:11211
3543939100 3545608820 10.0.1.5:11211
3876899353 3885324049 10.0.1.2:11211
3771318181 3773259708 10.0.1.8:11211
3457906597 3459285639 10.0.1.5:11211
3028975062 3031083168 10.0.1.7:11211
244467158 250943416 10.0.1.5:11211
1604785716 1609789509 10.0.1.3:11211
3905343649 3905751132 10.0.1.1:11211
1713497623 1725056963 10.0.1.5:11211
1668356087 1668827816 10.0.1.5:11211
3427369836 3438933308 10.0.1.1:11211
2515850457 2520092206 10.0.1.2:11211
3886138983 3887390208 10.0.1.1:11211
4019334756 4023153300 10.0.1.8:11211
1170561012 1170785765 10.0.1.7:11211
1841809344 1848425105 10.0.1.6:11211
973223976 973369204 10.0.1.1:11211
358093210 359562433 10.0.1.6:11211
378350808 380841931 10.0.1.5:11211
4008477862 4012085095 10.0.1.7:11211
1027226549 1028630030 10.0.1.6:11211
2386583967 2387706118 10.0.1.1:11211
522892146 524831677 10.0.1.7:11211
3779194982 3788912803 10.0.1.5:11211
3764731657 3771312500 10.0.1.7:11211
184756999 187529415 10.0.1.6:11211
838351231 845886003 10.0.1.3:11211
2827220548 2828019973 10.0.1.6:11211
3604721411 3607668249 10.0.1.6:11211
472866282 475506254 10.0.1.5:11211
2752268796 2754833471 10.0.1.5:11211
1791464754 1795042583 10.0.1.7:11211
3029359475 3031083168 10.0.1.7:11211
3633378211 3639985542 10.0.1.6:11211
3148267284 3149217023 10.0.1.6:11211
163887996 166705043 10.0.1.7:11211
3642803426 3649125922 10.0.1.7:11211
3901799218 3902199881 10.0.1.7:11211
418045394 425867331 10.0.1.6:11211
346775981 348578169 10.0.1.6:11211
368352208 372224616 10.0.1.7:11211
2643711995 2644259911 10.0.1.5:11211
2032983336 2033860601 10.0.1.6:11211
3567842357 3572867530 10.0.1.2:11211
1024982737 1028630030 10.0.1.6:11211
933966832 938106828 10.0.1.7:11211
2102520899 2103402846 10.0.1.7:11211
3537205399 3538094881 10.0.1.7:11211
2311233534 2314593262 10.0.1.1:11211
2500514664 2503565236 10.0.1.7:11211
1091958846 1093484995 10.0.1.6:11211
3984972691 3987453644 10.0.1.1:11211
2669994439 2670911201 10.0.1.4:11211
2846111786 2846115813 10.0.1.5:11211
1805010806 1808593732 10.0.1.8:11211
1587024774 1587746378 10.0.1.5:11211
3214549588 3215619351 10.0.1.2:11211
1965214866 1970922428 10.0.1.7:11211
1038671000 1040777775 10.0.1.7:11211
820820468 823114475 10.0.1.6:11211
2722835329 2723166435 10.0.1.5:11211
1602053414 1604196066 10.0.1.5:11211
1330835426 1335097278 10.0.1.5:11211
556547565 557075710 10.0.1.4:11211
2977587884 2978402952 10.0.1.1:11211

EOT;

		$this->assertEquals( $expected, $ketama_test( 100 ), 'Ketama mode (diff check)' );

		// Hash of known correct values from C code
		$this->assertEquals(
			'd1a4912a80e4654ec2e4e462c8b911c6',
			md5( $ketama_test( 1e3 ) ),
			'Ketama mode (large, MD5 check)'
		);

		// Slower, full upstream MD5 check, manually verified 3/21/2018
		// $this->assertEquals( '5672b131391f5aa2b280936aec1eea74', md5( $ketama_test( 1e6 ) ) );
	}
}
