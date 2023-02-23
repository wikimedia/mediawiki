<?php

namespace MediaWiki\Logger;

use Psr\Log\NullLogger;

/**
 * Simple logger SPI for logging to STDERR.
 */
class ConsoleSpi implements Spi {

	/** @var string[]|null Channel allow-list: channel name => minimum level */
	private ?array $channels;

	private ?Spi $forwardTo;

	/**
	 * @param array $config
	 *   - channels: (string[]) List of channels to log: channel name => minimum level.
	 *     Omit to log everything.
	 *   - forwardTo: (Spi) Forward all log messages to this SPI (regardless of whether
	 *     ConsoleSPI logs them).
	 */
	public function __construct( array $config = [] ) {
		$this->channels = $config['channels'] ?? null;
		$this->forwardTo = $config['forwardTo'] ?? null;
	}

	/** @inheritDoc */
	public function getLogger( $channel ) {
		if ( !$this->channels || isset( $this->channels[$channel] ) ) {
			return new ConsoleLogger( $channel, $this->channels[$channel] ?? null,
				$this->forwardTo ? $this->forwardTo->getLogger( $channel ) : null );
		} else {
			return $this->forwardTo ? $this->forwardTo->getLogger( $channel ) : new NullLogger();
		}
	}
}
