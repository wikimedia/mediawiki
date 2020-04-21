<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface MarkPatrolledCompleteHook {
	/**
	 * After an edit is marked patrolled.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $rcid ID of the revision marked as patrolled
	 * @param ?mixed $user user (object) who marked the edit patrolled
	 * @param ?mixed $wcOnlySysopsCanPatrol config setting indicating whether the user must be a
	 *   sysop to patrol the edit.
	 * @param ?mixed $auto true if the edit is being marked as patrolled automatically
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMarkPatrolledComplete( $rcid, $user, $wcOnlySysopsCanPatrol,
		$auto
	);
}
