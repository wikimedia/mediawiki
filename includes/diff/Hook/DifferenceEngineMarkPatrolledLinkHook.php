<?php

namespace MediaWiki\Diff\Hook;

use DifferenceEngine;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface DifferenceEngineMarkPatrolledLinkHook {
	/**
	 * Use this hook to change the "mark as patrolled" link which is shown both
	 * on the diff header as well as on the bottom of a page, usually wrapped in
	 * a span element which has class="patrollink".
	 *
	 * @since 1.35
	 *
	 * @param DifferenceEngine $differenceEngine
	 * @param string &$markAsPatrolledLink "Mark as patrolled" link HTML
	 * @param int $rcid Recent change ID (rc_id) for this change
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDifferenceEngineMarkPatrolledLink( $differenceEngine,
		&$markAsPatrolledLink, $rcid
	);
}
