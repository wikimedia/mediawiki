<?php

namespace MediaWiki\Notification;

/**
 * Accept notification events and notify users about them.
 *
 * @since 1.44
 * @unstable
 */
interface NotificationHandler {
	/**
	 * Notify users about an event occuring.
	 */
	public function notify( Notification $notification, RecipientSet $recipients ): void;
}
