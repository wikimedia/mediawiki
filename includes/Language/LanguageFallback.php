<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Language;

use LocalisationCache;

/**
 * @since 1.35
 * @ingroup Language
 */
class LanguageFallback {
	/**
	 * Return a fallback chain for messages in getAll
	 * @since 1.35
	 * @deprecated since 1.46; use LanguageFallbackMode::MESSAGES
	 */
	public const MESSAGES = LanguageFallbackMode::MESSAGES;

	/**
	 * Return a strict fallback chain in getAll
	 * @since 1.35
	 * @deprecated since 1.46; use LanguageFallbackMode::STRICT
	 */
	public const STRICT = LanguageFallbackMode::STRICT;

	/** @var string */
	private $siteLangCode;

	/** @var LocalisationCache */
	private $localisationCache;

	/** @var LanguageNameUtils */
	private $langNameUtils;

	/** @var array */
	private $fallbackCache = [];

	/**
	 * Do not call this directly. Use MediaWikiServices.
	 *
	 * @since 1.35
	 * @param string $siteLangCode Language code of the site, typically $wgLanguageCode
	 * @param LocalisationCache $localisationCache
	 * @param LanguageNameUtils $langNameUtils
	 */
	public function __construct(
		$siteLangCode,
		LocalisationCache $localisationCache,
		LanguageNameUtils $langNameUtils
	) {
		$this->siteLangCode = $siteLangCode;
		$this->localisationCache = $localisationCache;
		$this->langNameUtils = $langNameUtils;
	}

	/**
	 * Get the first fallback for a given language.
	 *
	 * @since 1.35
	 * @param string $code
	 * @return string|null
	 */
	public function getFirst( $code ) {
		return $this->getAll( $code )[0] ?? null;
	}

	/**
	 * Get the ordered list of fallback languages.
	 *
	 * @since 1.35
	 * @param string $code Language code
	 * @param int|LanguageFallbackMode $mode Fallback mode, either MESSAGES (which always falls back to 'en'), or STRICT
	 *   (which only falls back to 'en' when explicitly defined)
	 * @return string[] List of language codes
	 * @note Using an `int` for $mode was deprecated in MW 1.46
	 */
	public function getAll( $code, $mode = LanguageFallbackMode::MESSAGES ) {
		// XXX The LanguageNameUtils dependency is just because of this line, is it needed?
		// Especially because isValidBuiltInCode() is just a one-line regex anyway, maybe it should
		// actually be static?
		if ( $code === 'en' || !$this->langNameUtils->isValidBuiltInCode( $code ) ) {
			return [];
		}
		if ( is_int( $mode ) ) {
			$mode = LanguageFallbackMode::from( $mode );
		}
		return match ( $mode ) {
			LanguageFallbackMode::MESSAGES =>
				// For unknown languages, fallbackSequence returns an empty array. Hardcode fallback
				// to 'en' in that case, as English messages are always defined.
				$this->localisationCache->getItem( $code, 'fallbackSequence' ) ?: [ 'en' ],

			LanguageFallbackMode::STRICT =>
				// Use this mode when you don't want to fall back to English unless explicitly
				// defined, for example, when you have language-variant icons and an international
				// language-independent fallback.
				$this->localisationCache->getItem( $code, 'originalFallbackSequence' ),
		};
	}

	/**
	 * Get the ordered list of fallback languages, ending with the fallback language chain for the
	 * site language. The site fallback list begins with the site language itself.
	 *
	 * @since 1.35
	 * @param string $code Language code
	 * @return string[][] [ fallbacks, site fallbacks ]
	 */
	public function getAllIncludingSiteLanguage( $code ) {
		// Usually, we will only store a tiny number of fallback chains, so we cache in a member.
		$cacheKey = "{$code}-{$this->siteLangCode}";

		if ( !array_key_exists( $cacheKey, $this->fallbackCache ) ) {
			$fallbacks = $this->getAll( $code );

			if ( $code === $this->siteLangCode ) {
				// Don't bother hitting the localisation cache a second time
				$siteFallbacks = [ $code ];
			} else {
				// Append the site's fallback chain, including the site language itself
				$siteFallbacks = $this->getAll( $this->siteLangCode );
				array_unshift( $siteFallbacks, $this->siteLangCode );

				// Eliminate any languages already included in the chain
				$siteFallbacks = array_diff( $siteFallbacks, $fallbacks );
			}

			$this->fallbackCache[$cacheKey] = [ $fallbacks, $siteFallbacks ];
		}
		return $this->fallbackCache[$cacheKey];
	}
}

/** @deprecated class alias since 1.45 */
class_alias( LanguageFallback::class, 'MediaWiki\\Languages\\LanguageFallback' );
