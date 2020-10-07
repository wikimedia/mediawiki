<?php

namespace MediaWiki\Hook;

use EmailNotification;
use Title;
use User;

/**
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
	 * @param EmailNotification $enotif
	 * @return bool|void True or no return value to send watchlist email
	 *   notification, or false to abort
	 */
	public function onSendWatchlistEmailNotification( $targetUser, $title, $enotif );
}
