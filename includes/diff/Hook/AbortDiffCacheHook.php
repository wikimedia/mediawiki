<?php

namespace MediaWiki\Diff\Hook;

use DifferenceEngine;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "AbortDiffCache" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface AbortDiffCacheHook {
	/**
	 * Use this hook to cancel the caching of a diff.
	 *
	 * @since 1.35
	 *
	 * @param DifferenceEngine $diffEngine
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAbortDiffCache( $diffEngine );
}
