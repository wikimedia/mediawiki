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
use Psr\Log\NullLogger;
use Wikimedia\Leximorph\Provider\LanguageFallbacks;
use Wikimedia\Leximorph\Util\JsonLoader;

/**
 * LanguageFallbacksTest
 *
 * This test class verifies the functionality of the {@see LanguageFallbacks} class.
 *
 * Covered tests include:
 *   - Ensuring that known language codes return the expected fallback codes.
 *   - Confirming that unknown language codes return an empty array.
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 *
 * @covers \Wikimedia\Leximorph\Provider\LanguageFallbacks
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
	 *
	 * @since 1.45
	 */
	public function testGetFallbacks( string $langCode, array $expected ): void {
		$fallbackProvider = new LanguageFallbacks( $langCode, new NullLogger(), new JsonLoader( new NullLogger() ) );
		$this->assertSame( $expected, $fallbackProvider->getFallbacks() );
	}
}
