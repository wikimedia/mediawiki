<?php

namespace MediaWiki\DomainEvent;

use LogicException;
use MediaWiki\Utils\MWTimestamp;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Base class for domain event objects to be used with DomainEventDispatcher.
 *
 * Domain events are used to notify other parts of the code (oder "domains")
 * about a change to the persistent state of the local wiki.
 *
 * The idea of domain events is borrowed from the Domain Driven Design paradigm.
 * For a thorough explanation, see
 * <https://learn.microsoft.com/en-us/dotnet/architecture/microservices/microservice-ddd-cqrs-patterns/domain-events-design-implementation>.
 * Also compare <https://martinfowler.com/eaaDev/DomainEvent.html>.
 *
 * Domain event objects must be immutable.
 *
 * An event object should contain all information that was used to affect that
 * change (the command parameters) as well as information representing the
 * outcome of the change.
 *
 * @note Subclasses must call declareEventType() in their constructor!
 *
 * @since 1.44
 *
 * @see docs/Events.md
 * @see https://www.mediawiki.org/wiki/Manual:Domain_events
 */
abstract class DomainEvent {

	public const ANY = '*';

	private string $eventType = self::ANY;
	private array $compatibleWithTypes = [ self::ANY ];
	private ConvertibleTimestamp $timestamp;
	private bool $isReconciliationRequest;

	/**
	 * @stable to call
	 *
	 * @param string|ConvertibleTimestamp|false $timestamp
	 * @param bool $isReconciliationRequest see isReconciliationRequest()
	 */
	public function __construct( $timestamp = false, bool $isReconciliationRequest = false ) {
		$this->timestamp = $timestamp instanceof ConvertibleTimestamp
			? $timestamp
			: MWTimestamp::getInstance( $timestamp );

		$this->isReconciliationRequest = $isReconciliationRequest;
	}

	/**
	 * Determines whether this is a reconciliation event, triggered artificially
	 * in order to give listeners an opportunity to catch up on missed events or
	 * recreate corrupted data.
	 *
	 * Reconciliation requests are typically issued by maintenance scripts,
	 * but can also be caused by user actions such as null-edits.
	 */
	public function isReconciliationRequest(): bool {
		return $this->isReconciliationRequest;
	}

	/**
	 * Declares the event type. Must be called from the constructors of
	 * all subclasses of DomainEvent!
	 *
	 * @param string $eventType
	 */
	protected function declareEventType( string $eventType ) {
		$this->eventType = $eventType;
		$this->compatibleWithTypes[] = $eventType;
	}

	/**
	 * Returns this event's type.
	 */
	public function getEventType(): string {
		if ( $this->eventType === self::ANY ) {
			throw new LogicException(
				'Constructor did not call the declareEventType() method!'
			);
		}
		return $this->eventType;
	}

	/**
	 * Returns the event types this event is compatible with.
	 *
	 * @internal for use in EventDispatchEngine.
	 *
	 * @return array An array containing the event's type and all parent types.
	 */
	public function getEventTypeChain(): array {
		if ( count( $this->compatibleWithTypes ) < 2 ) {
			throw new LogicException(
				'Constructor did not call the declareEventType() method!'
			);
		}
		return $this->compatibleWithTypes;
	}

	/**
	 * Returns the time at which the event was emitted.
	 */
	public function getEventTimestamp(): ConvertibleTimestamp {
		return $this->timestamp;
	}

}
