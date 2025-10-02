<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

declare( strict_types=1 );

namespace Wikimedia\Stats\Formatters;

use Wikimedia\Stats\Metrics\MetricInterface;

/**
 * StatsD Wire Format Implementation
 *
 * @author Cole White
 * @since 1.41
 */
class StatsdFormatter implements FormatterInterface {
	/** @inheritDoc */
	public function getFormattedSamples( string $prefix, MetricInterface $metric ): array {
		$output = [];

		// append component to prefix if set
		if ( $metric->getComponent() !== '' ) {
			$prefix .= ".{$metric->getComponent()}";
		}

		// Metrics used in HistogramMetrics are not compatible with StatsD
		if ( $metric->isHistogram() ) {
			return [];
		}

		foreach ( $metric->getSamples() as $sample ) {
			// dot-separate prefix, component, name, and label values `prefix.component.name.value1.value2`
			$stat = implode( '.', [ $prefix, $metric->getName(), ...$sample->getLabelValues() ] );

			// merge value with separator `:42`
			$value = ':' . $sample->getValue();

			// merge type indicator with separator `|c`
			$type = '|' . $metric->getTypeIndicator();

			// blank string if samplerate is 1.0, otherwise add samplerate indicator `|@0.5`
			$sampleRate = $metric->getSampleRate() !== 1.0 ? '|@' . $metric->getSampleRate() : '';

			// combine and append to output `prefix.component.name.value1.value2:42|c|@0.5`
			$output[] = $stat . $value . $type . $sampleRate;
		}
		return $output;
	}
}
