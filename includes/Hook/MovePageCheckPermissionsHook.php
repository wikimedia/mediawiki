<?php

namespace MediaWiki\Hook;

use Status;
use Title;
use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface MovePageCheckPermissionsHook {
	/**
	 * Use this hook to specify whether the user is allowed to move the page.
	 *
	 * @since 1.35
	 *
	 * @param Title $oldTitle Current (old) location
	 * @param Title $newTitle New location
	 * @param User $user User making the move
	 * @param string $reason Reason provided by the user
	 * @param Status $status Status object to pass error messages to
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMovePageCheckPermissions( $oldTitle, $newTitle, $user,
		$reason, $status
	);
}
