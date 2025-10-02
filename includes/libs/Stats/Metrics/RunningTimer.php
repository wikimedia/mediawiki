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

	public function __construct( float $startTime, TimingMetric $metric ) {
		$this->startTime = $startTime;
		$this->metric = $metric;
	}

	/**
	 * Handle for MetricInterface::setLabel
	 *
	 * @see MetricInterface::setLabel
	 * @return self
	 */
	public function setLabel( string $key, string $value ) {
		$this->metric = $this->metric->setLabel( $key, $value );
		return $this;
	}

	/**
	 * Handle for MetricInterface::setLabels
	 *
	 * @see MetricInterface::setLabels
	 * @return self
	 */
	public function setLabels( array $labels ) {
		$this->metric = $this->metric->setLabels( $labels );
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
		$this->metric->observeNanoseconds( ConvertibleTimestamp::hrtime() - $this->startTime );
		$this->startTime = null;
	}
}
