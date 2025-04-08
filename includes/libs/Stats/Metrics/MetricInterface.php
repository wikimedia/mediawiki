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
	 * Returns a count of samples recorded by the metric.
	 *
	 * @return int
	 */
	public function getSampleCount(): int;

	/**
	 * Sets sample rate on a new metric instance.
	 *
	 * @param float $sampleRate
	 * @return self|NullMetric
	 */
	public function setSampleRate( float $sampleRate );

	/**
	 * Returns the list of defined label keys.
	 *
	 * @return string[]
	 */
	public function getLabelKeys(): array;

	/**
	 * Adds a label $key with $value.
	 * Note that the order in which labels are added is significant for StatsD output.
	 *
	 * Example:
	 * ```php
	 * $statsFactory->getCounter( 'testMetric_total' )
	 *     ->setLabel( 'first', 'foo' )
	 *     ->setLabel( 'second', 'bar' )
	 *     ->increment();
	 * ```
	 * statsd: "mediawiki.testMetric_total.foo.bar"
	 * prometheus: "mediawiki_testMetric_total{ first='foo', second='bar' }
	 *
	 * @param string $key
	 * @param string $value
	 * @return self|NullMetric
	 */
	public function setLabel( string $key, string $value );

	/**
	 * Convenience function to set a number of labels at once.
	 * @see ::setLabel
	 * @param array<string,string> $labels
	 * @return self|NullMetric
	 */
	public function setLabels( array $labels );

	/**
	 * Copies metric operation to StatsD at provided namespace.
	 *
	 * Takes a namespace or multiple namespaces.
	 *
	 * This function existed to support the Graphite->Prometheus transition and is no longer needed.
	 *
	 * @deprecated since 1.45, see: https://www.mediawiki.org/wiki/Manual:Stats.
	 * @param string|string[] $statsdNamespaces
	 * @return self|NullMetric
	 */
	public function copyToStatsdAt( $statsdNamespaces );

	/**
	 * Returns metric with cleared labels.
	 *
	 * @return self|NullMetric
	 */
	public function fresh();

	/**
	 * Indicates the metric instance is used in a Histogram
	 *
	 * @return bool
	 */
	public function isHistogram(): bool;
}
