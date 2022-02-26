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

use Psr\Log\LoggerInterface;
use TypeError;
use UDPTransport;
use Wikimedia\Metrics\Exceptions\InvalidConfigurationException;
use Wikimedia\Metrics\Exceptions\UndefinedPrefixException;
use Wikimedia\Metrics\Exceptions\UnsupportedFormatException;

class MetricsFactory {

	/** @var string[] */
	private const SUPPORTED_OUTPUT_FORMATS = [ 'statsd', 'dogstatsd', 'null' ];

	/** @var string */
	private const NAME_DELIMITER = '.';

	/** @var array */
	private const DEFAULT_METRIC_CONFIG = [
		// 'name' => required,
		// 'extension' => required,
		'labels' => [],
		'sampleRate' => 1.0,
		'service' => '',
		'format' => 'statsd',
	];

	/** @var array<CounterMetric|GaugeMetric|TimingMetric> */
	private $cache = [];

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
	 * @param LoggerInterface $logger
	 * @throws UndefinedPrefixException
	 * @throws UnsupportedFormatException
	 */
	public function __construct( array $config, LoggerInterface $logger ) {
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
	 *   - extension: (string) The extension generating the metric
	 *   - labels: (array) List of metric dimensional instantiations for filters and aggregations
	 *   - sampleRate: (float) Optional sampling rate to apply
	 * @return CounterMetric|NullMetric
	 */
	public function getCounter( array $config = [] ) {
		$config = $this->getValidConfig( $config );
		$name = self::getFormattedName( $config['name'], $config['extension'] );
		try {
			$metric = $this->getCachedMetric( $name, CounterMetric::class );
		} catch ( TypeError $ex ) {
			return new NullMetric();
		}

		if ( $metric ) {
			$metric->validateLabels( $config['labels'] );
			return $metric;
		}
		$this->cache[$name] = new CounterMetric( $config, new MetricUtils() );
		return $this->cache[$name];
	}

	/**
	 * Makes a new GaugeMetric or fetches one from cache.
	 *
	 * If a collision occurs, returns a NullMetric to suppress exceptions.
	 *
	 * @param array $config associative array:
	 *   name: (string) The metric name.
	 *   extension: (string) The extension generating the metric.
	 *   labels: (array) Labels that further identify the metric.
	 * @return GaugeMetric|NullMetric
	 */
	public function getGauge( array $config = [] ) {
		$config = $this->getValidConfig( $config );
		$name = self::getFormattedName( $config['name'], $config['extension'] );
		try {
			$metric = $this->getCachedMetric( $name, GaugeMetric::class );
		} catch ( TypeError $ex ) {
			return new NullMetric();
		}

		if ( $metric ) {
			$metric->validateLabels( $config['labels'] );
			return $metric;
		}
		$this->cache[$name] = new GaugeMetric( $config, new MetricUtils() );
		return $this->cache[$name];
	}

	/**
	 * Makes a new TimingMetric or fetches one from cache.
	 *
	 * If a collision occurs, returns a NullMetric to suppress exceptions.
	 *
	 * @param array $config associative array:
	 *   - name: (string) The metric name
	 *   - extension: (string) The extension generating the metric
	 *   - labels: (array) List of metric dimensional instantiations for filters and aggregations
	 *   - sampleRate: (float) Optional sampling rate to apply
	 * @return TimingMetric|NullMetric
	 */
	public function getTiming( array $config = [] ) {
		$config = $this->getValidConfig( $config );
		$name = self::getFormattedName( $config['name'], $config['extension'] );
		try {
			$metric = $this->getCachedMetric( $name, TimingMetric::class );
		} catch ( TypeError $ex ) {
			return new NullMetric();
		}
		if ( $metric ) {
			$metric->validateLabels( $config['labels'] );
			return $metric;
		}
		$this->cache[$name] = new TimingMetric( $config, new MetricUtils() );
		return $this->cache[$name];
	}

	/**
	 * Send all buffered metrics to the target and destroy the cache.
	 */
	public function flush(): void {
		if ( $this->format !== 'null' && $this->target ) {
			$this->send( UDPTransport::newFromString( $this->target ) );
		}
		$this->cache = [];
	}

	/**
	 * Get all rendered samples from cache
	 *
	 * @param array $cache
	 * @return string[] Flattened list
	 */
	private function getRenderedSamples( array $cache ): array {
		$renderedSamples = [];
		foreach ( $cache as $metric ) {
			foreach ( $metric->render() as $rendered ) {
				$renderedSamples[] = $rendered;
			}
		}
		return $renderedSamples;
	}

	/**
	 * Searches the cache for an instance of the requested metric.  Returns null if not found.
	 *
	 * If the requested metric type does not match the metric found in cache, log the error
	 * and return a NullMetric instance.  This is so that exceptions aren't thrown if metric
	 * names are reused as different types.
	 *
	 * @param string $name
	 * @param string $requested_type
	 * @return CounterMetric|GaugeMetric|TimingMetric|null
	 * @throws TypeError
	 */
	private function getCachedMetric( string $name, string $requested_type ) {
		if ( !array_key_exists( $name, $this->cache ) ) {
			return null;
		}

		$metric = $this->cache[$name];
		if ( get_class( $metric ) !== $requested_type ) {
			$msg = 'Metric name collision detected: \'' . $name . '\' defined as type \'' . get_class( $metric )
				. '\' but a \'' . $requested_type . '\' was requested.';
			$this->logger->error( $msg );
			throw new TypeError( $msg );
		}

		return $metric;
	}

	/**
	 * Render the buffer of samples, group them into payloads, and send them through the
	 * provided UDPTransport instance.
	 *
	 * @param UDPTransport $transport
	 */
	protected function send( UDPTransport $transport ): void {
		$payload = '';
		$renderedSamples = $this->getRenderedSamples( $this->cache );
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
	 * Get the metric formatted name.
	 *
	 * Takes the provided name and constructs a more specific name by combining
	 * the service, extension, and name options.
	 *
	 * @param string $name
	 * @param string $extension
	 * @return string
	 */
	private function getFormattedName( string $name, string $extension ): string {
		return implode(
			self::NAME_DELIMITER,
			[ $this->prefix, $extension, self::normalizeString( $name ) ]
		);
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
	 *   - extension: (string) The extension generating the metric
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
		if ( !isset( $config['extension'] ) ) {
			throw new InvalidConfigurationException(
				'\'extension\' configuration option is required and cannot be empty.'
			);
		}

		$config['prefix'] = $this->prefix;
		$config['format'] = $this->format;
		$config['name'] = self::normalizeString( $config['name'] );
		$config['extension'] = self::normalizeString( $config['extension'] );
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
