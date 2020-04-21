<?php

namespace MediaWiki\User\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface PingLimiterHook {
	/**
	 * Allows extensions to override the results of User::pingLimiter().
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user User performing the action
	 * @param ?mixed $action Action being performed
	 * @param ?mixed &$result Whether or not the action should be prevented
	 *   Change $result and return false to give a definitive answer, otherwise
	 *   the built-in rate limiting checks are used, if enabled.
	 * @param ?mixed $incrBy Amount to increment counter by
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPingLimiter( $user, $action, &$result, $incrBy );
}
