<?php

namespace Wikimedia\WRStats;

/**
 * A class representing the results from a batch operation.
 *
 * @since 1.39
 */
class LimitBatchResult {
	/** @var LimitOperationResult[] */
	private $results;

	/** @var bool|null */
	private $allowed;

	/**
	 * @internal Use WRStatsFactory::createRateLimiter() instead
	 * @param LimitOperationResult[] $results
	 */
	public function __construct( $results ) {
		$this->results = $results;
	}

	/**
	 * Determine whether the batch as a whole is/was allowed
	 *
	 * @return bool
	 */
	public function isAllowed() {
		if ( $this->allowed === null ) {
			$this->allowed = true;
			foreach ( $this->results as $result ) {
				if ( !$result->isAllowed() ) {
					$this->allowed = false;
					break;
				}
			}
		}
		return $this->allowed;
	}

	/**
	 * Get LimitOperationResult objects for operations exceeding the limit.
	 *
	 * The keys will match the input array. For input arrays constructed by
	 * LimitBatch, the keys will be the condition names.
	 *
	 * @return LimitOperationResult[]
	 */
	public function getFailedResults() {
		$failed = [];
		foreach ( $this->results as $i => $result ) {
			if ( !$result->isAllowed() ) {
				$failed[$i] = $result;
			}
		}
		return $failed;
	}

	/**
	 * Get LimitOperationResult objects for operations not exceeding the limit.
	 *
	 * The keys will match the input array. For input arrays constructed by
	 * LimitBatch, the keys will be the condition names.
	 *
	 * @return LimitOperationResult[]
	 */
	public function getPassedResults() {
		$passed = [];
		foreach ( $this->results as $i => $result ) {
			if ( $result->isAllowed() ) {
				$passed[$i] = $result;
			}
		}
		return $passed;
	}

	/**
	 * Get LimitOperationResult objects for all operations in the batch.
	 *
	 * The keys will match the input array. For input arrays constructed by
	 * LimitBatch, the keys will be the condition names.
	 *
	 * @return LimitOperationResult[]
	 */
	public function getAllResults() {
		return $this->results;
	}
}
