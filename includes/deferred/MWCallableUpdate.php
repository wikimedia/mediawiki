<?php

use Wikimedia\Rdbms\IDatabase;

/**
 * Deferrable Update for closure/callback
 */
class MWCallableUpdate
	implements DeferrableUpdate, DeferrableCallback, TransactionRoundAwareUpdate
{
	/** @var callable|null Callback, or null if it was cancelled */
	private $callback;
	/** @var string Calling method name */
	private $fname;
	/** @var int One of the class TRX_ROUND_* constants */
	private $trxRoundRequirement = self::TRX_ROUND_PRESENT;

	/**
	 * @param callable $callback
	 * @param string $fname Calling method
	 * @param IDatabase|IDatabase[]|null $dbws Abort if any of the specified DB handles have
	 *   a currently pending transaction which later gets rolled back [optional] (since 1.28)
	 */
	public function __construct( callable $callback, $fname = 'unknown', $dbws = [] ) {
		$this->callback = $callback;
		$this->fname = $fname;

		$dbws = is_array( $dbws ) ? $dbws : [ $dbws ];
		foreach ( $dbws as $dbw ) {
			if ( $dbw && $dbw->trxLevel() ) {
				$dbw->onTransactionResolution( [ $this, 'cancelOnRollback' ], $fname );
			}
		}
	}

	public function doUpdate() {
		if ( $this->callback ) {
			call_user_func( $this->callback );
		}
	}

	/**
	 * @private This method is public so that it works with onTransactionResolution()
	 * @param int $trigger
	 */
	public function cancelOnRollback( $trigger ) {
		if ( $trigger === IDatabase::TRIGGER_ROLLBACK ) {
			$this->callback = null;
		}
	}

	public function getOrigin() {
		return $this->fname;
	}

	/**
	 * @since 1.34
	 * @param int $mode One of the class TRX_ROUND_* constants
	 */
	public function setTransactionRoundRequirement( $mode ) {
		$this->trxRoundRequirement = $mode;
	}

	public function getTransactionRoundRequirement() {
		return $this->trxRoundRequirement;
	}
}
