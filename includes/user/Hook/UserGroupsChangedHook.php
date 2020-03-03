<?php

namespace MediaWiki\User\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserGroupsChangedHook {
	/**
	 * Called after user groups are changed.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user User whose groups changed
	 * @param ?mixed $added Groups added
	 * @param ?mixed $removed Groups removed
	 * @param ?mixed $performer User who performed the change, false if via autopromotion
	 * @param ?mixed $reason The reason, if any, given by the user performing the change,
	 *   false if via autopromotion.
	 * @param ?mixed $oldUGMs An associative array (group name => UserGroupMembership object) of
	 *   the user's group memberships before the change.
	 * @param ?mixed $newUGMs An associative array (group name => UserGroupMembership object) of
	 *   the user's current group memberships.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserGroupsChanged( $user, $added, $removed, $performer,
		$reason, $oldUGMs, $newUGMs
	);
}
