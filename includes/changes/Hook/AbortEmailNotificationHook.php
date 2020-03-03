<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface AbortEmailNotificationHook {
	/**
	 * Can be used to cancel email notifications for an edit.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $editor The User who made the change.
	 * @param ?mixed $title The Title of the page that was edited.
	 * @param ?mixed $rc The current RecentChange object.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAbortEmailNotification( $editor, $title, $rc );
}
