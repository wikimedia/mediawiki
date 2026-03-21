<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests;

use MediaWiki\DomainEvent\DomainEventSource;
use MediaWiki\DomainEvent\DomainEventSubscriber;

/**
 * Null implementation for EventSubscriptionTest
 *
 * @internal
 */
class NullDomainEventSubscriber implements DomainEventSubscriber {
	public function registerListeners( DomainEventSource $eventSource ): void {
	}
}
