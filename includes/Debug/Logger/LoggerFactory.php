<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Logger;

use LogicException;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * Create PSR-3 logger objects.
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
 * @since 1.25
 * @ingroup Debug
 * @copyright © 2014 Wikimedia Foundation and contributors
 */
class LoggerFactory {

	/**
	 * Service provider.
	 * @var \MediaWiki\Logger\Spi
	 */
	private static $spi;

	private static ?LoggingContext $context;

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
	 * Get a logging context, which can be used to add information to all log events.
	 * @since 1.44
	 */
	public static function getContext(): LoggingContext {
		self::$context ??= new LoggingContext();
		return self::$context;
	}

	/**
	 * Replace the logging context, for testing.
	 * Can also be used to reset the context between tests, by passing new LoggingContext().
	 * @internal Should only be used by PHPUnit and parser tests.
	 */
	public static function setContext( LoggingContext $context ): void {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new LogicException( __METHOD__ . ' can only be used in PHPUnit tests' );
		}
		self::$context = $context;
	}

	/**
	 * Construction of utility class is not allowed.
	 */
	private function __construct() {
		// no-op
	}
}
