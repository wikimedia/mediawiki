<?php

namespace MediaWiki\Diff\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface DifferenceEngineMarkPatrolledLinkHook {
	/**
	 * Allows extensions to change the "mark as
	 * patrolled" link which is shown both on the diff header as well as on the bottom
	 * of a page, usually wrapped in a span element which has class="patrollink".
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $differenceEngine DifferenceEngine object
	 * @param ?mixed &$markAsPatrolledLink The "mark as patrolled" link HTML (string)
	 * @param ?mixed $rcid Recent change ID (rc_id) for this change (int)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDifferenceEngineMarkPatrolledLink( $differenceEngine,
		&$markAsPatrolledLink, $rcid
	);
}
