<?php

namespace MediaWiki\User\Hook;

use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UserLogout" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UserLogoutHook {
	/**
	 * This hook is called before a user logs out.
	 *
	 * @since 1.35
	 *
	 * @param User $user The user that is about to be logged out
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserLogout( $user );
}
