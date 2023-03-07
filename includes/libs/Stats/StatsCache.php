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
 *
 * @file
 */

declare( strict_types=1 );

namespace Wikimedia\Stats;

use TypeError;
use Wikimedia\Stats\Metrics\MetricInterface;
use Wikimedia\Stats\Metrics\NullMetric;

/**
 * Singleton cache for Metric instances.
 *
 * For reuse and collision avoidance.  Serves as the data source for Metric Renderers.
 *
 * @author Cole White
 * @since 1.41
 */
class StatsCache {
	/** @var string */
	public const DELIMITER = '.';

	/** @var MetricInterface[] */
	private array $cache = [];

	/**
	 * Get a metric object from cache or null.
	 *
	 * @param string $component
	 * @param string $name
	 * @param string $expectedClass
	 * @return MetricInterface|null
	 * @throws TypeError If cached value is for a different metric type.
	 */
	public function get( string $component, string $name, string $expectedClass ) {
		$key = self::cacheKey( $component, $name );
		$metric = $this->cache[$key] ?? null;
		if ( $metric !== null && get_class( $metric ) !== $expectedClass ) {
			throw new TypeError( "Encountered metric name collision: $key defined as "
				. get_class( $metric ) . " but $expectedClass was requested" );
		}
		return $metric;
	}

	/**
	 * Add a metric object to the cache.
	 *
	 * @param string $component
	 * @param string $name
	 * @param MetricInterface|NullMetric $metric
	 */
	public function set( string $component, string $name, $metric ): void {
		$this->cache[self::cacheKey( $component, $name )] = $metric;
	}

	/**
	 * Get all metrics from cache.
	 *
	 * @return MetricInterface[]
	 */
	public function getAllMetrics(): array {
		return $this->cache;
	}

	/**
	 * Clears the cache.
	 *
	 * @return void
	 */
	public function clear(): void {
		$this->cache = [];
	}

	/**
	 * Get the metric formatted name.
	 *
	 * Takes the provided name and constructs a more specific name by combining
	 * the service, component, and name options.
	 *
	 * @param string $component
	 * @param string $name
	 * @return string
	 */
	private static function cacheKey( string $component, string $name ): string {
		return implode( self::DELIMITER, [ $component, $name ] );
	}
}
