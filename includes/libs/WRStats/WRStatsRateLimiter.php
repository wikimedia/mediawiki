<?php

namespace Wikimedia\WRStats;

/**
 * A rate limiter with a WRStats backend
 *
 * @since 1.39
 */
class WRStatsRateLimiter {
	/** @var StatsStore */
	private $store;
	/** @var array<string,LimitCondition> */
	private $conditions;
	/** @var array<string,array> */
	private $specs;
	/** @var string|string[] */
	private $prefix;
	/** @var float|int|null */
	private $now;

	/** Default number of time buckets per action */
	public const BUCKET_COUNT = 30;

	/**
	 * @internal Use WRStatsFactory::createRateLimiter instead
	 * @param StatsStore $store
	 * @param array<string,LimitCondition> $conditions
	 * @param string|string[] $prefix
	 * @param array $options
	 */
	public function __construct(
		StatsStore $store,
		$conditions,
		$prefix = 'WRLimit',
		$options = []
	) {
		$this->store = $store;
		$this->conditions = $conditions;
		$this->prefix = $prefix;
		$bucketCount = $options['bucketCount'] ?? self::BUCKET_COUNT;

		$specs = [];
		foreach ( $conditions as $name => $condition ) {
			$specs[$name] = [
				'sequences' => [ [
					'timeStep' => $condition->window / $bucketCount,
					'expiry' => $condition->window
				] ]
			];
		}
		$this->specs = $specs;
	}

	/**
	 * Create a batch object for rate limiting of multiple metrics.
	 *
	 * @param int $defaultAmount The amount to increment each metric by, if no
	 *   amount is passed to localOp/globalOp
	 * @return LimitBatch
	 */
	public function createBatch( $defaultAmount = 1 ) {
		return new LimitBatch( $this, $defaultAmount );
	}

	/**
	 * Check whether executing a single operation would exceed the defined limit,
	 * without incrementing the count.
	 *
	 * @param string $condName
	 * @param EntityKey|null $entityKey
	 * @param int $amount
	 * @return LimitOperationResult
	 */
	public function peek(
		string $condName,
		?EntityKey $entityKey = null,
		$amount = 1
	): LimitOperationResult {
		$actions = [ new LimitOperation( $condName, $entityKey, $amount ) ];
		$result = $this->peekBatch( $actions );
		return $result->getAllResults()[0];
	}

	/**
	 * Check whether executing a given set of increment operations would exceed
	 * any defined limit, without actually performing the increment.
	 *
	 * @param LimitOperation[] $operations
	 * @return LimitBatchResult
	 */
	public function peekBatch( array $operations ) {
		$reader = new WRStatsReader( $this->store, $this->specs, $this->prefix );
		if ( $this->now !== null ) {
			$reader->setCurrentTime( $this->now );
		}

		$rates = [];
		$amounts = [];
		foreach ( $operations as $operation ) {
			$name = $operation->condName;
			$cond = $this->conditions[$name] ?? null;
			if ( $cond === null ) {
				throw new WRStatsError( "Unrecognized metric \"$name\"" );
			}
			if ( !isset( $rates[$name] ) ) {
				$range = $reader->latest( $cond->window );
				$rates[$name] = $reader->getRate( $name, $operation->entityKey, $range );
				$amounts[$name] = 0;
			}
			$amounts[$name] += $operation->amount;
		}

		$results = [];
		foreach ( $operations as $i => $operation ) {
			$name = $operation->condName;
			$total = $rates[$name]->total();
			$cond = $this->conditions[$name];
			$results[$i] = new LimitOperationResult(
				$cond,
				$total,
				$total + $amounts[$name]
			);
		}
		return new LimitBatchResult( $results );
	}

	/**
	 * Check if the limit would be exceeded by incrementing the specified
	 * metric. If not, increment it.
	 *
	 * @param string $condName
	 * @param EntityKey|null $entityKey
	 * @param int $amount
	 * @return LimitOperationResult
	 */
	public function tryIncr(
		string $condName,
		?EntityKey $entityKey = null,
		$amount = 1
	): LimitOperationResult {
		$actions = [ new LimitOperation( $condName, $entityKey, $amount ) ];
		$result = $this->tryIncrBatch( $actions );
		return $result->getAllResults()[0];
	}

	/**
	 * Check if the limit would be exceeded by execution of the given set of
	 * increment operations. If not, perform the increments.
	 *
	 * @param LimitOperation[] $operations
	 * @return LimitBatchResult
	 */
	public function tryIncrBatch( array $operations ) {
		$result = $this->peekBatch( $operations );
		if ( $result->isAllowed() ) {
			$this->incrBatch( $operations );
		}
		return $result;
	}

	/**
	 * Unconditionally increment a metric.
	 *
	 * @param string $condName
	 * @param EntityKey|null $entityKey
	 * @param int $amount
	 * @return void
	 */
	public function incr(
		string $condName,
		?EntityKey $entityKey = null,
		$amount = 1
	) {
		$actions = [ new LimitOperation( $condName, $entityKey, $amount ) ];
		$this->incrBatch( $actions );
	}

	/**
	 * Unconditionally increment a set of metrics.
	 *
	 * @param LimitOperation[] $operations
	 */
	public function incrBatch( array $operations ) {
		$writer = new WRStatsWriter( $this->store, $this->specs, $this->prefix );
		if ( $this->now !== null ) {
			$writer->setCurrentTime( $this->now );
		}
		foreach ( $operations as $operation ) {
			$writer->incr(
				$operation->condName,
				$operation->entityKey,
				$operation->amount
			);
		}
		$writer->flush();
	}

	/**
	 * Set the current time.
	 *
	 * @param float|int $now
	 */
	public function setCurrentTime( $now ) {
		$this->now = $now;
	}

	/**
	 * Forget a time set with setCurrentTime(). Use the actual current time.
	 */
	public function resetCurrentTime() {
		$this->now = null;
	}
}
