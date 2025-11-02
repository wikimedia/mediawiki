<?php

namespace MediaWiki\User\Hook;

use MediaWiki\User\UserIdentity;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UserClearNewTalkNotification" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UserClearNewTalkNotificationHook {
	/**
	 * This hook is called post-send when viewing a user talk page.
	 *
	 * The hook may be aborted, in which case the TalkPageNotificationManager service
	 * will _not_ clear the "You have new messages!" notification, and if the page
	 * is on the viewer's watchlist then WatchlistManager will also _not_ update the
	 * "seen" marker.
	 *
	 * @see \MediaWiki\Watchlist\WatchlistManager::clearTitleUserNotifications
	 * @since 1.35
	 * @param UserIdentity $userIdentity User that will clear the message
	 * @param int $oldid Revision ID of the talk page being viewed (0 means the most recent one)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserClearNewTalkNotification( $userIdentity, $oldid );
}
