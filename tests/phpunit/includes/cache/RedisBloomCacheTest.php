<?php

/**
 * Test for BloomCacheRedis class.
 *
 * @TODO: some generic base "redis test server conf" for all testing?
 *
 * @covers BloomCacheRedis
 * @group Cache
 */
class BloomCacheRedisTest extends MediaWikiTestCase {
	private static $suffix;

	protected function setUp() {
		parent::setUp();

		self::$suffix = self::$suffix ? : mt_rand();

		$fcache = BloomCache::get( 'main' );
		if ( $fcache instanceof BloomCacheRedis ) {
			$fcache->delete( "unit-testing-" . self::$suffix );
		} else {
			$this->markTestSkipped( 'The main bloom cache is not redis.' );
		}
	}

	public function testBloomCache() {
		$key = "unit-testing-" . self::$suffix;
		$fcache = BloomCache::get( 'main' );
		$count = 1500;

		$this->assertTrue( $fcache->delete( $key ), "OK delete of filter '$key'." );
		$this->assertTrue( $fcache->init( $key, $count, .001 ), "OK init of filter '$key'." );

		$members = array();
		for ( $i = 0; $i < $count; ++$i ) {
			$members[] = "$i-value-$i";
		}
		$this->assertTrue( $fcache->add( $key, $members ), "Addition of members to '$key' OK." );

		for ( $i = 0; $i < $count; ++$i ) {
			$this->assertTrue( $fcache->isHit( $key, "$i-value-$i" ), "Hit on member '$i-value-$i'." );
		}

		$falsePositives = array();
		for ( $i = $count; $i < 2 * $count; ++$i ) {
			if ( $fcache->isHit( $key, "value$i" ) ) {
				$falsePositives[] = "value$i";
			}
		}

		$eFalsePositives = array(
			'value1763',
			'value2245',
			'value2353',
			'value2791',
			'value2898',
			'value2975'
		);
		$this->assertEquals( $eFalsePositives, $falsePositives, "Correct number of false positives found." );
	}

	protected function tearDown() {
		parent::tearDown();

		$fcache = BloomCache::get( 'main' );
		if ( $fcache instanceof BloomCacheRedis ) {
			$fcache->delete( "unit-testing-" . self::$suffix );
		}
	}
}
