<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

declare( strict_types=1 );

namespace Wikimedia\Stats\Metrics;

use Wikimedia\Stats\Exceptions\IllegalOperationException;
use Wikimedia\Stats\Exceptions\InvalidConfigurationException;
use Wikimedia\Stats\Sample;
use Wikimedia\Stats\StatsUtils;

/**
 * Base Metric Implementation V1
 *
 * Implements shared Metric functionality:
 *   * Label validation and handling
 *   * Sample rate validation and handling
 *   * Sample generation
 *   * Common properties
 *   * StatsD transition support
 *
 * @author Cole White
 * @since 1.41
 */
class BaseMetric implements BaseMetricInterface {

	private float $sampleRate = StatsUtils::DEFAULT_SAMPLE_RATE;
	private string $name;
	private string $component;

	/** @var array<string,string|null> key-value pairs of metric-specific labels */
	private array $workingLabels = [];

	/** @var Sample[] */
	private array $samples = [];

	/** @inheritDoc */
	public function __construct( string $component, string $name ) {
		$this->component = $component;
		$this->name = StatsUtils::normalizeString( $name );
	}

	/** @inheritDoc */
	public function addSample( Sample $sample ): void {
		$this->samples[] = $sample;
	}

	/** @inheritDoc */
	public function setSampleRate( float $sampleRate ): void {
		if ( $this->hasSamples() ) {
			throw new IllegalOperationException( 'Cannot change sample rate on metric with recorded samples.' );
		}
		StatsUtils::validateNewSampleRate( $sampleRate );
		$this->sampleRate = $sampleRate;
	}

	/** @inheritDoc */
	public function getName(): string {
		return $this->name;
	}

	/** @inheritDoc */
	public function getSampleRate(): float {
		return $this->sampleRate;
	}

	/** @inheritDoc */
	public function getSamples(): array {
		return StatsUtils::getFilteredSamples( $this->sampleRate, $this->samples );
	}

	/** @inheritDoc */
	public function getSampleCount(): int {
		return count( $this->samples );
	}

	/** @inheritDoc */
	public function addLabel( string $key, string $value ): void {
		// Performance optimization: Assume the key is valid and already registered
		if ( !array_key_exists( $key, $this->workingLabels ) ) {
			try {
				StatsUtils::validateLabelKey( $key );
			} catch ( InvalidConfigurationException ) {
				trigger_error(
					"Stats: ($this->name) Non-normalized label keys are deprecated, found '$key'",
					E_USER_WARNING );
				$key = StatsUtils::normalizeString( $key );
			}
			if ( $this->hasSamples() && !array_key_exists( $key, $this->workingLabels ) ) {
				throw new IllegalOperationException( 'Cannot add labels to a metric containing samples' );
			}
		}

		StatsUtils::validateLabelValue( $value );
		$this->workingLabels[$key] = StatsUtils::normalizeString( $value );
	}

	/** @inheritDoc */
	public function getLabels(): array {
		return $this->workingLabels;
	}

	/** @inheritDoc */
	public function getLabelKeys(): array {
		return array_keys( $this->workingLabels );
	}

	/** @inheritDoc */
	public function getLabelValues(): array {
		# make sure all labels are accounted for
		if ( in_array( null, $this->workingLabels, true ) ) {
			throw new IllegalOperationException(
				"Stats: ({$this->getName()}) Cannot associate label keys with label values - "
				. "Not all initialized labels have an assigned value." );
		}

		return array_values( $this->workingLabels );
	}

	/** @inheritDoc */
	public function clearLabels(): void {
		$this->workingLabels = array_fill_keys( array_keys( $this->workingLabels ), null );
	}

	/** @inheritDoc */
	public function getComponent(): string {
		return $this->component;
	}

	private function hasSamples(): bool {
		return (bool)$this->samples;
	}

}
