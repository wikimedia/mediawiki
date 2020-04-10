<?php

namespace MediaWiki\User\Hook;

use User;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserRemoveGroupHook {
	/**
	 * This hook is called when removing a group
	 *
	 * @since 1.35
	 *
	 * @param User $user the user object that is to have a group removed
	 * @param string &$group the group to be removed, can be modified
	 * @return bool|void True or no return value to continue or false to abort (override
	 *   the stock group removal)
	 */
	public function onUserRemoveGroup( $user, &$group );
}
