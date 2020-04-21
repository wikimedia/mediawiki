<?php

namespace MediaWiki\User\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialPasswordResetOnSubmitHook {
	/**
	 * When executing a form submission on
	 * Special:PasswordReset.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$users array of User objects.
	 * @param ?mixed $data array of data submitted by the user
	 * @param ?mixed &$error string, error code (message key) used to describe to error (out
	 *   parameter). The hook needs to return false when setting this, otherwise it
	 *   will have no effect.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialPasswordResetOnSubmit( &$users, $data, &$error );
}
