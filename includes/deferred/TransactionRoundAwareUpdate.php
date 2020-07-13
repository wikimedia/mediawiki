<?php

/**
 * Deferrable update that specifies whether it must run outside of any explicit
 * LBFactory transaction round or must run inside of a round owned by doUpdate().
 *
 * @stable to implement
 *
 * @since 1.34
 */
interface TransactionRoundAwareUpdate {
	/** @var int No explicit transaction round should be used */
	public const TRX_ROUND_ABSENT = 1;
	/** @var int An explicit transaction round owned by self::doUpdate should be used */
	public const TRX_ROUND_PRESENT = 2;

	/**
	 * @return int One of the class TRX_ROUND_* constants
	 */
	public function getTransactionRoundRequirement();
}
