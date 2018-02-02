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

use Wikimedia\ObjectFactory;

/**
 * PSR-3 logger instance factory.
 *
 * Creation of \Psr\Log\LoggerInterface instances is managed via the
 * LoggerFactory::getInstance() static method which in turn delegates to the
 * currently registered service provider.
 *
 * A service provider is any class implementing the Spi interface.
 * There are two possible methods of registering a service provider. The
 * LoggerFactory::registerProvider() static method can be called at any time
 * to change the service provider. If LoggerFactory::getInstance() is called
 * before any service provider has been registered, it will attempt to use the
 * $wgMWLoggerDefaultSpi global to bootstrap Spi registration.
 * $wgMWLoggerDefaultSpi is expected to be an array usable by
 * ObjectFactory::getObjectFromSpec() to create a class.
 *
 * @see \MediaWiki\Logger\Spi
 * @since 1.25
 * @copyright © 2014 Wikimedia Foundation and contributors
 */
class LoggerFactory {

	/**
	 * Service provider.
	 * @var \MediaWiki\Logger\Spi $spi
	 */
	private static $spi;

	/**
	 * Register a service provider to create new \Psr\Log\LoggerInterface
	 * instances.
	 *
	 * @param \MediaWiki\Logger\Spi $provider Provider to register
	 */
	public static function registerProvider( Spi $provider ) {
		self::$spi = $provider;
	}

	/**
	 * Get the registered service provider.
	 *
	 * If called before any service provider has been registered, it will
	 * attempt to use the $wgMWLoggerDefaultSpi global to bootstrap
	 * Spi registration. $wgMWLoggerDefaultSpi is expected to be an
	 * array usable by ObjectFactory::getObjectFromSpec() to create a class.
	 *
	 * @return \MediaWiki\Logger\Spi
	 * @see registerProvider()
	 * @see ObjectFactory::getObjectFromSpec()
	 */
	public static function getProvider() {
		if ( self::$spi === null ) {
			global $wgMWLoggerDefaultSpi;
			$provider = ObjectFactory::getObjectFromSpec(
				$wgMWLoggerDefaultSpi
			);
			self::registerProvider( $provider );
		}
		return self::$spi;
	}

	/**
	 * Get a named logger instance from the currently configured logger factory.
	 *
	 * @param string $channel Logger channel (name)
	 * @return \Psr\Log\LoggerInterface
	 */
	public static function getInstance( $channel ) {
		return self::getProvider()->getLogger( $channel );
	}

	/**
	 * Construction of utility class is not allowed.
	 */
	private function __construct() {
		// no-op
	}
}
