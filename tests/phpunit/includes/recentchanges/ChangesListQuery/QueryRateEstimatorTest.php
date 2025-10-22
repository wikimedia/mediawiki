<?php

namespace MediaWiki\Tests\RecentChanges\ChangesListQuery;

use MediaWiki\RecentChanges\ChangesListQuery\QueryRateEstimator;
use Wikimedia\ObjectCache\HashBagOStuff;

/**
 * @covers \MediaWiki\RecentChanges\ChangesListQuery\QueryRateEstimator
 */
class QueryRateEstimatorTest extends \MediaWikiIntegrationTestCase {
	/** @var float|int */
	private $now = 3.5 * 86_400;

	/**
	 * @return array{QueryRateEstimator, HashBagOStuff}
	 */
	private function createQueryRateEstimator() {
		$cache = new HashBagOStuff;
		$cache->setMockTime( $this->now );
		$qre = new QueryRateEstimator(
			$cache,
			30 * 86_400,
			'test'
		);
		return [ $qre, $cache ];
	}

	/**
	 * @param HashBagOStuff $cache
	 * @param int[] $buckets
	 * @return string[]
	 */
	private function makeKeys( $cache, $buckets ) {
		$res = [];
		foreach ( $buckets as $b ) {
			$res[] = $cache->makeKey( 'ChangesListQueryRate', 'test', $b );
		}
		return $res;
	}

	public function testFetchRateStoreRate() {
		[ $qre, $cache ] = $this->createQueryRateEstimator();
		$start = 0;
		$day = 86_400;
		$end = $this->now;
		$bucketPeriod = $qre->getBucketPeriod();
		// Just an assumption of the test, it's not an interface requirement
		$this->assertSame( $day, $bucketPeriod );

		// Empty state
		$rate = $qre->fetchRate( $start, $end );
		$this->assertNull( $rate );

		// Observe some results
		$qre->storeCounts( [ 2 => 100, 3 => 50 ], 2 * $day, $end );

		// It should have stored results for buckets 2 and 3
		$keys = $this->makeKeys( $cache, [ 2, 3 ] );
		$res = $cache->getMulti( $keys );
		$expected = [ $keys[0] => 100, $keys[1] => 50 ];
		$this->assertSame( $expected, $res );

		// Now we have a non-null rate estimate
		$rate = $qre->fetchRate( $start, $end );
		// There's 150 over 1.5 days that we know of
		$this->assertEqualsWithDelta( 150 / 1.5 / $day, $rate, 1e-9 );

		// We found some rows from day 1 now
		$qre->storeCounts( [ 1 => 100, 2 => 100, 3 => 50 ], 2 * $day, $end );
		// Now there's 250 over 2.5 days
		$rate = $qre->fetchRate( $start, $end );
		$this->assertEqualsWithDelta( 250 / 2.5 / $day, $rate, 1e-9 );

		// Some more changes happened
		$this->now += 0.25 * $day;
		$end = $this->now;
		$qre->storeCounts( [ 2 => 0, 3 => 75 ], 2 * $day, $end );
		// Counts only go up, they don't go down, so now we have 275 over 2.75 days
		$rate = $qre->fetchRate( $start, $end );
		$this->assertEqualsWithDelta( 275 / 2.75 / $day, $rate, 1e-9 );
	}
}
