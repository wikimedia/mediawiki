<?php

namespace MediaWiki\DomainEvent;

use LogicException;

/**
 * Base class for classes that implement DomainEventSubscriber.
 *
 * This class provides a default implementation of registerListeners() that will
 * attempt to find listener methods for the events defined in the constructor.
 * Listener methods must have a name based on the event type, following the
 * pattern "handle{$eventType}EventAfterCommit". The "AfterCommit" suffix
 * specifies the dispatch mode. More dispatch modes will be defined in the
 * future.
 *
 * @since 1.44
 * @unstable until 1.45, should become stable to extend
 */
abstract class EventSubscriberBase implements DomainEventSubscriber {

	/**
	 * @var string[]
	 */
	private array $eventTypes;

	/**
	 * @param string[] $eventTypes A list of events to subscribe to.
	 *        Required by the default implementation of registerListeners().
	 */
	public function __construct( array $eventTypes = [] ) {
		$this->eventTypes = $eventTypes;
	}

	protected function registerListenerMethod(
		DomainEventSource $eventSource,
		string $eventType,
		?string $method = null
	) {
		// TODO: use a different prefix, dispatch on dispatch mode
		$method ??= "handle{$eventType}EventAfterCommit";

		if ( !method_exists( $this, $method ) ) {
			throw new LogicException(
				"Missing listener method $method on " . get_class( $this )
			);
		}

		$eventSource->registerListener( $eventType, [ $this, $method ] );
	}

	public function registerListeners( DomainEventSource $eventSource ): void {
		if ( !$this->eventTypes ) {
			throw new LogicException(
				'Subclassed of EventSubscriberBase must either override ' .
				'registerListeners or provide a list of event types to ' .
				'the constructor'
			);
		}

		foreach ( $this->eventTypes as $type ) {
			$this->registerListenerMethod( $eventSource, $type );
		}
	}
}
