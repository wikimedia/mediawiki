<?php

namespace MediaWiki\Notification;

/**
 * Accept notification events and notify users about them.
 *
 * @stable to implement
 * @since 1.45
 */
interface NotificationHandler {

	/**
	 * Notify users about an event occurring.
	 */
	public function notify( Notification $notification, RecipientSet $recipients ): void;
}
