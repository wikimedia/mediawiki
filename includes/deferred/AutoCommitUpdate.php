<?php

namespace MediaWiki\Deferred;

use Wikimedia\Rdbms\IDatabase;

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
	 * @param IDatabase $dbw DB handle; update aborts if a transaction now this rolls back
	 * @param string $fname Caller name (usually __METHOD__)
	 * @param callable $callback Callback that takes (IDatabase, method name string)
	 * @param IDatabase[] $conns Cancel the update if a transaction on these
	 * connections is rolled back [optional]
	 */
	public function __construct( IDatabase $dbw, $fname, callable $callback, array $conns = [] ) {
		$this->dbw = $dbw;
		$this->fname = $fname;
		$this->callback = $callback;
		// Register DB connections for which uncommitted changes are related to this update
		$conns[] = $dbw;
		foreach ( $conns as $conn ) {
			if ( $conn->trxLevel() ) {
				$conn->onTransactionResolution( $this->cancelOnRollback( ... ), $fname );
			}
		}
	}

	public function doUpdate() {
		if ( !$this->callback ) {
			return;
		}

		$autoTrx = $this->dbw->getFlag( DBO_TRX );
		$this->dbw->clearFlag( DBO_TRX );
		try {
			( $this->callback )( $this->dbw, $this->fname );
		} finally {
			if ( $autoTrx ) {
				$this->dbw->setFlag( DBO_TRX );
			}
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
}

/** @deprecated class alias since 1.42 */
class_alias( AutoCommitUpdate::class, 'AutoCommitUpdate' );
