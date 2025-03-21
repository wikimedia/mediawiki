<?php

namespace MediaWiki\Notification\Middleware;

use MediaWiki\Notification\NotificationEnvelope;
use MediaWiki\Notification\NotificationMiddlewareInterface;
use MediaWiki\Notification\NotificationsBatch;

/**
 * An example Middleware that allows to remove notifications of specific types
 *
 * @since 1.44
 * @unstable
 */
class SuppressNotificationByTypeMiddleware implements NotificationMiddlewareInterface {

	private string $notificationToSuppress;

	/**
	 * Suppress sending specific notification
	 * @param string $notificationTypeToSuppress
	 */
	public function __construct( string $notificationTypeToSuppress ) {
		$this->notificationToSuppress = $notificationTypeToSuppress;
	}

	/**
	 * Decide whether we want to remove notification from the list
	 * @param NotificationEnvelope $envelope
	 * @return bool
	 */
	protected function shouldKeep( NotificationEnvelope $envelope ): bool {
		return $envelope->getNotification()->getType() !== $this->notificationToSuppress;
	}

	/**
	 * @param NotificationsBatch $batch
	 * @param callable():void $next
	 */
	public function handle( NotificationsBatch $batch, callable $next ): void {
		$batch->filter( fn ( NotificationEnvelope $envelope ) => $this->shouldKeep( $envelope ) );
		$next();
	}

}
