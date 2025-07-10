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
	/** @var array<string,LoggerInterface> */
	private array $singletons;
	private Spi $inner;
	/** @var array[] */
	private array $logs = [];

	public function __construct( Spi $inner ) {
		$this->inner = $inner;
	}

	/**
	 * @return array[]
	 */
	public function getLogs(): array {
		return $this->logs;
	}

	/** @inheritDoc */
	public function getLogger( $channel ) {
		if ( !isset( $this->singletons[$channel] ) ) {
			$this->singletons[$channel] = $this->createLogger( $channel );
		}
		return $this->singletons[$channel];
	}

	public function capture( array $log ): void {
		$this->logs[] = $log;
	}

	private function createLogger( string $channel ): LoggerInterface {
		$inner = $this->inner->getLogger( $channel );
		return new class( $channel, $inner, $this ) extends AbstractLogger {
			private string $channel;
			private LoggerInterface $logger;
			private LogCapturingSpi $parent;

			public function __construct( string $channel, LoggerInterface $logger, LogCapturingSpi $parent ) {
				$this->channel = $channel;
				$this->logger = $logger;
				$this->parent = $parent;
			}

			/** @inheritDoc */
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
	 */
	public function getInnerSpi(): Spi {
		return $this->inner;
	}

	/**
	 * @internal For use by MediaWikiIntegrationTestCase
	 */
	public function setLoggerForTest( string $channel, ?LoggerInterface $logger = null ): ?LoggerInterface {
		$ret = $this->singletons[$channel] ?? null;
		if ( $logger ) {
			$this->singletons[$channel] = $logger;
		} else {
			unset( $this->singletons[$channel] );
		}
		return $ret;
	}
}
