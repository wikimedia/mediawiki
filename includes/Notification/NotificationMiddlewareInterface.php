<?php
namespace MediaWiki\Notification;

/**
 * @since 1.44
 * @unstable
 */
interface NotificationMiddlewareInterface {

	/**
	 * Allows performing operations on batch, Should return modified batch
	 *
	 * @param NotificationsBatch $batch
	 * @param callable():void $next Call this method to continue the chain
	 */
	public function handle( NotificationsBatch $batch, callable $next ): void;

}
