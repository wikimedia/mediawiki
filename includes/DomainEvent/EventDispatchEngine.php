<?php
namespace MediaWiki\DomainEvent;

use InvalidArgumentException;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Deferred\MWCallableUpdate;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Implementation of DomainEventDispatcher and DomainEventSource based on
 * HookContainer and DeferredUpdates.
 *
 * Since this implementation of DomainEventDispatcher is based on HookContainer,
 * each event type also function as a hook name. This effectively means that
 * there are two ways to get informed about an event: asynchronously, using
 * a listener registered with an DomainEventSource; or synchronously, using
 * a hook handler registered with the HookContainer.
 *
 * @internal
 */
class EventDispatchEngine implements DomainEventDispatcher, DomainEventSource {

	/**
	 * An associative array mapping event names to lists of listeners.
	 * @var array<string,array<callable>>
	 */
	private array $listeners = [];

	/**
	 * An associative array mapping event names to lists of subscriber specs.
	 * Each subscriber spec is a reference to an associative array.
	 * @var array<string,array<array>>
	 */
	private array $pendingSubscribers = [];

	private ObjectFactory $objectFactory;

	public function __construct( ObjectFactory $objectFactory ) {
		$this->objectFactory = $objectFactory;
	}

	/**
	 * Emit the given event to any listeners that have been registered for
	 * the respective event type.
	 *
	 * Listeners are invoked through DeferredUpdates.
	 */
	public function dispatch( DomainEvent $event, IConnectionProvider $dbProvider ): void {
		foreach ( $event->getEventTypeChain() as $type ) {
			$this->dispatchAs( $type, $event, $dbProvider );
		}
	}

	private function dispatchAs( string $type, DomainEvent $event, IConnectionProvider $dbProvider ): void {
		$this->resolveSubscribers( $type );
		$listeners = $this->listeners[ $type ] ?? [];

		// NOTE: If we want to introduce a synchronous or pre-commit invocation mode
		//       here, it should only be used if the emitter opts into this.
		//       The contract of the dispatch() method doesn't allow listeners
		//       to be invoked within the current transaction.

		// Push a DeferredUpdate for listeners registered for handling AFTER_COMMIT.
		foreach ( $listeners ?? [] as $callback ) {
			$this->push( $callback, $event, $dbProvider );
		}
	}

	/**
	 * Add a listener that will be notified on events of the given type.
	 *
	 * @param string $eventType
	 * @param callable $listener
	 * @param array $options Currently unused. In the future, $options may
	 *        convey things like the listener priority or error handling.
	 */
	public function registerListener(
		string $eventType,
		$listener,
		array $options = self::DEFAULT_LISTENER_OPTIONS
	): void {
		if ( !is_callable( $listener ) ) {
			throw new InvalidArgumentException( '$listener must be callable' );
		}

		$this->listeners[$eventType][] = $listener;
	}

	/**
	 * @param DomainEventSubscriber|array $subscriber
	 */
	public function registerSubscriber( $subscriber ): void {
		if ( $subscriber instanceof DomainEventSubscriber ) {
			// If we have a DomainEventSubscriber, apply it immediately.
			// We can't wait until later, because we don't know what events
			// it wants to register for, so we don't know on what event to
			// call registerListeners().
			$subscriber->registerListeners( $this );
			return;
		}

		if ( !is_array( $subscriber ) ) {
			throw new InvalidArgumentException(
				'$subscriber must be a DomainEventSubscriber or an array'
			);
		}

		// NOTE: we could make the 'events' key optional, and just call
		// applySubscriberSpec() immediately if it's not given. But that would
		// make it too easy to just forget about providing it. Callers that want
		// to apply the subscriber immediately can just create the object instead
		// of passing a spec array.
		if ( !isset( $subscriber['events'] ) ) {
			throw new InvalidArgumentException(
				'$subscriber must contain the key "events" to specify which ' .
				'events will trigger instantiation of the subscriber'
			);
		}

		// Register the spec for lazy instantiation when any of the relevant
		// events is triggered.
		foreach ( $subscriber['events'] as $eventType ) {
			// NOTE: must be by reference, so the spec can be resolved for all
			// events that trigger instantiation at once.
			$this->pendingSubscribers[$eventType][] =& $subscriber;
		}
	}

	/**
	 * Perform lazy instantiation for any pending subscribers for the given
	 * event.
	 */
	private function resolveSubscribers( string $eventType ) {
		// Copy the list of pending subscribers, since applying subscribers
		// may modify $this->pendingSubscribers again.
		$pending = $this->pendingSubscribers[$eventType] ?? [];

		// Blank subscribers for this event. Once the subscribers have been
		// applied, all their listeners are registered, and the subscribers
		// are no longer relevant.
		$this->pendingSubscribers[$eventType] = [];

		// NOTE: entries in $this->subscribers are by reference!
		foreach ( $pending as &$spec ) {
			// NOTE: $spec may be empty if it was previously resolved through
			// a different event type.
			if ( !$spec ) {
				continue;
			}

			$this->applySubscriberSpec( $spec );

			// Empty the spec, so it's not re-triggered though another event
			// that also references it.
			$spec = [];
		}

		if ( $this->pendingSubscribers[$eventType] ) {
			// If more pending subscribers got added, recurse!
			// @phan-suppress-next-line PhanPossiblyInfiniteRecursionSameParams We've changed the data
			$this->resolveSubscribers( $eventType );
		}
	}

	private function applySubscriberSpec( array $spec ) {
		/** @var DomainEventSubscriber $subscriber */
		$subscriber = $this->objectFactory->createObject(
			$spec,
			[ 'assertClass' => DomainEventSubscriber::class, ]
		);

		if ( $subscriber instanceof InitializableDomainEventSubscriber ) {
			$subscriber->initSubscriber( $spec );
		}

		$subscriber->registerListeners( $this );
	}

	/**
	 * Push a deferred update that will eventually call invoke() unless the
	 * current transaction in $dbProvider is not successful.
	 */
	private function push(
		callable $callback,
		DomainEvent $event,
		IConnectionProvider $dbProvider
	) {
		// TODO: DeferredUpdates should take a more abstract representation of
		// the current transactional context!
		$dbw = $dbProvider->getPrimaryDatabase();
		DeferredUpdates::addUpdate( new MWCallableUpdate(
			function () use ( $callback, $event ) {
				$this->invoke( $callback, $event );
			},
			__METHOD__,
			[ $dbw ]
		) );
	}

	/**
	 * Invokes the given listener on the given event
	 */
	private function invoke( callable $callback, DomainEvent $event ) {
		$callback( $event );
	}

}
