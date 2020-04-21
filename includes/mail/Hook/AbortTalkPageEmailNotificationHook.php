<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface AbortTalkPageEmailNotificationHook {
	/**
	 * Return false to cancel talk page email
	 * notification
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $targetUser the user whom to send talk page email notification
	 * @param ?mixed $title the page title
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAbortTalkPageEmailNotification( $targetUser, $title );
}
