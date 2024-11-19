<?php
namespace MediaWiki\DomainEvent;

use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Service for sending domain events to registered listeners.
 *
 * @since 1.44
 * @unstable until 1.45
 */
interface DomainEventDispatcher {

	/**
	 * Dispatch the given event to any listeners that have been registered for
	 * the given event type. The event will be dispatched to listeners
	 * later, in a deferred update, after (and if) the current transaction
	 * in $dbw has been committed successfully.
	 *
	 * Implementations should aim to provide at-least-once delivery semantics,
	 * but guaranteed delivery is not a hard requirement of this interface.
	 * An application may guarantee delivery of domain events by providing
	 * an appropriate implementation (a transactional outbox).
	 */
	public function dispatch(
		DomainEvent $event,
		IConnectionProvider $dbProvider
	): void;

}
