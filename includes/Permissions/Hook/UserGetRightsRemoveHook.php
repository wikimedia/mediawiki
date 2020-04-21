<?php

namespace MediaWiki\Permissions\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserGetRightsRemoveHook {
	/**
	 * Called in User::getRights(). This hook override
	 * the UserGetRights hook. It can be used to remove rights from user
	 * and ensure that will not be reinserted by the other hook callbacks
	 * therefore this hook should not be used to add any rights, use UserGetRights instead.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user User to get rights for
	 * @param ?mixed &$rights Current rights
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserGetRightsRemove( $user, &$rights );
}
