<?php

namespace MediaWiki\DomainEvent;

/**
 * Objects implementing DomainEventSubscriber represent a collection of
 * related event listeners.
 *
 * @since 1.44
 * @unstable until 1.45, should become stable to extend
 */
interface DomainEventSubscriber {

	/**
	 * Registers listeners with the given $eventSource.
	 *
	 * @todo decide whether this should return a list of event types
	 */
	public function registerListeners( DomainEventSource $eventSource ): void;

}
