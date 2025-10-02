<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Tests\Leximorph;

use PHPUnit\Framework\TestCase;
use Wikimedia\Leximorph\Handler\Overrides\Grammar\GrammarFi;
use Wikimedia\Leximorph\Handler\Overrides\Grammar\GrammarKk;
use Wikimedia\Leximorph\Handler\Overrides\GrammarFallbackRegistry;

/**
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
 * @covers \Wikimedia\Leximorph\Handler\Overrides\GrammarFallbackRegistry
 * @author Doğu Abaris (abaris@null.net)
 */
class GrammarFallbackRegistryTest extends TestCase {

	/**
	 * Tests that a valid language code returns a proper grammar transformer instance.
	 */
	public function testProvidesGrammarProcessorForKnownLanguage(): void {
		$registry = new GrammarFallbackRegistry();
		$processor = $registry->getProcessor( 'kk' );
		$this->assertInstanceOf( GrammarKk::class, $processor );
	}

	/**
	 * Tests that another supported language returns a grammar transformer instance.
	 */
	public function testProvidesAnotherValidProcessor(): void {
		$registry = new GrammarFallbackRegistry();
		$processor = $registry->getProcessor( 'fi' );
		$this->assertInstanceOf( GrammarFi::class, $processor );
	}

	/**
	 * Tests that an unknown language code returns null.
	 */
	public function testReturnsNullForUnsupportedLanguage(): void {
		$registry = new GrammarFallbackRegistry();
		$this->assertNull( $registry->getProcessor( 'xx' ) );
	}

	/**
	 * Tests that `apply()` delegates to the processor or returns the original word.
	 */
	public function testApplyReturnsTransformedOrOriginal(): void {
		$registry = new GrammarFallbackRegistry();
		$this->assertSame( 'test', $registry->apply( 'xx', 'test', 'genitive' ) );
	}
}
