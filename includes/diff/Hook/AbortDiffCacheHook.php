<?php

namespace MediaWiki\Diff\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface AbortDiffCacheHook {
	/**
	 * Can be used to cancel the caching of a diff.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $diffEngine DifferenceEngine object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAbortDiffCache( $diffEngine );
}
