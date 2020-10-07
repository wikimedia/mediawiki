<?php

namespace MediaWiki\User\Hook;

use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UserSetEmailHook {
	/**
	 * This hook is called when changing user email address.
	 *
	 * @since 1.35
	 *
	 * @param User $user User object
	 * @param string &$email new email, change this to override new email address
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserSetEmail( $user, &$email );
}
