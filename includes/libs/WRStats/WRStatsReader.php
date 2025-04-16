<?php

namespace Wikimedia\WRStats;

/**
 * Readers gather a batch of read operations, returning
 * promises. The batch is executed when the first promise is resolved.
 *
 * @since 1.39
 */
class WRStatsReader {
	/** @var StatsStore */
	private $store;
	/** @var array<string,MetricSpec> */
	private $metricSpecs;
	/** @var string[] */
	private $prefixComponents;
	/** @var float|int|null The UNIX timestamp used for the current time */
	private $now;
	/** @var bool[] Storage keys ready to be fetched */
	private $queuedKeys = [];
	/** @var int[] Unscaled integers returned by the store, indexed by key */
	private $cachedValues = [];

	/**
	 * @internal Use WRStatsFactory::createReader instead
	 * @param StatsStore $store
	 * @param array<string,array> $specs
	 * @param string|string[] $prefix
	 */
	public function __construct( StatsStore $store, $specs, $prefix ) {
		$this->store = $store;
		$this->metricSpecs = [];
		foreach ( $specs as $name => $spec ) {
			$this->metricSpecs[$name] = new MetricSpec( $spec );
		}
		$this->prefixComponents = is_array( $prefix ) ? $prefix : [ $prefix ];
		if ( !count( $this->prefixComponents ) ) {
			throw new WRStatsError( __METHOD__ .
				': there must be at least one prefix component' );
		}
	}

	/**
	 * Get a TimeRange for some period ending at the current time. Note that
	 * this will use the same value of the current time for subsequent calls
	 * until resetCurrentTime() is called.
	 *
	 * @param int|float $numSeconds
	 * @return TimeRange
	 */
	public function latest( $numSeconds ) {
		$now = $this->now();
		return new TimeRange( $now - $numSeconds, $now );
	}

	/**
	 * Get a specified time range
	 *
	 * @param int|float $start The UNIX time of the start of the range
	 * @param int|float $end The UNIX time of the end of the range
	 * @return TimeRange
	 */
	public function timeRange( $start, $end ) {
		return new TimeRange( $start, $end );
	}

	/**
	 * Queue a fetch operation.
	 *
	 * @param string $metricName The metric name, the key into $specs.
	 * @param EntityKey|null $entity Additional storage key components
	 * @param TimeRange $range The time range to fetch
	 * @return RatePromise
	 */
	public function getRate( $metricName, ?EntityKey $entity, TimeRange $range ) {
		$metricSpec = $this->metricSpecs[$metricName] ?? null;
		if ( $metricSpec === null ) {
			throw new WRStatsError( "Unrecognised metric \"$metricName\"" );
		}
		$entity ??= new LocalEntityKey;
		$now = $this->now();
		$seqSpec = null;
		foreach ( $metricSpec->sequences as $seqSpec ) {
			$seqStart = $now - $seqSpec->softExpiry;
			if ( $seqStart <= $range->start ) {
				break;
			}
		}

		if ( !$seqSpec ) {
			// This check exists to make Phan happy.
			// It should never fail since we apply normalization in MetricSpec::__construct()
			throw new WRStatsError( 'There should have been at least one sequence' );
		}

		$timeStep = $seqSpec->timeStep;
		$firstBucket = (int)( $range->start / $timeStep );
		$lastBucket = (int)ceil( $range->end / $timeStep );
		for ( $bucket = $firstBucket; $bucket <= $lastBucket; $bucket++ ) {
			$key = $this->store->makeKey(
				$this->prefixComponents,
				[ $metricName, $seqSpec->name, $bucket ],
				$entity
			);
			if ( !isset( $this->cachedValues[$key] ) ) {
				$this->queuedKeys[$key] = true;
			}
		}
		return new RatePromise( $this, $metricName, $entity, $metricSpec, $seqSpec, $range );
	}

	/**
	 * Queue a batch of fetch operations for different metrics with the same
	 * time range.
	 *
	 * @param string[] $metricNames
	 * @param EntityKey|null $entity
	 * @param TimeRange $range
	 * @return RatePromise[]
	 */
	public function getRates( $metricNames, ?EntityKey $entity, TimeRange $range ) {
		$rates = [];
		foreach ( $metricNames as $name ) {
			$rates[$name] = $this->getRate( $name, $entity, $range );
		}
		return $rates;
	}

	/**
	 * Perform any queued fetch operations.
	 */
	public function fetch() {
		if ( !$this->queuedKeys ) {
			return;
		}
		$this->cachedValues += $this->store->query( array_keys( $this->queuedKeys ) );
		$this->queuedKeys = [];
	}

	/**
	 * Set the current time to be used in latest() etc.
	 *
	 * @param int|float $now
	 */
	public function setCurrentTime( $now ) {
		$this->now = $now;
	}

	/**
	 * Clear the current time so that it will be filled with the real current
	 * time on the next call.
	 */
	public function resetCurrentTime() {
		$this->now = null;
	}

	/**
	 * @return float|int
	 */
	private function now() {
		$this->now ??= microtime( true );
		return $this->now;
	}

	/**
	 * @internal Utility for resolution in RatePromise
	 * @param string $metricName
	 * @param EntityKey $entity
	 * @param MetricSpec $metricSpec
	 * @param SequenceSpec $seqSpec
	 * @param TimeRange $range
	 * @return float|int
	 */
	public function internalGetCount(
		$metricName,
		EntityKey $entity,
		MetricSpec $metricSpec,
		SequenceSpec $seqSpec,
		TimeRange $range
	) {
		$this->fetch();
		$timeStep = $seqSpec->timeStep;
		$firstBucket = (int)( $range->start / $timeStep );
		$lastBucket = (int)( $range->end / $timeStep );
		$now = $this->now();
		$total = 0;
		for ( $bucket = $firstBucket; $bucket <= $lastBucket; $bucket++ ) {
			$key = $this->store->makeKey(
				$this->prefixComponents,
				[ $metricName, $seqSpec->name, $bucket ],
				$entity
			);
			$value = $this->cachedValues[$key] ?? 0;
			if ( !$value ) {
				continue;
			} elseif ( $bucket === $firstBucket ) {
				if ( $bucket === $lastBucket ) {
					// It can be assumed that there are zero events in the future
					$bucketStartTime = $bucket * $timeStep;
					$rateInterpolationEndTime = min( $bucketStartTime + $timeStep, $now );
					$interpolationDuration = $rateInterpolationEndTime - $bucketStartTime;
					if ( $interpolationDuration > 0 ) {
						$total += $value * $range->getDuration() / $interpolationDuration;
					}
				} else {
					$overlapDuration = max( ( $bucket + 1 ) * $timeStep - $range->start, 0 );
					$total += $value * $overlapDuration / $timeStep;
				}
			} elseif ( $bucket === $lastBucket ) {
				// It can be assumed that there are zero events in the future
				$bucketStartTime = $bucket * $timeStep;
				$rateInterpolationEndTime = min( $bucketStartTime + $timeStep, $now );
				$overlapDuration = max( $range->end - $bucketStartTime, 0 );
				$interpolationDuration = $rateInterpolationEndTime - $bucketStartTime;
				if ( $overlapDuration === $interpolationDuration ) {
					// Special case for 0/0 -- current time exactly on boundary.
					$total += $value;
				} elseif ( $interpolationDuration > 0 ) {
					$total += $value * $overlapDuration / $interpolationDuration;
				}
			} else {
				$total += $value;
			}
		}
		// Round to nearest resolution step for nicer display
		$rounded = round( $total ) * $metricSpec->resolution;
		// Convert to integer if integer is expected
		if ( is_int( $metricSpec->resolution ) ) {
			$rounded = (int)$rounded;
		}
		return $rounded;
	}

	/**
	 * Resolve a batch of RatePromise objects, returning their counter totals,
	 * indexed as in the input array.
	 *
	 * @param array<mixed,RatePromise> $rates
	 * @return array<mixed,float|int>
	 */
	public function total( $rates ) {
		$result = [];
		foreach ( $rates as $key => $rate ) {
			$result[$key] = $rate->total();
		}
		return $result;
	}

	/**
	 * Resolve a batch of RatePromise objects, returning their per-second rates.
	 *
	 * @param array<mixed,RatePromise> $rates
	 * @return array<mixed,float>
	 */
	public function perSecond( $rates ) {
		$result = [];
		foreach ( $rates as $key => $rate ) {
			$result[$key] = $rate->perSecond();
		}
		return $result;
	}

	/**
	 * Resolve a batch of RatePromise objects, returning their per-minute rates.
	 *
	 * @param array<mixed,RatePromise> $rates
	 * @return array<mixed,float>
	 */
	public function perMinute( $rates ) {
		$result = [];
		foreach ( $rates as $key => $rate ) {
			$result[$key] = $rate->perMinute();
		}
		return $result;
	}

	/**
	 * Resolve a batch of RatePromise objects, returning their per-hour rates.
	 *
	 * @param array<mixed,RatePromise> $rates
	 * @return array<mixed,float>
	 */
	public function perHour( $rates ) {
		$result = [];
		foreach ( $rates as $key => $rate ) {
			$result[$key] = $rate->perHour();
		}
		return $result;
	}

	/**
	 * Resolve a batch of RatePromise objects, returning their per-day rates.
	 *
	 * @param array<mixed,RatePromise> $rates
	 * @return array<mixed,float>
	 */
	public function perDay( $rates ) {
		$result = [];
		foreach ( $rates as $key => $rate ) {
			$result[$key] = $rate->perDay();
		}
		return $result;
	}
}
