<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Tests\Leximorph\Provider;

use Generator;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Wikimedia\Leximorph\Provider\FormalityIndex;
use Wikimedia\Leximorph\Util\JsonLoader;

/**
 * This test class verifies the functionality of the {@see FormalityIndex} class.
 *
 * Covered tests include:
 *   - Direct lookup of language codes from the JSON file.
 *   - Fallback behavior when the language code has a '-formal' or '-informal' suffix.
 *   - Defaulting to 0 when no mapping exists.
 *
 * @covers \Wikimedia\Leximorph\Provider\FormalityIndex
 * @author Doğu Abaris (abaris@null.net)
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
	 */
	public function testGetFormalityIndex( string $langCode, int $expected ): void {
		$jsonLoaderStub = $this->createStub( JsonLoader::class );
		$jsonLoaderStub->method( 'load' )->willReturn( [ 'en' => 0, 'nl' => 1, ] );
		$formalityIndex = new FormalityIndex( $langCode, new NullLogger(), $jsonLoaderStub );
		$this->assertSame( $expected, $formalityIndex->getFormalityIndex() );
	}
}
