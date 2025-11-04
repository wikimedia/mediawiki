<?php

namespace MediaWiki\Tests\Unit\RecentChanges\ChangesListQuery;

use MediaWiki\RecentChanges\ChangesListQuery\TimestampRangePartitioner;

/**
 * @covers \MediaWiki\RecentChanges\ChangesListQuery\TimestampRangePartitioner
 */
class TimestampRangePartitionerTest extends \MediaWikiUnitTestCase {
	/**
	 * @param array $model Model parameters
	 * @param array $query Query parameters
	 * @return array Metrics
	 */
	private function runModel( $model, $query ) {
		$now = $model['rcMaxAge'];
		$partitioner = new TimestampRangePartitioner(
			$now - $query['days'] * 86_400,
			$now,
			$query['limit'],
			$query['rateEstimate'] ?? null,
			$query['densityEstimate'],
			$model['rcSize'],
			$model['rcMaxAge']
		);
		do {
			[ $min, $max, $limit ] = $partitioner->getNextPartition();
			[ $time, $numRows ] = $this->doQuery( $model, $min, $max, $limit );
			$partitioner->notifyResult( $time, $numRows );
		} while ( !$partitioner->isDone() );
		return $partitioner->getMetrics();
	}

	/**
	 * @param array $model Model parameters
	 * @param int $min
	 * @param int|null $max
	 * @param int $limit
	 * @return array Time and limit
	 */
	private function doQuery( $model, $min, $max, $limit ) {
		$now = $model['rcMaxAge'];
		$min = max( $min, 0 );
		$max = min( $max ?? $now, $now );
		$density = $model['density'];
		$rate = $model['rcSize'] / $model['rcMaxAge'];

		// Just iterate through the imaginary rows
		// I tried writing an expression to do the whole thing at once, but
		// rounding matters and is complicated. When the density is 0.1, we
		// want 1 in every 10 rows to be returned, and it should be the same
		// rows each time.
		$id = (int)( $max * $rate );
		$foundRows = 0;
		$minFoundTime = null;
		while ( $foundRows < $limit ) {
			$time = (int)( $id / $rate );
			if ( $time < $min ) {
				break;
			}
			if ( (int)( $id * $density ) !== (int)( ( $id + 1 ) * $density ) ) {
				$minFoundTime = $time;
				$foundRows++;
			}
			$id--;
		}
		return [ $minFoundTime, $foundRows ];
	}

	public static function provideNaiveRange() {
		$expected = [
			'actualRows' => 50,
		];
		$model = [
			// Like Commons
			'rcSize' => 31_000_000,
			'rcMaxAge' => 30 * 86_400,
		];
		return [
			'density 1' => [
				[ 'density' => 1 ] + $model,
				[
					// Density matches the naive estimate so the expected query
					// count is 1.
					'queryCount' => 1,
					'actualPeriod' => 5,
					// This is just copied from the results and will inevitably
					// change if the tunables are changed.
					'queryPeriod' => 7,
				] + $expected
			],
			'density 0.5' => [
				[ 'density' => 0.5 ] + $model,
				[
					'queryCount' => 2,
					'actualPeriod' => 10,
					'queryPeriod' => 28,
				] + $expected
			],
			'density 0.1' => [
				[ 'density' => 0.1 ] + $model,
				[
					'queryCount' => 2,
					'actualPeriod' => 42,
					'queryPeriod' => 89,
				] + $expected
			],
			'density 0.01' => [
				[ 'density' => 0.01 ] + $model,
				[
					'queryCount' => 2,
					'actualPeriod' => 410,
					'queryPeriod' => 711,
				] + $expected
			],
			'density 0.001' => [
				[ 'density' => 0.001 ] + $model,
				[
					// Here the density is low enough that the first query for 7s
					// only gets 1 row. The second query is for 710s but still
					// only gets 8 rows. So we need a third query.
					'queryCount' => 3,
					'actualPeriod' => 4098,
					'queryPeriod' => 8967,
				] + $expected
			],
		];
	}

	/**
	 * @dataProvider provideNaiveRange
	 * @param array $model
	 * @param array $expected
	 */
	public function testNaiveRange( $model, $expected ) {
		$query = [
			'days' => 7,
			'limit' => 50,
			'rateEstimate' => null,
			'densityEstimate' => 1,
		];
		$metrics = $this->runModel( $model, $query );
		$this->assertArrayEquals( $expected, $metrics, false, true );
	}

	public static function provideRateEstimate() {
		$expected = [
			'actualRows' => 50,
		];
		$model = [
			'rcSize' => 31_000_000,
			'rcMaxAge' => 30 * 86_400,
		];
		return [
			'density 1' => [
				[ 'density' => 1 ] + $model,
				[
					'queryCount' => 1,
					'actualPeriod' => 5,
					'queryPeriod' => 5,
				] + $expected
			],
			'density 0.5' => [
				[ 'density' => 0.5 ] + $model,
				[
					'queryCount' => 1,
					'actualPeriod' => 9,
					'queryPeriod' => 10,
				] + $expected
			],
			'density 0.1' => [
				[ 'density' => 0.1 ] + $model,
				[
					'queryCount' => 1,
					'actualPeriod' => 42,
					'queryPeriod' => 46,
				] + $expected
			],
			'density 0.01' => [
				[ 'density' => 0.01 ] + $model,
				[
					'queryCount' => 1,
					'actualPeriod' => 410,
					'queryPeriod' => 460,
				] + $expected
			],
			'density 0.001' => [
				[ 'density' => 0.001 ] + $model,
				[
					'queryCount' => 1,
					'actualPeriod' => 4098,
					'queryPeriod' => 4599,
				] + $expected
			],
			'bad rate estimate' => [
				[
					'density' => 0.001,
					// Actual rate is 0.01, so this is off by 400x
					'rateEstimate' => 4,
				] + $model,
				[
					// Still does OK, similar to the naive results at this density
					'queryCount' => 3,
					'actualPeriod' => 4181,
					'queryPeriod' => 10491,
				] + $expected
			],
		];
	}

	/**
	 * @dataProvider provideRateEstimate
	 * @param array $model
	 * @param array $expected
	 */
	public function testRateEstimate( $model, $expected ) {
		$query = [
			'days' => 7,
			'limit' => 50,
			'rateEstimate' => $model['rateEstimate']
				?? $model['rcSize'] * $model['density'] / $model['rcMaxAge'],
			'densityEstimate' => 1,
		];
		$metrics = $this->runModel( $model, $query );
		$this->assertArrayEquals( $expected, $metrics, false, true );
	}
}
