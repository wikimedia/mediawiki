<?php

namespace MediaWiki\Auth\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "AuthenticationAttemptThrottled" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface AuthenticationAttemptThrottledHook {
	/**
	 * This hook is called when a {@link Throttler} has throttled an authentication attempt.
	 * An authentication attempt includes account creation, logins, and temporary account auto-creation.
	 *
	 * @since 1.43
	 *
	 * @param string $type The name of the authentication throttle that caused the throttling
	 * @param string|null $username The username associated with the action that was throttled, or null if not
	 *    relevant.
	 * @param string|null $ip The IP used to make the action that was throttled, or null if not provided.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAuthenticationAttemptThrottled( string $type, ?string $username, ?string $ip );
}
