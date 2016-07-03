<?php

/**
 * Deferrable Update for closure/callback updates via IDatabase::doAtomicSection()
 * @since 1.27
 */
class AtomicSectionUpdate implements DeferrableUpdate {
	/** @var IDatabase */
	private $dbw;
	/** @var string */
	private $fname;
	/** @var Closure|callable */
	private $callback;

	/**
	 * @param IDatabase $dbw
	 * @param string $fname Caller name (usually __METHOD__)
	 * @param callable $callback
	 * @throws InvalidArgumentException
	 * @see IDatabase::doAtomicSection()
	 */
	public function __construct( IDatabase $dbw, $fname, $callback ) {
		$this->dbw = $dbw;
		$this->fname = $fname;
		if ( !is_callable( $callback ) ) {
			throw new InvalidArgumentException( 'Not a valid callback/closure!' );
		}
		$this->callback = $callback;
	}

	public function doUpdate() {
		$this->dbw->doAtomicSection( $this->fname, $this->callback );
	}
}
