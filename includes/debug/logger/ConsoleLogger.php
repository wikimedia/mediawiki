<?php

namespace MediaWiki\Logger;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Wikimedia\Assert\Assert;

/**
 * Write logs to command-line output (STDERR).
 *
 * The output is supposed to be human-readable, and should be changed as necessary
 * to better achieve that goal.
 *
 * This is developed for use in maintenance/eval.php.
 *
 * @internal For use in MediaWiki core only
 * @since 1.30
 * @ingroup Debug
 */
class ConsoleLogger extends AbstractLogger {
	private const LEVELS = [
		LogLevel::DEBUG => 0,
		LogLevel::INFO => 1,
		LogLevel::NOTICE => 2,
		LogLevel::WARNING => 3,
		LogLevel::ERROR => 4,
		LogLevel::CRITICAL => 5,
		LogLevel::ALERT => 6,
		LogLevel::EMERGENCY => 7,
	];

	private string $channel;
	private ?string $minLevel;
	private ?LoggerInterface $forwardTo;

	/**
	 * @param string $channel log channel name.
	 * @param string|null $minLevel Minimum PSR-3 level below which messages are ignored.
	 * @param LoggerInterface|null $forwardTo Other logger to forward to.
	 */
	public function __construct(
		string $channel,
		?string $minLevel = null,
		?LoggerInterface $forwardTo = null
	) {
		Assert::parameter( $minLevel === null || isset( self::LEVELS[$minLevel] ), '$minLevel',
			'must be a valid, lowercase PSR-3 log level' );

		$this->channel = $channel;
		$this->minLevel = $minLevel;
		$this->forwardTo = $forwardTo;
	}

	/**
	 * @inheritDoc
	 */
	public function log( $level, $message, array $context = [] ) {
		if ( !$this->minLevel || self::LEVELS[$level] >= self::LEVELS[$this->minLevel] ) {
			fwrite( STDERR, "[$level] " .
				LegacyLogger::format( $this->channel, $message, $context ) );
		}
		if ( $this->forwardTo ) {
			$this->forwardTo->log( $level, $message, $context );
		}
	}
}
