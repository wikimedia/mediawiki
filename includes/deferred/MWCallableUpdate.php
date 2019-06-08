<?php

use Wikimedia\Rdbms\IDatabase;

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
}
