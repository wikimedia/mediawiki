<?php

namespace MediaWiki\Notification\Middleware;

use MediaWiki\Notification\NotificationEnvelope;

/**
 * An example Middleware that allows to remove notifications of specific types
 *
 * @since 1.44
 * @unstable
 */
class SuppressNotificationByTypeMiddleware extends FilterMiddleware {

	private string $notificationToSuppress;

	/**
	 * Suppress sending specific notification
	 */
	public function __construct( string $notificationTypeToSuppress ) {
		$this->notificationToSuppress = $notificationTypeToSuppress;
	}

	/**
	 * Decide whether we want to remove notification from the list
	 */
	protected function filter( NotificationEnvelope $envelope ): bool {
		return $envelope->getNotification()->getType() !== $this->notificationToSuppress;
	}

}
