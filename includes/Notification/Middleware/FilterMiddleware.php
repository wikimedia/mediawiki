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
	 * Return self::KEEP or true to keep the envelope
	 * Return self::REMOVE or false to remove the envelope
	 */
	abstract protected function filter( NotificationEnvelope $envelope ): bool;

	/**
	 * @param NotificationsBatch $batch
	 * @param callable():void $next
	 */
	public function handle( NotificationsBatch $batch, callable $next ): void {
		$next();
		$batch->filter( fn ( NotificationEnvelope $envelope ) => $this->filter( $envelope ) );
	}

}
