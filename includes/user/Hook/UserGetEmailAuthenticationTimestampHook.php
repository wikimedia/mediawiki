<?php

namespace MediaWiki\User\Hook;

use User;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserGetEmailAuthenticationTimestampHook {
	/**
	 * This hook is called when getting the timestamp of email authentication.
	 *
	 * @since 1.35
	 *
	 * @param User $user User object
	 * @param string &$timestamp timestamp, change this to override local email authentication
	 *   timestamp
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserGetEmailAuthenticationTimestamp( $user, &$timestamp );
}
