<?php

namespace MediaWiki\Diff\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface DifferenceEngineShowDiffPageMaybeShowMissingRevisionHook {
	/**
	 * called in
	 * DifferenceEngine::showDiffPage() when revision data cannot be loaded.
	 * Return false in order to prevent displaying the missing revision message
	 * (i.e. to prevent DifferenceEngine::showMissingRevision() from being called).
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $differenceEngine DifferenceEngine object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDifferenceEngineShowDiffPageMaybeShowMissingRevision(
		$differenceEngine
	);
}
