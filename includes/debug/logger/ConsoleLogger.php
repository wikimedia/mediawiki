<?php

namespace MediaWiki\Logger;

use Psr\Log\AbstractLogger;

/**
 * A logger which writes to the terminal. The output is supposed to be
 * human-readable, and should be changed as necessary to better achieve that
 * goal.
 */
class ConsoleLogger extends AbstractLogger {
	public function __construct( $channel ) {
		$this->channel = $channel;
	}

	public function log( $level, $message, array $context = [] ) {
		fwrite( STDERR, "[$level] " .
			LegacyLogger::format( $this->channel, $message, $context ) );
	}
}
