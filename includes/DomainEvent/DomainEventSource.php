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
	 * @param mixed $listener
	 * - callable: a callback with the signature (DomainEvent): void.
	 * - object: an object that has a method that starts with "after"
	 *           followed by the event name and the signature
	 *           (DomainEvent): void
	 *
	 * @todo support named handler objects
	 * @todo support object specs
	 */
	public function registerListener( string $eventType, $listener ): void;

}
