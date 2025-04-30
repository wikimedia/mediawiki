<?php

namespace Wikimedia\WRStats;

/**
 * The main entry point to WRStats, for creating readers and writers.
 *
 * Readers and writers should generally be used for a batch and then discarded.
 * Factory objects can be retained indefinitely.
 *
 * @since 1.39
 */
class WRStatsFactory {
	/** @var StatsStore */
	private $store;

	/** @var float|int|null */
	private $now;

	public function __construct( StatsStore $store ) {
		$this->store = $store;
	}

	/**
	 * Create a writer. Writers gather a batch of increment operations and then
	 * commit them when flush() is called, or when the writer is destroyed.
	 *
	 * @param array $specs An array of metric specification arrays, indexed by
	 *   name, where each element is an associative array with the following
	 *   keys (all optional):
	 *     - type: (string) Always "counter"
	 *     - resolution: (int|float) The resolution of the counter value.
	 *       For example, if this is 0.01, counters will be rounded to two
	 *       decimal places. Necessary because we support backends that only
	 *       store integers, but we present an interface that allows float
	 *       values.
	 *     - sequences: (array) An array of sequence specification, each
	 *       sequence spec being an associative array with the following keys:
	 *         - expiry: (int|float) The expiry time of the counters, in seconds.
	 *         - timeStep: (int|float) The time duration represented by a counter
	 *           bucket. If this is too small, many buckets will be required,
	 *           making fetches slower. If this is too large, there will be some
	 *           jitter in the resulting rates as the current time moves from one
	 *           bucket to the next.
	 *
	 * @param string|string[] $prefix A string or array of strings to prefix
	 *   before storage keys.
	 * @return WRStatsWriter
	 */
	public function createWriter( $specs, $prefix = 'WRStats' ) {
		$writer = new WRStatsWriter( $this->store, $specs, $prefix );
		if ( $this->now !== null ) {
			$writer->setCurrentTime( $this->now );
		}
		return $writer;
	}

	/**
	 * Create a reader. Readers gather a batch of read operations, returning
	 * promises. The batch is executed when the first promise is resolved.
	 *
	 * @see createWriter
	 *
	 * @param array $specs
	 * @param string|string[] $prefix
	 * @return WRStatsReader
	 */
	public function createReader( $specs, $prefix = 'WRStats' ) {
		$reader = new WRStatsReader( $this->store, $specs, $prefix );
		if ( $this->now !== null ) {
			$reader->setCurrentTime( $this->now );
		}
		return $reader;
	}

	/**
	 * Create a rate limiter.
	 *
	 * @param array<string,LimitCondition> $conditions An array in which the key is the
	 *   condition name, and the value is a LimitCondition describing the limit.
	 * @param string|string[] $prefix A string or array of strings to prefix
	 *   before storage keys.
	 * @param array $options An associative array of options:
	 *   - bucketCount: Each window is divided into this many time buckets.
	 *     Fetching the current count will typically result in a request for
	 *     this many keys.
	 * @return WRStatsRateLimiter
	 */
	public function createRateLimiter(
		$conditions, $prefix = 'WRStats', $options = []
	) {
		$rateLimiter = new WRStatsRateLimiter(
			$this->store, $conditions, $prefix, $options );
		if ( $this->now !== null ) {
			$rateLimiter->setCurrentTime( $this->now );
		}
		return $rateLimiter;
	}

	/**
	 * Set a current timestamp to be injected into new instances on creation
	 *
	 * @param float|int $now Seconds since epoch
	 */
	public function setCurrentTime( $now ) {
		$this->now = $now;
	}
}
