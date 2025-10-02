<?php
/**
 * @license GPL-2.0-or-later
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
