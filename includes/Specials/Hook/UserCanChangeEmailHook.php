<?php

namespace MediaWiki\Hook;

use MediaWiki\Status\Status;
use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UserCanChangeEmail" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UserCanChangeEmailHook {
	/**
	 * This hook is called when user changes their email address.
	 *
	 * @since 1.45
	 *
	 * @param User $user User (object) changing his email address
	 * @param string $oldaddr old email address (string)
	 * @param string $newaddr new email address (string)
	 * @param Status &$status Set this and return false to override the internal checks
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserCanChangeEmail( $user, $oldaddr, $newaddr, &$status );
}
