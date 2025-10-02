<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Tests\Leximorph\Provider;

use Generator;
use PHPUnit\Framework\TestCase;
use Wikimedia\Leximorph\Provider\TextDirection;

/**
 * This test class verifies the functionality of the {@see TextDirection} class.
 *
 * Covered tests include:
 *   - Verifying the correct identification of left-to-right text.
 *   - Verifying the correct identification of right-to-left text.
 *   - Handling cases with BIDI control characters.
 *   - Returning null when no strong directional character is found.
 *
 * @covers \Wikimedia\Leximorph\Provider\TextDirection
 * @author Doğu Abaris (abaris@null.net)
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
	 */
	public function testGetDirection( string $text, ?string $expected ): void {
		$textDirection = new TextDirection();
		$this->assertSame( $expected, $textDirection->getDirection( $text ) );
	}
}
