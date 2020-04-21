<?php

namespace MediaWiki\User\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserIsBlockedGloballyHook {
	/**
	 * Check if user is blocked on all wikis.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user User object
	 * @param ?mixed $ip User's IP address
	 * @param ?mixed &$blocked Whether the user is blocked, to be modified by the hook
	 * @param ?mixed &$block The Block object, to be modified by the hook
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserIsBlockedGlobally( $user, $ip, &$blocked, &$block );
}
