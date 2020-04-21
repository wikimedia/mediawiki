<?php

namespace MediaWiki\Diff\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface DifferenceEngineShowDiffHook {
	/**
	 * Allows extensions to affect the diff text which
	 * eventually gets sent to the OutputPage object.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $differenceEngine DifferenceEngine object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDifferenceEngineShowDiff( $differenceEngine );
}
