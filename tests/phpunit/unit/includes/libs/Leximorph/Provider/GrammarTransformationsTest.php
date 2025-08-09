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

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Wikimedia\Leximorph\Provider as LeximorphProvider;

/**
 * GrammarTransformationsTest
 *
 * This test class verifies the functionality of the {@see GrammarTransformations} class.
 *
 * Covered tests include:
 *   - Injecting a language code and verifying that the grammar transformations match.
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 *
 * @covers \Wikimedia\Leximorph\Provider\GrammarTransformations
 */
class GrammarTransformationsTest extends TestCase {

	/**
	 * Tests that grammar transformations for a given language code are identical
	 * whether accessed via the Provider or via the language object's method.
	 *
	 * @since 1.45
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
