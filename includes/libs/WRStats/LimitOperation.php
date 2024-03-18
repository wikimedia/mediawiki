<?php

namespace Wikimedia\WRStats;

/**
 * One item in a LimitBatch.
 *
 * To perform a single operation, it is generally recommended to use
 * the simpler interface of WRStatsRateLimiter::peek(), ::incr(), and
 * ::tryIncr() instead of constructing LimitOperation objects.
 *
 * @newable
 * @since 1.39
 */
class LimitOperation {
	/** @var string */
	public $condName;
	/** @var EntityKey */
	public $entityKey;
	/** @var int */
	public $amount;

	/**
	 * @param string $condName
	 * @param EntityKey|null $entityKey
	 * @param int $amount
	 */
	public function __construct(
		string $condName,
		EntityKey $entityKey = null,
		$amount = 1
	) {
		$this->condName = $condName;
		$this->entityKey = $entityKey ?? new LocalEntityKey;
		$this->amount = $amount;
	}
}
