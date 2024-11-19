<?php
namespace MediaWiki\DomainEvent;

use InvalidArgumentException;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Deferred\MWCallableUpdate;
use MediaWiki\HookContainer\HookContainer;
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
	private const HOOK_CONTAINER_OPTIONS = [
		'prefix' => 'after',
	];

	/**
	 * An associative array mapping event names to lists of listeners.
	 * Each listener is represented as an associative array.
	 * @var array<string,array<array>>
	 */
	private array $listeners = [];

	private HookContainer $hookContainer;

	public function __construct( HookContainer $hookContainer ) {
		$this->hookContainer = $hookContainer;
	}

	/**
	 * Emit the given event to any listeners that have been registered for
	 * the respective event type. Listeners will be invoked later, in a deferred
	 * update. If the transaction currently active in $dbProvider fails,
	 * the event will not be dispatched to listeners.
	 *
	 * Note that any hook handlers registered for a hook that has the same as
	 * the event type will be invoked immediately, before this method returns.
	 */
	public function dispatch( DomainEvent $event, IConnectionProvider $dbProvider ): void {
		$this->hookContainer->run(
			$event->getEventType(),
			[ $event, $dbProvider, ]
		);
	}

	/**
	 * Add a listener that will be notified on events of the given type.
	 *
	 * @param string $eventType
	 * @param mixed $listener
	 */
	public function registerListener( string $eventType, $listener ): void {
		if ( is_callable( $listener ) ) {
			$spec = [ 'callback' => $listener ];
		} else {
			$spec = $this->hookContainer->normalizeHandler(
				$eventType,
				$listener,
				self::HOOK_CONTAINER_OPTIONS
			);
		}

		if ( !$spec ) {
			throw new InvalidArgumentException( "Invalid event listener for $eventType" );
		}

		// If this is the first listener, register a hook handler for this
		// event. The hook handler will call push(), which will register a
		// deferred update which will eventually call dispatch().
		if ( !isset( $this->listeners[$eventType] ) ) {
			$this->registerTrigger( $eventType );
		}

		$this->listeners[$eventType][] = $spec;
	}

	public function registerSubscriber( $subscriber ): void {
		// TODO: Allow $subscriber to be an object spec. Determine list of events!
		$subscriber->registerListeners( $this );
	}

	/**
	 * Register a hook handler that will invoke dispatchInternal() when the event's hook
	 * is run.
	 */
	private function registerTrigger( string $eventName ) {
		$this->hookContainer->register(
			$eventName,
			function ( DomainEvent $event, IConnectionProvider $dbProvider ) use ( $eventName ) {
				$this->dispatchInternal( $event, $dbProvider );
			}
		);
	}

	/**
	 * Dispatches the given event to any registered listeners by
	 * capping push() for each listener.
	 */
	private function dispatchInternal( DomainEvent $event, IConnectionProvider $dbProvider ) {
		$listeners = $this->listeners[ $event->getEventType() ] ?? [];

		foreach ( $listeners as $spec ) {
			$this->push( $spec, $event, $dbProvider );
		}
	}

	/**
	 * Push a deferred update that will eventually call invoke() unless the
	 * current transaction in $dbProvider is not successful.
	 */
	private function push(
		array $spec,
		DomainEvent $event,
		IConnectionProvider $dbProvider
	) {
		// TODO: DeferredUpdates should take a more abstract representation of
		// the current transactional context!
		$dbw = $dbProvider->getPrimaryDatabase();

		DeferredUpdates::addUpdate( new MWCallableUpdate(
			function () use ( $spec, $event, $dbProvider ) {
				$this->invoke( $spec, $event, $dbProvider );
			},
			__METHOD__,
			[ $dbw ]
		) );
	}

	/**
	 * Invokes the given listener on the given event
	 */
	private function invoke(
		array $spec,
		DomainEvent $event,
		IConnectionProvider $dbProvider
	) {
		$callback = $spec['callback'];
		$callback( $event, $dbProvider );
	}
}
