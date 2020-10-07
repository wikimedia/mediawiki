<?php

namespace MediaWiki\Diff\Hook;

use DifferenceEngine;

/**
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
