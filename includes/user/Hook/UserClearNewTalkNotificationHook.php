<?php

namespace MediaWiki\User\Hook;

use User;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserClearNewTalkNotificationHook {
	/**
	 * This hook is called before clearing the "You have new messages!" message
	 *
	 * @since 1.35
	 *
	 * @param User $user User (object) that will clear the message
	 * @param int $oldid ID of the talk page revision being viewed (0 means the most recent one)
	 * @return bool|void True or no return value to continue or false to abort (not clear the message)
	 */
	public function onUserClearNewTalkNotification( $user, $oldid );
}
