<?php
namespace MediaWiki\DomainEvent;

/**
 * Service object for registering listeners for domain events.
 *
 * @since 1.44
 *
 * @see docs/Events.md
 * @see https://www.mediawiki.org/wiki/Manual:Domain_events
 */
interface DomainEventSource {

	/**
	 * Default options to apply when registering listeners.
	 * In the future, options may convey things like the listener priority or
	 * error handling.
	 */
	public const DEFAULT_LISTENER_OPTIONS = [];

	/**
	 * Add a listener that will be notified on events of the given type,
	 * triggered by a change to an entity on the local wiki.
	 *
	 * Listeners will be invoked after the transaction that produced the event
	 * was committed successfully. Delivery guarantees depend on the respective
	 * DomainEventDispatcher implementation.
	 *
	 * In a web request, listeners should be invoked after the response has been
	 * sent to the client, but still within the current process.
	 *
	 * Listeners may be invoked immediately if there is no active transaction
	 * round associated with the ConnectionProvider passed to
	 * DomainEventDispatcher::dispatch().
	 *
	 * Listeners should be implemented to be idempotent, that is, calling
	 * them multiple times with the same parameters should produce the same
	 * outcome.
	 *
	 * @param string $eventType
	 * @param callable $listener
	 * @param array $options Currently unused. In the future, $options may
	 * *        convey things like the listener priority or error handling.
	 */
	public function registerListener(
		string $eventType,
		$listener,
		array $options = self::DEFAULT_LISTENER_OPTIONS
	): void;

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
