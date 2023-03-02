<?php

namespace MediaWiki\Hook;

use MediaWiki\Title\Title;
use Status;
use User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "MovePageCheckPermissions" to register handlers implementing this interface.
 *
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
	 * @param string|null $reason Reason provided by the user
	 * @param Status $status Status object to pass error messages to
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMovePageCheckPermissions( $oldTitle, $newTitle, $user,
		$reason, $status
	);
}
