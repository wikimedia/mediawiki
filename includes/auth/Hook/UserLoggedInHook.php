<?php

namespace MediaWiki\Auth\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserLoggedInHook {
	/**
	 * Called after a user is logged in
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user User object for the logged-in user
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserLoggedIn( $user );
}
