<?php

namespace MediaWiki\Permissions\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserIsEveryoneAllowedHook {
	/**
	 * Check if all users are allowed some user right; return
	 * false if a UserGetRights hook might remove the named right.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $right The user right being checked
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserIsEveryoneAllowed( $right );
}
