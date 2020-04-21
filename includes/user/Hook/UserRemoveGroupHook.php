<?php

namespace MediaWiki\User\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserRemoveGroupHook {
	/**
	 * Called when removing a group; return false to override stock
	 * group removal.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user the user object that is to have a group removed
	 * @param ?mixed &$group the group to be removed, can be modified
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserRemoveGroup( $user, &$group );
}
