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
 * @since 1.44
 * @unstable until 1.45, should become stable to extend
 */
abstract class DomainEvent {

	public const ANY = '*';

	private string $eventType = self::ANY;
	private array $compatibleWithTypes = [ self::ANY ];
	private ConvertibleTimestamp $timestamp;

	/**
	 * @stable to call
	 * @param string|ConvertibleTimestamp|false $timestamp
	 */
	public function __construct( $timestamp = false ) {
		$this->timestamp = $timestamp instanceof ConvertibleTimestamp
			? $timestamp
			: MWTimestamp::getInstance( $timestamp );
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
	 * @return ConvertibleTimestamp
	 */
	public function getEventTimestamp(): string {
		return $this->timestamp;
	}

}
