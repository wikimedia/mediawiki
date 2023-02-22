<?php
/**
 * MetricsFactory Implementation
 *
 * This is the primary interface for validating metrics definitions
 * caching defined metrics, and returning metric instances from cache
 * if previously defined.
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
use Psr\Log\LoggerInterface;
use TypeError;
use UDPTransport;
use Wikimedia\Metrics\Exceptions\InvalidConfigurationException;
use Wikimedia\Metrics\Exceptions\UndefinedPrefixException;
use Wikimedia\Metrics\Exceptions\UnsupportedFormatException;
use Wikimedia\Metrics\Metrics\CounterMetric;
use Wikimedia\Metrics\Metrics\GaugeMetric;
use Wikimedia\Metrics\Metrics\NullMetric;
use Wikimedia\Metrics\Metrics\TimingMetric;

class MetricsFactory {

	/** @var string[] */
	private const SUPPORTED_OUTPUT_FORMATS = [ 'statsd', 'dogstatsd', 'null' ];

	/** @var array */
	private const DEFAULT_METRIC_CONFIG = [
		// 'name' => required,
		// 'component' => required,
		'labels' => [],
		'sampleRate' => 1.0,
		'service' => '',
		'format' => 'statsd',
	];

	/** @var MetricsCache */
	private $cache;

	/** @var string|null */
	private $target;

	/** @var string */
	private $format;

	/** @var string */
	private $prefix;

	/** @var LoggerInterface */
	private $logger;

	/**
	 * MetricsFactory builds, configures, and caches Metrics.
	 *
	 * @param array $config associative array:
	 *   - prefix (string): The prefix applied to all metrics.  This could be the service name.
	 *   - target (string): The URI of the statsd/statsd-exporter server.
	 *   - format (string): The output format. See: MetricsFactory::SUPPORTED_OUTPUT_FORMATS
	 *   - component: (string) The MediaWiki component this MetricsFactory instance is for.
	 * @param MetricsCache $cache
	 * @param LoggerInterface $logger
	 * @throws UndefinedPrefixException
	 * @throws UnsupportedFormatException
	 */
	public function __construct( array $config, MetricsCache $cache, LoggerInterface $logger ) {
		$this->cache = $cache;
		$this->logger = $logger;
		$this->target = $config['target'] ?? null;
		$this->format = $config['format'] ?? 'null';
		$this->prefix = $config['prefix'] ?? '';
		if ( $this->prefix === '' ) {
			throw new UndefinedPrefixException( '\'prefix\' option is required and cannot be empty.' );
		}
		$this->prefix = self::normalizeString( $config['prefix'] );
		if ( !in_array( $this->format, self::SUPPORTED_OUTPUT_FORMATS ) ) {
			throw new UnsupportedFormatException(
				'Format "' . $this->format . '" not supported. Expected one of '
				. json_encode( self::SUPPORTED_OUTPUT_FORMATS )
			);
		}
	}

	/**
	 * Makes a new CounterMetric or fetches one from cache.
	 *
	 * If a collision occurs, returns a NullMetric to suppress exceptions.
	 *
	 * @param array $config associative array:
	 *   - name: (string) The metric name
	 *   - component: (string) The component generating the metric
	 *   - labels: (array) List of metric dimensional instantiations for filters and aggregations
	 *   - sampleRate: (float) Optional sampling rate to apply
	 * @return CounterMetric|NullMetric
	 */
	public function getCounter( array $config = [] ) {
		$config = $this->getValidConfig( $config );
		$name = self::normalizeString( $config['name'] );
		$component = self::normalizeString( $config['component'] );
		try {
			MetricUtils::validateMetricName( $name );
			$metric = $this->cache->get( $component, $name, CounterMetric::class );
		} catch ( TypeError | InvalidArgumentException | InvalidConfigurationException $ex ) {
			trigger_error( $ex->getMessage(), E_USER_WARNING );
			// Give the caller something that will absorb further calls.
			return new NullMetric;
		}
		if ( $metric === null ) {
			$metric = new CounterMetric( $config, new MetricUtils() );
			$this->cache->set( $component, $name, $metric );
		}
		$metric->validateLabels( $config['labels'] );
		return $metric;
	}

	/**
	 * Makes a new GaugeMetric or fetches one from cache.
	 *
	 * If a collision occurs, returns a NullMetric to suppress exceptions.
	 *
	 * @param array $config associative array:
	 *   name: (string) The metric name.
	 *   component: (string) The component generating the metric.
	 *   labels: (array) Labels that further identify the metric.
	 * @return GaugeMetric|NullMetric
	 */
	public function getGauge( array $config = [] ) {
		$config = $this->getValidConfig( $config );
		$name = self::normalizeString( $config['name'] );
		$component = self::normalizeString( $config['component'] );
		try {
			MetricUtils::validateMetricName( $name );
			$metric = $this->cache->get( $component, $name, GaugeMetric::class );
		} catch ( TypeError | InvalidArgumentException | InvalidConfigurationException $ex ) {
			// Log the condition and give the caller something that will absorb calls.
			trigger_error( $ex->getMessage(), E_USER_WARNING );
			return new NullMetric;
		}
		if ( $metric === null ) {
			$metric = new GaugeMetric( $config, new MetricUtils() );
			$this->cache->set( $component, $name, $metric );
		}
		$metric->validateLabels( $config['labels'] );
		return $metric;
	}

	/**
	 * Makes a new TimingMetric or fetches one from cache.
	 *
	 * If a collision occurs, returns a NullMetric to suppress exceptions.
	 *
	 * @param array $config associative array:
	 *   - name: (string) The metric name
	 *   - component: (string) The component generating the metric
	 *   - labels: (array) List of metric dimensional instantiations for filters and aggregations
	 *   - sampleRate: (float) Optional sampling rate to apply
	 * @return TimingMetric|NullMetric
	 */
	public function getTiming( array $config = [] ) {
		$config = $this->getValidConfig( $config );
		$name = self::normalizeString( $config['name'] );
		$component = self::normalizeString( $config['component'] );
		try {
			MetricUtils::validateMetricName( $name );
			$metric = $this->cache->get( $component, $name, TimingMetric::class );
		} catch ( TypeError | InvalidArgumentException | InvalidConfigurationException $ex ) {
			// Log the condition and give the caller something that will absorb calls.
			trigger_error( $ex->getMessage(), E_USER_WARNING );
			return new NullMetric;
		}
		if ( $metric === null ) {
			$metric = new TimingMetric( $config, new MetricUtils() );
			$this->cache->set( $component, $name, $metric );
		}
		$metric->validateLabels( $config['labels'] );
		return $metric;
	}

	/**
	 * Send all buffered metrics to the target and destroy the cache.
	 */
	public function flush(): void {
		if ( $this->format !== 'null' && $this->target ) {
			$this->send( UDPTransport::newFromString( $this->target ) );
		}
		$this->cache->clear();
	}

	/**
	 * Get all rendered samples from cache
	 *
	 * @return string[] Flattened list
	 */
	private function getRenderedSamples(): array {
		$renderedSamples = [];
		foreach ( $this->cache->getAllMetrics() as $metric ) {
			foreach ( $metric->render() as $rendered ) {
				$renderedSamples[] = $rendered;
			}
		}
		return $renderedSamples;
	}

	/**
	 * Render the buffer of samples, group them into payloads, and send them through the
	 * provided UDPTransport instance.
	 *
	 * @param UDPTransport $transport
	 */
	protected function send( UDPTransport $transport ): void {
		$payload = '';
		$renderedSamples = $this->getRenderedSamples();
		foreach ( $renderedSamples as $sample ) {
			if ( strlen( $payload ) + strlen( $sample ) + 1 < UDPTransport::MAX_PAYLOAD_SIZE ) {
				$payload .= $sample . "\n";
			} else {
				// Send this payload and make a new one
				$transport->emit( $payload );
				$payload = '';
			}
		}
		// Send what is left in the payload
		if ( strlen( $payload ) > 0 ) {
			$transport->emit( $payload );
		}
	}

	/**
	 * Renders a valid configuration.
	 *
	 * 1. Checks for required options.
	 * 2. Normalize provided options.
	 * 3. Merge provided configuration with default configuration.
	 *
	 * @param array $config associative array:
	 *   - name: (string) The metric name
	 *   - component: (string) The component generating the metric
	 *   - labels: (array) List of metric dimensional instantiations for filters and aggregations
	 *   - sampleRate: (float) Optional sampling rate to apply
	 * @return array
	 * @throws InvalidConfigurationException
	 */
	private function getValidConfig( array $config = [] ): array {
		if ( !isset( $config['name'] ) ) {
			throw new InvalidConfigurationException(
				'\'name\' configuration option is required and cannot be empty.'
			);
		}
		if ( !isset( $config['component'] ) ) {
			throw new InvalidConfigurationException(
				'\'component\' configuration option is required and cannot be empty.'
			);
		}

		$config['prefix'] = $this->prefix;
		$config['format'] = $this->format;
		$config['name'] = self::normalizeString( $config['name'] );
		$config['component'] = self::normalizeString( $config['component'] );
		$config['labels'] = self::normalizeArray( $config['labels'] ?? [] );

		return $config + self::DEFAULT_METRIC_CONFIG;
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
	 * Normalize an array of strings.
	 *
	 * @param string[] $entities
	 * @return string[]
	 */
	public static function normalizeArray( array $entities ): array {
		$normalizedEntities = [];
		foreach ( $entities as $entity ) {
			$normalizedEntities[] = self::normalizeString( $entity );
		}
		return $normalizedEntities;
	}
}
