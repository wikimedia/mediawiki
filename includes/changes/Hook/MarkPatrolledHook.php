<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface MarkPatrolledHook {
	/**
	 * Before an edit is marked patrolled.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $rcid ID of the revision to be marked patrolled
	 * @param ?mixed $user the user (object) marking the revision as patrolled
	 * @param ?mixed $wcOnlySysopsCanPatrol config setting indicating whether the user needs to be a
	 *   sysop in order to mark an edit patrolled.
	 * @param ?mixed $auto true if the edit is being marked as patrolled automatically
	 * @param ?mixed &$tags the tags to be applied to the patrol log entry
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMarkPatrolled( $rcid, $user, $wcOnlySysopsCanPatrol, $auto,
		&$tags
	);
}
