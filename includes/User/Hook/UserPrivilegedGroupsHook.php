<?php

namespace MediaWiki\User\Hook;

use MediaWiki\User\UserIdentity;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UserPrivilegedGroups" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UserPrivilegedGroupsHook {
	/**
	 * This hook is called in UserGroupManager::getUserPrivilegedGroups().
	 *
	 * @since 1.41 (also backported to 1.39.5 and 1.40.1)
	 *
	 * @param UserIdentity $userIdentity User identity to get groups for
	 * @param string[] &$groups Current privileged groups
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserPrivilegedGroups( UserIdentity $userIdentity, array &$groups );
}
