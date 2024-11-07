<?php
namespace MediaWiki\DomainEvent;

/**
 * Service object for registering listeners for domain events.
 *
 * @since 1.44
 * @unstable until 1.45
 */
interface DomainEventSource {

	/**
	 * Add a listener that will be notified on events of the given type,
	 * triggered by changed to the persistent state of the local wiki.
	 *
	 * Listeners should be implemented to be idempotent, that is, calling
	 * them multiple times with the same parameters should produce the same
	 * outcome.
	 *
	 * Listeners will be invoked after the transaction that produced the event
	 * was committed successfully. Delivery guarantees depend on the
	 * implementation of DomainEvent sink used.
	 *
	 * @param string $eventType
	 * @param callable $listener
	 */
	public function registerListener( string $eventType, $listener ): void;

	/**
	 * Register the given subscriber to this event source. A subscriber
	 * is a way to bundle related listeners, typically by implementing them
	 * as methods on the subscriber object.
	 *
	 * If the subscriber is supplied as a spec array, instantiation and
	 * application may be deferred until one of the relevant events is
	 * triggered.
	 *
	 * @param DomainEventSubscriber|array $subscriber
	 * - object: a DomainEventSubscriber
	 * - array: An object spec suitable for use with ObjectFactory.
	 *          The array must use the key 'events' to specify which
	 *          events will trigger application of the subscriber.
	 */
	public function registerSubscriber( $subscriber ): void;

}
