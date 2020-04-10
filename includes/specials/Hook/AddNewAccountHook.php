<?php

namespace MediaWiki\Hook;

use User;

/**
 * @deprecated since 1.27. Use LocalUserCreated instead.
 * @ingroup Hooks
 */
interface AddNewAccountHook {
	/**
	 * This hook is called after a user account is created.
	 *
	 * @since 1.35
	 *
	 * @param User $user the User object that was created. (Parameter added in 1.7)
	 * @param bool $byEmail true when account was created "by email" (added in 1.12)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAddNewAccount( $user, $byEmail );
}
