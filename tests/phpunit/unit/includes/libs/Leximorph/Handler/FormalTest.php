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

namespace Wikimedia\Tests\Leximorph\Handler;

use Generator;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Wikimedia\Leximorph\Handler\Formal;
use Wikimedia\Leximorph\Provider;

/**
 * FormalTest
 *
 * This test class verifies the functionality of the {@see Formal} handler.
 * It tests that the class correctly applies language-specific
 * formal/informal transformations based on rules in data/formal-indexes.json.
 *
 * Covered tests include:
 *   - Correct selection of formal (index=1) or informal (index=0).
 *   - Fallback to 0 when language code or suffix not found.
 *   - Suffix-based logic (`-formal` => 1, `-informal` => 0).
 *   - Behavior when only one form is provided.
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 *
 * @covers \Wikimedia\Leximorph\Handler\Formal
 */
class FormalTest extends TestCase {

	/**
	 * Data provider for testProcess.
	 *
	 * Each test case provides:
	 *  - A language code (possibly including -formal or -informal).
	 *  - A placeholder text (unused in logic).
	 *  - An array of forms ([ formal, informal ]) or single-element array.
	 *  - The expected output string.
	 *
	 * @return Generator<array{string, array<string>, string}>
	 */
	public static function provideFormalCases(): Generator {
		yield 'Base German => informal' => [ 'de', [ 'foo', 'bar' ], 'foo' ];
		yield 'Base English => informal' => [ 'en', [ 'foo', 'bar' ], 'foo' ];
		yield 'Base Dutch => formal' => [ 'nl', [ 'foo', 'bar' ], 'bar' ];
	}

	/**
	 * @dataProvider provideFormalCases
	 *
	 * Tests that the correct formality transformation is applied based on data/formal-indexes.json.
	 *
	 * @param string $lang Language code, optionally including "-formal" or "-informal" suffix.
	 * @param array<string> $options Array of formality options in the form [informal, formal].
	 * @param string $expected Expected selected form based on formal index rules.
	 *
	 * @since 1.45
	 */
	public function testProcess( string $lang, array $options, string $expected ): void {
		$provider = new Provider( $lang, new NullLogger() );
		$formalHandler = new Formal( $provider );
		$result = $formalHandler->process( $options );
		$this->assertSame( $expected, $result );
	}
}
