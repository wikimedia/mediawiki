<?php

namespace MediaWiki\User\Hook;

use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UserRemoveGroupHook {
	/**
	 * This hook is called when removing a group.
	 *
	 * @since 1.35
	 *
	 * @param User $user The user that is to have a group removed
	 * @param string &$group The group to be removed. Can be modified.
	 * @return bool|void True or no return value to continue or false to abort (override
	 *   the stock group removal)
	 */
	public function onUserRemoveGroup( $user, &$group );
}
