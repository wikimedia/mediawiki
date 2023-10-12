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

/**
 * @defgroup Debug Debug logging
 *
 * To primary APIs for this feature, and the classes where their documentation
 * starts, are:
 *
 * - MediaWiki\Logger\LoggerFactory, this creates all logger objects at
 *   run time.
 *
 * - MediaWiki\Logger\Spi, to develop or configure the service classes
 *   that internally create logger objects. For example, MediaWiki\Logger\LegacyLogger
 *   is the default Spi that backs features like $wgDebugLogFile.
 *   MediaWiki\Logger\MonologSpi is an Spi you can opt-in to via $wgMWLoggerDefaultSpi
 *   to enable structured logging with destinations like Syslog and Logstash,
 *   and "processor" and "formatter" features to augment messages with metadata,
 *   as powered by the Monolog library.
 *
 * - MWDebug, the logic behind $wgDebugToolbar.
 *
 * @see [Logger documentation](@ref debuglogger) in docs/Logger.md
 */

/**
 * Service provider interface to create \Psr\Log\LoggerInterface objects.
 *
 * MediaWiki can be configured to use a class implementing this interface
 * via the $wgMWLoggerDefaultSpi configuration variable.
 *
 * This configuration is consumed by MediaWiki\Logger\LoggerFactory, which is
 * where we create logger objects.
 *
 * While not recommended in production code, you can construct and install
 * an Spi class at runtime via MediaWiki\Logger\LoggerFactory::registerProvider
 * (e.g. to power debug features in PHPUnit bootstrapping, or Maintenance
 * scripts).
 *
 * @stable to implement
 * @since 1.25
 * @ingroup Debug
 * @copyright © 2014 Wikimedia Foundation and contributors
 */
interface Spi {

	/**
	 * Get a logger instance.
	 *
	 * @param string $channel Logging channel
	 * @return \Psr\Log\LoggerInterface Logger instance
	 */
	public function getLogger( $channel );

}
