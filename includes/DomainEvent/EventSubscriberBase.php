<?php

namespace MediaWiki\DomainEvent;

use InvalidArgumentException;
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
 * Subclasses can either override registerListeners() and register listeners
 * directly with the given DomainEventSource, or they can rely on the default
 * implementation of registerListeners() which will automatically register
 * method for each event passed to the constructor based on a naming convention.
 *
 * @since 1.44
 * @unstable until 1.45, should become stable to extend
 */
abstract class EventSubscriberBase implements InitializableDomainEventSubscriber {

	/**
	 * @var string[]
	 */
	private array $eventTypes = [];

	/**
	 * May be called from the constructor of subclasses that want to
	 * directly specify the list of events.
	 *
	 * @param string[] $events
	 */
	protected function initEvents( array $events ): void {
		$this->initSubscriber( [ 'events' => $events ] );
	}

	/**
	 * Called by DomainEventDispatcher to provide access to the list of events to
	 * subscribe to and any other relevant information from the extension.json.
	 *
	 * @param array $options the object spec describing the subscriber, typically
	 *        from extension.json.
	 */
	public function initSubscriber( array $options ): void {
		if ( !isset( $options['events'] ) ) {
			throw new InvalidArgumentException( '$options must contain the "events" key' );
		}

		if ( $this->eventTypes && $options['events'] != $this->eventTypes ) {
			throw new InvalidArgumentException(
				'A different set of events was provided previously, ' .
				'probably by a call to initEvents().'
			);
		}

		$this->eventTypes = $options['events'];
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

	/**
	 * This default implementation of registerListeners() will automatically
	 * register a listener method for each event passed to initEvents() or
	 * initSubscriber(). The methods have to start with "handler" followed
	 * by the name of the event followed by "Event" followed by an appropriate
	 * suffix, e.g. handlePageUpdatedEventAfterCommit().
	 *
	 * @stable to override
	 */
	public function registerListeners( DomainEventSource $eventSource ): void {
		if ( !$this->eventTypes ) {
			throw new LogicException(
				'Subclassed of EventSubscriberBase must either override ' .
				'registerListeners or provide a list of event types via ' .
				'initSubscriber() or initEvents().'
			);
		}

		foreach ( $this->eventTypes as $type ) {
			$this->registerListenerMethod( $eventSource, $type );
		}
	}

}
