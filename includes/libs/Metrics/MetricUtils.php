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

use Wikimedia\Metrics\Exceptions\InvalidConfigurationException;
use Wikimedia\Metrics\Exceptions\InvalidLabelsException;

class MetricUtils {

	/** @var string */
	private const RE_VALID_NAME_AND_LABEL_NAME = '/^[a-zA-Z_][a-zA-Z0-9_]*$/';

	/** @var string */
	protected $prefix;

	/** @var string */
	protected $extension;

	/** @var string */
	protected $format;

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
	 *   - extension: (string) The extension generating the metric
	 *   - labels: (array) List of metric dimensional instantiations for filters and aggregations
	 *   - sampleRate: (float) Optional sampling rate to apply
	 *   - format: (string) The expected output format -- one of MetricsFactory::SUPPORTED_OUTPUT_FORMATS
	 */
	public function validateConfig( $config ) {
		$this->prefix = $config['prefix'];
		$this->extension = $config['extension'];
		$this->name = $config['name'];
		$this->sampleRate = $config['sampleRate'];
		$this->format = $config['format'];
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
	 * @return string[]
	 */
	public function render(): array {
		$output = [];
		switch ( $this->format ) {
			case 'dogstatsd':
				foreach ( $this->getFilteredSamples() as $sample ) {
					$output[] = $this->renderDogStatsD( $sample );
				}
				break;
			case 'statsd':
				foreach ( $this->getFilteredSamples() as $sample ) {
					$output[] = $this->renderStatsD( $sample );
				}
				break;
			default:  // "null"
				break;
		}
		return $output;
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
	 * Get set of samples filtered according to configured sampleRate.
	 * @return array
	 */
	private function getFilteredSamples() {
		if ( $this->sampleRate === 1.0 ) {
			return $this->samples;
		}
		$output = [];
		$randMax = mt_getrandmax();
		foreach ( $this->samples as $sample ) {
			if ( mt_rand() / $randMax < $this->sampleRate ) {
				$output[] = $sample;
			}
		}
		return $output;
	}

	/**
	 * Renders metrics in StatsD format
	 * @param Sample $sample
	 * @return string
	 */
	private function renderStatsD( Sample $sample ): string {
		$stat = implode( '.',
			array_merge( [ $this->prefix, $this->extension, $this->name ], $sample->getLabels() )
		);
		$value = ':' . $sample->getValue();
		$type = '|' . $this->typeIndicator;
		$sampleRate = $this->sampleRate !== 1.0 ? '|@' . $this->sampleRate : '';

		return $stat . $value . $type . $sampleRate;
	}

	/**
	 * Renders metrics in DogStatsD format
	 * https://docs.datadoghq.com/developers/dogstatsd/datagram_shell/?tab=metrics
	 *
	 * @param Sample $sample
	 * @return string
	 */
	private function renderDogStatsD( Sample $sample ): string {
		$stat = implode( '.', [ $this->prefix, $this->extension, $this->name ] );
		$sampleLabels = $sample->getLabels();
		$labels = [];
		foreach ( $this->labels as $i => $label ) {
			$labels[] = $label . ':' . $sampleLabels[$i];
		}
		$value = ':' . $sample->getValue();
		$type = '|' . $this->typeIndicator;
		$sampleRate = $this->sampleRate !== 1.0 ? '|@' . $this->sampleRate : '';
		$tags = $labels === [] ? '' : '|#' . implode( ',', $labels );
		return $stat . $value . $type . $sampleRate . $tags;
	}

}
