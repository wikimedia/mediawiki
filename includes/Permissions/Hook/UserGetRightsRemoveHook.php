<?php

namespace MediaWiki\Permissions\Hook;

use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UserGetRightsRemoveHook {
	/**
	 * This hook is called in User::getRights(). This hook overrides
	 * the UserGetRights hook. It can be used to remove rights from a user
	 * and ensure that they will not be reinserted by the other hook callbacks.
	 * This hook should not be used to add any rights; use UserGetRights instead.
	 *
	 * @since 1.35
	 *
	 * @param User $user User to get rights for
	 * @param string[] &$rights Current rights
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserGetRightsRemove( $user, &$rights );
}
