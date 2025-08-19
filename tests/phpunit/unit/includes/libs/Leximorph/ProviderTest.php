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

namespace Wikimedia\Tests\Leximorph;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Wikimedia\Leximorph\Provider;
use Wikimedia\Leximorph\Provider\FormalityIndex;
use Wikimedia\Leximorph\Provider\GrammarTransformations;
use Wikimedia\Leximorph\Provider\LanguageFallbacks;
use Wikimedia\Leximorph\Provider\PluralRules;
use Wikimedia\Leximorph\Provider\TextDirection;

/**
 * This test class verifies that the {@see Provider} class correctly instantiates
 * and returns the expected provider classes.
 * Each test checks only the type of the returned instance.
 *
 * @covers \Wikimedia\Leximorph\Provider
 * @author Doğu Abaris (abaris@null.net)
 */
class ProviderTest extends TestCase {

	public function testProvidesBidiDirection(): void {
		$provider = new Provider( 'en', new NullLogger() );
		$this->assertInstanceOf( TextDirection::class, $provider->getBidiProvider() );
	}

	public function testProvidesFormalityIndex(): void {
		$provider = new Provider( 'en', new NullLogger() );
		$this->assertInstanceOf( FormalityIndex::class, $provider->getFormalityIndexProvider() );
	}

	public function testProvidesGrammarTransformations(): void {
		$provider = new Provider( 'en', new NullLogger() );
		$this->assertInstanceOf( GrammarTransformations::class, $provider->getGrammarTransformationsProvider() );
	}

	public function testProvidesLanguageFallbacks(): void {
		$provider = new Provider( 'en', new NullLogger() );
		$this->assertInstanceOf( LanguageFallbacks::class, $provider->getLanguageFallbacksProvider() );
	}

	public function testProvidesPluralRules(): void {
		$provider = new Provider( 'en', new NullLogger() );
		$this->assertInstanceOf( PluralRules::class, $provider->getPluralProvider() );
	}
}
