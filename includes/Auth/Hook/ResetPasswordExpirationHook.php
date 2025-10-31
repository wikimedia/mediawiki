<?php

namespace MediaWiki\Auth\Hook;

use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ResetPasswordExpiration" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ResetPasswordExpirationHook {
	/**
	 * Use this hook to allow extensions to set a default password expiration.
	 *
	 * @since 1.35
	 *
	 * @param User $user User having their password expiration reset
	 * @param string &$newExpire New expiration date
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onResetPasswordExpiration( $user, &$newExpire );
}
