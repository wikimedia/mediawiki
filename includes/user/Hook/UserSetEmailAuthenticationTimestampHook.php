<?php

namespace MediaWiki\User\Hook;

use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UserSetEmailAuthenticationTimestamp" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UserSetEmailAuthenticationTimestampHook {
	/**
	 * This hook is called when setting the timestamp of a User's email authentication.
	 *
	 * @since 1.35
	 *
	 * @param User $user
	 * @param ?string &$timestamp new timestamp, change this to override local email
	 *   authentication timestamp
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserSetEmailAuthenticationTimestamp( $user, &$timestamp );
}
