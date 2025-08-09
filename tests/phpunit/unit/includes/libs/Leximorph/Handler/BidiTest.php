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
use Wikimedia\Leximorph\Handler\Bidi;
use Wikimedia\Leximorph\Provider;

/**
 * BidiTest
 *
 * This test class verifies the functionality of the {@see Bidi} handler.
 * It tests that the class correctly processes bidirectional text handling
 * by applying directional isolation to mixed LTR and RTL content.
 *
 * Covered tests include:
 *   - Correct wrapping of LTR text with LRE...PDF.
 *   - Correct wrapping of RTL text with RLE...PDF.
 *   - Handling of text with no strong directional characters.
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 *
 * @covers \Wikimedia\Leximorph\Handler\Bidi
 */
class BidiTest extends TestCase {

	/**
	 * Data provider for testProcess.
	 *
	 * Each test case provides:
	 *  - A text input.
	 *  - The expected output with proper directionality wrapping.
	 *
	 * @return Generator<array{0: string, 1: string}>
	 */
	public static function provideBidiCases(): Generator {
		yield 'LTR text' => [ 'Hello, world!', Bidi::LRE . 'Hello, world!' . Bidi::PDF ];
		yield 'RTL text' => [ 'שלום עולם', Bidi::RLE . 'שלום עולם' . Bidi::PDF ];
		yield 'Mixed LTR and RTL' => [ 'Hello שלום', Bidi::LRE . 'Hello שלום' . Bidi::PDF ];
		yield 'Neutral text (no strong directionality)' => [ '123456', '123456' ];
		yield 'RTL text with numbers' => [ 'مرحبا 123', Bidi::RLE . 'مرحبا 123' . Bidi::PDF ];
	}

	/**
	 * @dataProvider provideBidiCases
	 *
	 * Tests that the correct bidirectional isolation is applied.
	 *
	 * @param string $text Input string potentially containing directional text.
	 * @param string $expected Expected string output with correct directional markers.
	 *
	 * @since 1.45
	 */
	public function testProcess( string $text, string $expected ): void {
		$provider = new Provider( 'en', new NullLogger() );
		$bidiHandler = new Bidi( $provider );
		$result = $bidiHandler->process( $text );
		$this->assertSame( $expected, $result );
	}
}
