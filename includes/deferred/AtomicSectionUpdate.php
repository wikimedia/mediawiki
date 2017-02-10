<?php

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
	 * @param IDatabase $dbw
	 * @param string $fname Caller name (usually __METHOD__)
	 * @param callable $callback
	 * @see IDatabase::doAtomicSection()
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
		if ( $this->callback ) {
			$this->dbw->doAtomicSection( $this->fname, $this->callback );
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
