<?php

namespace MediaWiki\Hook;

use Title;
use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface AbortTalkPageEmailNotificationHook {
	/**
	 * Use this hook to disable email notifications of edits to users' talk pages.
	 *
	 * @since 1.35
	 *
	 * @param User $targetUser User whom to send talk page email notification
	 * @param Title $title Page title
	 * @return bool|void True or no return value to continue, or false to cancel talk
	 *   page email notification
	 */
	public function onAbortTalkPageEmailNotification( $targetUser, $title );
}
