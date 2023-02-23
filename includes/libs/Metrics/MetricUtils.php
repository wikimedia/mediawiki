<?php
/**
 * MetricUtils Implementation
 *
 * Functionality common to all metric types provided as a dependency to
 * be injected into the instance.
 *
 * Handles caching, label validation, and rendering.
 *
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
 *
 * @license GPL-2.0-or-later
 * @author Cole White
 * @since 1.38
 */

declare( strict_types=1 );

namespace Wikimedia\Metrics;

use InvalidArgumentException;
use Wikimedia\Metrics\Exceptions\InvalidConfigurationException;
use Wikimedia\Metrics\Exceptions\InvalidLabelsException;

class MetricUtils {

	/** @var string */
	private const RE_VALID_NAME_AND_LABEL_NAME = '/^[a-zA-Z_][a-zA-Z0-9_]*$/';

	/** @var string */
	protected $prefix;

	/** @var string */
	protected $component;

	/** @var string */
	protected $name;

	/** @var float */
	protected $sampleRate;

	/** @var string[] */
	protected $labels;

	/** @var Sample[] */
	protected $samples = [];

	/** @var string */
	protected $typeIndicator;

	/**
	 * @param array $config associative array:
	 *   - prefix: (string) The prefix prepended to the start of the metric name.
	 *   - name: (string) The metric name
	 *   - component: (string) The component generating the metric
	 *   - labels: (array) List of metric dimensional instantiations for filters and aggregations
	 *   - sampleRate: (float) Optional sampling rate to apply
	 */
	public function validateConfig( $config ) {
		$this->prefix = $config['prefix'];
		$this->component = $config['component'];
		$this->name = $config['name'];
		$this->sampleRate = $config['sampleRate'];
		if ( !preg_match( self::RE_VALID_NAME_AND_LABEL_NAME, $this->name ) ) {
			throw new InvalidConfigurationException( "Invalid metric name: '" . $this->name . "'" );
		}
		$this->labels = $config['labels'];
		foreach ( $this->labels as $label ) {
			if ( !preg_match( self::RE_VALID_NAME_AND_LABEL_NAME, $label ) ) {
				throw new InvalidConfigurationException( "Invalid label name: '" . $label . "'" );
			}
		}
	}

	/**
	 * Sets the StatsD protocol type indicator
	 * @param string $typeIndicator
	 */
	public function setTypeIndicator( string $typeIndicator ) {
		$this->typeIndicator = $typeIndicator;
	}

	/**
	 * Adds a sample to cache
	 * @param Sample $sample
	 */
	public function addSample( Sample $sample ) {
		$this->samples[] = $sample;
	}

	/**
	 * @param array $labels
	 * @throws InvalidLabelsException
	 */
	public function validateLabels( array $labels ): void {
		if ( count( $this->labels ) !== count( $labels ) ) {
			throw new InvalidLabelsException(
				'Not enough or too many labels provided to metric instance.'
				. 'Configured: ' . json_encode( $this->labels ) . ' Provided: ' . json_encode( $labels )
			);
		}
	}

	/**
	 * Determines if provided string is a valid name.
	 *
	 * @param string $name
	 * @throws InvalidArgumentException
	 * @throws InvalidConfigurationException
	 */
	public static function validateMetricName( string $name ): void {
		if ( $name === '' ) {
			throw new InvalidArgumentException( 'Metric name cannot be empty' );
		}
		if ( !preg_match( self::RE_VALID_NAME_AND_LABEL_NAME, $name ) ) {
			throw new InvalidConfigurationException( "Invalid metric name: $name" );
		}
	}

	public function getComponent(): string {
		return $this->component;
	}

	public function getLabelKeys(): array {
		return $this->labels;
	}

	public function getName(): string {
		return $this->name;
	}

	public function getSamples(): array {
		return $this->samples;
	}

	public function getSampleRate(): float {
		return $this->sampleRate;
	}

	/**
	 * Normalize strings to a metrics-compatible format.
	 *
	 * Replace any other non-alphanumeric characters with underscores.
	 * Eliminate repeated underscores.
	 * Trim leading or trailing underscores.
	 *
	 * @param string $entity
	 * @return string
	 */
	public static function normalizeString( string $entity ): string {
		$entity = preg_replace( '/[^a-z0-9]/i', '_', $entity );
		$entity = preg_replace( '/_+/', '_', $entity );
		return trim( $entity, '_' );
	}

	/**
	 * Returns a subset of samples based on configured sample rate.
	 *
	 * @param float $sampleRate
	 * @param array $samples
	 * @return array
	 */
	public static function getFilteredSamples( float $sampleRate, array $samples ): array {
		if ( $sampleRate === 1.0 ) {
			return $samples;
		}
		$output = [];
		$randMax = mt_getrandmax();
		foreach ( $samples as $sample ) {
			if ( mt_rand() / $randMax < $sampleRate ) {
				$output[] = $sample;
			}
		}
		return $output;
	}

}
