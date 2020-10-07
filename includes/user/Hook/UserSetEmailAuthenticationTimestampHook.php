<?php

namespace MediaWiki\User\Hook;

use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UserSetEmailAuthenticationTimestampHook {
	/**
	 * This hook is called when setting the timestamp of a User's email authentication.
	 *
	 * @since 1.35
	 *
	 * @param User $user User object
	 * @param ?string &$timestamp new timestamp, change this to override local email
	 *   authentication timestamp
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserSetEmailAuthenticationTimestamp( $user, &$timestamp );
}
