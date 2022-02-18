<?php
/**
 * Timing Metric Implementation
 *
 * Timing metrics track duration data which can be broken into histograms.
 * They are identified by type 'ms'.
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

class TimingMetric {

	/**
	 * The StatsD protocol type indicator:
	 * https://github.com/statsd/statsd/blob/master/docs/metric_types.md
	 * https://docs.datadoghq.com/developers/dogstatsd/datagram_shell/?tab=metrics
	 *
	 * @var string
	 */
	private const TYPE_INDICATOR = 'ms';

	/** @var MetricUtils */
	private $metricUtils;

	/**
	 * @param array $config associative array:
	 *   - name: (string) The metric name
	 *   - extension: (string) The extension generating the metric
	 *   - labels: (array) List of metric dimensional instantiations for filters and aggregations
	 *   - sampleRate: (float) Optional sampling rate to apply
	 * @param MetricUtils $metricUtils
	 */
	public function __construct( array $config, MetricUtils $metricUtils ) {
		$metricUtils->validateConfig( $config );
		$metricUtils->setTypeIndicator( $this::TYPE_INDICATOR );
		$this->metricUtils = $metricUtils;
	}

	/**
	 * Validate provided labels
	 *
	 * @param string[] $labels
	 */
	public function validateLabels( array $labels = [] ) {
		$this->metricUtils->validateLabels( $labels );
	}

	/**
	 * @param float $value
	 * @param string[] $labels
	 */
	public function observe( float $value, array $labels = [] ): void {
		$this->validateLabels( $labels );
		$this->metricUtils->addSample( new Sample( [
			'labels' => MetricsFactory::normalizeArray( $labels ),
			'value' => $value
		] ) );
	}

	/**
	 * @return string[]
	 */
	public function render(): array {
		return $this->metricUtils->render();
	}
}
