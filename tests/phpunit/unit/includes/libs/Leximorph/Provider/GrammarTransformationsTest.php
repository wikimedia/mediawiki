<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Tests\Leximorph\Provider;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Wikimedia\Leximorph\Provider as LeximorphProvider;

/**
 * This test class verifies the functionality of the {@see GrammarTransformations} class.
 *
 * Covered tests include:
 *   - Injecting a language code and verifying that the grammar transformations match.
 *
 * @covers \Wikimedia\Leximorph\Provider\GrammarTransformations
 * @author Doğu Abaris (abaris@null.net)
 */
class GrammarTransformationsTest extends TestCase {

	/**
	 * Tests that grammar transformations for a given language code are identical
	 * whether accessed via the Provider or via the language object's method.
	 */
	public function testGrammarTransformationsAreIdentical(): void {
		$provider = new LeximorphProvider( 'he', new NullLogger() );
		$expected = $provider->getGrammarTransformationsProvider()->getTransformations();

		$lang = new class( $provider ) {
			private LeximorphProvider $provider;

			public function __construct( LeximorphProvider $provider ) {
				$this->provider = $provider;
			}

			/**
			 * Returns the grammar transformations.
			 *
			 * @return array<int|string, mixed>
			 */
			public function getGrammarTransformations(): array {
				return $this->provider->getGrammarTransformationsProvider()->getTransformations();
			}
		};

		$this->assertSame(
			$expected,
			$lang->getGrammarTransformations(),
			"Grammar transformations for 'he' do not match."
		);
	}
}
