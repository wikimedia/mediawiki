<?php

namespace MediaWiki\User\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserCanSendEmailHook {
	/**
	 * To override User::canSendEmail() permission check.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user User (object) whose permission is being checked
	 * @param ?mixed &$canSend bool set on input, can override on output
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserCanSendEmail( $user, &$canSend );
}
