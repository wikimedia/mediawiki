<?php

namespace MediaWiki\User\Hook;

use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UserGetEmailHook {
	/**
	 * This hook is called when getting an user email address.
	 *
	 * @since 1.35
	 *
	 * @param User $user User object
	 * @param string &$email Email, change this to override local email
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserGetEmail( $user, &$email );
}
