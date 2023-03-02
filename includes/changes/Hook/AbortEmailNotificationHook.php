<?php

namespace MediaWiki\Hook;

use MediaWiki\Title\Title;
use RecentChange;
use User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "AbortEmailNotification" to register handlers implementing this interface.
 *
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
