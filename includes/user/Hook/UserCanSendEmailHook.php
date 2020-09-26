<?php

namespace MediaWiki\User\Hook;

use User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UserCanSendEmail" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UserCanSendEmailHook {
	/**
	 * Use this hook to override User::canSendEmail() permission check.
	 *
	 * @since 1.35
	 *
	 * @param User $user User (object) whose permission is being checked
	 * @param bool &$canSend Set on input, can override on output
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserCanSendEmail( $user, &$canSend );
}
