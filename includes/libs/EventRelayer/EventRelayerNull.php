<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\EventRelayer;

/**
 * No-op class for publishing messages into a PubSub system
 */
class EventRelayerNull extends EventRelayer {
	/** @inheritDoc */
	public function doNotify( $channel, array $events ) {
		return true;
	}
}

/** @deprecated class alias since 1.41 */
class_alias( EventRelayerNull::class, 'EventRelayerNull' );
