<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

declare( strict_types=1 );

namespace Wikimedia\Stats\Formatters;

use Wikimedia\Stats\Metrics\MetricInterface;

/**
 * DogStatsD Wire Format Implementation
 *
 * @author Cole White
 * @since 1.41
 */
class DogStatsdFormatter implements FormatterInterface {
	/** @inheritDoc */
	public function getFormattedSamples( string $prefix, MetricInterface $metric ): array {
		$output = [];

		// append component to prefix if set
		if ( $metric->getComponent() !== '' ) {
			$prefix .= ".{$metric->getComponent()}";
		}

		foreach ( $metric->getSamples() as $sample ) {
			// dot-separate prefix, component, and name `prefix.component.name`
			$stat = implode( '.', [ $prefix, $metric->getName() ] );

			// merge value with separator `:42`
			$value = ':' . $sample->getValue();

			// merge type indicator with separator `|c`
			$type = '|' . $metric->getTypeIndicator();

			// blank string if samplerate is 1.0, otherwise add samplerate indicator `|@0.5`
			$sampleRate = $metric->getSampleRate() !== 1.0 ? '|@' . $metric->getSampleRate() : '';

			// merge label keys and label values `key:value`
			$labels = [];
			$labelValues = $sample->getLabelValues();
			foreach ( $metric->getLabelKeys() as $i => $labelKey ) {
				$labels[] = $labelKey . ':' . $labelValues[$i];
			}

			// combine label kv pairs `|# key1:value1,key2:value2`
			$tags = $labels === [] ? '' : '|#' . implode( ",", $labels );

			// combine and append to output `prefix.component.name:42|c|@0.5|#key1:value1,key2:value2`
			$output[] = $stat . $value . $type . $sampleRate . $tags;
		}
		return $output;
	}
}
