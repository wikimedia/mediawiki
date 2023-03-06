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

use IBufferingStatsdDataFactory;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use TypeError;
use Wikimedia\Stats\Emitters\EmitterInterface;
use Wikimedia\Stats\Exceptions\IllegalOperationException;
use Wikimedia\Stats\Exceptions\InvalidConfigurationException;
use Wikimedia\Stats\Metrics\BaseMetric;
use Wikimedia\Stats\Metrics\CounterMetric;
use Wikimedia\Stats\Metrics\GaugeMetric;
use Wikimedia\Stats\Metrics\NullMetric;
use Wikimedia\Stats\Metrics\TimingMetric;

/**
 * StatsFactory Implementation
 *
 * This is the primary interface for validating metrics definitions
 * caching defined metrics, and returning metric instances from cache
 * if previously defined.
 *
 * @author Cole White
 * @since 1.41
 */
class StatsFactory {

	/** @var string */
	private string $component;

	/** @var string[] */
	private array $staticLabelKeys = [];

	/** @var string[] */
	private array $staticLabelValues = [];

	/** @var StatsCache */
	private StatsCache $cache;

	/** @var EmitterInterface */
	private EmitterInterface $emitter;

	/** @var LoggerInterface */
	private LoggerInterface $logger;

	/** @var IBufferingStatsdDataFactory|null */
	private ?IBufferingStatsdDataFactory $statsdDataFactory = null;

	/**
	 * StatsFactory builds, configures, and caches Metrics.
	 *
	 * @param StatsCache $cache
	 * @param EmitterInterface $emitter
	 * @param LoggerInterface $logger
	 * @param string $component
	 */
	public function __construct(
		StatsCache $cache,
		EmitterInterface $emitter,
		LoggerInterface $logger,
		string $component = ''
	) {
		$this->component = StatsUtils::normalizeString( $component );
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
		return new StatsFactory( $this->cache, $this->emitter, $this->logger, $component );
	}

	/**
	 * Adds a label key-value pair to all metrics created by this StatsFactory instance.
	 *
	 * @param string $key
	 * @param string $value
	 * @return $this
	 */
	public function addStaticLabel( string $key, string $value ): StatsFactory {
		if ( !$this->component ) {
			throw new IllegalOperationException( 'Stats: cannot set static labels with undefined component.' );
		}
		if ( count( $this->cache->getAllMetrics() ) > 0 ) {
			throw new IllegalOperationException( 'Stats: cannot set static labels when metrics are in the cache.' );
		}
		$key = StatsUtils::normalizeString( $key );
		StatsUtils::validateLabelKey( $key );
		$this->staticLabelKeys[] = $key;
		$this->staticLabelValues[] = StatsUtils::normalizeString( $value );
		return $this;
	}

	public function withStatsdDataFactory( IBufferingStatsdDataFactory $statsdDataFactory ): StatsFactory {
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
	 * If a collision occurs, returns a NullMetric to suppress exceptions.
	 *
	 * @param string $name
	 * @return TimingMetric|NullMetric
	 */
	public function getTiming( string $name ) {
		return $this->getMetric( $name, TimingMetric::class );
	}

	/**
	 * Send all buffered metrics to the target and destroy the cache.
	 */
	public function flush(): void {
		$this->emitter->send();
		$this->cache->clear();
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
			trigger_error( $ex->getMessage(), E_USER_WARNING );
			return new NullMetric;
		}
		if ( $metric === null ) {
			$baseMetric = new BaseMetric( $this->component, $name );
			$metric = new $className(
				$baseMetric
					->withStatsdDataFactory( $this->statsdDataFactory )
					->withStaticLabels( $this->staticLabelKeys, $this->staticLabelValues ),
				$this->logger
			);
			$this->cache->set( $this->component, $name, $metric );
		}
		return $metric->fresh();
	}
}
