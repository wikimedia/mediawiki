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
 * pattern "handle{$eventType}Event".
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

	/**
	 * Registered any listener methods for the given event.
	 *
	 * @param DomainEventSource $eventSource
	 * @param string $eventType
	 *
	 * @return void
	 */
	protected function registerForEvent(
		DomainEventSource $eventSource,
		string $eventType
	) {
		$found = false;

		// Suffixed are a stand-in for method attributes.
		// NOTE: The 'AfterCommit' suffix is supported for backwards compatibility,
		// but should be removed before the 1.44 release.
		$suffixes = [ '', 'AfterCommit' ];

		foreach ( $suffixes as $sfx ) {
			if ( $this->maybeRegisterListener( $eventSource, $eventType, $sfx ) ) {
				$found = true;
			}
		}

		if ( !$found ) {
			throw new LogicException(
				"No listener methods found for $eventType on " . get_class( $this )
			);
		}
	}

	private function maybeRegisterListener(
		DomainEventSource $eventSource,
		string $eventType,
		string $suffix
	) {
		$method = "handle{$eventType}Event{$suffix}";
		if ( !method_exists( $this, $method ) ) {
			return false;
		}

		$eventSource->registerListener(
			$eventType,
			[ $this, $method ],
			$this->getListenerOptions( $eventType, $suffix )
		);
		return true;
	}

	/**
	 * Placeholder method for allowing subclasses to define listener options.
	 * The default implementation could make use of method attributes in the future,
	 * e.g. to determine a listener's priority.
	 *
	 * @unstable for now, intended to become stable to override once the signature is clear.
	 */
	protected function getListenerOptions(
		string $eventType,
		string $suffix
	): array {
		// We may use the options array in the future to communicates things
		// like listener priority, error handling, etc.
		return [];
	}

	/**
	 * This default implementation of registerListeners() will automatically
	 * register a listener method for each event passed to initEvents() or
	 * initSubscriber(). The methods have to start with "handler" followed
	 * by the name of the event followed by "Event".
	 *
	 * @stable to override
	 */
	public function registerListeners( DomainEventSource $eventSource ): void {
		// TODO: look at static::EVENTS too?

		if ( !$this->eventTypes ) {
			throw new LogicException(
				'Subclassed of EventSubscriberBase must either override ' .
				'registerListeners or provide a list of event types via ' .
				'initSubscriber() or initEvents().'
			);
		}

		foreach ( $this->eventTypes as $type ) {
			$this->registerForEvent( $eventSource, $type );
		}
	}

}
