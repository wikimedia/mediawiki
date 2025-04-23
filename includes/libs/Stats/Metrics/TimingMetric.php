<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 * @file
 */

declare( strict_types=1 );

namespace Wikimedia\Stats\Metrics;

use Wikimedia\Stats\Exceptions\IllegalOperationException;
use Wikimedia\Stats\Sample;

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
	 * @return $this
	 */
	public function start() {
		$this->startTime = hrtime( true );
		return $this;
	}

	/**
	 * Stop the running timer.
	 */
	public function stop(): void {
		if ( $this->startTime === null ) {
			trigger_error( "Stats: ({$this->getName()}) stop() called before start()", E_USER_WARNING );
			return;
		}
		$this->observeNanoseconds( hrtime( true ) - $this->startTime );
		$this->startTime = null;
	}

	/**
	 * Record a previously calculated observation in nanoseconds.
	 *
	 * Example:
	 *
	 * ```php
	 * $startTime = hrtime( true )
	 * # work to be measured...
	 * $metric->observeNanoseconds( hrtime( true ) - $startTime )
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
	 * This method is provided for tracking externally-generated values, timestamp deltas, and
	 * situations where the expected input value is the expected Prometheus graphed value.
	 *
	 * Performance measurements in process should be done with hrtime() and observeNanoseconds()
	 * to ensure monotonic time is used and not wall-clock time.
	 *
	 * Example:
	 *
	 * ```php
	 * $startTime = microtime( true )
	 * # work to be measured...
	 * $metric->observeSeconds( microtime( true ) - $startTime )
	 * ```
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
