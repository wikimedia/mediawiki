<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SendWatchlistEmailNotificationHook {
	/**
	 * Return true to send watchlist email
	 * notification
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $targetUser the user whom to send watchlist email notification
	 * @param ?mixed $title the page title
	 * @param ?mixed $enotif EmailNotification object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSendWatchlistEmailNotification( $targetUser, $title, $enotif );
}
