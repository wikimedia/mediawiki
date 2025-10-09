<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

/**
 * The mathematical bits of partitioning a query by timestamp range.
 *
 * Avoid dependencies so that it is easily unit testable.
 */
class TimestampRangePartitioner {
	/**
	 * The target number of extra rows to scan. The theory is that doing an
	 * extra query has the same cost as scanning, say, 1000 rows, and so it's
	 * worthwhile to scan an extra 100 rows if that will reduce the chance of
	 * doing a query by 90%.
	 *
	 * Like the other constants, this has not been measured or tuned.
	 */
	private const MIN_OVERRUN = 100;

	/**
	 * The maximum number of queries to do.
	 */
	private const MAX_QUERIES = 4;

	/**
	 * When extrapolating from an underfilled result set to calculate the period
	 * needed to fill the result set, multiply the period by this fudge factor to
	 * reduce the chance of needing to do another query.
	 */
	private const EXTRAPOLATION_FACTOR = 1.5;

	/**
	 * The fudge factor to apply when calculating the period from configured
	 * density guesstimates.
	 */
	private const NAIVE_INITIAL_FACTOR = 1.5;

	/**
	 * The fudge factor to apply when calculating the period from a stored
	 * rate estimate.
	 */
	private const INFORMED_INITIAL_FACTOR = 1.1;

	/** @var int Number of rows found */
	private $rowsFoundCount = 0;
	/** @var int|null The earliest found UNIX timestamp */
	private $minFoundTime = null;
	/** @var int|null The UNIX timestamp of the start of the previous partition */
	private $prevPartitionStart = null;
	/** @var int The number of queries done */
	private $queryCount = 0;

	/**
	 * @param int $minTime The user-specified timestamp cutoff (UNIX time)
	 * @param int $now The maximum UNIX timestamp, conventionally the current time.
	 * @param int $limit The maximum number of rows to find
	 * @param int|float|null $rateEstimate The query rate (rows/second) if available
	 * @param int|float $densityEstimate An estimate of the query density. The
	 *   number of rows returned per row scanned. The probability that the
	 *   conditions will match.
	 * @param int|float $rcSize The total number of rows in the table
	 * @param int|float $rcMaxAge The maximum age of rows in the table
	 */
	public function __construct(
		private int $minTime,
		private int $now,
		private int $limit,
		private int|float|null $rateEstimate,
		private int|float $densityEstimate,
		private int|float $rcSize,
		private int|float $rcMaxAge,
	) {
	}

	/**
	 * Get the minimum timestamp, optional maximum timestamp and query limit
	 * for the next partitioned query.
	 *
	 * @return array{int,?int,int}
	 */
	public function getNextPartition(): array {
		if ( $this->prevPartitionStart === null ) {
			$period = $this->getInitialPeriod();
			$max = null;
		} else {
			$max = $this->prevPartitionStart - 1;
			if ( $this->queryCount < self::MAX_QUERIES - 1 ) {
				$period = $this->extrapolateRange();
			} else {
				// Give up, do the rest of the input range
				$period = INF;
			}
		}
		$min = (int)max( $this->minTime, $this->now - $period );
		$this->prevPartitionStart = $min;
		$limit = $this->limit - $this->rowsFoundCount;
		return [ $min, $max, $limit ];
	}

	/**
	 * Call this to tell us about the rows that were found by the query
	 * specified by the previous call to getNextPartition().
	 *
	 * @param int|null $earliestFound The earliest found UNIX timestamp
	 * @param int $numRows The number of rows found
	 */
	public function notifyResult( ?int $earliestFound, int $numRows ) {
		$this->queryCount++;
		$this->rowsFoundCount += $numRows;
		if ( $earliestFound !== null ) {
			$this->minFoundTime = $earliestFound;
		}
	}

	/**
	 * Check whether the overall set of queries is done.
	 *
	 * @return bool
	 */
	public function isDone() {
		return $this->rowsFoundCount >= $this->limit
			|| $this->prevPartitionStart <= $this->minTime;
	}

	/**
	 * Get metrics describing the overall partitioning operation.
	 *
	 * @return array
	 *   - queryCount: The number of queries done
	 *   - actualPeriod: The period covered by the returned rows, in seconds
	 *   - queryPeriod: The period covered by the query conditions, in seconds
	 *   - actualRows: The total number of rows found
	 */
	public function getMetrics() {
		return [
			'queryCount' => $this->queryCount,
			'actualPeriod' => $this->now - $this->minFoundTime,
			'queryPeriod' => $this->now - $this->prevPartitionStart,
			'actualRows' => $this->rowsFoundCount,
		];
	}

	public function getMinFoundTime(): ?int {
		return $this->minFoundTime;
	}

	/**
	 * Use previous query results to estimate the required range of the next
	 * query. The return value is a number of seconds before "now".
	 *
	 * @return float
	 */
	private function extrapolateRange() {
		$limit = $this->limit;
		$period = $this->now - $this->prevPartitionStart;
		$scaledMinOverrun = self::MIN_OVERRUN / self::EXTRAPOLATION_FACTOR;
		if ( $limit - $this->rowsFoundCount < $scaledMinOverrun ) {
			$limit = $this->rowsFoundCount + $scaledMinOverrun;
		}
		return $period * $limit * self::EXTRAPOLATION_FACTOR / ( $this->rowsFoundCount ?: 1 );
	}

	/**
	 * Calculate the number of seconds which will be covered by the initial query.
	 *
	 * @return float
	 */
	private function getInitialPeriod() {
		if ( $this->rateEstimate ) {
			return $this->limit / $this->rateEstimate * self::INFORMED_INITIAL_FACTOR;
		} elseif ( $this->rcSize > 0 && $this->densityEstimate > 0 ) {
			return $this->limit / $this->densityEstimate / $this->rcSize
				* $this->rcMaxAge * self::NAIVE_INITIAL_FACTOR;
		} else {
			return 86_400;
		}
	}
}
