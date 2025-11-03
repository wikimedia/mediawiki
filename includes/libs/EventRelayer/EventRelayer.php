<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\EventRelayer;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Base class for reliable event relays
 *
 * @stable to extend
 */
abstract class EventRelayer implements LoggerAwareInterface {
	/** @var LoggerInterface */
	protected $logger;

	/**
	 * @stable to call
	 *
	 * @param array $params
	 */
	public function __construct( array $params ) {
		$this->logger = new NullLogger();
	}

	/**
	 * @param string $channel
	 * @param array $event Event data map
	 * @return bool Success
	 */
	final public function notify( $channel, $event ) {
		return $this->doNotify( $channel, [ $event ] );
	}

	/**
	 * @param string $channel
	 * @param array $events List of event data maps
	 * @return bool Success
	 */
	final public function notifyMulti( $channel, $events ) {
		return $this->doNotify( $channel, $events );
	}

	public function setLogger( LoggerInterface $logger ): void {
		$this->logger = $logger;
	}

	/**
	 * @param string $channel
	 * @param array $events List of event data maps
	 * @return bool Success
	 */
	abstract protected function doNotify( $channel, array $events );
}

/** @deprecated class alias since 1.41 */
class_alias( EventRelayer::class, 'EventRelayer' );
