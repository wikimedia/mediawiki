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

namespace Wikimedia\Tests\Leximorph;

use PHPUnit\Framework\TestCase;
use Wikimedia\Leximorph\Handler\Overrides\Grammar\GrammarFi;
use Wikimedia\Leximorph\Handler\Overrides\Grammar\GrammarKk;
use Wikimedia\Leximorph\Handler\Overrides\GrammarFallbackRegistry;

/**
 * GrammarFallbackRegistryTest
 *
 * This test class verifies the functionality of the {@see GrammarFallbackRegistry} class.
 * It tests that the class correctly registers and provides language-specific grammar
 * transformation processors used for procedural grammar overrides.
 *
 * Covered tests include:
 *   - Providing a grammar transformer instance for supported language codes.
 *   - Returning null for unsupported or unknown languages.
 *   - Delegating transformation via `apply()` when a processor exists.
 *   - Falling back to the original word when no processor is defined.
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 *
 * @covers \Wikimedia\Leximorph\Handler\Overrides\GrammarFallbackRegistry
 */
class GrammarFallbackRegistryTest extends TestCase {

	/**
	 * Tests that a valid language code returns a proper grammar transformer instance.
	 *
	 * @since 1.45
	 */
	public function testProvidesGrammarProcessorForKnownLanguage(): void {
		$registry = new GrammarFallbackRegistry();
		$processor = $registry->getProcessor( 'kk' );
		$this->assertInstanceOf( GrammarKk::class, $processor );
	}

	/**
	 * Tests that another supported language returns a grammar transformer instance.
	 *
	 * @since 1.45
	 */
	public function testProvidesAnotherValidProcessor(): void {
		$registry = new GrammarFallbackRegistry();
		$processor = $registry->getProcessor( 'fi' );
		$this->assertInstanceOf( GrammarFi::class, $processor );
	}

	/**
	 * Tests that an unknown language code returns null.
	 *
	 * @since 1.45
	 */
	public function testReturnsNullForUnsupportedLanguage(): void {
		$registry = new GrammarFallbackRegistry();
		$this->assertNull( $registry->getProcessor( 'xx' ) );
	}

	/**
	 * Tests that `apply()` delegates to the processor or returns the original word.
	 *
	 * @since 1.45
	 */
	public function testApplyReturnsTransformedOrOriginal(): void {
		$registry = new GrammarFallbackRegistry();
		$this->assertSame( 'test', $registry->apply( 'xx', 'test', 'genitive' ) );
	}
}
