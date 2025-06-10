<?php

namespace MediaWiki\Deferred;

use Closure;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\Platform\ISQLPlatform;

/**
 * DeferrableUpdate for closure/callable
 *
 * @internal Use DeferredUpdates::addCallableUpdate instead
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
	 * @param callable $callback One of the following:
	 *    - A Closure callback that takes the caller name as its argument
	 *    - A non-Closure callback that takes no arguments
	 * @param string $fname Calling method @phan-mandatory-param
	 * @param IDatabase|IDatabase[] $dependeeDbws DB handles which might have pending writes
	 *  upon which this update depends. If any of the handles already has an open transaction,
	 *  a rollback thereof will cause this update to be cancelled (if it has not already run).
	 *  [optional]
	 */
	public function __construct(
		callable $callback,
		$fname = ISQLPlatform::CALLER_UNKNOWN,
		$dependeeDbws = []
	) {
		$this->callback = $callback;
		$this->fname = $fname;

		$dependeeDbws = is_array( $dependeeDbws ) ? $dependeeDbws : [ $dependeeDbws ];
		foreach ( $dependeeDbws as $dbw ) {
			if ( $dbw->trxLevel() ) {
				$dbw->onTransactionResolution( $this->cancelOnRollback( ... ), $fname );
			}
		}
	}

	public function doUpdate() {
		if ( $this->callback instanceof Closure ) {
			( $this->callback )( $this->fname );
		} elseif ( $this->callback ) {
			// For backwards-compatibility with [$classOrObject, 'func'] style callbacks
			// where the function happened to already take an optional parameter.
			( $this->callback )();
		}
	}

	/**
	 * @internal This method is public so that it works with onTransactionResolution()
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
	 * @param int $mode One of the class TRX_ROUND_* constants
	 */
	public function setTransactionRoundRequirement( $mode ) {
		$this->trxRoundRequirement = $mode;
	}

	public function getTransactionRoundRequirement() {
		return $this->trxRoundRequirement;
	}
}

/** @deprecated class alias since 1.42 */
class_alias( MWCallableUpdate::class, 'MWCallableUpdate' );
