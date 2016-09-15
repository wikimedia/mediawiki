<?php

/**
 * Deferrable Update for closure/callback
 */
class MWCallableUpdate implements DeferrableUpdate, DeferrableCallback {
	/** @var callable|null */
	private $callback;
	/** @var string */
	private $fname;

	/**
	 * @param callable $callback
	 * @param string $fname Calling method
	 * @param IDatabase|null $dbw Abort if this DB is rolled back [optional] (since 1.28)
	 */
	public function __construct( callable $callback, $fname = 'unknown', IDatabase $dbw = null ) {
		$this->callback = $callback;
		$this->fname = $fname;

		if ( $dbw && $dbw->trxLevel() ) {
			$dbw->onTransactionResolution( [ $this, 'cancelOnRollback' ], $fname );
		}
	}

	public function doUpdate() {
		if ( $this->callback ) {
			call_user_func( $this->callback );
		}
	}

	public function cancelOnRollback( $trigger ) {
		if ( $trigger === IDatabase::TRIGGER_ROLLBACK ) {
			$this->callback = null;
		}
	}

	public function getOrigin() {
		return $this->fname;
	}
}
