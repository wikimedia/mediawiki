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


/**
 * Backwards compatibility stub for usage from before the introduction of
 * MWLoggerFactory.
 *
 * @deprecated since 1.25 Use MWLoggerFactory
 * @todo This class should be removed before the 1.25 final release.
 */
class MWLogger {

	/**
	 * Register a service provider to create new \Psr\Log\LoggerInterface
	 * instances.
	 *
	 * @param MWLoggerSpi $provider Provider to register
	 * @deprecated since 1.25 Use MWLoggerFactory::registerProvider()
	 */
	public static function registerProvider( MWLoggerSpi $provider ) {
		MWLoggerFactory::registerProvider( $provider );
	}


	/**
	 * Get the registered service provider.
	 *
	 * If called before any service provider has been registered, it will
	 * attempt to use the $wgMWLoggerDefaultSpi global to bootstrap
	 * MWLoggerSpi registration. $wgMWLoggerDefaultSpi is expected to be an
	 * array usable by ObjectFactory::getObjectFromSpec() to create a class.
	 *
	 * @return MWLoggerSpi
	 * @see registerProvider()
	 * @see ObjectFactory::getObjectFromSpec()
	 * @deprecated since 1.25 Use MWLoggerFactory::getProvider()
	 */
	public static function getProvider() {
		return MWLoggerFactory::getProvider();
	}


	/**
	 * Get a named logger instance from the currently configured logger factory.
	 *
	 * @param string $channel Logger channel (name)
	 * @return \Psr\Log\LoggerInterface
	 * @deprecated since 1.25 Use MWLoggerFactory::getInstance()
	 */
	public static function getInstance( $channel ) {
		return MWLoggerFactory::getInstance( $channel );
	}

}
