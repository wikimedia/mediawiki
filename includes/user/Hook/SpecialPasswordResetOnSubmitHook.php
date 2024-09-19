<?php

namespace MediaWiki\User\Hook;

use MediaWiki\User\User;
use Wikimedia\Message\MessageSpecifier;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpecialPasswordResetOnSubmit" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialPasswordResetOnSubmitHook {
	/**
	 * This hook is called when executing a form submission on Special:PasswordReset.
	 *
	 * The data submitted by the user ($data) is an associative array with the keys 'Username' and
	 * 'Email', whose values are already validated user input (a valid username, and a valid email
	 * address), or null if not given by the user. At least one of the values is not null.
	 *
	 * Since MediaWiki 1.43, hook handlers should check each user's 'requireemail' preference, and
	 * if it is enabled by the user, only return that user if both username and email were present.
	 * Until MediaWiki 1.42 only one of username and email could be present (the other would be null).
	 *
	 * @since 1.35
	 *
	 * @param User[] &$users
	 * @param array $data Array of data submitted by the user
	 * @phan-param array{Username:?string, Email:?string} $data
	 * @param string|array|MessageSpecifier &$error String, error code (message key)
	 *   used to describe to error (out parameter). The hook needs to return false
	 *   when setting this, otherwise it will have no effect.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialPasswordResetOnSubmit( &$users, $data, &$error );
}
