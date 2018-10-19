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
 * @ingroup Language
 */

/**
 * Methods for dealing with language codes.
 * @todo Move some of the code-related static methods out of Language into this class
 *
 * @since 1.29
 * @ingroup Language
 */
class LanguageCode {
	/**
	 * Mapping of deprecated language codes that were used in previous
	 * versions of MediaWiki to up-to-date, current language codes.
	 * These may or may not be valid BCP 47 codes; they are included here
	 * because MediaWiki renamed these particular codes at some point.
	 *
	 * @var array Mapping from deprecated MediaWiki-internal language code
	 *   to replacement MediaWiki-internal language code.
	 *
	 * @since 1.30
	 * @see https://meta.wikimedia.org/wiki/Special_language_codes
	 */
	private static $deprecatedLanguageCodeMapping = [
		// Note that als is actually a valid ISO 639 code (Tosk Albanian), but it
		// was previously used in MediaWiki for Alsatian, which comes under gsw
		'als' => 'gsw', // T25215
		'bat-smg' => 'sgs', // T27522
		'be-x-old' => 'be-tarask', // T11823
		'fiu-vro' => 'vro', // T31186
		'roa-rup' => 'rup', // T17988
		'zh-classical' => 'lzh', // T30443
		'zh-min-nan' => 'nan', // T30442
		'zh-yue' => 'yue', // T30441
	];

	/**
	 * Mapping of non-standard language codes used in MediaWiki to
	 * standardized BCP 47 codes.  These are not deprecated (yet?):
	 * IANA may eventually recognize the subtag, in which case the `-x-`
	 * infix could be removed, or else we could rename the code in
	 * MediaWiki, in which case they'd move up to the above mapping
	 * of deprecated codes.
	 *
	 * As a rule, we preserve all distinctions made by MediaWiki
	 * internally.  For example, `de-formal` becomes `de-x-formal`
	 * instead of just `de` because MediaWiki distinguishes `de-formal`
	 * from `de` (for example, for interface translations).  Similarly,
	 * BCP 47 indicates that `kk-Cyrl` SHOULD not be used because it
	 * "typically does not add information", but in our case MediaWiki
	 * LanguageConverter distinguishes `kk` (render content in a mix of
	 * Kurdish variants) from `kk-Cyrl` (convert content to be uniformly
	 * Cyrillic).  As the BCP 47 requirement is a SHOULD not a MUST,
	 * `kk-Cyrl` is a valid code, although some validators may emit
	 * a warning note.
	 *
	 * @var array Mapping from nonstandard MediaWiki-internal codes to
	 *   BCP 47 codes
	 *
	 * @since 1.32
	 * @see https://meta.wikimedia.org/wiki/Special_language_codes
	 * @see https://phabricator.wikimedia.org/T125073
	 */
	private static $nonstandardLanguageCodeMapping = [
		// All codes returned by Language::fetchLanguageNames() validated
		// against IANA registry at
		//   https://www.iana.org/assignments/language-subtag-registry/language-subtag-registry
		// with help of validator at
		//   http://schneegans.de/lv/
		'cbk-zam' => 'cbk', // T124657
		'de-formal' => 'de-x-formal',
		'eml' => 'egl', // T36217
		'en-rtl' => 'en-x-rtl',
		'es-formal' => 'es-x-formal',
		'hu-formal' => 'hu-x-formal',
		'map-bms' => 'jv-x-bms', // [[en:Banyumasan_dialect]] T125073
		'mo' => 'ro-Cyrl-MD', // T125073
		'nrm' => 'nrf', // [[en:Norman_language]] T25216
		'nl-informal' => 'nl-x-informal',
		'roa-tara' => 'nap-x-tara', // [[en:Tarantino_dialect]]
		'simple' => 'en-simple',
		'sr-ec' => 'sr-Cyrl', // T117845
		'sr-el' => 'sr-Latn', // T117845

		// Although these next codes aren't *wrong* per se, including
		// both the script and the country code helps compatibility with
		// other BCP 47 users. Note that MW also uses `zh-Hans`/`zh-Hant`,
		// without a country code, and those should be left alone.
		// (See $variantfallbacks in LanguageZh.php for Hans/Hant id.)
		'zh-cn' => 'zh-Hans-CN',
		'zh-sg' => 'zh-Hans-SG',
		'zh-my' => 'zh-Hans-MY',
		'zh-tw' => 'zh-Hant-TW',
		'zh-hk' => 'zh-Hant-HK',
		'zh-mo' => 'zh-Hant-MO',
	];

	/**
	 * Returns a mapping of deprecated language codes that were used in previous
	 * versions of MediaWiki to up-to-date, current language codes.
	 *
	 * This array is merged into $wgDummyLanguageCodes in Setup.php, along with
	 * the fake language codes 'qqq' and 'qqx', which are used internally by
	 * MediaWiki's localisation system.
	 *
	 * @return string[]
	 *
	 * @since 1.29
	 */
	public static function getDeprecatedCodeMapping() {
		return self::$deprecatedLanguageCodeMapping;
	}

	/**
	 * Returns a mapping of non-standard language codes used by
	 * (current and previous version of) MediaWiki, mapped to standard
	 * BCP 47 names.
	 *
	 * This array is exported to JavaScript to ensure
	 * mediawiki.language.bcp47 stays in sync with LanguageCode::bcp47().
	 *
	 * @return string[]
	 *
	 * @since 1.32
	 */
	public static function getNonstandardLanguageCodeMapping() {
		$result = [];
		foreach ( self::$deprecatedLanguageCodeMapping as $code => $ignore ) {
			$result[$code] = self::bcp47( $code );
		}
		foreach ( self::$nonstandardLanguageCodeMapping as $code => $ignore ) {
			$result[$code] = self::bcp47( $code );
		}
		return $result;
	}

	/**
	 * Replace deprecated language codes that were used in previous
	 * versions of MediaWiki to up-to-date, current language codes.
	 * Other values will returned unchanged.
	 *
	 * @param string $code Old language code
	 * @return string New language code
	 *
	 * @since 1.30
	 */
	public static function replaceDeprecatedCodes( $code ) {
		if ( isset( self::$deprecatedLanguageCodeMapping[$code] ) ) {
			return self::$deprecatedLanguageCodeMapping[$code];
		}
		return $code;
	}

	/**
	 * Get the normalised IETF language tag
	 * See unit test for examples.
	 * See mediawiki.language.bcp47 for the JavaScript implementation.
	 *
	 * @param string $code The language code.
	 * @return string A language code complying with BCP 47 standards.
	 *
	 * @since 1.31
	 */
	public static function bcp47( $code ) {
		$code = self::replaceDeprecatedCodes( strtolower( $code ) );
		if ( isset( self::$nonstandardLanguageCodeMapping[$code] ) ) {
			$code = self::$nonstandardLanguageCodeMapping[$code];
		}
		$codeSegment = explode( '-', $code );
		$codeBCP = [];
		foreach ( $codeSegment as $segNo => $seg ) {
			// when previous segment is x, it is a private segment and should be lc
			if ( $segNo > 0 && strtolower( $codeSegment[( $segNo - 1 )] ) == 'x' ) {
				$codeBCP[$segNo] = strtolower( $seg );
			// ISO 3166 country code
			} elseif ( ( strlen( $seg ) == 2 ) && ( $segNo > 0 ) ) {
				$codeBCP[$segNo] = strtoupper( $seg );
			// ISO 15924 script code
			} elseif ( ( strlen( $seg ) == 4 ) && ( $segNo > 0 ) ) {
				$codeBCP[$segNo] = ucfirst( strtolower( $seg ) );
			// Use lowercase for other cases
			} else {
				$codeBCP[$segNo] = strtolower( $seg );
			}
		}
		$langCode = implode( '-', $codeBCP );
		return $langCode;
	}
}
