<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Logger;

use Psr\Log\NullLogger;

/**
 * LoggerFactory service provider that creates \Psr\Log\NullLogger
 * instances. A NullLogger silently discards all log events sent to it.
 *
 * Usage:
 *
 *     $wgMWLoggerDefaultSpi = [
 *         'class' => \MediaWiki\Logger\NullSpi::class,
 *     ];
 *
 * @see \MediaWiki\Logger\LoggerFactory
 * @since 1.25
 * @ingroup Debug
 * @copyright © 2014 Wikimedia Foundation and contributors
 */
class NullSpi implements Spi {

	/**
	 * @var \Psr\Log\NullLogger
	 */
	protected $singleton;

	public function __construct() {
		$this->singleton = new NullLogger();
	}

	/**
	 * Get a logger instance.
	 *
	 * @param string $channel Logging channel
	 * @return \Psr\Log\NullLogger Logger instance
	 */
	public function getLogger( $channel ) {
		return $this->singleton;
	}

}
