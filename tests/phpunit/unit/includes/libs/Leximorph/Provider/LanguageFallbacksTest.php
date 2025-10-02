<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Tests\Leximorph\Provider;

use Generator;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Wikimedia\Leximorph\Provider\LanguageFallbacks;
use Wikimedia\Leximorph\Util\JsonLoader;

/**
 * This test class verifies the functionality of the {@see LanguageFallbacks} class.
 *
 * Covered tests include:
 *   - Ensuring that known language codes return the expected fallback codes.
 *   - Confirming that unknown language codes return an empty array.
 *
 * @covers \Wikimedia\Leximorph\Provider\LanguageFallbacks
 * @author Doğu Abaris (abaris@null.net)
 */
class LanguageFallbacksTest extends TestCase {

	/**
	 * Data provider for fallback cases.
	 *
	 * Each test case provides:
	 *  - A language code.
	 *  - The expected fallback language codes.
	 *
	 * @return Generator<array{string, array<string>}>
	 */
	public static function provideFallbackCases(): Generator {
		yield 'Gagauz (gag)' => [ 'gag', [ 'tr' ], ];
		yield 'Adyghe (ady)' => [ 'ady', [ 'ady-cyrl' ], ];
		yield 'Kara-Kalpak (kaa)' => [ 'kaa', [ 'kk-latn', 'kk-cyrl', ], ];
		yield 'Lombard (lmo)' => [ 'lmo', [ 'pms', 'eml', 'lij', 'vec', 'it', ], ];
		yield 'Unknown language' => [ 'xxx-lang', [], ];
	}

	/**
	 * @dataProvider provideFallbackCases
	 *
	 * Tests that getFallbacks returns the correct fallback languages for a given language code.
	 *
	 * @param string $langCode The language code.
	 * @param array<string> $expected The expected fallback language codes.
	 */
	public function testGetFallbacks( string $langCode, array $expected ): void {
		$fallbackProvider = new LanguageFallbacks( $langCode, new NullLogger(), new JsonLoader( new NullLogger() ) );
		$this->assertSame( $expected, $fallbackProvider->getFallbacks() );
	}
}
