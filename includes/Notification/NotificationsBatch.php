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

	private array $envelopes = [];

	public function __construct( NotificationEnvelope ...$envelopes ) {
		$this->envelopes = $envelopes;
	}

	public function add( NotificationEnvelope $envelope ) {
		$this->envelopes[] = $envelope;
	}

	public function remove( NotificationEnvelope $toRemove ) {
		$this->filter( static function ( NotificationEnvelope $existing ) use ( $toRemove ) {
			return !$existing->equals( $toRemove );
		} );
	}

	/**
	 * @param callable(NotificationEnvelope): bool $callback Filter, return true to preserve the $envelope
	 */
	public function filter( $callback ): void {
		$this->envelopes =
			array_values(
				array_filter( $this->envelopes, $callback )
			);
	}

	public function count(): int {
		return count( $this->envelopes );
	}

	/**
	 * @return ArrayIterator<NotificationEnvelope>
	 */
	public function getIterator(): ArrayIterator {
		return new ArrayIterator( $this->envelopes );
	}
}
