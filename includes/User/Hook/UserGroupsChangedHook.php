<?php

namespace MediaWiki\User\Hook;

use MediaWiki\User\User;
use MediaWiki\User\UserGroupMembership;
use MediaWiki\User\UserIdentity;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UserGroupsChanged" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UserGroupsChangedHook {
	/**
	 * This hook is called after user groups are changed.
	 *
	 * @since 1.35
	 *
	 * @param User|UserIdentity $user User whose groups changed, for local group changes this is a
	 *   User class, for interwiki group changes this was a UserRightsProxy until 1.40 and is a UserIdentity since 1.41
	 * @param string[] $added Groups added
	 * @param string[] $removed Groups removed
	 * @param User|false $performer User who performed the change, false if via autopromotion
	 * @param string|false $reason The reason, if any, given by the user performing the change,
	 *   false if via autopromotion.
	 * @param UserGroupMembership[] $oldUGMs An associative array (group name => UserGroupMembership)
	 *   of the user's group memberships before the change.
	 * @param UserGroupMembership[] $newUGMs An associative array (group name => UserGroupMembership)
	 *   of the user's current group memberships.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserGroupsChanged(
		$user,
		$added,
		$removed,
		$performer,
		$reason,
		$oldUGMs,
		$newUGMs
	);
}
