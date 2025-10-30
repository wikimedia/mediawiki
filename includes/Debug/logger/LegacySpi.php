<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Logger;

use Psr\Log\LoggerInterface;

/**
 * The default service provider for MediaWiki\Logger\LoggerFactory, which creates
 * LegacyLogger objects.
 *
 * Usage:
 * @code
 * $wgMWLoggerDefaultSpi = [
 *   'class' => \MediaWiki\Logger\LegacySpi::class,
 * ];
 * @endcode
 *
 * @since 1.25
 * @ingroup Debug
 * @copyright © 2014 Wikimedia Foundation and contributors
 */
class LegacySpi implements Spi {

	/**
	 * @var array
	 */
	protected $singletons = [];

	/**
	 * Get a logger instance.
	 *
	 * @param string $channel Logging channel
	 * @return \Psr\Log\LoggerInterface Logger instance
	 */
	public function getLogger( $channel ) {
		if ( !isset( $this->singletons[$channel] ) ) {
			$this->singletons[$channel] = new LegacyLogger( $channel );
		}
		return $this->singletons[$channel];
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
