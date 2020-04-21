<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface PasswordPoliciesForUserHook {
	/**
	 * Alter the effective password policy for a user.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user User object whose policy you are modifying
	 * @param ?mixed &$effectivePolicy Array of policy statements that apply to this user
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPasswordPoliciesForUser( $user, &$effectivePolicy );
}
