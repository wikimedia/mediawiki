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
	 * Notify users about an event occurring.
	 * @todo - decide maybe instead of handling single notification, Handlers can handle batches
	 */
	public function notify( Notification $notification, RecipientSet $recipients ): void;
}
