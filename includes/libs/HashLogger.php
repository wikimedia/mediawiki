<?php

use Psr\Log\AbstractLogger;

/**
 * A basic in-memory logger
 *
 * Doesn't care about levels, just records the messages
 * provided to it and allows retrival.
 *
 * @since 1.25
 */
class HashLogger extends AbstractLogger {

	private $messages = array();

	/**
	 * @param mixed $level
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function log( $level, $message, array $context = array() ) {
		$this->messages[] = $message;
	}

	/**
	 * @return array
	 */
	public function getMessages() {
		return $this->messages;
	}
}
