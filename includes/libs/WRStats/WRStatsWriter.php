<?php

namespace Wikimedia\WRStats;

/**
 * Writers gather a batch of increment operations and then
 * commit them when flush() is called, or when the writer is destroyed.
 *
 * @since 1.39
 */
class WRStatsWriter {
	/** @var StatsStore */
	private $store;
	/** @var MetricSpec[] */
	private $metricSpecs;
	/** @var float[][] Values indexed by TTL and storage key */
	private $queuedValues = [];
	/** @var float|int|null The UNIX timestamp used for the current time */
	private $now;
	/** @var string[] */
	private $prefixComponents;

	/**
	 * @internal
	 *
	 * @param StatsStore $store
	 * @param array $specs
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
	 * Queue an increment operation.
	 *
	 * @param string $name The metric name
	 * @param EntityKey|null $entity Additional storage key components
	 * @param float|int $value The value to add
	 */
	public function incr( $name, ?EntityKey $entity = null, $value = 1 ) {
		$metricSpec = $this->metricSpecs[$name] ?? null;
		$entity ??= new LocalEntityKey;
		if ( $metricSpec === null ) {
			throw new WRStatsError( __METHOD__ . ": Unrecognised metric \"$name\"" );
		}
		$res = $metricSpec->resolution;
		$scaledValue = $value / $res;

		foreach ( $metricSpec->sequences as $seqSpec ) {
			$timeStep = $seqSpec->timeStep;
			$timeBucket = (int)( $this->now() / $timeStep );
			$key = $this->store->makeKey(
				$this->prefixComponents,
				[ $name, $seqSpec->name, $timeBucket ],
				$entity
			);

			$ttl = $seqSpec->hardExpiry;

			if ( !isset( $this->queuedValues[$ttl][$key] ) ) {
				$this->queuedValues[$ttl][$key] = 0;
			}
			$this->queuedValues[$ttl][$key] += (int)round( $scaledValue );
		}
	}

	/**
	 * Set the time to be used as the current time
	 *
	 * @param float|int $now
	 */
	public function setCurrentTime( $now ) {
		$this->now = $now;
	}

	/**
	 * Reset the stored current time. In a long-running process this should be
	 * called regularly to write new results.
	 *
	 * @return void
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
	 * Commit the batch of increment operations.
	 */
	public function flush() {
		foreach ( $this->queuedValues as $ttl => $values ) {
			$this->store->incr( $values, $ttl );
		}
		$this->queuedValues = [];
	}

	/**
	 * Commit the batch of increment operations.
	 */
	public function __destruct() {
		$this->flush();
	}

	/**
	 * Delete all stored metrics corresponding to the specs supplied to the
	 * constructor, resetting the counters to zero.
	 *
	 * @param EntityKey[]|null $entities An array of additional storage key
	 *   components. The default is the empty local entity.
	 */
	public function resetAll( ?array $entities = null ) {
		$entities ??= [ new LocalEntityKey ];
		$this->queuedValues = [];
		$keys = [];
		foreach ( $this->metricSpecs as $name => $metricSpec ) {
			foreach ( $metricSpec->sequences as $seqSpec ) {
				$timeStep = $seqSpec->timeStep;
				$ttl = $seqSpec->hardExpiry;
				$lastBucket = (int)( $this->now() / $timeStep ) + 1;
				$firstBucket = (int)( ( $this->now() - $ttl ) / $timeStep ) - 1;
				for ( $bucket = $firstBucket; $bucket <= $lastBucket; $bucket++ ) {
					foreach ( $entities as $entity ) {
						$keys[] = $this->store->makeKey(
							$this->prefixComponents,
							[ $name, $seqSpec->name, $bucket ],
							$entity
						);
					}
				}
			}
		}
		$this->store->delete( $keys );
	}
}
