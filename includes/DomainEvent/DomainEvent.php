<?php

namespace MediaWiki\DomainEvent;

use MediaWiki\Utils\MWTimestamp;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Base class for domain event objects to be used with DomainEventSink.
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

	private string $type;
	private ConvertibleTimestamp $timestamp;

	/**
	 * @stable to call
	 * @param string $type
	 * @param string|ConvertibleTimestamp|false $timestamp
	 */
	public function __construct( string $type, $timestamp = false ) {
		$this->type = $type;

		$this->timestamp = $timestamp instanceof ConvertibleTimestamp
			? $timestamp
			: MWTimestamp::getInstance( $timestamp );
	}

	/**
	 * @return string
	 */
	public function getEventType(): string {
		return $this->type;
	}

	/**
	 * @return ConvertibleTimestamp
	 */
	public function getEventTimestamp(): string {
		return $this->timestamp;
	}

}
