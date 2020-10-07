<?php

namespace MediaWiki\Diff\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface DifferenceEngineRenderRevisionShowFinalPatrolLinkHook {
	/**
	 * Use this hook to not show the final "mark as patrolled" link on the bottom of a page.
	 * This hook has no arguments.
	 *
	 * @since 1.35
	 *
	 * @return bool|void True or no return value to continue, or false to not show the final
	 *   "mark as patrolled" link
	 */
	public function onDifferenceEngineRenderRevisionShowFinalPatrolLink();
}
