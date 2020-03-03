<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface LogExceptionHook {
	/**
	 * Called before an exception (or PHP error) is logged. This is
	 * meant for integration with external error aggregation services; returning false
	 * will NOT prevent logging.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $e The exception (in case of a plain old PHP error, a wrapping ErrorException)
	 * @param ?mixed $suppressed true if the error was suppressed via
	 *   error_reporting()/wfSuppressWarnings()
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLogException( $e, $suppressed );
}
