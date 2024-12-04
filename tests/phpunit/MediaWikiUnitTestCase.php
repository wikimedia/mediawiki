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
 * @ingroup Testing
 */

use MediaWiki\Config\HashConfig;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Deferred\DeferredUpdatesScopeMediaWikiStack;
use MediaWiki\Deferred\DeferredUpdatesScopeStack;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Logger\NullSpi;
use MediaWiki\MediaWikiServices;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Settings\SettingsBuilder;
use PHPUnit\Framework\TestCase;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\Services\NoSuchServiceException;

/**
 * Base class for unit tests.
 *
 * Extend this class if you are testing classes which use dependency injection and do not access
 * global functions, variables, services or a storage backend.
 *
 * @stable to extend
 * @since 1.34
 */
abstract class MediaWikiUnitTestCase extends TestCase {
	use MediaWikiCoversValidator;
	use MediaWikiTestCaseTrait;

	/** @var array */
	private static $originalGlobals;
	/** @var array */
	private static $unitGlobals;

	private ?MediaWikiServices $serviceContainer = null;

	/**
	 * @var array<string,object>
	 */
	private array $services = [];

	/**
	 * List of allowed globals to allow in MediaWikiUnitTestCase.
	 *
	 * Please, keep this list to the bare minimum.
	 */
	private const ALLOWED_GLOBALS_LIST = [
		// The autoloader may change between bootstrap and the first test,
		// so (lazily) capture these here instead.
		'wgAutoloadClasses',
		'wgAutoloadLocalClasses',
		// Need for LoggerFactory. Default is NullSpi.
		'wgMWLoggerDefaultSpi',
		'wgLegalTitleChars',
		'wgDevelopmentWarnings',
		// Dependency of wfParseUrl()
		'wgUrlProtocols',
		// For LegacyLogger, injected by DevelopmentSettings.php
		'wgDebugLogFile',
		'wgDebugLogGroups',
	];

	/**
	 * The annotation causes this to be called immediately before setUpBeforeClass()
	 * @beforeClass
	 */
	final public static function mediaWikiSetUpBeforeClass(): void {
		$reflection = new ReflectionClass( static::class );
		$dirSeparator = DIRECTORY_SEPARATOR;
		if ( stripos( $reflection->getFileName(), "{$dirSeparator}unit{$dirSeparator}" ) === false ) {
			self::fail( 'This unit test needs to be in "tests/phpunit/unit"!' );
		}

		self::$unitGlobals =& TestSetup::$bootstrapGlobals;

		foreach ( self::ALLOWED_GLOBALS_LIST as $global ) {
			self::$unitGlobals[ $global ] =& $GLOBALS[ $global ];
		}

		// Would be nice if we could simply replace $GLOBALS as a whole,
		// but un-setting or re-assigning that breaks the reference of this magic
		// variable. Thus we have to modify it in place.
		self::$originalGlobals = [];
		foreach ( $GLOBALS as $key => $_ ) {
			// Stash current values
			self::$originalGlobals[$key] =& $GLOBALS[$key];

			// Remove globals not part of the snapshot (see bootstrap.php, phpunit.php).
			if ( $key !== 'GLOBALS' && !array_key_exists( $key, self::$unitGlobals ) ) {
				unset( $GLOBALS[$key] );
			}
		}
		// Restore values from the early snapshot
		// Not by ref because tests must not be able to modify the snapshot.
		foreach ( self::$unitGlobals as $key => $value ) {
			$GLOBALS[ $key ] = $value;
		}

		// Set DeferredUpdates into standalone mode
		DeferredUpdates::setScopeStack( new DeferredUpdatesScopeStack() );
		MediaWikiServices::disallowGlobalInstanceInUnitTests();
		ExtensionRegistry::disableForTest();
		SettingsBuilder::disableAccessForUnitTests();

		// Don't let LoggerFactory::getProvider() access globals or other things we don't want.
		LoggerFactory::registerProvider( ObjectFactory::getObjectFromSpec( [
			'class' => NullSpi::class
		] ) );
	}

	/**
	 * The annotation causes this to be called immediately after tearDown()
	 * @after
	 */
	final protected function mediaWikiTearDown(): void {
		// Quick reset between tests
		foreach ( $GLOBALS as $key => $_ ) {
			if ( $key !== 'GLOBALS' && !array_key_exists( $key, self::$unitGlobals ) ) {
				unset( $GLOBALS[$key] );
			}
		}
		foreach ( self::$unitGlobals as $key => $value ) {
			$GLOBALS[ $key ] = $value;
		}
	}

	/**
	 * The annotation causes this to be called immediately after tearDownAfterClass()
	 * @afterClass
	 */
	final public static function mediaWikiTearDownAfterClass(): void {
		// Remove globals created by the test
		foreach ( $GLOBALS as $key => $_ ) {
			if ( $key !== 'GLOBALS' && !array_key_exists( $key, self::$originalGlobals ) ) {
				unset( $GLOBALS[$key] );
			}
		}
		// Restore values (including reference!)
		foreach ( self::$originalGlobals as $key => &$value ) {
			$GLOBALS[ $key ] =& $value;
		}
		unset( $value );

		MediaWikiServices::allowGlobalInstanceAfterUnitTests();
		DeferredUpdates::setScopeStack( new DeferredUpdatesScopeMediaWikiStack() );
		ExtensionRegistry::enableForTest();
		SettingsBuilder::enableAccessAfterUnitTests();
	}

	/**
	 * Returns a mock service container.
	 * To populate the service container with service objects, use setService().
	 *
	 * @since 1.43
	 */
	protected function getServiceContainer(): MediaWikiServices {
		if ( !$this->serviceContainer ) {
			$this->serviceContainer = $this->getMockBuilder( MediaWikiServices::class )
				->setConstructorArgs( [ new HashConfig() ] )
				->onlyMethods( [
					'getService',
					'disableStorage',
					'isStorageDisabled',
					'redefineService',
					'resetServiceForTesting',
					'resetChildProcessServices',
					'peekService'
				] )
				->getMock();

			$this->serviceContainer
				->method( 'getService' )
				->willReturnCallback( function ( $name ) {
					return $this->getService( $name );
				} );
		}

		return $this->serviceContainer;
	}

	/**
	 * Returns a service previously defined with setService().
	 *
	 * @param string $name
	 *
	 * @return mixed The service instance
	 */
	protected function getService( string $name ) {
		if ( !isset( $this->services[$name] ) ) {
			throw new NoSuchServiceException( $name );
		}

		if ( is_callable( $this->services[$name] ) ) {
			$func = $this->services[$name];
			$this->services[$name] = $func( $this->serviceContainer );
		}

		return $this->services[$name];
	}

	/**
	 * Register a service object with the service container returned by
	 * getServiceContainer().
	 *
	 * @param string $name
	 * @param mixed $service
	 *
	 * @since 1.43
	 */
	protected function setService( string $name, $service ) {
		$this->services[$name] = $service;
	}

}
