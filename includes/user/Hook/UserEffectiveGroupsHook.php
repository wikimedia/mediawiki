<?php

namespace MediaWiki\User\Hook;

use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UserEffectiveGroups" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UserEffectiveGroupsHook {
	/**
	 * This hook is called in UserGroupManager::getUserEffectiveGroups().
	 *
	 * @since 1.35
	 *
	 * @param User $user User to get groups for
	 * @param string[] &$groups Current effective groups
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserEffectiveGroups( $user, &$groups );
}
