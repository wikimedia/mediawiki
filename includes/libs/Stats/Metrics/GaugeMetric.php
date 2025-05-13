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
 * Gauge Metric Implementation
 *
 * Gauge Metrics can be set to any numeric value and are identified by type "g".
 *
 * @author Cole White
 * @since 1.38
 */
class GaugeMetric implements MetricInterface {
	use MetricTrait;

	/**
	 * The StatsD protocol type indicator:
	 * https://github.com/statsd/statsd/blob/v0.9.0/docs/metric_types.md
	 * https://docs.datadoghq.com/developers/dogstatsd/datagram_shell/?tab=metrics
	 */
	private const TYPE_INDICATOR = "g";

	/**
	 * Sets metric to value.
	 *
	 * @param float $value
	 * @return void
	 */
	public function set( float $value ): void {
		foreach ( $this->baseMetric->getStatsdNamespaces() as $namespace ) {
			$this->baseMetric->getStatsdDataFactory()->gauge( $namespace, $value );
		}

		try {
			$this->baseMetric->addSample( new Sample( $this->baseMetric->getLabelValues(), $value ) );
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
