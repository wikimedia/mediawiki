<?php

namespace MediaWiki\Diff\Hook;

use DifferenceEngine;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface DifferenceEngineShowDiffPageMaybeShowMissingRevisionHook {
	/**
	 * This hook is called in DifferenceEngine::showDiffPage() when revision
	 * data cannot be loaded.
	 *
	 * @since 1.35
	 *
	 * @param DifferenceEngine $differenceEngine
	 * @return bool|void True or no return value to continue, or false to
	 *   prevent displaying the missing revision message (i.e. to prevent
	 *   DifferenceEngine::showMissingRevision() from being called)
	 */
	public function onDifferenceEngineShowDiffPageMaybeShowMissingRevision(
		$differenceEngine
	);
}
