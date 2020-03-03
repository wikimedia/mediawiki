<?php

namespace MediaWiki\User\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserSetEmailAuthenticationTimestampHook {
	/**
	 * Called when setting the timestamp of
	 * email authentication.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user User object
	 * @param ?mixed &$timestamp new timestamp, change this to override local email
	 *   authentication timestamp
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserSetEmailAuthenticationTimestamp( $user, &$timestamp );
}
