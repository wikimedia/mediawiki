<?php

namespace MediaWiki\Diff\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface DifferenceEngineRenderRevisionShowFinalPatrolLinkHook {
	/**
	 * An extension can hook into
	 * this hook point and return false to not show the final "mark as patrolled" link
	 * on the bottom of a page.
	 * This hook has no arguments.
	 *
	 * @since 1.35
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDifferenceEngineRenderRevisionShowFinalPatrolLink();
}
