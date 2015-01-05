<?php
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Implementation of the MessageReporter interface which
 * logs messages via the wfLogMessage function.
 *
 * @since 1.24
 *
 * @licence GNU GPL v2+
 */
class LogMessageReporter implements MessageReporter {

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	private $level = LogLevel::INFO;

	public function __construct( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * @see LogLevel
	 *
	 * @param string $level
	 */
	public function setLevel( $level ) {
		$this->level = $level;
	}

	/**
	 * @see LogLevel
	 *
	 * @return string
	 */
	public function getLevel() {
		return $this->level;
	}

	/**
	 * @see MessageReporter::reportMessage
	 *
	 * @param string $message
	 */
	public function reportMessage( $message ) {
		$this->logger->log( $this->level, $message );
	}
}
