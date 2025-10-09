<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use Wikimedia\ObjectCache\BagOStuff;

/**
 * Estimate the query rate, that is, the number of rows per second of timestamp
 * interval likely to be returned by a query, by storing the number of results
 * returned by previous queries with identical conditions.
 *
 * Counts are stored by timestamp bucket, and it's assumed that rows will be
 * added to a bucket but not deleted, so we update by taking the maximum of
 * the existing and new value.
 *
 * An object instance relates to a single fetch and update for a single query.
 *
 * All timestamps are UNIX time.
 *
 * @since 1.45
 */
class QueryRateEstimator {
	/** @var array The previously fetched results indexed by cache key */
	private $results = [];

	/**
	 * @param BagOStuff $cache
	 * @param int|float $maxAge The maximum age of rows in the table, in seconds
	 * @param string $key The query identifier
	 */
	public function __construct(
		private BagOStuff $cache,
		private int|float $maxAge,
		private string $key,
	) {
	}

	/**
	 * Get the period covered by a bucket in seconds.
	 *
	 * @return int
	 */
	public function getBucketPeriod(): int {
		// Use coarse buckets, otherwise the cure may be worse than the disease.
		// We don't want it to take longer to fetch the metrics than to run the
		// query.
		return (int)( $this->maxAge / 30 );
	}

	/**
	 * Get the best estimate of the query rate, or null if it is unknown.
	 *
	 * @param int $startTime The start of the planned timestamp range
	 * @param int $endTime The end of the planned timestamp range
	 * @return float|int|null
	 */
	public function fetchRate( int $startTime, int $endTime ) {
		$cache = $this->cache;
		$period = $this->getBucketPeriod();
		$startBucket = (int)( ( $startTime - $this->maxAge ) / $period );
		$endBucket = (int)( $endTime / $period );
		$keys = [];
		for ( $b = $startBucket; $b <= $endBucket; $b++ ) {
			$keys[] = $cache->makeKey( 'ChangesListQueryRate', $this->key, $b );
		}
		$this->results = $cache->getMulti( $keys );
		if ( !$this->results ) {
			return null;
		} else {
			return array_sum( $this->results ) / count( $keys ) / $period;
		}
	}

	/**
	 * Store the observed row counts after the query completes.
	 *
	 * @param int[] $counts The number of rows in each timestamp bucket. The
	 *   key is the current UNIX time divided by the bucket period, rounded
	 *   down to an integer.
	 * @param int $startTime The start of the observed timestamp range
	 * @param int $endTime The end of the observed timestamp range
	 */
	public function storeCounts( array $counts, int $startTime, int $endTime ) {
		$cache = $this->cache;
		$period = $this->getBucketPeriod();
		$startBucket = (int)( $startTime / $period );
		$endBucket = (int)( $endTime / $period );
		$batch = [];
		for ( $b = $startBucket; $b < $endBucket; $b++ ) {
			$bucketKey = $cache->makeKey( 'ChangesListQueryRate', $this->key, $b );
			$prevResult = $this->results[$bucketKey] ?? null;
			$newResult = $counts[$b] ?? 0;
			if ( $prevResult === null || $prevResult < $newResult ) {
				$batch[$bucketKey] = $newResult;
			}
		}
		if ( $batch ) {
			$cache->setMulti( $batch, $this->maxAge );
		}
	}
}
