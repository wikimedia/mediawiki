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

namespace MediaWiki\Languages;

use InvalidArgumentException;
use LocalisationCache;
use Wikimedia\Assert\Assert;

/**
 * @since 1.35
 * @ingroup Language
 */
class LanguageFallback {
	/**
	 * Return a fallback chain for messages in getAll
	 * @since 1.35
	 */
	public const MESSAGES = 0;

	/**
	 * Return a strict fallback chain in getAll
	 * @since 1.35
	 */
	public const STRICT = 1;

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
	 * @param int $mode Fallback mode, either MESSAGES (which always falls back to 'en'), or STRICT
	 *   (which only falls back to 'en' when explicitly defined)
	 * @throws InvalidArgumentException If $mode is invalid
	 * @return string[] List of language codes
	 */
	public function getAll( $code, $mode = self::MESSAGES ) {
		// XXX The LanguageNameUtils dependency is just because of this line, is it needed?
		// Especially because isValidBuiltInCode() is just a one-line regex anyway, maybe it should
		// actually be static?
		if ( $code === 'en' || !$this->langNameUtils->isValidBuiltInCode( $code ) ) {
			return [];
		}
		switch ( $mode ) {
			case self::MESSAGES:
				// For unknown languages, fallbackSequence returns an empty array. Hardcode fallback
				// to 'en' in that case, as English messages are always defined.
				$ret = $this->localisationCache->getItem( $code, 'fallbackSequence' ) ?: [ 'en' ];
				break;

			case self::STRICT:
				// Use this mode when you don't want to fall back to English unless explicitly
				// defined, for example when you have language-variant icons and an international
				// language-independent fallback.
				$ret = $this->localisationCache->getItem( $code, 'originalFallbackSequence' );
				break;

			default:
				throw new InvalidArgumentException( "Invalid fallback mode \"$mode\"" );
		}

		foreach ( $ret as $fallbackCode ) {
			Assert::postcondition( $this->langNameUtils->isValidBuiltInCode( $fallbackCode ),
				"Invalid fallback code '$fallbackCode' in fallback sequence for '$code'" );
		}

		return $ret;
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
