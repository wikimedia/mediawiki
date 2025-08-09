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

namespace Wikimedia\Leximorph;

use CLDRPluralRuleParser\Evaluator;
use Psr\Log\LoggerInterface;
use Wikimedia\Leximorph\Provider\FormalityIndex;
use Wikimedia\Leximorph\Provider\GrammarTransformations;
use Wikimedia\Leximorph\Provider\LanguageFallbacks;
use Wikimedia\Leximorph\Provider\PluralRules;
use Wikimedia\Leximorph\Provider\TextDirection;
use Wikimedia\Leximorph\Traits\SpecBasedFactoryTrait;
use Wikimedia\Leximorph\Util\JsonLoader;
use Wikimedia\Leximorph\Util\XmlLoader;

/**
 * Provider
 *
 * This class is responsible for instantiating Leximorph provider objects
 * (Index, GrammarTransformations, LanguageFallbacks, and PluralRules)
 * using Wikimedia’s ObjectFactory. The Provider holds a default language code
 * that is injected into language-specific providers.
 *
 * Usage Example:
 * <code>
 *            $provider = new Provider( 'en', $logger );
 *            $plural = $provider->getPluralProvider();
 * </code>
 *
 * @newable
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 */
final class Provider {

	use SpecBasedFactoryTrait;

	/**
	 * Provider specifications.
	 *
	 * Each entry defines:
	 * - args: Default constructor arguments.
	 * - langDependent: Prepend the language code.
	 * - needsEvaluator: Inject a CLDR Evaluator instance.
	 * - needsLogger: Pass a logger instance.
	 * - needsJsonLoader: Inject a JsonLoader instance.
	 * - needsXmlLoader: Inject an XmlLoader instance.
	 * - needsSelf: Inject this Provider instance.
	 *
	 * @var array<class-string, array<string, mixed>>
	 */
	private const PROVIDER_SPECS = [
		TextDirection::class => [
			'args' => [],
		],
		FormalityIndex::class => [
			'args' => [],
			'langDependent' => true,
			'needsLogger' => true,
			'needsJsonLoader' => true,
		],
		GrammarTransformations::class => [
			'args' => [],
			'langDependent' => true,
			'needsJsonLoader' => true,
		],
		LanguageFallbacks::class => [
			'args' => [],
			'langDependent' => true,
			'needsLogger' => true,
			'needsJsonLoader' => true,
		],
		PluralRules::class => [
			'args' => [],
			'langDependent' => true,
			'needsEvaluator' => true,
			'needsSelf' => true,
			'needsLogger' => true,
			'needsXmlLoader' => true,
		],
	];

	/**
	 * Initializes a new Provider instance with a default language code and a logger.
	 *
	 * @param string $langCode The default language code to use.
	 * @param ?LoggerInterface $logger Optional logger; defaults to a NullLogger.
	 *
	 * @since 1.45
	 */
	public function __construct( string $langCode, ?LoggerInterface $logger = null ) {
		$this->langCode = $langCode;
		$this->logger = $logger;
	}

	/**
	 * Get the current language code.
	 *
	 * @return string
	 * @since 1.45
	 */
	public function getLanguageCode(): string {
		return $this->langCode;
	}

	/**
	 * Get the handler spec map.
	 *
	 * Returns an array of handler specs indexed by class name.
	 *
	 * @since 1.45
	 * @return array<class-string, array<string,mixed>>
	 */
	protected function getSpecMap(): array {
		return self::PROVIDER_SPECS;
	}

	/**
	 * Builds the constructor arguments.
	 *
	 * @param array<string,mixed> $spec
	 *
	 * @since 1.45
	 * @return array<int,mixed>
	 */
	protected function getSpecArgs( array $spec, LoggerInterface $logger ): array {
		$args = [];
		if ( $spec['langDependent'] ?? false ) {
			$args[] = $this->langCode;
		}
		if ( $spec['needsEvaluator'] ?? false ) {
			$args[] = new Evaluator();
		}
		if ( $spec['needsSelf'] ?? false ) {
			$args[] = $this;
		}
		if ( $spec['needsLogger'] ?? false ) {
			$args[] = $logger;
		}
		if ( $spec['needsJsonLoader'] ?? false ) {
			$args[] = new JsonLoader( $logger );
		}
		if ( $spec['needsXmlLoader'] ?? false ) {
			$args[] = new XmlLoader( $logger );
		}

		return $args;
	}

	/**
	 * Get the TextDirection provider.
	 *
	 * @since 1.45
	 */
	public function getBidiProvider(): TextDirection {
		return $this->createFromSpec( TextDirection::class );
	}

	/**
	 * Get the Index provider.
	 *
	 * @since 1.45
	 */
	public function getFormalityIndexProvider(): FormalityIndex {
		return $this->createFromSpec( FormalityIndex::class );
	}

	/**
	 * Get the GrammarTransformations provider.
	 *
	 * @since 1.45
	 * @return GrammarTransformations
	 */
	public function getGrammarTransformationsProvider(): GrammarTransformations {
		return $this->createFromSpec( GrammarTransformations::class );
	}

	/**
	 * Get the LanguageFallbacks provider.
	 *
	 * @since 1.45
	 * @return LanguageFallbacks
	 */
	public function getLanguageFallbacksProvider(): LanguageFallbacks {
		return $this->createFromSpec( LanguageFallbacks::class );
	}

	/**
	 * Get the PluralRules provider.
	 *
	 * @since 1.45
	 * @return PluralRules
	 */
	public function getPluralProvider(): PluralRules {
		return $this->createFromSpec( PluralRules::class );
	}
}
