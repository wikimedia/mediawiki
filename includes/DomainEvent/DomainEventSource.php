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
	 * Listeners will be invoked before the transaction that produced the event
	 * is committed. Delivery guarantees depend on the DomainEventDispatcher
	 * implementation.
	 *
	 * Listeners may be invoked immediately, before the call to
	 * DomainEventDispatcher::dispatch() returns, or later.
	 */
	public const INVOKE_BEFORE_COMMIT = 'BeforeCommit';

	/**
	 * Listeners will be invoked after the transaction that produced the event
	 * was committed successfully. Delivery guarantees depend on the
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
	 */
	public const INVOKE_AFTER_COMMIT = 'AfterCommit';

	public const INVOCATION_MODE = 'invocation';

	/**
	 * Default options to apply when registering listeners.
	 */
	public const DEFAULT_LISTENER_OPTIONS = [
		self::INVOCATION_MODE => self::INVOKE_AFTER_COMMIT
	];

	/**
	 * Add a listener that will be notified on events of the given type,
	 * triggered by changed to the persistent state of the local wiki.
	 *
	 * Listeners should make sure to apply DEFAULT_OPTIONS to $options.
	 *
	 * @param string $eventType
	 * @param callable $listener
	 * @param array $options Options that control how the listener is invoked.
	 *        Options may be implementation specific can could control things
	 *        like invocation order (priority) and error handling.
	 *        Well known keys are:
	 *        - self::INVOCATION_MODE: one of the invocation modes defined
	 *          in this class, e.g. self::INVOKE_AFTER_COMMIT.
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
