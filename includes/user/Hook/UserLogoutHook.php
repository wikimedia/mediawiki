<?php

namespace MediaWiki\User\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserLogoutHook {
	/**
	 * Before a user logs out.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user the user object that is about to be logged out
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserLogout( $user );
}
