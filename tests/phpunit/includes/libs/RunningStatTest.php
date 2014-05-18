<?php
/**
 * PHP Unit tests for RunningStat class.
 * @covers RunningStat
 */
class RunningStatTest extends MediaWikiTestCase {

	public $points = array(
		49.7168, 74.3804,  7.0115, 96.5769, 34.9458,
		36.9947, 33.8926, 89.0774, 23.7745, 73.5154,
		86.1322, 53.2124, 16.2046, 73.5130, 10.4209,
		42.7299, 49.3330, 47.0215, 34.9950, 18.2914,
	);

	/**
	 * Verify that the statistical moments and extrema computed by RunningStat
	 * match expected values.
	 * @covers RunningStat::push
	 * @covers RunningStat::count
	 * @covers RunningStat::getMean
	 * @covers RunningStat::getVariance
	 * @covers RunningStat::getStdDev
	 */
	public function testRunningStatAccuracy() {
		$rstat = new RunningStat();
		foreach( $this->points as $point ) {
			$rstat->push( $point );
		}

		$mean = array_sum( $this->points ) / count( $this->points );
		$variance = array_sum( array_map( function ( $x ) use ( $mean ) {
			return pow( $mean - $x, 2 );
		}, $this->points ) ) / ( count( $rstat ) - 1 );
		$stddev = sqrt( $variance );

		$this->assertEquals( count( $rstat ), count( $this->points ) );
		$this->assertEquals( $rstat->min, min( $this->points ) );
		$this->assertEquals( $rstat->max, max( $this->points ) );
		$this->assertEquals( $rstat->getMean(), $mean );
		$this->assertEquals( $rstat->getVariance(), $variance );
		$this->assertEquals( $rstat->getStdDev(), $stddev );
	}

	/**
	 * When one RunningStat instance is merged into another, the state of the
	 * target RunningInstance should have the state that it would have had if
	 * all the data had been accumulated by it alone.
	 * @covers RunningStat::merge
	 * @covers RunningStat::count
	 */
	public function testRunningStatMerge() {
		$expected = new RunningStat();

		foreach( $this->points as $point ) {
			$expected->push( $point );
		}

		// Split the data into two sets
		$sets = array_chunk( $this->points, floor( count( $this->points ) / 2 ) );

		// Accumulate the first half into one RunningStat object
		$first = new RunningStat();
		foreach( $sets[0] as $point ) {
			$first->push( $point );
		}

		// Accumulate the second half into another RunningStat object
		$second = new RunningStat();
		foreach( $sets[1] as $point ) {
			$second->push( $point );
		}

		// Merge the second RunningStat object into the first
		$first->merge( $second );

		$this->assertEquals( count( $first ), count( $this->points ) );
		$this->assertEquals( $first, $expected );
	}
}
