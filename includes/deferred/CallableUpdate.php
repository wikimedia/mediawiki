<?php

/**
 * Deferrable Update for closure/callback
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
		if ( !is_callable( $callback ) ) {
			throw new MWException( 'Not a valid callback/closure!' );
		}
		$this->callback = $callback;
	}

	/**
	 * Run the update
	 */
	public function doUpdate() {
		call_user_func( $this->callback );
	}

}
