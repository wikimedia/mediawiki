<?php

namespace MediaWiki\Hook;

use Throwable;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "LogException" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface LogExceptionHook {
	/**
	 * This hook is called before an exception (or PHP error) is logged. This is
	 * meant for integration with external error aggregation services; returning false
	 * will NOT prevent logging.
	 *
	 * @since 1.35
	 *
	 * @param Throwable $e
	 * @param bool $suppressed True if the error was suppressed via
	 *   error_reporting()/wfSuppressWarnings()
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLogException( $e, $suppressed );
}
