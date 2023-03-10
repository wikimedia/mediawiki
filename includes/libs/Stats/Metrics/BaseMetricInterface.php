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

use IBufferingStatsdDataFactory;
use Wikimedia\Stats\Sample;

/**
 * Base Metric Interface
 *
 * Interface for defining a Base Metric.
 *
 * @author Cole White
 * @since 1.41
 */
interface BaseMetricInterface {

	/**
	 * @param string $component The component this metric will track.
	 * @param string $name The Metric Name.
	 */
	public function __construct( string $component, string $name );

	/**
	 * Records a Metric Sample.
	 *
	 * @param Sample $sample
	 * @return void
	 */
	public function addSample( Sample $sample ): void;

	/**
	 * Returns the configured sample rate.
	 *
	 * @return float
	 */
	public function getSampleRate(): float;

	/**
	 * Sets the sample rate.
	 *
	 * @param float $sampleRate
	 */
	public function setSampleRate( float $sampleRate ): void;

	/**
	 * Returns subset of samples corresponding to sample rate setting.
	 *
	 * @return Sample[]
	 */
	public function getSamples(): array;

	/**
	 * Returns the Metric Name
	 *
	 * @return string
	 */
	public function getName(): string;

	/**
	 * Configures the metric with static labels.
	 *
	 * @param string[] $labelKeys
	 * @param string[] $labelValues
	 * @return BaseMetricInterface
	 */
	public function withStaticLabels( array $labelKeys, array $labelValues ): BaseMetricInterface;

	/**
	 * Add a label with key => value
	 *
	 * @param string $key
	 * @param string $value
	 * @return void
	 */
	public function addLabel( string $key, string $value ): void;

	/**
	 * Returns array of label keys.
	 *
	 * @return string[]
	 */
	public function getLabelKeys(): array;

	/**
	 * Returns an array of label values with static label values in the order of label keys.
	 *
	 * @return string[]
	 */
	public function getLabelValues(): array;

	/**
	 * Returns the Metric Component
	 *
	 * @return string
	 */
	public function getComponent(): string;

	/**
	 * Clears the working labels.
	 *
	 * @return void
	 */
	public function clearLabels(): void;

	/**
	 * Gets StatsD Data Factory instance or null.
	 */
	public function getStatsdDataFactory();

	/**
	 * StatsD Data Factory instance to copy metrics to.
	 *
	 * @param IBufferingStatsdDataFactory $statsdDataFactory
	 * @return BaseMetricInterface
	 */
	public function withStatsdDataFactory( IBufferingStatsdDataFactory $statsdDataFactory );
}
