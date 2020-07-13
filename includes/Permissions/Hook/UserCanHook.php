<?php

namespace MediaWiki\Permissions\Hook;

use Title;
use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UserCanHook {
	/**
	 * Use this hook to interrupt or advise the "user can do X to Y article" check.
	 * If you want to display an error message, try getUserPermissionsErrors.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title being checked against
	 * @param User $user Current user
	 * @param string $action Action being checked
	 * @param string &$result Pointer to result returned if hook returns false.
	 *   If null is returned, userCan checks are continued by internal code.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserCan( $title, $user, $action, &$result );
}
