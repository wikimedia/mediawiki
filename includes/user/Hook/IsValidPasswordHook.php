<?php

namespace MediaWiki\User\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface IsValidPasswordHook {
	/**
	 * Override the result of User::isValidPassword()
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $password The password entered by the user
	 * @param ?mixed &$result Set this and return false to override the internal checks
	 * @param ?mixed $user User the password is being validated for
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onIsValidPassword( $password, &$result, $user );
}
