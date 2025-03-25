<?php

namespace MediaWiki\DomainEvent;

/**
 * An DomainEventSubscriber that requires initialization with an options array
 * after construction.
 *
 * This is useful when constructing an DomainEventSubscriber from an object spec.
 *
 * @internal for use by DomainEventSubscriber
 */
interface InitializableDomainEventSubscriber extends DomainEventSubscriber {

	/**
	 * Initializer used to inform the behavior of the registerListeners() method.
	 * registerListeners() may throw if called before initSubscriber().
	 *
	 * The $options array must specify at least the 'event's key, listing
	 * any events that this subscriber should register events for.
	 */
	public function initSubscriber( array $options ): void;

}
