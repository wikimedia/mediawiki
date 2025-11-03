<?php

namespace MediaWiki\Hook;

use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "MarkPatrolled" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface MarkPatrolledHook {
	/**
	 * This hook is called before an edit is marked patrolled.
	 *
	 * @since 1.35
	 *
	 * @param int $rcid ID of the revision to be marked patrolled
	 * @param User $user User marking the revision as patrolled
	 * @param bool $wcOnlySysopsCanPatrol Always false
	 * @param bool $auto Always false
	 * @param string[] &$tags Tags to be applied to the patrol log entry
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMarkPatrolled( $rcid, $user, $wcOnlySysopsCanPatrol, $auto,
		&$tags
	);
}
