<?php

namespace MediaWiki\User\Hook;

use User;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserAddGroupHook {
	/**
	 * This hook is alled when adding a group or changing a group's expiry
	 *
	 * @since 1.35
	 *
	 * @param User $user the user object that is to have a group added
	 * @param string &$group the group to add; can be modified
	 * @param string|null &$expiry the expiry time in TS_MW format, or null if the group is not to
	 *   expire; can be modified
	 * @return bool|void True or no return value to continue or false to abort (not add the group)
	 */
	public function onUserAddGroup( $user, &$group, &$expiry );
}
