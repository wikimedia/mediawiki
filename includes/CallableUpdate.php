<?php

/**
 * Deferrable Update for closure/callabcks
 */
class MWCallableUpdate implements DeferrableUpdate {

	/**
	 * @var closure/callabck
	 */
	private $callback;

	/**
	 * @param callable $callback
	 */
	public function __construct( $callback ) {
		$this->callback = $callback;
	}

	/**
	 * Run the update
	 */
	public function doUpdate() {
		if ( is_callable( $this->callback ) ) {
			call_user_func( $this->callback );
		}
	}

}

