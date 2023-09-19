<?php

namespace MediaWiki\User\Hook;

use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UserGetEmail" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UserGetEmailHook {
	/**
	 * This hook is called when getting an user email address.
	 *
	 * @since 1.35
	 *
	 * @param User $user
	 * @param string &$email Email, change this to override local email
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserGetEmail( $user, &$email );
}
