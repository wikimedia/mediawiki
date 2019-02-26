<?php

namespace MediaWiki\Logger;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

/**
 * Wraps another spi to capture all logs generated. This can be
 * used, for example, to collect all logs generated during a
 * unit test and report them when the test fails.
 */
class LogCapturingSpi implements Spi {
	/** @var LoggerInterface[] */
	private $singletons;
	/** @var Spi */
	private $inner;
	/** @var array */
	private $logs = [];

	public function __construct( Spi $inner ) {
		$this->inner = $inner;
	}

	/**
	 * @return array
	 */
	public function getLogs() {
		return $this->logs;
	}

	/**
	 * @param string $channel
	 * @return LoggerInterface
	 */
	public function getLogger( $channel ) {
		if ( !isset( $this->singletons[$channel] ) ) {
			$this->singletons[$channel] = $this->createLogger( $channel );
		}
		return $this->singletons[$channel];
	}

	/**
	 * @param array $log
	 */
	public function capture( $log ) {
		$this->logs[] = $log;
	}

	/**
	 * @param string $channel
	 * @return LoggerInterface
	 */
	private function createLogger( $channel ) {
		$inner = $this->inner->getLogger( $channel );
		return new class( $channel, $inner, $this ) extends AbstractLogger {
			/** @var string */
			private $channel;
			/** @var LoggerInterface */
			private $logger;
			/** @var LogCapturingSpi */
			private $parent;

			// phpcs:ignore MediaWiki.Usage.NestedFunctions.NestedFunction
			public function __construct( $channel, LoggerInterface $logger, LogCapturingSpi $parent ) {
				$this->channel = $channel;
				$this->logger = $logger;
				$this->parent = $parent;
			}

			// phpcs:ignore MediaWiki.Usage.NestedFunctions.NestedFunction
			public function log( $level, $message, array $context = [] ) {
				$this->parent->capture( [
					'channel' => $this->channel,
					'level' => $level,
					'message' => $message,
					'context' => $context
				] );
				$this->logger->log( $level, $message, $context );
			}
		};
	}
}
