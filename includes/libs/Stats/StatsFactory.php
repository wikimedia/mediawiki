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
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use TypeError;
use Wikimedia\Stats\Emitters\EmitterInterface;
use Wikimedia\Stats\Emitters\NullEmitter;
use Wikimedia\Stats\Exceptions\InvalidConfigurationException;
use Wikimedia\Stats\Metrics\BaseMetric;
use Wikimedia\Stats\Metrics\CounterMetric;
use Wikimedia\Stats\Metrics\GaugeMetric;
use Wikimedia\Stats\Metrics\HistogramMetric;
use Wikimedia\Stats\Metrics\NullMetric;
use Wikimedia\Stats\Metrics\TimingMetric;

/**
 * This is the primary interface for validating metrics definitions,
 * caching defined metrics, and returning metric instances from cache
 * if previously defined.
 *
 * @author Cole White
 * @since 1.41
 */
class StatsFactory {

	private string $component = '';
	private StatsCache $cache;
	private EmitterInterface $emitter;
	private LoggerInterface $logger;

	private ?IBufferingStatsdDataFactory $statsdDataFactory = null;

	/**
	 * StatsFactory builds, configures, and caches Metrics.
	 */
	public function __construct(
		StatsCache $cache,
		EmitterInterface $emitter,
		LoggerInterface $logger,
		?string $component = null
	) {
		if ( $component !== null ) {
			$this->component = StatsUtils::normalizeString( $component );
		}
		$this->cache = $cache;
		$this->emitter = $emitter;
		$this->logger = $logger;
	}

	/**
	 * Returns a new StatsFactory instance prefixed by component.
	 *
	 * @param string $component
	 * @return StatsFactory
	 */
	public function withComponent( string $component ): StatsFactory {
		$statsFactory = new StatsFactory( $this->cache, $this->emitter, $this->logger, $component );
		return $statsFactory->withStatsdDataFactory( $this->statsdDataFactory );
	}

	public function withStatsdDataFactory( ?IBufferingStatsdDataFactory $statsdDataFactory ): StatsFactory {
		$this->statsdDataFactory = $statsdDataFactory;
		return $this;
	}

	/**
	 * Makes a new CounterMetric or fetches one from cache.
	 *
	 * If a collision occurs, returns a NullMetric to suppress exceptions.
	 *
	 * @param string $name
	 * @return CounterMetric|NullMetric
	 */
	public function getCounter( string $name ) {
		return $this->getMetric( $name, CounterMetric::class );
	}

	/**
	 * Makes a new GaugeMetric or fetches one from cache.
	 *
	 * If a collision occurs, returns a NullMetric to suppress exceptions.
	 *
	 * @param string $name
	 * @return GaugeMetric|NullMetric
	 */
	public function getGauge( string $name ) {
		return $this->getMetric( $name, GaugeMetric::class );
	}

	/**
	 * Makes a new TimingMetric or fetches one from cache.
	 *
	 * The timing data should be in the range [5ms, 60s]; use
	 * ::getHistogram() if you need a different range.
	 *
	 * This range limitation is a consequence of the recommended
	 * setup with prometheus/statsd_exporter (as dogstatsd target)
	 * and Prometheus (as time series database), with
	 * `statsd_exporter::histogram_buckets` set to a 5ms-60s range.
	 *
	 * If a collision occurs, returns a NullMetric to suppress exceptions.
	 *
	 * @param string $name
	 * @return TimingMetric|NullMetric
	 */
	public function getTiming( string $name ) {
		return $this->getMetric( $name, TimingMetric::class );
	}

	/**
	 * Makes a new HistogramMetric from a list of buckets.
	 *
	 * Beware: this is for storing non-time data in histograms, like byte
	 * sizes, or time data outside of the range [5ms, 60s].
	 *
	 * Avoid changing the bucket list once a metric has been
	 * deployed.  When bucket list changes are unavoidable, change the metric
	 * name and handle the transition in PromQL.
	 *
	 * @param string $name
	 * @param array<int|float> $buckets
	 * @return HistogramMetric
	 */
	public function getHistogram( string $name, array $buckets ) {
		$name = StatsUtils::normalizeString( $name );
		StatsUtils::validateMetricName( $name );
		return new HistogramMetric( $this, $name, $buckets );
	}

	/**
	 * Send all buffered metrics to the target and destroy the cache.
	 */
	public function flush(): void {
		$cacheSize = $this->getCacheCount();

		// Optimization: To encourage long-running scripts to frequently yield
		// and flush (T181385), it is important that we don't do any work here
		// unless new stats were added to the cache since the last flush.
		if ( $cacheSize > 0 ) {
			$this->getCounter( 'stats_buffered_total' )
				->copyToStatsdAt( 'stats.statslib.buffered' )
				->incrementBy( $cacheSize );

			$this->emitter->send();
			$this->cache->clear();
		}
	}

	/**
	 * Get a total of the number of samples in cache.
	 */
	private function getCacheCount(): int {
		$accumulator = 0;
		foreach ( $this->cache->getAllMetrics() as $metric ) {
			$accumulator += $metric->getSampleCount();
		}
		return $accumulator;
	}

	/**
	 * Fetches a metric from cache or makes a new metric.
	 *
	 * If a metric name collision occurs, returns a NullMetric to suppress runtime exceptions.
	 *
	 * @param string $name
	 * @param string $className
	 * @return CounterMetric|TimingMetric|GaugeMetric|NullMetric
	 */
	private function getMetric( string $name, string $className ) {
		$name = StatsUtils::normalizeString( $name );
		StatsUtils::validateMetricName( $name );
		try {
			$metric = $this->cache->get( $this->component, $name, $className );
		} catch ( TypeError | InvalidArgumentException | InvalidConfigurationException $ex ) {
			// Log the condition and give the caller something that will absorb calls.
			trigger_error( "Stats: {$name}: {$ex->getMessage()}", E_USER_WARNING );
			return new NullMetric;
		}
		if ( $metric === null ) {
			$baseMetric = new BaseMetric( $this->component, $name );
			$metric = new $className( $baseMetric->withStatsdDataFactory( $this->statsdDataFactory ), $this->logger );
			$this->cache->set( $this->component, $name, $metric );
		}
		return $metric->fresh();
	}

	/**
	 * Create a no-op StatsFactory.
	 *
	 * Use this as the default in a service that takes an optional StatsFactory,
	 * or as null implementation in PHPUnit tests, where we don't need to send
	 * output to an actual network service.
	 *
	 * @since 1.42
	 * @return self
	 */
	public static function newNull(): self {
		return new self( new StatsCache(), new NullEmitter(), new NullLogger() );
	}

	/**
	 * Create a stats helper for use in PHPUnit tests.
	 *
	 * Example:
	 *
	 * ```php
	 * $statsHelper = StatsFactory::newUnitTestingHelper();
	 *
	 * $x = new MySubject( $statsHelper->getStatsFactory() );
	 * $x->execute();
	 *
	 * // Assert full (emitting more is unexpected)
	 * $this->assertSame(
	 *     [
	 *         'example_executions_total:1|c|#foo:bar'
	 *     ],
	 *     $statsHelper->consumeAllFormatted()
	 * );
	 *
	 * // Assert partially (at least this should be emitted)
	 * $this->assertSame( 1, $statsHelper->count( 'example_executions_total{foo="bar"}' ) );
	 * ```
	 *
	 * @since 1.44
	 * @return UnitTestingHelper
	 */
	public static function newUnitTestingHelper() {
		return new UnitTestingHelper();
	}
}
