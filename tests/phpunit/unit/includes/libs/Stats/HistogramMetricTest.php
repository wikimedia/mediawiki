<?php

namespace Wikimedia\Tests\Stats;

use PHPUnit\Framework\TestCase;
use Wikimedia\Stats\StatsFactory;

/**
 * @covers \Wikimedia\Stats\Metrics\HistogramMetric
 */
class HistogramMetricTest extends TestCase {

	private StatsFactory $statsFactory;
	private array $buckets;

	protected function setUp(): void {
		$this->statsFactory = StatsFactory::newNull();
		$this->buckets = [ 0.01, 0.1, 1, 10 ];
		$metric = $this->statsFactory->getHistogram( 'test', $this->buckets );
		$metric->setLabel( 'foo', 'bar' );
		$metric->observe( 0.08 );
		$metric->observe( 0.09 );
		$metric->observe( 0.9 );
		$metric->observe( 9 );
		$metric->observe( 19 );
	}

	/**
	 * Ensure all values summed match what we expect
	 */
	public function testSumValues() {
		$samples = $this->statsFactory->getCounter( 'test_sum' )->getSamples();
		self::assertEquals( 29.07, array_sum( self::getSampleValues( $samples ) ) );
	}

	/**
	 * Ensure first bucket samples are always zeros and the rest matches observations
	 */
	public function testBucketValues() {
		$samples = $this->statsFactory->getCounter( 'test_bucket' )->getSamples();
		$firstBuckets = array_slice( $samples, 0, count( $this->buckets ) + 1 );

		self::assertEquals( [
			'+Inf' => 0.0,
			'10' => 0.0,
			'1' => 0.0,
			'0.1' => 0.0,
			'0.01' => 0.0
		], self::sumBuckets( $firstBuckets ) );

		self::assertEquals( [
			'+Inf' => 5.0,
			'10' => 4.0,
			'1' => 3.0,
			'0.1' => 2.0,
			'0.01' => 0.0
		], self::sumBuckets( $samples ) );
	}

	/**
	 * Ensure we accurately track the number of times we called observe()
	 */
	public function testCountValue() {
		$samples = $this->statsFactory->getCounter( 'test_count' )->getSamples();
		self::assertEquals( 5, array_sum( self::getSampleValues( $samples ) ) );
	}

	public function testThrowOnInvalidBucketValue() {
		$this->expectException( 'InvalidArgumentException' );
		$this->expectExceptionMessage( 'Stats: (metricName) Histogram buckets can only be float or int.' );
		StatsFactory::newNull()->getHistogram( 'metricName', [ 'foo' ] );
	}

	public function testThrowOnEmptyBucketArray() {
		$this->expectException( 'InvalidArgumentException' );
		$this->expectExceptionMessage( 'Stats: (metricName) Histogram buckets cannot be an empty array.' );
		StatsFactory::newNull()->getHistogram( 'metricName', [] );
	}

	public function testThrowOnTooManyBuckets() {
		$this->expectException( 'InvalidArgumentException' );
		$this->expectExceptionMessage( 'Stats: (metricName) Too many buckets defined. Got:11, Max:10' );
		StatsFactory::newNull()->getHistogram( 'metricName', [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ] );
	}

	// Return an array of just the values
	public static function getSampleValues( $samples ) {
		$output = [];
		foreach ( $samples as $sample ) {
			$output[] = $sample->getValue();
		}
		return $output;
	}

	// Return an associative array of bucket => value
	// bucket should always be the last item in the sample array
	public static function sumBuckets( $samples ) {
		$output = [];
		foreach ( $samples as $sample ) {
			$labelValues = $sample->getLabelValues();
			$lastItemIdx = array_key_last( $labelValues );
			$output[ $labelValues[ $lastItemIdx ] ] = 0;
		}
		foreach ( $samples as $sample ) {
			$labelValues = $sample->getLabelValues();
			$lastItemIdx = array_key_last( $labelValues );
			$output[ $labelValues[ $lastItemIdx ] ] += $sample->getValue();
		}
		return $output;
	}
}
