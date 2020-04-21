<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface AddNewAccountHook {
	/**
	 * DEPRECATED since 1.27! Use LocalUserCreated.
	 * After a user account is created.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user the User object that was created. (Parameter added in 1.7)
	 * @param ?mixed $byEmail true when account was created "by email" (added in 1.12)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAddNewAccount( $user, $byEmail );
}
