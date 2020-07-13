<?php

namespace MediaWiki\User\Hook;

use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UserIsBlockedGloballyHook {
	/**
	 * Use this hook to establish that a user is blocked on all wikis.
	 *
	 * @since 1.35
	 *
	 * @param User $user User object
	 * @param string $ip User's IP address
	 * @param bool &$blocked Whether the user is blocked, to be modified by the hook
	 * @param null &$block The Block object, to be modified by the hook
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserIsBlockedGlobally( $user, $ip, &$blocked, &$block );
}
