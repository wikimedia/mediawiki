<?php

namespace MediaWiki\Notification\Middleware;

use MediaWiki\Notification\NotificationEnvelope;
use MediaWiki\Notification\NotificationMiddlewareInterface;
use MediaWiki\Notification\NotificationsBatch;

/**
 * Middleware that allows to filter notifications
 *
 * @since 1.44
 * @unstable
 */
abstract class FilterMiddleware implements NotificationMiddlewareInterface {

	protected const KEEP = true;
	protected const REMOVE = false;

	/**
	 * Decide whether we want to remove notification from the list
	 *
	 * Return FilterMiddlewareAction::KEEP or true to keep the envelope
	 * Return FilterMiddlewareAction::REMOVE or false to remove the envelope
	 */
	abstract protected function filter( NotificationEnvelope $envelope ): bool|FilterMiddlewareAction;

	/**
	 * @param NotificationsBatch $batch
	 * @param callable():void $next
	 */
	public function handle( NotificationsBatch $batch, callable $next ): void {
		$next();
		$batch->filter( function ( NotificationEnvelope $envelope ) {
			$result = $this->filter( $envelope );
			return $result === self::KEEP || $result === FilterMiddlewareAction::KEEP;
		} );
	}

}
