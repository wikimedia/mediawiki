<?php

namespace MediaWiki\Auth\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ResetPasswordExpirationHook {
	/**
	 * Allow extensions to set a default password expiration
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user The user having their password expiration reset
	 * @param ?mixed &$newExpire The new expiration date
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onResetPasswordExpiration( $user, &$newExpire );
}
