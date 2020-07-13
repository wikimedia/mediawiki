<?php

namespace MediaWiki\Hook;

use RecentChange;
use Title;
use User;

/**
 * @stable to implement
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
