<?php

namespace MediaWiki\User\Hook;

use User;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserCanSendEmailHook {
	/**
	 * Use this hook to override User::canSendEmail() permission check.
	 *
	 * @since 1.35
	 *
	 * @param User $user User (object) whose permission is being checked
	 * @param bool &$canSend bool set on input, can override on output
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserCanSendEmail( $user, &$canSend );
}
