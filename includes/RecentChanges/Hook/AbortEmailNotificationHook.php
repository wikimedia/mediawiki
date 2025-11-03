<?php

namespace MediaWiki\Hook;

use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Title\Title;
use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "AbortEmailNotification" to register handlers implementing this interface.
 *
 * @deprecated since 1.45 Use the NotificationMiddleware instead
 * @ingroup Hooks
 */
interface AbortEmailNotificationHook {
	/**
	 * Use this hook to cancel email notifications for an edit.
	 *
	 * @since 1.35
	 *
	 * @param User $editor User who made the change
	 * @param Title $title Title of the page that was edited
	 * @param RecentChange $rc Current RecentChange object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAbortEmailNotification( $editor, $title, $rc );
}
