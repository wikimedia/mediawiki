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

namespace Wikimedia\Stats\Metrics;

use Psr\Log\LoggerInterface;
use Wikimedia\Stats\Sample;

/**
 * Metric Interface
 *
 * @author Cole White
 * @since 1.41
 */
interface MetricInterface {
	/**
	 * @param BaseMetricInterface $baseMetric
	 * @param LoggerInterface $logger
	 */
	public function __construct( BaseMetricInterface $baseMetric, LoggerInterface $logger );

	/** @return string */
	public function getName(): string;

	/** @return string */
	public function getComponent(): string;

	/** @return float */
	public function getSampleRate(): float;

	/** @return string */
	public function getTypeIndicator(): string;

	/**
	 * Returns subset of samples corresponding to sample rate setting.
	 *
	 * @return Sample[]
	 */
	public function getSamples(): array;

	/**
	 * Sets sample rate on a new metric instance.
	 *
	 * @param float $sampleRate
	 * @return CounterMetric|GaugeMetric|TimingMetric|NullMetric
	 */
	public function withSampleRate( float $sampleRate );

	/**
	 * Returns the list of defined label keys.
	 *
	 * @return string[]
	 */
	public function getLabelKeys(): array;

	/**
	 * Adds a label $key with $value.
	 *
	 * @param string $key
	 * @param string $value
	 * @return CounterMetric|GaugeMetric|TimingMetric|NullMetric
	 */
	public function withLabel( string $key, string $value );

	/**
	 * Copies metric operation to StatsD at provided namespace.
	 *
	 * @param string $statsdNamespace
	 * @return CounterMetric|GaugeMetric|TimingMetric|NullMetric
	 */
	public function copyToStatsdAt( string $statsdNamespace );

	/**
	 * Returns metric with cleared labels.
	 *
	 * @return CounterMetric|GaugeMetric|TimingMetric|NullMetric
	 */
	public function fresh();
}
