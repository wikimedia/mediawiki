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
use Psr\Log\LoggerInterface;
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

	/**
	 * The StatsD protocol type indicator:
	 * https://github.com/statsd/statsd/blob/v0.9.0/docs/metric_types.md
	 * https://docs.datadoghq.com/developers/dogstatsd/datagram_shell/?tab=metrics
	 *
	 * @var string
	 */
	private const TYPE_INDICATOR = "g";

	/** @var string|null */
	private ?string $statsdNamespace = null;

	/** @var BaseMetricInterface */
	private BaseMetricInterface $baseMetric;

	/** @var LoggerInterface */
	private LoggerInterface $logger;

	/** @inheritDoc */
	public function __construct( $baseMetric, $logger ) {
		$this->baseMetric = $baseMetric;
		$this->logger = $logger;
	}

	/**
	 * Sets metric to value.
	 *
	 * @param float $value
	 * @return void
	 */
	public function set( float $value ): void {
		if ( $this->statsdNamespace !== null ) {
			$this->baseMetric->getStatsdDataFactory()->updateCount( $this->statsdNamespace, $value );
			$this->statsdNamespace = null;
		}
		$this->baseMetric->addSample( new Sample( $this->baseMetric->getLabelValues(), $value ) );
	}

	/** @inheritDoc */
	public function getName(): string {
		return $this->baseMetric->getName();
	}

	/** @inheritDoc */
	public function getComponent(): string {
		return $this->baseMetric->getComponent();
	}

	/** @inheritDoc */
	public function getTypeIndicator(): string {
		return self::TYPE_INDICATOR;
	}

	/** @inheritDoc */
	public function getSamples(): array {
		return $this->baseMetric->getSamples();
	}

	/** @inheritDoc */
	public function getSampleRate(): float {
		return $this->baseMetric->getSampleRate();
	}

	/** @inheritDoc */
	public function withSampleRate( float $sampleRate ) {
		try {
			$this->baseMetric->setSampleRate( $sampleRate );
		} catch ( IllegalOperationException | InvalidArgumentException $ex ) {
			// Log the condition and give the caller something that will absorb calls.
			trigger_error( $ex->getMessage(), E_USER_WARNING );
			return new NullMetric;
		}
		return $this;
	}

	/** @inheritDoc */
	public function getLabelKeys(): array {
		return $this->baseMetric->getLabelKeys();
	}

	/** @inheritDoc */
	public function withLabel( string $key, string $value ) {
		try {
			$this->baseMetric->addLabel( $key, $value );
		} catch ( IllegalOperationException | InvalidArgumentException $ex ) {
			// Log the condition and give the caller something that will absorb calls.
			trigger_error( $ex->getMessage(), E_USER_WARNING );
			return new NullMetric;
		}
		return $this;
	}

	/** @inheritDoc */
	public function copyToStatsdAt( string $statsdNamespace ) {
		if ( $this->baseMetric->getStatsdDataFactory() !== null ) {
			$this->statsdNamespace = $statsdNamespace;
		}
		return $this;
	}

	/** @inheritDoc */
	public function fresh(): GaugeMetric {
		$this->baseMetric->clearLabels();
		return $this;
	}
}
