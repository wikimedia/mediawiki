<?php

/**
 * Deferrable Update for closure/callback updates that should use auto-commit mode
 * @since 1.28
 */
class AutoCommitUpdate implements DeferrableUpdate, DeferrableCallback {
	/** @var IDatabase */
	private $dbw;
	/** @var string */
	private $fname;
	/** @var callable|null */
	private $callback;

	/**
	 * @param IDatabase $dbw
	 * @param string $fname Caller name (usually __METHOD__)
	 * @param callable $callback Callback that takes (IDatabase, method name string)
	 */
	public function __construct( IDatabase $dbw, $fname, callable $callback ) {
		$this->dbw = $dbw;
		$this->fname = $fname;
		$this->callback = $callback;

		if ( $this->dbw->trxLevel() ) {
			$this->dbw->onTransactionResolution( [ $this, 'cancelOnRollback' ], $fname );
		}
	}

	public function doUpdate() {
		if ( !$this->callback ) {
			return;
		}

		$autoTrx = $this->dbw->getFlag( DBO_TRX );
		$this->dbw->clearFlag( DBO_TRX );
		try {
			/** @var Exception $e */
			$e = null;
			call_user_func_array( $this->callback, [ $this->dbw, $this->fname ] );
		} catch ( Exception $e ) {
		}
		if ( $autoTrx ) {
			$this->dbw->setFlag( DBO_TRX );
		}
		if ( $e ) {
			throw $e;
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
