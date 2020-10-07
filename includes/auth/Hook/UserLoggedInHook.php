<?php

namespace MediaWiki\Auth\Hook;

use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UserLoggedInHook {
	/**
	 * This hook is called after a user is logged in.
	 *
	 * @since 1.35
	 *
	 * @param User $user User object for the logged-in user
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserLoggedIn( $user );
}
