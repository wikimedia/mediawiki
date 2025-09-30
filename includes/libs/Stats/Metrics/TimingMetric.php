<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

declare( strict_types=1 );

namespace Wikimedia\Stats\Metrics;

use Wikimedia\Stats\Exceptions\IllegalOperationException;
use Wikimedia\Stats\Sample;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Timing Metric Implementation
 *
 * Timing metrics track duration data which can be broken into histograms.
 * They are identified by type "ms".
 *
 * @author Cole White
 * @since 1.38
 */
class TimingMetric implements MetricInterface {
	use MetricTrait;

	/**
	 * The StatsD protocol type indicator:
	 * https://github.com/statsd/statsd/blob/v0.9.0/docs/metric_types.md
	 * https://docs.datadoghq.com/developers/dogstatsd/datagram_shell/?tab=metrics
	 */
	private const TYPE_INDICATOR = "ms";

	private ?float $startTime = null;

	/**
	 * Start the timer.
	 *
	 * Example:
	 *
	 * ```php
	 * $timer = StatsFactory->getTiming( 'example_seconds' )
	 *     ->setLabel( 'foo', 'bar' )
	 *     ->start();
	 * # work to be measured...
	 * $timer->stop();
	 * ```
	 *
	 * Example with an extra label informed by the work:
	 *
	 * ```php
	 * $timer = StatsFactory->getTiming( 'example_seconds' )
	 *     ->start();
	 * # work to be measured...
	 * $timer
	 *     ->setLabel( 'status', $status->isOK() ? 'ok' : 'error' )
	 *     ->stop();
	 * ```
	 *
	 * @return RunningTimer
	 */
	public function start() {
		$this->startTime = ConvertibleTimestamp::hrtime();
		return new RunningTimer( $this->startTime, $this, $this->baseMetric->getLabels() );
	}

	/**
	 * Stop the running timer.
	 *
	 * @deprecated since 1.45 Call RunningTimer::stop on the object returned by start() instead.
	 */
	public function stop(): void {
		if ( $this->startTime === null ) {
			trigger_error( "Stats: ({$this->getName()}) stop() called before start()", E_USER_WARNING );
			return;
		}

		trigger_error( 'Use of shared timer is deprecated. Use the returned start object instead.', E_USER_DEPRECATED );
		$this->observeNanoseconds( ConvertibleTimestamp::hrtime() - $this->startTime );
		$this->startTime = null;
	}

	/**
	 * Record a previously calculated observation in nanoseconds.
	 *
	 * It is recommended to use TimingMetric::start() and RunningTimer::stop() instead.
	 *
	 * Only measure latency yourself if you also need the duration value elsewhere.
	 *
	 * Example:
	 *
	 * ```php
	 * $startTime = ConvertibleTimestamp::hrtime( true );
	 * # work to be measured...
	 * $durationNano = ConvertibleTimestamp::hrtime( true ) - $startTime;
	 * $metric->observeNanoseconds( $durationNano );
	 *
	 * $durationMs = $durationNano / 1e6;
	 * $durationSec = $durationNano / 1e9;
	 * ```
	 *
	 * @param float $nanoseconds
	 * @return void
	 * @since 1.43
	 */
	public function observeNanoseconds( float $nanoseconds ): void {
		$this->addSample( $nanoseconds * 1e-6 );
	}

	/**
	 * Record a previously calculated observation in seconds.
	 *
	 * This method is provided to ease recording of externally-generated time values.
	 * For example, when a service returns a delta in seconds to you, and you are not
	 * measuring or multiplying this value yourself.
	 *
	 * To instrument your own code, it is recommended to use TimingMetric::start()
	 * and RunningTimer::stop() instead. Or, if measuring by hand, use hrtime()
	 * with observeNanoseconds() to guarantee a monotonic clock and not a wall-clock.
	 *
	 * Do not measure latency with time() or microtime(), per T245464.
	 *
	 * NOTE: If you previously used observeSeconds to store non-time values in a histogram,
	 * such as kilobytes or other unrelated quantities, use StatsFactory::getHistogram
	 * instead (T348796, T364240, T383208).
	 *
	 * @param float $seconds
	 * @return void
	 * @since 1.43
	 */
	public function observeSeconds( float $seconds ): void {
		$this->addSample( $seconds * 1000 );
	}

	/**
	 * Record a previously calculated observation in milliseconds.
	 *
	 * NOTE: You MUST pass values converted to milliseconds.
	 *
	 * This method is discouraged in new code, because PHP does not measure
	 * time in milliseconds. It will be less error-prone if you use start()
	 * and stop(), or pass values from hrtime() directly to observeNanoseconds()
	 * without manual multiplication to another unit.
	 *
	 * @deprecated since 1.45 Use TimingMetric::start instead, or switch to hrtime() and
	 * use TimingMetric::observeNanoseconds.
	 * @param float $milliseconds
	 * @return void
	 */
	public function observe( float $milliseconds ): void {
		$this->addSample( $milliseconds );
	}

	private function addSample( float $milliseconds ): void {
		foreach ( $this->baseMetric->getStatsdNamespaces() as $namespace ) {
			$this->baseMetric->getStatsdDataFactory()->timing( $namespace, $milliseconds );
		}

		try {
			$this->baseMetric->addSample( new Sample( $this->baseMetric->getLabelValues(), $milliseconds ) );
		} catch ( IllegalOperationException $ex ) {
			// Log the condition and give the caller something that will absorb calls.
			trigger_error( "Stats: ({$this->getName()}): {$ex->getMessage()}", E_USER_WARNING );
		}
	}

	/** @inheritDoc */
	public function getTypeIndicator(): string {
		return self::TYPE_INDICATOR;
	}
}
