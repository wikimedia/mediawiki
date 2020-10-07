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
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\MediaWikiServices;
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

	/** @var array */
	private $langObjCache = [];

	/** @var array */
	private $parentLangCache = [];

	/**
	 * @since 1.35
	 * @var array
	 */
	public const CONSTRUCTOR_OPTIONS = [
		'DummyLanguageCodes',
		'LangObjCacheSize',
	];

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
	}

	/**
	 * Get a cached or new language object for a given language code
	 * @param string $code
	 * @throws MWException
	 * @return Language
	 */
	public function getLanguage( $code ) : Language {
		$code = $this->options->get( 'DummyLanguageCodes' )[$code] ?? $code;

		// This is horrible, horrible code, but is necessary to support Language::$mLangObjCache
		// per the deprecation policy. Kill with fire in 1.36!
		if (
			MediaWikiServices::hasInstance() &&
			$this === MediaWikiServices::getInstance()->getLanguageFactory()
		) {
			$this->langObjCache = Language::$mLangObjCache;
		}

		// Get the language object to process
		$langObj = $this->langObjCache[$code] ?? $this->newFromCode( $code );

		// Merge the language object in to get it up front in the cache
		$this->langObjCache = array_merge( [ $code => $langObj ], $this->langObjCache );
		// Get rid of the oldest ones in case we have an overflow
		$this->langObjCache =
			array_slice( $this->langObjCache, 0, $this->options->get( 'LangObjCacheSize' ), true );

		// As above, remove this in 1.36
		if (
			MediaWikiServices::hasInstance() &&
			$this === MediaWikiServices::getInstance()->getLanguageFactory()
		) {
			Language::$mLangObjCache = $this->langObjCache;
		}

		return $langObj;
	}

	/**
	 * Create a language object for a given language code
	 * @param string $code
	 * @param bool $fallback Whether we're going through language fallback chain
	 * @throws MWException
	 * @return Language
	 */
	private function newFromCode( $code, $fallback = false ) : Language {
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
			$codeBase = explode( '-', $code )[0];
			if ( !in_array( $codeBase, LanguageConverter::$languagesWithVariants ) ) {
				$this->parentLangCache[$code] = null;
				return null;
			}

			$lang = $this->getLanguage( $codeBase );
			$converter = $this->langConverterFactory->getLanguageConverter( $lang );
			if ( !$converter->hasVariant( $code ) ) {
				$this->parentLangCache[$code] = null;
				return null;
			}

			$this->parentLangCache[$code] = $lang;
		}

		return $this->parentLangCache[$code];
	}
}
