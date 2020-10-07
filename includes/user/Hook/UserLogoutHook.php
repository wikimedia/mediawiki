<?php

namespace MediaWiki\User\Hook;

use User;

/**
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
