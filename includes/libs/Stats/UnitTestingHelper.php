<?php
/**
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
 * @file
 */

declare( strict_types=1 );

namespace Wikimedia\Stats;

use InvalidArgumentException;
use MediaWiki\Logger\LoggerFactory;
use OutOfBoundsException;
use OutOfRangeException;
use Psr\Log\LoggerInterface;
use Wikimedia\Stats\Emitters\NullEmitter;
use Wikimedia\Stats\Formatters\DogStatsdFormatter;
use Wikimedia\Stats\Metrics\MetricInterface;

/**
 * A helper class for testing metrics in Unit Tests.
 *
 * @author Cole White
 * @since 1.44
 */
class UnitTestingHelper {
	public const EQUALS = '=';
	public const NOT_EQUALS = '!=';
	public const EQUALS_REGEX = '=~';
	public const NOT_EQUALS_REGEX = '!~';

	private StatsCache $cache;
	private StatsFactory $factory;
	private string $component = '';
	private LoggerInterface $logger;

	/**
	 * @internal Use StatsFactory::newUnitTestingHelper() instead.
	 */
	public function __construct() {
		$this->cache = new StatsCache();
		$this->logger = LoggerFactory::getInstance( 'Stats' );
		// Disable StatsFactory::flush() and its StatsCache::clear() calls, because automatic
		// flushes would otherwise delete metrics before we can assert them, e.g. after whenever
		// a subject under test commits a database transaction or when a tested Maintenance script
		// prints output.
		$this->factory = new class( $this->cache, new NullEmitter(), $this->logger ) extends StatsFactory {
			public function flush(): void {
			}
		};
	}

	/**
	 * Set a component on the underlying StatsFactory
	 */
	public function withComponent( string $component ): self {
		$this->factory = $this->factory->withComponent( $component );
		$this->component = $component;
		return $this;
	}

	/**
	 * Get the underlying StatsFactory, to pass to your subject under test.
	 */
	public function getStatsFactory(): StatsFactory {
		return $this->factory;
	}

	/**
	 * Get all samples in dogstatsd format
	 *
	 * @return string[]
	 */
	public function getAllFormatted(): array {
		$output = [];
		$dogFmt = new DogStatsdFormatter();
		foreach ( $this->cache->getAllMetrics() as $metric ) {
			$output = array_merge( $output, $dogFmt->getFormattedSamples( 'mediawiki', $metric ) );
		}
		return $output;
	}

	/**
	 * Get all samples in dogstatsd format and clear the buffer
	 *
	 * @return string[]
	 */
	public function consumeAllFormatted(): array {
		$output = $this->getAllFormatted();
		$this->cache->clear();
		return $output;
	}

	/**
	 * How many samples were observed for a given metric.
	 *
	 * Example:
	 * ```php
	 * $unitTestingHelper->count( 'the_metric_name{fooLabel="bar"}' );
	 * ```
	 */
	public function count( string $selector ): int {
		return count( $this->getFilteredSamples( $selector ) );
	}

	/**
	 * The last recorded sample value for a given metric.
	 *
	 * Example:
	 * ```php
	 * $unitTestingHelper->last( 'the_metric_name{fooLabel="bar"}' );
	 * ```
	 */
	public function last( string $selector ): float {
		$samples = $this->getFilteredSamples( $selector );
		return $samples[array_key_last( $samples )]->getValue();
	}

	/**
	 * The sum of all sample values for a given metric.
	 *
	 * Example:
	 * ```php
	 * $unitTestingHelper->sum( 'the_metric_name{fooLabel="bar"}' );
	 * ```
	 */
	public function sum( string $selector ): float {
		$output = 0;
		foreach ( $this->getFilteredSamples( $selector ) as $sample ) {
			$output += $sample->getValue();
		}
		return $output;
	}

	/**
	 * The max of all sample values for a given metric.
	 *
	 * Example:
	 * ```php
	 * $unitTestingHelper->max( 'the_metric_name{fooLabel="bar"}' );
	 * ```
	 */
	public function max( string $selector ): float {
		$output = 0;
		foreach ( $this->getFilteredSamples( $selector ) as $sample ) {
			if ( $sample->getValue() > $output ) {
				$output = $sample->getValue();
			}
		}
		return $output;
	}

	/**
	 * The median of all sample values for a given metric.
	 *
	 * Example:
	 * ```php
	 * $unitTestingHelper->median( 'the_metric_name{fooLabel="bar"}' );
	 * ```
	 */
	public function median( string $selector ): float {
		return $this->sum( $selector ) / $this->count( $selector );
	}

	/**
	 * The min of all sample values for a given metric.
	 *
	 * Example:
	 * ```php
	 * $unitTestingHelper->min( 'the_metric_name{fooLabel="bar"}' );
	 * ```
	 */
	public function min( string $selector ): float {
		$output = INF;
		foreach ( $this->getFilteredSamples( $selector ) as $sample ) {
			if ( $sample->getValue() < $output ) {
				$output = $sample->getValue();
			}
		}
		return $output;
	}

	private function getMetricFromSelector( string $selector ): MetricInterface {
		$key = StatsCache::cacheKey( $this->component, $this->getName( $selector ) );
		$metric = $this->cache->getAllMetrics()[$key] ?? null;
		if ( $metric === null ) {
			# provide debug info
			$this->logger->debug( 'Metrics in cache:' );
			foreach ( $this->cache->getAllMetrics() as $metric ) {
				$name = $metric->getName();
				$sampleCount = $metric->getSampleCount();
				$this->logger->debug( "  $name", [ 'samples' => $sampleCount ] );
			}
			throw new OutOfBoundsException( "Could not find metric with key '$key'" );
		}
		return $metric;
	}

	private function getFilteredSamples( string $selector ): array {
		$metric = $this->getMetricFromSelector( $selector );
		$filters = $this->getFilters( $selector );
		$labelKeys = $metric->getLabelKeys();
		$left = $metric->getSamples();
		$right = [];
		foreach ( $filters as $filter ) {
			[ $key, $value, $operator ] = $filter;
			$labelPosition = array_search( $key, $labelKeys );
			foreach ( $left as $sample ) {
				if ( $this->matches( $sample->getLabelValues()[$labelPosition], $value, $operator ) ) {
					$right[] = $sample;
				}
			}
			$left = $right;
		}
		if ( count( $left ) === 0 ) {
			throw new OutOfRangeException( "Metric selector '$selector' matched zero samples." );
		}
		return $left;
	}

	private function getName( string $selector ): string {
		$selector = preg_replace( '/\'/', '"', $selector );
		if ( str_contains( $selector, '{' ) ) {
			$selector = substr( $selector, 0, strpos( $selector, '{' ) );
		}
		if ( !$selector ) {
			throw new InvalidArgumentException( 'Selector cannot be empty.' );
		}
		return $selector;
	}

	private function getFilters( string $selector ): array {
		$selector = preg_replace( '/\'/', '"', $selector );
		if ( !str_contains( $selector, '{' ) && !str_contains( $selector, ',' ) ) {
			return [];
		}
		$output = [];
		$filters = substr( $selector, strpos( $selector, '{' ) + 1, -1 );
		$filters = explode( ',', $filters );
		foreach ( $filters as $filter ) {
			$output[] = $this->getFilterComponents( $filter );
		}
		return $output;
	}

	private function getFilterComponents( string $filter ): array {
		$key = null;
		$value = null;
		$operator = null;
		if ( str_contains( $filter, self::EQUALS ) ) {
			[ $key, $value ] = explode( self::EQUALS, $filter );
			$operator = self::EQUALS;
		}
		if ( str_contains( $filter, self::EQUALS_REGEX ) ) {
			[ $key, $value ] = explode( self::EQUALS_REGEX, $filter );
			$operator = self::EQUALS_REGEX;
		}
		if ( str_contains( $filter, self::NOT_EQUALS_REGEX ) ) {
			[ $key, $value ] = explode( self::NOT_EQUALS_REGEX, $filter );
			$operator = self::NOT_EQUALS_REGEX;
		}
		if ( str_contains( $filter, self::NOT_EQUALS ) ) {
			[ $key, $value ] = explode( self::NOT_EQUALS, $filter );
			$operator = self::NOT_EQUALS;
		}
		if ( !$key || !$value || !$operator ) {
			$this->logger->debug(
				'Got filter expression: {' . $filter . '}',
				[ 'key' => $key, 'value' => $value, 'operator' => $operator ]
			);
			throw new InvalidArgumentException( "Filter components cannot be empty." );
		}
		$key = preg_replace( '/[^a-z\d_]+/i', '', $key );
		$value = preg_replace( '/[^a-z\d_]+/i', '', $value );
		return [ $key, $value, $operator ];
	}

	/**
	 * Return the boolean result of stored and expected values according to the operator.
	 */
	private function matches( string $stored, string $expected, string $operator ): bool {
		if ( $operator === self::NOT_EQUALS ) {
			return $stored != $expected;
		}
		if ( $operator === self::EQUALS_REGEX ) {
			return (bool)preg_match( "/$expected/", $stored );
		}
		if ( $operator === self::NOT_EQUALS_REGEX ) {
			return !preg_match( "/$expected/", $stored );
		}
		return $stored === $expected;
	}
}
