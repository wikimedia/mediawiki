<?php
/**
 * @section LICENSE
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
 * PSR-3 logging service.
 *
 * This class provides a service interface for logging system events. The
 * MWLogger class itself is intended to be a thin wrapper around another PSR-3
 * compliant logging library. Creation of MWLogger instances is managed via
 * the MWLogger::getInstance() static method which in turn delegates to the
 * currently registered service provider.
 *
 * A service provider is any class implementing the MWLoggerSpi interface.
 * There are two possible methods of registering a service provider. The
 * MWLogger::registerProvider() static method can be called at any time to
 * change the service provider. If MWLogger::getInstance() is called before
 * any service provider has been registered, it will attempt to use the
 * $wgMWLoggerDefaultSpi global to bootstrap MWLoggerSpi registration.
 * $wgMWLoggerDefaultSpi can either be the name of a class implementing the
 * MWLoggerSpi interface with a zero argument constructor or a callable that
 * will return an MWLoggerSpi instance.
 *
 * @see MWLoggerSpi
 * @since 1.24
 * @author Bryan Davis <bd808@wikimedia.org>
 * @copyright © 2014 Bryan Davis and Wikimedia Foundation.
 */
class MWLogger implements \Psr\Log\LoggerInterface {

	/**
	 * Service provider.
	 * @var MWLoggerSpi $spi
	 */
	protected static $spi;


	/**
	 * Wrapped PSR-3 logger instance.
	 *
	 * @var \Psr\Log\LoggerInterface $delegate
	 */
	protected $delegate;


	/**
	 * @param \Psr\Log\LoggerInterface $logger
	 */
	public function __construct( \Psr\Log\LoggerInterface $logger ) {
		$this->delegate = $logger;
	}


	/**
	 * Logs with an arbitrary level.
	 *
	 * @param string|int $level
	 * @param string $message
	 * @param array $context
	 */
	public function log( $level, $message, array $context = array() ) {
		$this->delegate->log( $level, $message, $context );
	}


	/**
	 * System is unusable.
	 *
	 * @param string $message
	 * @param array $context
	 */
	public function emergency( $message, array $context = array() ) {
		$this->log( \Psr\Log\LogLevel::EMERGENCY, $message, $context );
	}


	/**
	 * Action must be taken immediately.
	 *
	 * Example: Entire website down, database unavailable, etc. This should
	 * trigger the SMS alerts and wake you up.
	 *
	 * @param string $message
	 * @param array $context
	 */
	public function alert( $message, array $context = array() ) {
		$this->log( \Psr\Log\LogLevel::ALERT, $message, $context );
	}


	/**
	 * Critical conditions.
	 *
	 * Example: Application component unavailable, unexpected exception.
	 *
	 * @param string $message
	 * @param array $context
	 */
	public function critical( $message, array $context = array( ) ) {
		$this->log( \Psr\Log\LogLevel::CRITICAL, $message, $context );
	}


	/**
	 * Runtime errors that do not require immediate action but should typically
	 * be logged and monitored.
	 *
	 * @param string $message
	 * @param array $context
	 */
	public function error( $message, array $context = array( ) ) {
		$this->log( \Psr\Log\LogLevel::ERROR, $message, $context );
	}


	/**
	 * Exceptional occurrences that are not errors.
	 *
	 * Example: Use of deprecated APIs, poor use of an API, undesirable things
	 * that are not necessarily wrong.
	 *
	 * @param string $message
	 * @param array $context
	 */
	public function warning( $message, array $context = array() ) {
		$this->log( \Psr\Log\LogLevel::WARNING, $message, $context );
	}


	/**
	 * Normal but significant events.
	 *
	 * @param string $message
	 * @param array $context
	 */
	public function notice( $message, array $context = array() ) {
		$this->log( \Psr\Log\LogLevel::NOTICE, $message, $context );
	}


	/**
	 * Interesting events.
	 *
	 * Example: User logs in, SQL logs.
	 *
	 * @param string $message
	 * @param array $context
	 */
	public function info( $message, array $context = array() ) {
		$this->log( \Psr\Log\LogLevel::INFO, $message, $context );
	}


	/**
	 * Detailed debug information.
	 *
	 * @param string $message
	 * @param array $context
	 */
	public function debug( $message, array $context = array() ) {
		$this->log( \Psr\Log\LogLevel::DEBUG, $message, $context );
	}


	/**
	 * Register a service provider to create new MWLogger instances.
	 *
	 * @param MWLoggerSpi $provider Provider to register
	 */
	public static function registerProvider( MWLoggerSpi $provider ) {
		self::$spi = $provider;
	}


	/**
	 * Get a named logger instance from the currently configured logger factory.
	 *
	 * @param string $channel Logger channel (name)
	 * @return MWLogger
	 */
	public static function getInstance( $channel ) {
		if ( self::$spi === null ) {
			global $wgMWLoggerDefaultSpi;
			if ( is_callable( $wgMWLoggerDefaultSpi ) ) {
				$provider = $wgMWLoggerDefaultSpi();
			} else {
				$provider = new $wgMWLoggerDefaultSpi();
			}
			self::registerProvider( $provider );
		}

		return self::$spi->getLogger( $channel );
	}

}
