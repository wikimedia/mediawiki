<?php

namespace MediaWiki\Notification\Middleware;

use MediaWiki\Notification\NotificationEnvelope;
use MediaWiki\Notification\NotificationMiddlewareInterface;
use MediaWiki\Notification\NotificationsBatch;

/**
 * Middleware that allows to filter notifications
 *
 * @stable to extend
 * @since 1.45
 */
abstract class FilterMiddleware implements NotificationMiddlewareInterface {

	/**
	 * Decide whether we want to remove notification from the list
	 *
	 * Return FilterMiddlewareAction::KEEP to keep the envelope
	 * Return FilterMiddlewareAction::REMOVE to remove the envelope
	 */
	abstract protected function filter( NotificationEnvelope $envelope ): FilterMiddlewareAction;

	/**
	 * @param NotificationsBatch $batch
	 * @param callable():void $next
	 */
	public function handle( NotificationsBatch $batch, callable $next ): void {
		$next();
		$batch->filter( function ( NotificationEnvelope $envelope ) {
			return $this->filter( $envelope ) === FilterMiddlewareAction::KEEP;
		} );
	}

}
