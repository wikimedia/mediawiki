<?php

namespace MediaWiki\Deferred;

use Wikimedia\Rdbms\IDatabase;

/**
 * Deferrable Update for closure/callback updates via IDatabase::doAtomicSection()
 * @since 1.27
 */
class AtomicSectionUpdate implements DeferrableUpdate, DeferrableCallback {
	/** @var IDatabase */
	private $dbw;
	/** @var string */
	private $fname;
	/** @var callable|null */
	private $callback;

	/**
	 * @see IDatabase::doAtomicSection()
	 * @param IDatabase $dbw DB handle; update aborts if a transaction now this rolls back
	 * @param string $fname Caller name (usually __METHOD__)
	 * @param callable $callback
	 * @param IDatabase[] $conns Cancel the update if a DB transaction is rolled back [optional]
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
		if ( $this->callback ) {
			$this->dbw->doAtomicSection( $this->fname, $this->callback );
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

	/** @inheritDoc */
	public function getOrigin() {
		return $this->fname;
	}
}

/** @deprecated class alias since 1.42 */
class_alias( AtomicSectionUpdate::class, 'AtomicSectionUpdate' );
