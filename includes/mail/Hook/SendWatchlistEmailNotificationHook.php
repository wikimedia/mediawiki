<?php

namespace MediaWiki\Hook;

use MediaWiki\RecentChanges\RecentChangeNotifier;
use MediaWiki\Title\Title;
use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SendWatchlistEmailNotification" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SendWatchlistEmailNotificationHook {
	/**
	 * Use this hook to cancel watchlist email notifications (enotifwatchlist) for an edit.
	 *
	 * @since 1.35
	 *
	 * @param User $targetUser User whom to send watchlist email notification
	 * @param Title $title Page title
	 * @param RecentChangeNotifier $enotif
	 * @return bool|void True or no return value to send watchlist email
	 *   notification, or false to abort
	 */
	public function onSendWatchlistEmailNotification( $targetUser, $title, $enotif );
}
