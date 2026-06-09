<?php

namespace MediaWiki\User\Hook;

use MediaWiki\Message\Message;
use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UserModifyCreateAccountEmailHook" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UserModifyCreateAccountEmailHook {
	/**
	 * This hook runs when a user account is being created on behalf of another user,
	 * such as when using Special:CreateAccount when already logged in.
	 *
	 * This allows overriding, wrapping, or otherwise modifying both the subject
	 * and the body of the email being sent to $user.
	 *
	 * This can be used, for example, by extensions to add additional information to the
	 * email body.
	 *
	 * @since 1.47
	 *
	 * @param User $user The user account being created
	 * @param User $performer The user account performing the creation on behalf of $user
	 * @param Message &$subject The email subject
	 * @param Message &$body The email body
	 */
	public function onUserModifyCreateAccountEmail(
		User $user,
		User $performer,
		Message &$subject,
		Message &$body
	): void;
}
