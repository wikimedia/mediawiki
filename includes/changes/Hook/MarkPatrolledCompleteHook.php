<?php

namespace MediaWiki\Hook;

use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface MarkPatrolledCompleteHook {
	/**
	 * This hook is called after an edit is marked patrolled.
	 *
	 * @since 1.35
	 *
	 * @param int $rcid ID of the revision marked as patrolled
	 * @param User $user User who marked the edit patrolled
	 * @param bool $wcOnlySysopsCanPatrol Config setting indicating whether the user must be a
	 *   sysop to patrol the edit
	 * @param bool $auto True if the edit is being marked as patrolled automatically
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMarkPatrolledComplete( $rcid, $user, $wcOnlySysopsCanPatrol,
		$auto
	);
}
