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

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Logger\NullSpi;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Wikimedia\ObjectFactory\ObjectFactory;

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

	private static $originalGlobals;
	private static $unitGlobals;

	/**
	 * List of allowed globals to allow in MediaWikiUnitTestCase.
	 *
	 * Please, keep this list to the bare minimum.
	 *
	 * @return string[]
	 */
	private static function getAllowedGlobalsList() {
		return [
			// The autoloader may change between bootstrap and the first test,
			// so (lazily) capture these here instead.
			'wgAutoloadClasses',
			'wgAutoloadLocalClasses',
			// Need for LoggerFactory. Default is NullSpi.
			'wgMWLoggerDefaultSpi',
			'wgAutoloadAttemptLowercase',
			'wgLegalTitleChars',
			'wgDevelopmentWarnings',
			// Dependency of wfParseUrl()
			'wgUrlProtocols',
			// For LegacyLogger, injected by DevelopmentSettings.php
			'wgDebugLogFile',
			'wgDebugLogGroups',
		];
	}

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

		foreach ( self::getAllowedGlobalsList() as $global ) {
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
	}

	/**
	 * @inheritDoc
	 */
	protected function runTest() {
		try {
			// Don't let LoggerFactory::getProvider() access globals or other things we don't want.
			LoggerFactory::registerProvider( ObjectFactory::getObjectFromSpec( [
				'class' => NullSpi::class
			] ) );
			return parent::runTest();
		} catch ( ConfigException $exception ) {
			throw new Exception(
				'Config variables must be mocked, they cannot be accessed directly in tests which extend '
				. self::class,
				$exception->getCode(),
				$exception
			);
		}
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
	}

}
