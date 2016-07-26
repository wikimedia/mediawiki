<?php

/**
 * Deferrable Update for closure/callback
 */
class MWCallableUpdate implements DeferrableUpdate, DeferrableCallback {
	/** @var callable */
	private $callback;
	/** @var string */
	private $fname;

	/**
	 * @param callable $callback
	 * @param string $fname Calling method
	 */
	public function __construct( callable $callback, $fname = 'unknown' ) {
		$this->callback = $callback;
		$this->fname = $fname;
	}

	public function doUpdate() {
		call_user_func( $this->callback );
	}

	public function getOrigin() {
		return $this->fname;
	}
}
