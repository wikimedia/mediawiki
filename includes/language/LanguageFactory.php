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

/**
 * @defgroup Language Language
 */

namespace MediaWiki\Languages;

use Language;
use LanguageConverter;
use LocalisationCache;
use MapCacheLRU;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MWException;

/**
 * Internationalisation code
 * See https://www.mediawiki.org/wiki/Special:MyLanguage/Localisation for more information.
 *
 * @ingroup Language
 * @since 1.35
 */
class LanguageFactory {
	/** @var ServiceOptions */
	private $options;

	/** @var LocalisationCache */
	private $localisationCache;

	/** @var LanguageNameUtils */
	private $langNameUtils;

	/** @var LanguageFallback */
	private $langFallback;

	/** @var LanguageConverterFactory */
	private $langConverterFactory;

	/** @var HookContainer */
	private $hookContainer;

	/** @var MapCacheLRU */
	private $langObjCache;

	/** @var array */
	private $parentLangCache = [];

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		'DummyLanguageCodes',
	];

	/** How many distinct Language objects to retain at most in memory (T40439). */
	private const LANG_CACHE_SIZE = 10;

	/**
	 * @param ServiceOptions $options
	 * @param LocalisationCache $localisationCache
	 * @param LanguageNameUtils $langNameUtils
	 * @param LanguageFallback $langFallback
	 * @param LanguageConverterFactory $langConverterFactory
	 * @param HookContainer $hookContainer
	 */
	public function __construct(
		ServiceOptions $options,
		LocalisationCache $localisationCache,
		LanguageNameUtils $langNameUtils,
		LanguageFallback $langFallback,
		LanguageConverterFactory $langConverterFactory,
		HookContainer $hookContainer
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->options = $options;
		$this->localisationCache = $localisationCache;
		$this->langNameUtils = $langNameUtils;
		$this->langFallback = $langFallback;
		$this->langConverterFactory = $langConverterFactory;
		$this->hookContainer = $hookContainer;
		$this->langObjCache = new MapCacheLRU( self::LANG_CACHE_SIZE );
	}

	/**
	 * Get a cached or new language object for a given language code
	 * @param string $code
	 * @throws MWException if the language code contains dangerous characters, e.g. HTML special
	 *  characters or characters illegal in MediaWiki titles.
	 * @return Language
	 */
	public function getLanguage( $code ): Language {
		$code = $this->options->get( 'DummyLanguageCodes' )[$code] ?? $code;
		return $this->langObjCache->getWithSetCallback(
			$code,
			function () use ( $code ) {
				return $this->newFromCode( $code );
			}
		);
	}

	/**
	 * Create a language object for a given language code
	 * @param string $code
	 * @param bool $fallback Whether we're going through language fallback chain
	 * @throws MWException if the language code or fallback sequence is invalid
	 * @return Language
	 */
	private function newFromCode( $code, $fallback = false ): Language {
		if ( !$this->langNameUtils->isValidCode( $code ) ) {
			throw new MWException( "Invalid language code \"$code\"" );
		}

		$constructorArgs = [
			$code,
			$this->localisationCache,
			$this->langNameUtils,
			$this->langFallback,
			$this->langConverterFactory,
			$this->hookContainer
		];

		if ( !$this->langNameUtils->isValidBuiltInCode( $code ) ) {
			// It's not possible to customise this code with class files, so
			// just return a Language object. This is to support uselang= hacks.
			return new Language( ...$constructorArgs );
		}

		// Check if there is a language class for the code
		$class = $this->classFromCode( $code, $fallback );
		// LanguageCode does not inherit Language
		if ( class_exists( $class ) && is_a( $class, 'Language', true ) ) {
			return new $class( ...$constructorArgs );
		}

		// Keep trying the fallback list until we find an existing class
		$fallbacks = $this->langFallback->getAll( $code );
		foreach ( $fallbacks as $fallbackCode ) {
			$class = $this->classFromCode( $fallbackCode );
			if ( class_exists( $class ) ) {
				// TODO allow additional dependencies to be injected for subclasses somehow
				return new $class( ...$constructorArgs );
			}
		}

		throw new MWException( "Invalid fallback sequence for language '$code'" );
	}

	/**
	 * @param string $code
	 * @param bool $fallback Whether we're going through language fallback chain
	 * @return string Name of the language class
	 */
	private function classFromCode( $code, $fallback = true ) {
		if ( $fallback && $code == 'en' ) {
			return 'Language';
		} else {
			return 'Language' . str_replace( '-', '_', ucfirst( $code ) );
		}
	}

	/**
	 * Get the "parent" language which has a converter to convert a "compatible" language
	 * (in another variant) to this language (eg. zh for zh-cn, but not en for en-gb).
	 *
	 * @param string $code
	 * @return Language|null
	 * @since 1.22
	 */
	public function getParentLanguage( $code ) {
		// We deliberately use array_key_exists() instead of isset() because we cache null.
		if ( !array_key_exists( $code, $this->parentLangCache ) ) {
			if ( !$this->langNameUtils->isValidBuiltInCode( $code ) ) {
				$this->parentLangCache[$code] = null;
				return null;
			}
			foreach ( LanguageConverter::$languagesWithVariants as $mainCode ) {
				$lang = $this->getLanguage( $mainCode );
				$converter = $this->langConverterFactory->getLanguageConverter( $lang );
				if ( $converter->hasVariant( $code ) ) {
					$this->parentLangCache[$code] = $lang;
					return $lang;
				}
			}
			$this->parentLangCache[$code] = null;
		}

		return $this->parentLangCache[$code];
	}
}
