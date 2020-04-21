<?php

namespace MediaWiki\User\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserClearNewTalkNotificationHook {
	/**
	 * Called when clearing the "You have new
	 * messages!" message, return false to not delete it.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user User (object) that will clear the message
	 * @param ?mixed $oldid ID of the talk page revision being viewed (0 means the most recent one)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserClearNewTalkNotification( $user, $oldid );
}
