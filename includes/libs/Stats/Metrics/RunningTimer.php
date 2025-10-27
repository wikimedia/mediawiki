<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Stats\Metrics;

use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * RunningTimer Implementation
 *
 * A class to help TimingMetrics handle instances of recursion.
 *
 * @author Cole White
 * @since 1.45
 */
class RunningTimer {

	/** @var TimingMetric|NullMetric */
	private $metric;
	private ?float $startTime;
	private array $workingLabels;

	public function __construct( float $startTime, TimingMetric $metric, array $initialLabels ) {
		$this->startTime = $startTime;
		$this->metric = $metric;
		$this->workingLabels = $initialLabels;
	}

	/**
	 * Handle for MetricInterface::setLabel
	 *
	 * @see MetricInterface::setLabel
	 * @return self
	 */
	public function setLabel( string $key, string $value ) {
		$this->workingLabels[$key] = $value;
		return $this;
	}

	/**
	 * Handle for MetricInterface::setLabels
	 *
	 * @see MetricInterface::setLabels
	 * @return self
	 */
	public function setLabels( array $labels ) {
		$this->workingLabels = $labels;
		return $this;
	}

	/**
	 * Stop the running timer.
	 */
	public function stop() {
		if ( $this->startTime === null ) {
			trigger_error(
				"Stats: ({$this->metric->getName()}) cannot call stop() more than once on a RunningTimer.",
				E_USER_WARNING
			);
			return;
		}
		// T406170 - move setting labels near recording the sample.
		//
		// Downstream label changes can affect upstream usage because they're the same
		// metric instance.  Here, we'll assume any labels set when the metric was
		// initially declared or changed against the RunningTimer instance are correct
		// and set them before sample capture time.
		$this->metric = $this->metric->setLabels( $this->workingLabels );
		$this->metric->observeNanoseconds( ConvertibleTimestamp::hrtime() - $this->startTime );
		$this->startTime = null;
	}
}
