<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Tests\Leximorph\Handler;

use Generator;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Wikimedia\Leximorph\Handler\Bidi;
use Wikimedia\Leximorph\Provider;

/**
 * This test class verifies the functionality of the {@see Bidi} handler.
 * It tests that the class correctly processes bidirectional text handling
 * by applying directional isolation to mixed LTR and RTL content.
 *
 * Covered tests include:
 *   - Correct wrapping of LTR text with LRE...PDF.
 *   - Correct wrapping of RTL text with RLE...PDF.
 *   - Handling of text with no strong directional characters.
 *
 * @covers \Wikimedia\Leximorph\Handler\Bidi
 * @author Doğu Abaris (abaris@null.net)
 */
class BidiTest extends TestCase {

	/**
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
	 */
	public function testProcess( string $text, string $expected ): void {
		$provider = new Provider( 'en', new NullLogger() );
		$bidiHandler = new Bidi( $provider );
		$result = $bidiHandler->process( $text );
		$this->assertSame( $expected, $result );
	}
}
