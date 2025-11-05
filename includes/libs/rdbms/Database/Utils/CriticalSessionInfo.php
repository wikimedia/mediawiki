<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\Rdbms;

/**
 * @ingroup Database
 * @internal This class should not be used outside of Database
 */
class CriticalSessionInfo {
	/** @var TransactionIdentifier|null */
	public $trxId;
	/** @var bool */
	public $trxExplicit;
	/** @var string[] */
	public $trxWriteCallers;
	/** @var string[] */
	public $trxPreCommitCbCallers;
	/** @var array<string,array> */
	public $namedLocks;
	/** @var array<string,array<string, TempTableInfo>> */
	public $tempTables;

	/**
	 * @param TransactionIdentifier|null $trxId
	 * @param bool $trxExplicit
	 * @param array $trxWriteCallers
	 * @param array $trxPreCommitCbCallers
	 * @param array $namedLocks
	 * @param array $tempTables
	 */
	public function __construct(
		?TransactionIdentifier $trxId,
		bool $trxExplicit,
		array $trxWriteCallers,
		array $trxPreCommitCbCallers,
		array $namedLocks,
		array $tempTables
	) {
		$this->trxId = $trxId;
		$this->trxExplicit = $trxExplicit;
		$this->trxWriteCallers = $trxWriteCallers;
		$this->trxPreCommitCbCallers = $trxPreCommitCbCallers;
		$this->namedLocks = $namedLocks;
		$this->tempTables = $tempTables;
	}
}
