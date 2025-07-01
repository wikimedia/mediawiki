<?php

namespace MediaWiki\Logger;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

/**
 * Wrap another Spi and keep a copy of all log messages.
 *
 * This is developed for use by PHPUnit bootstrapping, to collect logs
 * generated during a given unit test, and print them after a failing test.
 *
 * @internal For use in MediaWiki core only
 * @ingroup Debug
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

			public function __construct( $channel, LoggerInterface $logger, LogCapturingSpi $parent ) {
				$this->channel = $channel;
				$this->logger = $logger;
				$this->parent = $parent;
			}

			public function log( $level, $message, array $context = [] ): void {
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

	/**
	 * @internal For use by MediaWikiIntegrationTestCase
	 * @return Spi
	 */
	public function getInnerSpi(): Spi {
		return $this->inner;
	}

	/**
	 * @internal For use by MediaWikiIntegrationTestCase
	 * @param string $channel
	 * @param LoggerInterface|null $logger
	 * @return LoggerInterface|null
	 */
	public function setLoggerForTest( $channel, ?LoggerInterface $logger = null ) {
		$ret = $this->singletons[$channel] ?? null;
		$this->singletons[$channel] = $logger;
		return $ret;
	}
}
