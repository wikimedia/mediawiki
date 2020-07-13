<?php

namespace MediaWiki\User\Hook;

use MessageSpecifier;
use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface IsValidPasswordHook {
	/**
	 * Use this hook to override the result of User::isValidPassword()
	 *
	 * @since 1.35
	 *
	 * @param string $password The password entered by the user
	 * @param bool|string|MessageSpecifier &$result Set this and return false
	 *   to override the internal checks
	 * @param User $user User the password is being validated for
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onIsValidPassword( $password, &$result, $user );
}
