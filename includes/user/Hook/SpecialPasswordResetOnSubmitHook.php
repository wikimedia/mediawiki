<?php

namespace MediaWiki\User\Hook;

use MessageSpecifier;
use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialPasswordResetOnSubmitHook {
	/**
	 * This hook is called when executing a form submission on Special:PasswordReset.
	 *
	 * @since 1.35
	 *
	 * @param User[] &$users Array of User objects.
	 * @param array $data Array of data submitted by the user
	 * @param string|array|MessageSpecifier &$error String, error code (message key)
	 *   used to describe to error (out parameter). The hook needs to return false
	 *   when setting this, otherwise it will have no effect.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialPasswordResetOnSubmit( &$users, $data, &$error );
}
