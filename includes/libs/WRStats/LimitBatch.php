<?php

namespace Wikimedia\WRStats;

/**
 * A class representing a batch of increment/peek operations on a WRStatsRateLimiter
 *
 * @since 1.39
 */
class LimitBatch {
	/** @var WRStatsRateLimiter */
	private $limiter;

	/** @var int */
	private $defaultAmount;

	/** @var LimitOperation[] */
	private $operations = [];

	/**
	 * @internal Use WRStatsFactory::createRateLimiter()->createBatch() instead
	 * @param WRStatsRateLimiter $limiter
	 * @param int $defaultAmount
	 */
	public function __construct(
		WRStatsRateLimiter $limiter,
		$defaultAmount
	) {
		$this->limiter = $limiter;
		$this->defaultAmount = $defaultAmount;
	}

	/**
	 * Construct a local entity key and queue an operation for it.
	 *
	 * @param string $condName The condition name to be incremented/tested,
	 *   which must match one of the ones passed to createRateLimiter()
	 * @param mixed $components Entity key component or array of components
	 * @param int|null $amount The amount to increment by, or null to use the default
	 * @return $this
	 */
	public function localOp( $condName, $components = [], $amount = null ) {
		if ( !is_array( $components ) ) {
			$components = [ $components ];
		}
		$this->queueOp(
			$condName,
			new LocalEntityKey( [ $condName, ...$components ] ),
			$amount
		);
		return $this;
	}

	/**
	 * Construct a global entity key and queue an operation for it.
	 *
	 * @param string $condName The condition name to be incremented/tested,
	 *   which must match one of the ones passed to createRateLimiter()
	 * @param mixed $components Entity key components
	 * @param int|null $amount The amount, or null to use the default
	 * @return $this
	 */
	public function globalOp( $condName, $components = [], $amount = null ) {
		if ( !is_array( $components ) ) {
			$components = [ $components ];
		}
		$this->queueOp(
			$condName,
			new GlobalEntityKey( [ $condName, ...$components ] ),
			$amount
		);
		return $this;
	}

	private function queueOp( string $type, ?EntityKey $entity, ?int $amount ) {
		$amount ??= $this->defaultAmount;
		if ( isset( $this->operations[$type] ) ) {
			throw new WRStatsError( 'Cannot queue multiple actions of the same type, ' .
				'because the result array is indexed by type' );
		}
		$this->operations[$type] = new LimitOperation( $type, $entity, $amount );
	}

	/**
	 * Execute the batch, checking each operation against the defined limit,
	 * but don't actually increment the metrics.
	 *
	 * @return LimitBatchResult
	 */
	public function peek() {
		return $this->limiter->peekBatch( $this->operations );
	}

	/**
	 * Execute the batch, unconditionally incrementing all the specified metrics.
	 */
	public function incr() {
		$this->limiter->incrBatch( $this->operations );
	}

	/**
	 * Execute the batch, checking each operation against the defined limit.
	 * If all operations are allowed, all metrics will be incremented. If some
	 * of the operations exceed the limit, none of the metrics will be
	 * incremented.
	 *
	 * @return LimitBatchResult
	 */
	public function tryIncr() {
		return $this->limiter->tryIncrBatch( $this->operations );
	}
}
