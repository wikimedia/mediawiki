<?php

namespace MediaWiki\User\Hook;

use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UserGetEmailAuthenticationTimestampHook {
	/**
	 * This hook is called when getting the timestamp of email authentication.
	 *
	 * @since 1.35
	 *
	 * @param User $user User object
	 * @param string &$timestamp Timestamp. Change this to override the local email
	 *   authentication timestamp.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserGetEmailAuthenticationTimestamp( $user, &$timestamp );
}
