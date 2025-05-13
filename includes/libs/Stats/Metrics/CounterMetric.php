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

use InvalidArgumentException;
use Wikimedia\Stats\Exceptions\IllegalOperationException;
use Wikimedia\Stats\Sample;

/**
 * Counter Metric Implementation
 *
 * Counter Metrics only ever increase and are identified by type "c".
 *
 * @author Cole White
 * @since 1.38
 */
class CounterMetric implements MetricInterface {
	use MetricTrait;

	/**
	 * The StatsD protocol type indicator:
	 * https://github.com/statsd/statsd/blob/v0.9.0/docs/metric_types.md
	 * https://docs.datadoghq.com/developers/dogstatsd/datagram_shell/?tab=metrics
	 */
	private const TYPE_INDICATOR = "c";

	/**
	 * Increments metric by one.
	 */
	public function increment(): void {
		$this->incrementBy( 1 );
	}

	/**
	 * Increments metric by provided value.
	 *
	 * @param float $value
	 * @return void
	 */
	public function incrementBy( float $value ): void {
		if ( $value < 0 ) {
			trigger_error( "Stats: ({$this->getName()}) Counter got negative value", E_USER_WARNING );
			return;
		}

		foreach ( $this->baseMetric->getStatsdNamespaces() as $namespace ) {
			$this->baseMetric->getStatsdDataFactory()->updateCount( $namespace, $value );
		}

		try {
			$labelValues = $this->baseMetric->getLabelValues();
			if ( $this->bucket ) {
				$labelValues[] = $this->bucket;
			}
			$this->baseMetric->addSample( new Sample( $labelValues, $value ) );
		} catch ( IllegalOperationException $ex ) {
			// Log the condition and give the caller something that will absorb calls.
			trigger_error( "Stats: ({$this->getName()}): {$ex->getMessage()}", E_USER_WARNING );
		}
	}

	/**
	 * Sets the bucket value
	 *
	 * Only allows float, int, or literal '+Inf' as value.
	 *
	 * WARNING: This function exists to support HistogramMetric. It should not be used elsewhere.
	 *
	 * @internal
	 * @param float|int|string $value
	 * @return CounterMetric
	 */
	public function setBucket( $value ) {
		if ( $value == "+Inf" || ( is_float( $value ) || is_int( $value ) ) ) {
			$this->bucket = "{$value}";
			return $this;
		}
		throw new InvalidArgumentException(
			"Stats: ({$this->getName()}) Got illegal bucket value '{$value}' - must be float or '+Inf'"
		);
	}

	/** @inheritDoc */
	public function getTypeIndicator(): string {
		return self::TYPE_INDICATOR;
	}
}
