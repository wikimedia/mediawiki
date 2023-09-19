<?php

namespace MediaWiki\Hook;

use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "MarkPatrolledComplete" to register handlers implementing this interface.
 *
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
	 * @param bool $wcOnlySysopsCanPatrol Always false
	 * @param bool $auto Always false
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMarkPatrolledComplete( $rcid, $user, $wcOnlySysopsCanPatrol,
		$auto
	);
}
