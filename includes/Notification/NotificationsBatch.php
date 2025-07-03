<?php
namespace MediaWiki\Notification;

use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * An object representing notification with list of recipients
 * @since 1.44
 * @unstable
 */
class NotificationsBatch implements Countable, IteratorAggregate {

	/** @var NotificationEnvelope[] */
	private array $envelopes = [];

	public function __construct( NotificationEnvelope ...$envelopes ) {
		foreach ( $envelopes as $envelope ) {
			$this->add( $envelope );
		}
	}

	public function add( NotificationEnvelope $envelope ): void {
		$this->envelopes[ spl_object_id( $envelope ) ] = $envelope;
	}

	public function remove( NotificationEnvelope $envelope ): void {
		unset( $this->envelopes[ spl_object_id( $envelope ) ] );
	}

	/**
	 * @param callable(NotificationEnvelope): bool $callback Filter, return true to preserve the $envelope
	 */
	public function filter( $callback ): void {
		$this->envelopes = array_filter( $this->envelopes, $callback );
	}

	public function count(): int {
		return count( $this->envelopes );
	}

	/**
	 * @return ArrayIterator<NotificationEnvelope>
	 */
	public function getIterator(): ArrayIterator {
		return new ArrayIterator( array_values( $this->envelopes ) );
	}
}
