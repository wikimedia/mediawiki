<?php

namespace Wikimedia\WRStats;

/**
 * A factory for WRStats readers and writers.
 *
 * Readers and writers should generally be used for a batch and then discarded.
 * Factory objects can be retained indefinitely.
 *
 * @since 1.39
 */
class WRStatsFactory {
	/** @var StatsStore */
	private $store;

	/**
	 * @param StatsStore $store
	 */
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
		return new WRStatsWriter( $this->store, $specs, $prefix );
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
		return new WRStatsReader( $this->store, $specs, $prefix );
	}
}
