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

use Psr\Log\LoggerInterface;
use Wikimedia\Leximorph\Handler\Bidi;
use Wikimedia\Leximorph\Handler\Formal;
use Wikimedia\Leximorph\Handler\Gender;
use Wikimedia\Leximorph\Handler\Grammar;
use Wikimedia\Leximorph\Handler\Overrides\GrammarFallbackRegistry;
use Wikimedia\Leximorph\Handler\Plural;
use Wikimedia\Leximorph\Traits\SpecBasedFactoryTrait;
use Wikimedia\Leximorph\Util\JsonLoader;

/**
 * Manager
 *
 * This class is responsible for instantiating Leximorph handler objects
 * (Grammar, Formal, Gender, Bidi, and Plural) using Wikimedia’s ObjectFactory.
 * The Manager holds a default language code that is injected into
 * language-specific handlers.
 *
 * Note that Gender and Bidi handlers are language independent.
 *
 * Usage Example:
 * <code>
 *            $manager = new Manager( 'en', new NullLogger() );
 *            $pluralHandler = $manager->getPlural();
 *            echo $pluralHandler->process( 3, [ 'article', 'articles' ] );
 * </code>
 *
 * @newable
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 */
final class Manager {

	use SpecBasedFactoryTrait;

	/**
	 * Handler specifications.
	 *
	 * Each entry defines:
	 * - args: Default constructor arguments.
	 * - langDependent: Prepend the language code.
	 * - needsLogger: Pass a logger.
	 * - needsProvider: Use a provider instance.
	 * - needsPostProcessor: Pass a GrammarFallbackRegistry instance.
	 *
	 * @var array<class-string, array<string, mixed>>
	 */
	private const HANDLER_SPECS = [
		Bidi::class => [
			'args' => [],
			'needsProvider' => true,
		],
		Formal::class => [
			'args' => [],
			'langDependent' => true,
			'needsLogger' => true,
			'needsProvider' => true,
		],
		Gender::class => [
			'args' => [],
		],
		Grammar::class => [
			'args' => [],
			'needsLogger' => true,
			'needsProvider' => true,
			'needsPostProcessor' => true,
		],
		Plural::class => [
			'args' => [],
			'langDependent' => false,
			'needsProvider' => true,
		],
	];

	/**
	 * Initializes a new Manager instance with a default language code.
	 *
	 * @param string $langCode The default language code.
	 * @param ?LoggerInterface $logger Optional logger; defaults to NullLogger.
	 *
	 * @since 1.45
	 */
	public function __construct( string $langCode, ?LoggerInterface $logger = null ) {
		$this->langCode = $langCode;
		$this->logger = $logger;
	}

	/**
	 * Get the handler spec map.
	 *
	 * Returns an array of handler specs indexed by class name.
	 *
	 * @since 1.45
	 * @return array<class-string, array<string, mixed>>
	 */
	protected function getSpecMap(): array {
		return self::HANDLER_SPECS;
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
		if ( $spec['needsProvider'] ?? false ) {
			$args[] = new Provider( $this->langCode, $logger );
		}
		if ( $spec['needsPostProcessor'] ?? false ) {
			$args[] = new GrammarFallbackRegistry();
		}
		if ( $spec['needsLogger'] ?? false ) {
			$args[] = $logger;
		}
		if ( $spec['langDependent'] ?? false ) {
			$args[] = $this->langCode;
		}
		if ( $spec['needsJsonLoader'] ?? false ) {
			$args[] = new JsonLoader( $logger );
		}

		return $args;
	}

	/**
	 * Get the Bidi handler.
	 *
	 * @since 1.45
	 * @return Bidi
	 */
	public function getBidi(): Bidi {
		return $this->createFromSpec( Bidi::class );
	}

	/**
	 * Get the Formal handler.
	 *
	 * @since 1.45
	 * @return Formal
	 */
	public function getFormal(): Formal {
		return $this->createFromSpec( Formal::class );
	}

	/**
	 * Get the Gender handler.
	 *
	 * @since 1.45
	 * @return Gender
	 */
	public function getGender(): Gender {
		return $this->createFromSpec( Gender::class );
	}

	/**
	 * Get the Grammar handler.
	 *
	 * @since 1.45
	 * @return Grammar
	 */
	public function getGrammar(): Grammar {
		return $this->createFromSpec( Grammar::class );
	}

	/**
	 * Get the Plural handler.
	 *
	 * @since 1.45
	 * @return Plural
	 */
	public function getPlural(): Plural {
		return $this->createFromSpec( Plural::class );
	}
}
