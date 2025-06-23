<?php

namespace MediaWiki\DomainEvent;

use InvalidArgumentException;
use LogicException;

/**
 * Base class for event ingress objects.
 *
 * Event ingress objects implements listener methods for events that a
 * component or extension is interested in. It is responsible for determining
 * which event should trigger which logic in the component and for mapping from
 * the model used by the emitter of the event to the component's own model.
 *
 * DomainEventIngress implements InitializableDomainEventSubscriber so it can be
 * registered with and initialized by a DomainEventSource. Registration is
 * typically done in the form of an object spec for lazy instantiation. For
 * extensions' ingress objects that object spec can be provided in the
 * DomainEventIngresses section of extension.json.
 *
 * After instantiating a subscriber (typically a subclass of DomainEventIngress),
 * the event source will call initSubscriber() to initialize the subscriber and
 * then registerListeners() to allow the subscriber to register listeners for
 * the events it is interested in.
 *
 * This class provides a default implementation of registerListeners() that will
 * attempt to find listener methods for the events the ingress object is
 * interested in. Listener methods must have a name based on the event type,
 * following the pattern "handle{$eventType}Event".
 *
 * The set of events the ingress objects is interested in must be provided as
 * part of the $options array passed to initSubscriber() when it is called by
 * the event source. This array is simply the same as the object spec used to
 * register the ingress object with the event source. That means that for
 * extensions, the list of events is given as part of the ingress object's spec
 * in extension.json.
 *
 * @since 1.44
 *
 * @see docs/Events.md
 * @see https://www.mediawiki.org/wiki/Manual:Domain_events
 */
abstract class DomainEventIngress implements InitializableDomainEventSubscriber {

	/**
	 * @var string[]
	 */
	private array $eventTypes = [];

	/**
	 * Called by DomainEventDispatcher to provide access to the list of events to
	 * subscribe to and any other relevant information from the extension.json.
	 *
	 * Known keys used in $options:
	 * - 'events': a list of events the ingress object should register listeners
	 *   for (required). The object must implement a listener method for each
	 *   of the events listed here, using the following pattern:
	 *   public function handleSomeEventEvent( SomeEvent $event ).
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
			$msg = "No listener methods found for $eventType on " . get_class( $this ) . ".";
			if ( str_ends_with( $eventType, 'Event' ) ) {
				$fixedType = substr( $eventType, 0, -5 );
				$msg .= "\nThe event type name should not include the 'Event' suffix, " .
					"use '$fixedType' instead!";
			}

			throw new LogicException( $msg );
		}
	}

	private function maybeRegisterListener(
		DomainEventSource $eventSource,
		string $eventType,
		string $suffix
	): bool {
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
				'Subclassed of DomainEventIngress must either override ' .
				'registerListeners or provide a list of event types via ' .
				'initSubscriber() or initEvents().'
			);
		}

		foreach ( $this->eventTypes as $type ) {
			$this->registerForEvent( $eventSource, $type );
		}
	}

}
