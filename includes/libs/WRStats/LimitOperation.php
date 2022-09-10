<?php

namespace Wikimedia\WRStats;

/**
 * Class representing one item in a limit batch
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
