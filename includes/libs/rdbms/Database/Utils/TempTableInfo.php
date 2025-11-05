<?php

namespace Wikimedia\Rdbms;

/**
 * @internal
 */
class TempTableInfo {
	/**
	 * @var ?TransactionIdentifier The transaction ID in which the table was
	 *   created. This is used to judge whether a rollback is tolerable.
	 */
	public ?TransactionIdentifier $trxId;

	/**
	 * @var bool Whether the table is a pseudo-permanent temporary table, that is,
	 * duplicated for PHPUnit testing.
	 */
	public bool $pseudoPermanent;

	public function __construct( ?TransactionIdentifier $trxId, bool $pseudoPermanent ) {
		$this->trxId = $trxId;
		$this->pseudoPermanent = $pseudoPermanent;
	}
}
