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

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Exception;

/**
 * Base class for unit tests.
 *
 * Extend this class if you are testing classes which use dependency injection and do not access
 * global functions, variables, services or a storage backend.
 *
 * @since 1.34
 */
abstract class MediaWikiUnitTestCase extends TestCase {
	use PHPUnit4And6Compat;
	use MediaWikiCoversValidator;
	use MediaWikiTestCaseTrait;

	private static $originalGlobals;
	private static $unitGlobals;

	/**
	 * Whitelist of globals to allow in MediaWikiUnitTestCase.
	 *
	 * Please, keep this list to the bare minimum.
	 *
	 * @return string[]
	 */
	private static function getGlobalsWhitelist() {
		return [
			// The autoloader may change between bootstrap and the first test,
			// so (lazily) capture these here instead.
			'wgAutoloadClasses',
			'wgAutoloadLocalClasses',
			// Need for LoggerFactory. Default is NullSpi.
			'wgMWLoggerDefaultSpi',
			'wgAutoloadAttemptLowercase',
			'wgLegalTitleChars'
		];
	}

	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();

		$reflection = new ReflectionClass( static::class );
		$dirSeparator = DIRECTORY_SEPARATOR;
		if ( stripos( $reflection->getFilename(), "${dirSeparator}unit${dirSeparator}" ) === false ) {
			self::fail( 'This unit test needs to be in "tests/phpunit/unit"!' );
		}

		if ( defined( 'HHVM_VERSION' ) ) {
			// There are a number of issues we encountered in trying to make this
			// work on HHVM. Specifically, once an MediaWikiIntegrationTestCase executes
			// before us, the original globals go missing. This might have to do with
			// one of the non-unit tests passing GLOBALS somewhere and causing HHVM
			// to get confused somehow.
			return;
		}

		self::$unitGlobals =& TestSetup::$bootstrapGlobals;

		foreach ( self::getGlobalsWhitelist() as $global ) {
			self::$unitGlobals[ $global ] =& $GLOBALS[ $global ];
		}

		// Would be nice if we coud simply replace $GLOBALS as a whole,
		// but unsetting or re-assigning that breaks the reference of this magic
		// variable. Thus we have to modify it in place.
		self::$originalGlobals = [];
		foreach ( $GLOBALS as $key => $_ ) {
			// Stash current values
			self::$originalGlobals[$key] =& $GLOBALS[$key];

			// Remove globals not part of the snapshot (see bootstrap.php, phpunit.php).
			// Support: HHVM (avoid self-ref)
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

	protected function tearDown() {
		if ( !defined( 'HHVM_VERSION' ) ) {
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

		parent::tearDown();
	}

	public static function tearDownAfterClass() {
		if ( !defined( 'HHVM_VERSION' ) ) {
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

		parent::tearDownAfterClass();
	}

	/**
	 * Create a temporary hook handler which will be reset by tearDown.
	 * This replaces other handlers for the same hook.
	 * @param string $hookName Hook name
	 * @param mixed $handler Value suitable for a hook handler
	 * @since 1.34
	 */
	protected function setTemporaryHook( $hookName, $handler ) {
		// This will be reset by tearDown() when it restores globals. We don't want to use
		// Hooks::register()/clear() because they won't replace other handlers for the same hook,
		// which doesn't match behavior of MediaWikiIntegrationTestCase.
		global $wgHooks;
		$wgHooks[$hookName] = [ $handler ];
	}

	protected function getMockMessage( $text, ...$params ) {
		if ( isset( $params[0] ) && is_array( $params[0] ) ) {
			$params = $params[0];
		}

		$msg = $this->getMockBuilder( Message::class )
			->disableOriginalConstructor()
			->setMethods( [] )
			->getMock();

		$msg->method( 'toString' )->willReturn( $text );
		$msg->method( '__toString' )->willReturn( $text );
		$msg->method( 'text' )->willReturn( $text );
		$msg->method( 'parse' )->willReturn( $text );
		$msg->method( 'plain' )->willReturn( $text );
		$msg->method( 'parseAsBlock' )->willReturn( $text );
		$msg->method( 'escaped' )->willReturn( $text );

		$msg->method( 'title' )->willReturn( $msg );
		$msg->method( 'inLanguage' )->willReturn( $msg );
		$msg->method( 'inContentLanguage' )->willReturn( $msg );
		$msg->method( 'useDatabase' )->willReturn( $msg );
		$msg->method( 'setContext' )->willReturn( $msg );

		$msg->method( 'exists' )->willReturn( true );
		$msg->method( 'content' )->willReturn( new MessageContent( $msg ) );

		return $msg;
	}
}
