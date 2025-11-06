<?php

namespace Wikimedia\Stats;

use Liuggio\StatsdClient\Entity\StatsdData;
use Liuggio\StatsdClient\Entity\StatsdDataInterface;

/**
 * @author Addshore
 * @deprecated since 1.45, use StatsFactory::newNull() instead
 * @since 1.27
 */
class NullStatsdDataFactory implements IBufferingStatsdDataFactory {

	/**
	 * This function creates a 'timing' StatsdData.
	 *
	 * @param string|array $key The metric(s) to set.
	 * @param float $time The elapsed time (ms) to log
	 */
	public function timing( $key, $time ) {
	}

	/**
	 * This function creates a 'gauge' StatsdData.
	 *
	 * @param string|array $key The metric(s) to set.
	 * @param float $value The value for the stats.
	 */
	public function gauge( $key, $value ) {
	}

	/**
	 * This function creates a 'set' StatsdData object
	 * A "Set" is a count of unique events.
	 * This data type acts like a counter, but supports counting
	 * of unique occurrences of values between flushes. The backend
	 * receives the number of unique events that happened since
	 * the last flush.
	 *
	 * The reference use case involved tracking the number of active
	 * and logged in users by sending the current userId of a user
	 * with each request with a key of "uniques" (or similar).
	 *
	 * @param string|array $key The metric(s) to set.
	 * @param float $value The value for the stats.
	 *
	 * @return array
	 */
	public function set( $key, $value ) {
		return [];
	}

	/**
	 * This function creates a 'increment' StatsdData object.
	 *
	 * @param string|array $key The metric(s) to increment.
	 *
	 * @return array
	 */
	public function increment( $key ) {
		return [];
	}

	/**
	 * This function creates a 'decrement' StatsdData object.
	 *
	 *
	 * @param string|array $key The metric(s) to decrement.
	 *
	 * @return mixed
	 */
	public function decrement( $key ) {
		return [];
	}

	/**
	 * This function creates a 'updateCount' StatsdData object.
	 *
	 * @param string|array $key The metric(s) to decrement.
	 * @param int $delta The delta to add to the each metric
	 *
	 * @return mixed
	 */
	public function updateCount( $key, $delta ) {
		return [];
	}

	/**
	 * Produce a StatsdDataInterface Object.
	 *
	 * @param string $key The key of the metric
	 * @param int $value The amount to increment/decrement each metric by.
	 * @param string $metric The metric type
	 *                      ("c" for count, "ms" for timing, "g" for gauge, "s" for set)
	 *
	 * @return StatsdDataInterface
	 */
	public function produceStatsdData(
		$key,
		$value = 1,
		$metric = StatsdDataInterface::STATSD_METRIC_COUNT
	) {
		$data = new StatsdData();
		$data->setKey( $key );
		$data->setValue( $value );
		$data->setMetric( $metric );
		return $data;
	}

	/** @inheritDoc */
	public function hasData() {
		return false;
	}

	/** @inheritDoc */
	public function getData() {
		return [];
	}

	public function clearData() {
		// Nothing to do, always empty
	}

	/** @inheritDoc */
	public function getDataCount() {
		return 0;
	}

	/** @inheritDoc */
	public function setEnabled( $enabled ) {
		// Nothing to do, null factory is always disabled.
	}
}

/** @deprecated class alias since 1.43 */
class_alias( NullStatsdDataFactory::class, 'NullStatsdDataFactory' );
