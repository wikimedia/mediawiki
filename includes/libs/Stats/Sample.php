<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

declare( strict_types=1 );

namespace Wikimedia\Stats;

/**
 * A container for a metric sample to be passed to the rendering function.
 *
 * @author Cole White
 * @since 1.41
 */
class Sample {

	/** @var string[] */
	private array $labelValues;
	private float $value;

	/**
	 * @param string[] $labelValues
	 * @param float $value
	 */
	public function __construct( array $labelValues, float $value ) {
		$this->labelValues = $labelValues;
		$this->value = $value;
	}

	/** @return string[] */
	public function getLabelValues(): array {
		return $this->labelValues;
	}

	public function getValue(): float {
		return $this->value;
	}
}
