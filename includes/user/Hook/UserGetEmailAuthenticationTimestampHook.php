<?php

namespace MediaWiki\User\Hook;

use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UserGetEmailAuthenticationTimestamp" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UserGetEmailAuthenticationTimestampHook {
	/**
	 * This hook is called when getting the timestamp of email authentication.
	 *
	 * @since 1.35
	 *
	 * @param User $user
	 * @param string &$timestamp Timestamp. Change this to override the local email
	 *   authentication timestamp.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserGetEmailAuthenticationTimestamp( $user, &$timestamp );
}
