<?php

namespace MediaWiki\Permissions\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserCanHook {
	/**
	 * To interrupt/advise the "user can do X to Y article" check. If you
	 * want to display an error message, try getUserPermissionsErrors.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title Title object being checked against
	 * @param ?mixed $user Current user object
	 * @param ?mixed $action Action being checked
	 * @param ?mixed &$result Pointer to result returned if hook returns false. If null is returned,
	 *   userCan checks are continued by internal code.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserCan( $title, $user, $action, &$result );
}
