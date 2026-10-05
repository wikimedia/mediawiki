<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

declare( strict_types=1 );

namespace Wikimedia\Stats\Formatters;

use Wikimedia\Stats\Metrics\MetricInterface;

/**
 * Metrics Formatter Interface
 *
 * @author Cole White
 * @since 1.41
 */
interface FormatterInterface {
	/**
	 * Renders metric to line format.
	 *
	 * @param string $prefix
	 * @param MetricInterface $metric
	 * @return string[]
	 */
	public function getFormattedSamples( string $prefix, MetricInterface $metric ): array;
}
