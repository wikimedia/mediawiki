<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface MovePageCheckPermissionsHook {
	/**
	 * Specify whether the user is allowed to move the
	 * page.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $oldTitle Title object of the current (old) location
	 * @param ?mixed $newTitle Title object of the new location
	 * @param ?mixed $user User making the move
	 * @param ?mixed $reason string of the reason provided by the user
	 * @param ?mixed $status Status object to pass error messages to
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMovePageCheckPermissions( $oldTitle, $newTitle, $user,
		$reason, $status
	);
}
