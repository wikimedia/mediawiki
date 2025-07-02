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

namespace MediaWiki\Language;

use InvalidArgumentException;
use LocalisationCache;
use LogicException;
use MediaWiki\Config\Config;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\MainConfigNames;
use MediaWiki\Title\NamespaceInfo;
use Wikimedia\Bcp47Code\Bcp47Code;
use Wikimedia\MapCacheLRU\MapCacheLRU;

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

	/** @var NamespaceInfo */
	private $namespaceInfo;

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

	/** @var Config */
	private $config;

	/** @var array */
	private $parentLangCache = [];

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::DummyLanguageCodes,
	];

	/** How many distinct Language objects to retain at most in memory (T40439). */
	private const LANG_CACHE_SIZE = 10;

	/**
	 * @param ServiceOptions $options
	 * @param NamespaceInfo $namespaceInfo
	 * @param LocalisationCache $localisationCache
	 * @param LanguageNameUtils $langNameUtils
	 * @param LanguageFallback $langFallback
	 * @param LanguageConverterFactory $langConverterFactory
	 * @param HookContainer $hookContainer
	 * @param Config $config
	 */
	public function __construct(
		ServiceOptions $options,
		NamespaceInfo $namespaceInfo,
		LocalisationCache $localisationCache,
		LanguageNameUtils $langNameUtils,
		LanguageFallback $langFallback,
		LanguageConverterFactory $langConverterFactory,
		HookContainer $hookContainer,
		Config $config
	) {
		// We have both ServiceOptions and a Config object because
		// the Language class hasn't (yet) been updated to use ServiceOptions
		// and for now gets a full Config
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->options = $options;
		$this->namespaceInfo = $namespaceInfo;
		$this->localisationCache = $localisationCache;
		$this->langNameUtils = $langNameUtils;
		$this->langFallback = $langFallback;
		$this->langConverterFactory = $langConverterFactory;
		$this->hookContainer = $hookContainer;
		$this->langObjCache = new MapCacheLRU( self::LANG_CACHE_SIZE );
		$this->config = $config;
	}

	/**
	 * Get a cached or new language object for a given language code
	 * with normalization of the language code.
	 *
	 * If the language code comes from user input, check
	 * LanguageNameUtils::isValidCode() before calling this method.
	 *
	 * The language code is presumed to be a MediaWiki-internal code,
	 * unless you pass a Bcp47Code opaque object, in which case it is
	 * presumed to be a standard BCP-47 code.  (There are, regrettably,
	 * some ambiguous codes where this makes a difference.)
	 *
	 * As the Language class itself implements Bcp47Code, this method is an efficient
	 * and safe downcast if you pass in a Language object.
	 *
	 * @param string|Bcp47Code $code
	 * @return Language
	 */
	public function getLanguage( $code ): Language {
		if ( $code instanceof Language ) {
			return $code;
		}
		if ( $code instanceof Bcp47Code ) {
			// Any compatibility remapping of valid BCP-47 codes would be done
			// inside ::bcp47ToInternal, not here.
			$code = LanguageCode::bcp47ToInternal( $code );
		} else {
			// Perform various deprecated and compatibility mappings of
			// internal codes.
			$code = $this->options->get( MainConfigNames::DummyLanguageCodes )[$code] ?? $code;
		}
		return $this->getRawLanguage( $code );
	}

	public function getLanguageCode( string $code ): LanguageCode {
		$code = $this->options->get( MainConfigNames::DummyLanguageCodes )[$code] ?? $code;
		if ( !$this->langNameUtils->isValidCode( $code ) ) {
			throw new InvalidArgumentException( "Invalid language code \"$code\"" );
		}
		return new LanguageCode( $code );
	}

	/**
	 * Get a cached or new language object for a given language code
	 * without normalization of the language code.
	 *
	 * If the language code comes from user input, check LanguageNameUtils::isValidCode()
	 * before calling this method.
	 *
	 * @param string $code
	 * @return Language
	 * @since 1.39
	 */
	public function getRawLanguage( $code ): Language {
		return $this->langObjCache->getWithSetCallback(
			$code,
			function () use ( $code ) {
				return $this->newFromCode( $code );
			}
		);
	}

	/**
	 * Create a language object for a given language code.
	 *
	 * @param string $code
	 * @param bool $fallback Whether we're going through the language fallback chain
	 * @return Language
	 */
	private function newFromCode( $code, $fallback = false ): Language {
		if ( !$this->langNameUtils->isValidCode( $code ) ) {
			throw new InvalidArgumentException( "Invalid language code \"$code\"" );
		}

		$constructorArgs = [
			$code,
			$this->namespaceInfo,
			$this->localisationCache,
			$this->langNameUtils,
			$this->langFallback,
			$this->langConverterFactory,
			$this->hookContainer,
			$this->config
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

		throw new LogicException( "Invalid fallback sequence for language '$code'" );
	}

	/**
	 * @param string $code
	 * @param bool $fallback Whether we're going through the language fallback chain
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
	 * (in another variant) to this language (eg., zh for zh-cn, but not en for en-gb).
	 *
	 * @note This method does not contain the deprecated and compatibility
	 *  mappings of Language::getLanguage(string).
	 *
	 * @param string|Bcp47Code $code The language to convert to; can be an
	 *  internal MediaWiki language code or a Bcp47Code object (which includes
	 *  Language, which implements Bcp47Code).
	 * @return Language|null A base language which has a converter to the given
	 *  language, or null if none exists.
	 * @since 1.22
	 */
	public function getParentLanguage( $code ) {
		if ( $code instanceof Language ) {
			$code = $code->getCode();
		} elseif ( $code instanceof Bcp47Code ) {
			$code = LanguageCode::bcp47ToInternal( $code );
		}
		// $code is now a mediawiki internal code string.
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

/** @deprecated class alias since 1.45 */
class_alias( LanguageFactory::class, 'MediaWiki\\Languages\\LanguageFactory' );
