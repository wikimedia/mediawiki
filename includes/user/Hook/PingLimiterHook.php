<?php

namespace MediaWiki\User\Hook;

use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "PingLimiter" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface PingLimiterHook {
	/**
	 * Use this hook to override the results of User::pingLimiter().
	 *
	 * @since 1.35
	 *
	 * @param User $user User performing the action
	 * @param string $action Action being performed
	 * @param bool &$result Whether or not the action should be prevented
	 *   Change $result and return false to give a definitive answer, otherwise
	 *   the built-in rate limiting checks are used, if enabled.
	 * @param int $incrBy Amount to increment counter by
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPingLimiter( $user, $action, &$result, $incrBy );
}
