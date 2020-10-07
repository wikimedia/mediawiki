<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Logger;

use Psr\Log\LoggerInterface;

/**
 * LoggerFactory service provider that creates LegacyLogger instances.
 *
 * Usage:
 * @code
 * $wgMWLoggerDefaultSpi = [
 *   'class' => \MediaWiki\Logger\LegacySpi::class,
 * ];
 * @endcode
 *
 * @see \MediaWiki\Logger\LoggerFactory
 * @since 1.25
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
	public function setLoggerForTest( $channel, LoggerInterface $logger = null ) {
		$ret = $this->singletons[$channel] ?? null;
		$this->singletons[$channel] = $logger;
		return $ret;
	}

}
