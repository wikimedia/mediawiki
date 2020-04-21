<?php

namespace MediaWiki\Permissions\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserGetRightsHook {
	/**
	 * Called in User::getRights().
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user User to get rights for
	 * @param ?mixed &$rights Current rights
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserGetRights( $user, &$rights );
}
