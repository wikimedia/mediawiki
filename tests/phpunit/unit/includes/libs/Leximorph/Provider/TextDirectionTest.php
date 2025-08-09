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

namespace Wikimedia\Tests\Leximorph\Provider;

use Generator;
use PHPUnit\Framework\TestCase;
use Wikimedia\Leximorph\Provider\TextDirection;

/**
 * TextDirectionTest
 *
 * This test class verifies the functionality of the {@see TextDirection} class.
 *
 * Covered tests include:
 *   - Verifying the correct identification of left-to-right text.
 *   - Verifying the correct identification of right-to-left text.
 *   - Handling cases with BIDI control characters.
 *   - Returning null when no strong directional character is found.
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 *
 * @covers \Wikimedia\Leximorph\Provider\TextDirection
 */
class TextDirectionTest extends TestCase {

	/**
	 * Data provider for text direction test cases.
	 *
	 * Each test case provides:
	 *  - A text string.
	 *  - The expected text direction ('ltr', 'rtl', or null).
	 *
	 * @return Generator<string, array{0: string, 1: ?string}>
	 */
	public static function provideDirectionCases(): Generator {
		yield 'Empty string' => [ '', null ];
		yield 'English text' => [ 'Hello, world!', 'ltr' ];
		yield 'Arabic text' => [ 'مرحبا بالعالم', 'rtl' ];
		yield 'Hebrew text' => [ 'שלום עולם', 'rtl' ];
		// Test with a leading BIDI control code: LEFT-TO-RIGHT EMBEDDING (LRE) U+202A.
		yield 'LRE followed by English' => [ "\u{202A}Hello", 'ltr' ];
		// Test with a leading BIDI control code: RIGHT-TO-LEFT EMBEDDING (RLE) U+202B.
		yield 'RLE followed by Arabic' => [ "\u{202B}مرحبا", 'rtl' ];
		// Test with neutral characters (digits)
		yield 'Neutral digits' => [ '12345', null ];
		// Test with punctuation only
		yield 'Punctuation only' => [ '!!!', null ];
	}

	/**
	 * @dataProvider provideDirectionCases
	 *
	 * Tests that getDirection returns the correct text direction for a given input.
	 *
	 * @param string $text The input text.
	 * @param ?string $expected The expected text direction.
	 *
	 * @since 1.45
	 */
	public function testGetDirection( string $text, ?string $expected ): void {
		$textDirection = new TextDirection();
		$this->assertSame( $expected, $textDirection->getDirection( $text ) );
	}
}
