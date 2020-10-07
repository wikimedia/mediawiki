<?php

namespace MediaWiki\Permissions\Hook;

use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UserGetRightsHook {
	/**
	 * This hook is called in User::getRights().
	 *
	 * @since 1.35
	 *
	 * @param User $user User to get rights for
	 * @param string[] &$rights Current rights
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserGetRights( $user, &$rights );
}
