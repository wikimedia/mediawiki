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
 * MediaWiki\Logger\LoggerFactory.
 *
 * @deprecated since 1.25 Use \MediaWiki\Logger\LoggerFactory
 * @todo This class should be removed before the 1.25 final release.
 */
class MWLogger {

	/**
	 * Register a service provider to create new \Psr\Log\LoggerInterface
	 * instances.
	 *
	 * @param \MediaWiki\Logger\Spi $provider Provider to register
	 * @deprecated since 1.25 Use MediaWiki\Logger\LoggerFactory::registerProvider()
	 */
	public static function registerProvider( \MediaWiki\Logger\Spi $provider ) {
		\MediaWiki\Logger\LoggerFactory::registerProvider( $provider );
	}


	/**
	 * Get the registered service provider.
	 *
	 * If called before any service provider has been registered, it will
	 * attempt to use the $wgMWLoggerDefaultSpi global to bootstrap
	 * MWLoggerSpi registration. $wgMWLoggerDefaultSpi is expected to be an
	 * array usable by ObjectFactory::getObjectFromSpec() to create a class.
	 *
	 * @return \MediaWiki\Logger\Spi
	 * @see registerProvider()
	 * @see ObjectFactory::getObjectFromSpec()
	 * @deprecated since 1.25 Use MediaWiki\Logger\LoggerFactory::getProvider()
	 */
	public static function getProvider() {
		return \MediaWiki\Logger\LoggerFactory::getProvider();
	}


	/**
	 * Get a named logger instance from the currently configured logger factory.
	 *
	 * @param string $channel Logger channel (name)
	 * @return \Psr\Log\LoggerInterface
	 * @deprecated since 1.25 Use MediaWiki\Logger\LoggerFactory::getInstance()
	 */
	public static function getInstance( $channel ) {
		return \MediaWiki\Logger\LoggerFactory::getInstance( $channel );
	}

}

/**
 * Backwards compatibility stub for usage from before the introduction of
 * the MediaWiki\Logger namespace.
 *
 * @deprecated since 1.25 Use \MediaWiki\Logger\LoggerFactory
 * @todo This class should be removed before the 1.25 final release.
 */
class MWLoggerFactory extends \MediaWiki\Logger\LoggerFactory {
}

/**
 * Backwards compatibility stub for usage from before the introduction of
 * the MediaWiki\Logger namespace.
 *
 * @deprecated since 1.25 Use \MediaWiki\Logger\LegacyLogger
 * @todo This class should be removed before the 1.25 final release.
 */
class MWLoggerLegacyLogger extends \MediaWiki\Logger\LegacyLogger {
}

/**
 * Backwards compatibility stub for usage from before the introduction of
 * the MediaWiki\Logger namespace.
 *
 * @deprecated since 1.25 Use \MediaWiki\Logger\LegacySpi
 * @todo This class should be removed before the 1.25 final release.
 */
class MWLoggerLegacySpi extends \MediaWiki\Logger\LegacySpi {
}

/**
 * Backwards compatibility stub for usage from before the introduction of
 * the MediaWiki\Logger namespace.
 *
 * @deprecated since 1.25 Use \MediaWiki\Logger\Monolog\LegacyHandler
 * @todo This class should be removed before the 1.25 final release.
 */
class MWLoggerMonologHandler extends \MediaWiki\Logger\Monolog\LegacyHandler {
}

/**
 * Backwards compatibility stub for usage from before the introduction of
 * the MediaWiki\Logger namespace.
 *
 * @deprecated since 1.25 Use \MediaWiki\Logger\Monolog\LegacyFormatter
 * @todo This class should be removed before the 1.25 final release.
 */
class MWLoggerMonologLegacyFormatter extends \MediaWiki\Logger\Monolog\LegacyFormatter {
}

/**
 * Backwards compatibility stub for usage from before the introduction of
 * the MediaWiki\Logger namespace.
 *
 * @deprecated since 1.25 Use \MediaWiki\Logger\Monolog\WikiProcessor
 * @todo This class should be removed before the 1.25 final release.
 */
class MWLoggerMonologProcessor extends \MediaWiki\Logger\Monolog\WikiProcessor {
}

/**
 * Backwards compatibility stub for usage from before the introduction of
 * the MediaWiki\Logger namespace.
 *
 * @deprecated since 1.25 Use \MediaWiki\Logger\MonologSpi
 * @todo This class should be removed before the 1.25 final release.
 */
class MWLoggerMonologSpi extends \MediaWiki\Logger\MonologSpi {
}

/**
 * Backwards compatibility stub for usage from before the introduction of
 * the MediaWiki\Logger namespace.
 *
 * @deprecated since 1.25 Use \MediaWiki\Logger\Monolog\SyslogHandler
 * @todo This class should be removed before the 1.25 final release.
 */
class MWLoggerMonologSyslogHandler extends \MediaWiki\Logger\Monolog\SyslogHandler {
}

/**
 * Backwards compatibility stub for usage from before the introduction of
 * the MediaWiki\Logger namespace.
 *
 * @deprecated since 1.25 Use \MediaWiki\Logger\NullSpi
 * @todo This class should be removed before the 1.25 final release.
 */
class MWLoggerNullSpi extends \MediaWiki\Logger\NullSpi {
}

/**
 * Backwards compatibility stub for usage from before the introduction of
 * the MediaWiki\Logger namespace.
 *
 * @deprecated since 1.25 Use \MediaWiki\Logger\Spi
 * @todo This class should be removed before the 1.25 final release.
 */
interface MWLoggerSpi extends \MediaWiki\Logger\Spi {
}
