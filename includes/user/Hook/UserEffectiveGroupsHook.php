<?php

namespace MediaWiki\User\Hook;

use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UserEffectiveGroupsHook {
	/**
	 * This hook is called in User::getEffectiveGroups().
	 *
	 * @since 1.35
	 *
	 * @param User $user User to get groups for
	 * @param string[] &$groups Current effective groups
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserEffectiveGroups( $user, &$groups );
}
