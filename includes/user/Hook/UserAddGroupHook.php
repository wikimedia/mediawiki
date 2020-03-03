<?php

namespace MediaWiki\User\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserAddGroupHook {
	/**
	 * Called when adding a group or changing a group's expiry; return
	 * false to override stock group addition.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user the user object that is to have a group added
	 * @param ?mixed &$group the group to add; can be modified
	 * @param ?mixed &$expiry the expiry time in TS_MW format, or null if the group is not to
	 *   expire; can be modified
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserAddGroup( $user, &$group, &$expiry );
}
