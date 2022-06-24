<?php

namespace Wikimedia\WRStats;

/**
 * Information about the result of a single item in a limit batch
 *
 * @since 1.39
 */
class LimitOperationResult {
	/** @var LimitCondition */
	public $condition;

	/** @var int The previous metric value before the current action was executed */
	public $prevTotal;

	/** @var int The value the metric would have if the increment operation were allowed */
	public $newTotal;

	/**
	 * @internal
	 *
	 * @param LimitCondition $condition
	 * @param int $prevTotal
	 * @param int $newTotal
	 */
	public function __construct( LimitCondition $condition, $prevTotal, $newTotal ) {
		$this->condition = $condition;
		$this->prevTotal = $prevTotal;
		$this->newTotal = $newTotal;
	}

	/**
	 * Whether the operation was/is allowed.
	 *
	 * @return bool
	 */
	public function isAllowed() {
		return $this->newTotal <= $this->condition->limit;
	}

	/**
	 * Get a string representing the object, for testing or debugging
	 *
	 * @return string
	 */
	public function dump() {
		return "LimitActionResult{{$this->newTotal}/{$this->condition->limit}}";
	}
}
