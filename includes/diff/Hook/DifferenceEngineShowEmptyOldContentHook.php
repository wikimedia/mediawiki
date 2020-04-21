<?php

namespace MediaWiki\Diff\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface DifferenceEngineShowEmptyOldContentHook {
	/**
	 * Allows extensions to change the diff
	 * table body (without header) in cases when there is no old revision or the old
	 * and new revisions are identical.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $differenceEngine DifferenceEngine object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDifferenceEngineShowEmptyOldContent( $differenceEngine );
}
