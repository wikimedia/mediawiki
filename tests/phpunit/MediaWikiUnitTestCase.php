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

/**
 * Base class for unit tests.
 *
 * Extend this class if you are testing classes which use dependency injection and do not access
 * global functions, variables, services or a storage backend.
 */
abstract class MediaWikiUnitTestCase extends TestCase {
	use PHPUnit4And6Compat;
	use MediaWikiCoversValidator;

	private $unitGlobals = [];

	protected function setUp() {
		parent::setUp();
		$reflection = new ReflectionClass( $this );
		$dirSeparator = DIRECTORY_SEPARATOR;
		if ( strpos( $reflection->getFilename(), "${dirSeparator}unit${dirSeparator}" ) === false ) {
			$this->fail( 'This unit test needs to be in "tests/phpunit/unit" !' );
		}
		$this->unitGlobals = $GLOBALS;
		unset( $GLOBALS );
		$GLOBALS = [];
		// Add back the minimal set of globals needed for unit tests to run for core +
		// extensions/skins.
		foreach ( $this->unitGlobals['wgPhpUnitBootstrapGlobals'] ?? [] as $key => $value ) {
			$GLOBALS[ $key ] = $this->unitGlobals[ $key ];
		}
	}

	protected function tearDown() {
		$GLOBALS = $this->unitGlobals;
		parent::tearDown();
	}
}
