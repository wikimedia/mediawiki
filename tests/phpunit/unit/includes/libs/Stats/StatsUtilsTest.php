<?php

namespace Wikimedia\Tests\Stats;

use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;
use Wikimedia\Stats\StatsUtils;

/**
 * @covers \Wikimedia\Stats\StatsUtils
 */
class StatsUtilsTest extends TestCase {
	use MediaWikiCoversValidator;

	/**
	 * @dataProvider provideMakeBucketsFromMean
	 */
	public function testMakeBucketsFromMean( $mean, $skip, $expected ) {
		$actual = StatsUtils::makeBucketsFromMean( $mean, $skip );
		$this->assertEquals( $expected, $actual );
	}

	public function provideMakeBucketsFromMean() {
		yield 'Skip = 1' => [
			11, 1,
			[ 4.7, 5.6, 6.8, 8.2, 10, 12, 15, 18, 22 ]
		];
		yield 'Skip = 4 (default)' => [
			11, 4,
			[ 0.47, 1, 2.2, 4.7, 10, 22, 47, 100, 220 ]
		];
		yield 'Skip = 12' => [
			11, 12,
			[ 0.001, 0.01, 0.1, 1, 10, 100, 1000, 10000, 100000 ]
		];
		yield 'Large mean, skip = 4' => [
			2345, 4,
			[ 100, 220, 470, 1000, 2200, 4700, 10000, 22000, 47000 ]
		];
		yield "Shifting mean slightly doesn't affect buckets, skip = 4" => [
			3456, 4,
			[ 100, 220, 470, 1000, 2200, 4700, 10000, 22000, 47000 ]
		];
		yield "Shifting mean more only slightly affects buckets, skip = 4" => [
			5678, 4,
			[ 220, 470, 1000, 2200, 4700, 10000, 22000, 47000, 100000 ]
		];
		yield "Small mean, skip = 3" => [
			0.1234, 3,
			[ 0.018, 0.033, 0.056, 0.1, 0.18, 0.33, 0.56, 1.0, 1.8 ]
		];
		// Multiplying/dividing the mean by a small factor keeps most buckets
		yield "Small mean, skip = 6" => [
			0.1234, 6,
			[ 0.0033, 0.01, 0.033, 0.1, 0.33, 1.0, 3.3, 10, 33 ]
		];
		yield "Smaller mean, skip = 1" => [
			0.01234, 1,
			[ 0.0056, 0.0068, 0.0082, 0.01, 0.012, 0.015, 0.018, 0.022, 0.027 ]
		];
	}
}
