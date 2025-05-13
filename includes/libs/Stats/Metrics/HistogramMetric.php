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

namespace Wikimedia\Stats\Metrics;

use InvalidArgumentException;
use Wikimedia\Stats\StatsFactory;

/**
 * Histogram Metric Implementation
 *
 * Histogram Metrics are a collection of CounterMetric arranged into buckets.
 *
 * @author Cole White
 * @since 1.44
 */
class HistogramMetric {

	/** @var int Maximum number of allowed buckets. */
	private const MAX_BUCKETS = 10;

	private StatsFactory $statsFactory;
	private string $name;
	private array $buckets;
	private array $labels = [];

	public function __construct( StatsFactory $statsFactory, string $name, array $buckets ) {
		$this->statsFactory = $statsFactory;
		$this->name = $name;
		if ( !$buckets ) {
			throw new InvalidArgumentException( "Stats: ({$name}) Histogram buckets cannot be an empty array." );
		}
		$bucketCount = count( $buckets );
		if ( $bucketCount > self::MAX_BUCKETS ) {
			throw new InvalidArgumentException(
				"Stats: ({$name}) Too many buckets defined. Got:{$bucketCount}, Max:" . self::MAX_BUCKETS
			);
		}
		foreach ( $buckets as $bucket ) {
			if ( !( is_float( $bucket ) || is_int( $bucket ) ) ) {
				throw new InvalidArgumentException( "Stats: ({$name}) Histogram buckets can only be float or int." );
			}
		}
		$normalizedBuckets = array_unique( $buckets );
		sort( $normalizedBuckets, SORT_NUMERIC );
		if ( $buckets !== $normalizedBuckets ) {
			throw new InvalidArgumentException(
				"Stats: ({$name}) Histogram buckets must be unique and in order of least to greatest."
			);
		}
		$this->buckets = $buckets;
	}

	/**
	 * Ensure every bucket has 0 as the first sample so that it is
	 * emitted for the exporter to report.
	 */
	private function preloadBuckets( CounterMetric $metric ): void {
		$metric->setBucket( '+Inf' );
		$metric->incrementBy( 0 );
		foreach ( $this->buckets as $bucket ) {
			$metric->setBucket( $bucket );
			$metric->incrementBy( 0 );
		}
	}

	/**
	 * Increments bucket associated with the provided value.
	 */
	public function observe( float $value ): void {
		$count = $this->statsFactory->getCounter( "{$this->name}_count" );
		$bucket = $this->statsFactory->getCounter( "{$this->name}_bucket" );
		$sum = $this->statsFactory->getCounter( "{$this->name}_sum" );

		foreach ( $this->labels as $k => $v ) {
			$count->setLabel( $k, $v );
			$bucket->setLabel( $k, $v );
			$sum->setLabel( $k, $v );
		}

		if ( $bucket->getSampleCount() === 0 ) {
			$this->preloadBuckets( $bucket );
		}

		$bucket->setBucket( '+Inf' )->increment();
		foreach ( $this->buckets as $le ) {
			if ( $value <= $le ) {
				$bucket->setBucket( $le )->increment();
			}
		}

		$count->increment();
		$sum->incrementBy( $value );
	}

	/**
	 * Adds a label $key with $value.  No order is respected.
	 *
	 * @return HistogramMetric
	 */
	public function setLabel( string $key, string $value ): self {
		// each metric will run its own validation logic
		$this->labels[$key] = $value;
		return $this;
	}

	/**
	 * Returns metric with cleared labels.
	 *
	 * @return HistogramMetric
	 */
	public function fresh(): self {
		$this->labels = [];
		return $this;
	}
}
