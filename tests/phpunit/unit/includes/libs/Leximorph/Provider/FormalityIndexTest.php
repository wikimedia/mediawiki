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
use Wikimedia\Leximorph\Provider\FormalityIndex;
use Wikimedia\Leximorph\Util\JsonLoader;

/**
 * FormalityIndexTest
 *
 * This test class verifies the functionality of the {@see FormalityIndex} class.
 *
 * Covered tests include:
 *   - Direct lookup of language codes from the JSON file.
 *   - Fallback behavior when the language code has a '-formal' or '-informal' suffix.
 *   - Defaulting to 0 when no mapping exists.
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 *
 * @covers \Wikimedia\Leximorph\Provider\FormalityIndex
 */
class FormalityIndexTest extends TestCase {

	/**
	 * Data provider for formality index test cases.
	 *
	 * Each test case provides:
	 *  - A language code.
	 *  - The expected formality index (1 for formal, 0 for informal/default).
	 *
	 * @return Generator<string, array{0: string, 1: int}>
	 */
	public static function provideFormalityIndexCases(): Generator {
		yield 'English direct (informal)' => [ 'en', 0 ];
		yield 'Dutch direct (formal)' => [ 'nl', 1 ];
		yield 'Unknown language' => [ 'fr', 0 ];
		yield 'English with formal suffix' => [ 'en-formal', 1 ];
		yield 'English with informal suffix' => [ 'en-informal', 0 ];
	}

	/**
	 * @dataProvider provideFormalityIndexCases
	 *
	 * Tests that getFormalityIndex returns the correct value for a given language code.
	 *
	 * @param string $langCode The language code.
	 * @param int $expected The expected formality index.
	 *
	 * @since 1.45
	 */
	public function testGetFormalityIndex( string $langCode, int $expected ): void {
		$jsonLoaderStub = $this->createStub( JsonLoader::class );
		$jsonLoaderStub->method( 'load' )->willReturn( [ 'en' => 0, 'nl' => 1, ] );
		$formalityIndex = new FormalityIndex( $langCode, new NullLogger(), $jsonLoaderStub );
		$this->assertSame( $expected, $formalityIndex->getFormalityIndex() );
	}
}
