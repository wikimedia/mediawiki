<?php

namespace MediaWiki\Permissions\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UserIsEveryoneAllowedHook {
	/**
	 * Use this hook to check if all users are allowed some user right; return
	 * false if a UserGetRights hook might remove the named right.
	 *
	 * @since 1.35
	 *
	 * @param string $right User right being checked
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserIsEveryoneAllowed( $right );
}
