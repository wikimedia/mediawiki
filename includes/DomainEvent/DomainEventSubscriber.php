<?php

namespace MediaWiki\DomainEvent;

/**
 * Objects implementing DomainEventSubscriber represent a collection of
 * related event listeners.
 *
 * @since 1.44
 * @note Extensions should not implement this interface directly but should
 *       extend DomainEventIngress.
 *
 * @see docs/Events.md
 * @see https://www.mediawiki.org/wiki/Manual:Domain_events
 */
interface DomainEventSubscriber {

	/**
	 * Registers listeners with the given $eventSource.
	 */
	public function registerListeners( DomainEventSource $eventSource ): void;

}
