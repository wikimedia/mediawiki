<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Language;

use Wikimedia\Bcp47Code\Bcp47Code;
use Wikimedia\Bcp47Code\Bcp47CodeValue;

/**
 * Methods for dealing with language codes.
 *
 * @since 1.29
 * @ingroup Language
 */
class LanguageCode {

	private string $code;

	/**
	 * @param string $code
	 * @unstable
	 * @since 1.43
	 */
	public function __construct( string $code ) {
		$this->code = $code;
	}

	/**
	 * @return string
	 * @since 1.43
	 */
	public function toString(): string {
		return $this->code;
	}

	/**
	 * Mapping of deprecated language codes that were used in previous
	 * versions of MediaWiki to up-to-date, current language codes.
	 * These may or may not be valid BCP 47 codes; they are included here
	 * because MediaWiki renamed these particular codes at some point.
	 *
	 * @var array Mapping from deprecated MediaWiki-internal language code
	 *   to replacement MediaWiki-internal language code.
	 *
	 * @see https://meta.wikimedia.org/wiki/Special_language_codes
	 * @phpcs-require-sorted-array
	 */
	private const DEPRECATED_LANGUAGE_CODE_MAPPING = [
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
	 * standardized BCP 47 codes. These are not deprecated (yet?):
	 * IANA may eventually recognize the subtag, in which case the `-x-`
	 * infix could be removed, or else we could rename the code in
	 * MediaWiki, in which case they'd move up to the above mapping
	 * of deprecated codes.
	 *
	 * As a rule, we preserve all distinctions made by MediaWiki
	 * internally. For example, `de-formal` becomes `de-x-formal`
	 * instead of just `de` because MediaWiki distinguishes `de-formal`
	 * from `de` (for example, for interface translations). Similarly,
	 * BCP 47 indicates that `kk-Cyrl` SHOULD not be used because it
	 * "typically does not add information", but in our case MediaWiki
	 * LanguageConverter distinguishes `kk` (render content in a mix of
	 * Kurdish variants) from `kk-Cyrl` (convert content to be uniformly
	 * Cyrillic). As the BCP 47 requirement is a SHOULD not a MUST,
	 * `kk-Cyrl` is a valid code, although some validators may emit
	 * a warning note.
	 *
	 * @var array Mapping from nonstandard MediaWiki-internal codes to
	 *   BCP 47 codes
	 *
	 * @see https://meta.wikimedia.org/wiki/Special_language_codes
	 * @see https://phabricator.wikimedia.org/T125073
	 */
	private const NON_STANDARD_LANGUAGE_CODE_MAPPING = [
		// All codes returned by LanguageNameUtils::getLanguageNames() validated
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
		// other BCP 47 users. Note that MW also uses
		// `kk-Arab`/`kk-Cyrl`/`kk-Latn`, `zh-Hans`/`zh-Hant`,
		// without a country code, and those should be left alone.
		// `kk` has the Suppress-Script: Cyrl field, so `kk-KZ` won't be mapped
		// to `kk-Cyrl-KZ`.
		// (See getVariantsFallbacks() in KkConverter.php for Arab/Cyrl/Latn id.)
		// (See getVariantsFallbacks() in ZhConverter.php for Hans/Hant id.)
		'crh-ro' => 'crh-Latn-RO',
		'kk-cn' => 'kk-Arab-CN',
		'kk-tr' => 'kk-Latn-TR',
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
	 * This array is merged into $wgDummyLanguageCodes in
	 * SetupDynamicConfig.php, along with the fake language codes
	 * 'qqq' and 'qqx', which are used internally by MediaWiki's
	 * localisation system.
	 *
	 * @return string[]
	 *
	 * @since 1.29
	 */
	public static function getDeprecatedCodeMapping() {
		return self::DEPRECATED_LANGUAGE_CODE_MAPPING;
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
		static $result = [];
		if ( $result ) {
			return $result;
		}
		foreach ( self::DEPRECATED_LANGUAGE_CODE_MAPPING as $code => $ignore ) {
			$result[$code] = self::bcp47( $code );
		}
		foreach ( self::NON_STANDARD_LANGUAGE_CODE_MAPPING as $code => $ignore ) {
			$result[$code] = self::bcp47( $code );
		}
		return $result;
	}

	/**
	 * Replace deprecated language codes that were used in previous
	 * versions of MediaWiki to up-to-date, current language codes.
	 * Other values will be returned unchanged.
	 *
	 * @param string $code Old language code
	 * @return string New language code
	 *
	 * @since 1.30
	 */
	public static function replaceDeprecatedCodes( $code ) {
		return self::DEPRECATED_LANGUAGE_CODE_MAPPING[$code] ?? $code;
	}

	/**
	 * Get the normalised IANA language tag
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
		if ( isset( self::NON_STANDARD_LANGUAGE_CODE_MAPPING[$code] ) ) {
			$code = self::NON_STANDARD_LANGUAGE_CODE_MAPPING[$code];
		}
		$codeSegment = explode( '-', $code );
		$codeBCP = [];
		foreach ( $codeSegment as $segNo => $seg ) {
			// when the previous segment is x, it is a private segment and should be lc
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
		return implode( '-', $codeBCP );
	}

	/**
	 * Convert standardized BCP 47 codes to the internal names used
	 * by MediaWiki and returned by Language::getCode(). This function
	 * should be the inverse of LanguageCode::bcp47(). Note that BCP 47
	 * explicitly states that language codes are case-insensitive.
	 *
	 * Since LanguageFactory::getLanguage() is pretty generous about
	 * accepting aliases (as long as they are lowercased), this function
	 * should be equivalent to:
	 *   LanguageFactory::getLanguage(strtolower($code))->getCode()
	 * but (a) better describes the caller's intention, and (b) should
	 * be much more efficient in practice.
	 *
	 * @param string|Bcp47Code $code The standard BCP-47 language code
	 * @return string A MediaWiki-internal code, as returned, for example, by
	 *    Language::getCode()
	 * @since 1.40
	 */
	public static function bcp47ToInternal( $code ): string {
		if ( $code instanceof Language ) {
			return $code->getCode();
		}
		if ( $code instanceof Bcp47Code ) {
			$code = $code->toBcp47Code();
		}
		static $invertedLookup = [];
		if ( !$invertedLookup ) {
			// There should never be two different entries in
			// NON_STANDARD_LANGUAGE_CODE_MAPPING that map *different*
			// internal codes to the same external BCP-47 code.  That is,
			// BCP-47 should preserve all the information from the internal
			// code (discussed further above)[*].  But note the converse isn't
			// true: multiple BCP-47 codes can alias to the same internal code:
			//     BCP-47      internal
			//   zh-Hans-CN => zh-cn    (in NON_STANDARD_LANGUAGE_CODE_MAPPING)
			//   zh-Hans    => zh-hans  (not in " )
			//   zh-CN      => zh-cn    (not in " )
			//
			// [*] eml/egl are the "exception that proves the rule": `egl` *is*
			// (prematurely?) defined as an internal code, but only
			// eml.wikipedia.org exists, and it defines its language as `eml`;
			// for internal purposes `egl` should map back into `eml` until
			// `eml` is deprecated (aka an `eml => egl` entry is added to
			// DEPRECATED_LANGUAGE_CODE_MAPPING): T36217.
			foreach ( self::NON_STANDARD_LANGUAGE_CODE_MAPPING as $internal => $bcp47 ) {
				$invertedLookup[strtolower( $bcp47 )] = $internal;
			}
			// We deliberately do *not* use DEPRECATED_LANGUAGE_CODE_MAPPING
			// here: deprecated codes are no longer valid mediawiki internal
			// codes, and we should never return them.
		}
		// Internal codes are all lowercase.  This also achieves
		// case-insensitivity in the lookup.
		$code = strtolower( $code );
		return $invertedLookup[$code] ?? $code;
	}

	/**
	 * We want to eventually require valid BCP-47 codes on HTTP and HTML
	 * APIs (where the standards require it).  This will "prefer" to
	 * interpret the given $code as BCP-47, but if a mediawiki internal
	 * code is provided, it will map it to the proper BCP-47 code.  We
	 * don't emit a logged warning on this path yet, but we intend to
	 * in the future.
	 *
	 * @param string $code A "language code" provided from an HTTP or HTML
	 *   API, presumed to be BCP-47
	 * @return Bcp47Code An "actual" BCP-47 code
	 * @internal
	 */
	public static function normalizeNonstandardCodeAndWarn( string $code ): Bcp47Code {
		$compatMap = self::getNonstandardLanguageCodeMapping();
		if ( isset( $compatMap[strtolower( $code )] ) ) {
			// Backward compatibility, since clients may have been
			// sending us non-standards-compliant
			// "mediawiki internal language codes"; eventually we'll
			// emit a logged warning here.
			$code = $compatMap[strtolower( $code )];
		}
		return new Bcp47CodeValue( $code );
	}

	/**
	 * Returns true if a language code string is a well-formed language tag
	 * according to RFC 5646.
	 * This function only checks well-formedness; it doesn't check that
	 * language, script or variant codes actually exist in the repositories.
	 *
	 * Based on regexes by Mark Davis of the Unicode Consortium:
	 * https://github.com/unicode-org/icu/blob/37e295627156bc334e1f1e88807025fac984da0e/icu4j/main/tests/translit/src/com/ibm/icu/dev/test/translit/langtagRegex.txt
	 *
	 * @param string $code
	 * @param bool $lenient Whether to allow '_' as separator. The default is only '-'.
	 *
	 * @return bool
	 * @since 1.39
	 */
	public static function isWellFormedLanguageTag( string $code, bool $lenient = false ): bool {
		$alpha = '[a-z]';
		$digit = '[0-9]';
		$alphanum = '[a-z0-9]';
		$x = 'x'; # private use singleton
		$singleton = '[a-wy-z]'; # other singleton
		$s = $lenient ? '[-_]' : '-';

		$language = "$alpha{2,8}|$alpha{2,3}$s$alpha{3}";
		$script = "$alpha{4}"; # ISO 15924
		$region = "(?:$alpha{2}|$digit{3})"; # ISO 3166-1 alpha-2 or UN M.49
		$variant = "(?:$alphanum{5,8}|$digit$alphanum{3})";
		$extension = "$singleton(?:$s$alphanum{2,8})+";
		$privateUse = "$x(?:$s$alphanum{1,8})+";

		# Define certain legacy language tags (marked as “Type: grandfathered” in BCP 47),
		# since otherwise the regex is pretty useless.
		# Since these are limited, this is safe even later changes to the registry --
		# the only oddity is that it might change the type of the tag, and thus
		# the results from the capturing groups.
		# https://www.iana.org/assignments/language-subtag-registry

		$legacy = "en{$s}gb{$s}oed"
			. "|i{$s}(?:ami|bnn|default|enochian|hak|klingon|lux|mingo|navajo|pwn|tao|tay|tsu)"
			. "|no{$s}(?:bok|nyn)"
			. "|sgn{$s}(?:be{$s}(?:fr|nl)|ch{$s}de)"
			. "|zh{$s}min{$s}nan";

		$variantList = "$variant(?:$s$variant)*";
		$extensionList = "$extension(?:$s$extension)*";

		$langtag = "(?:($language)"
			. "(?:$s$script)?"
			. "(?:$s$region)?"
			. "(?:$s$variantList)?"
			. "(?:$s$extensionList)?"
			. "(?:$s$privateUse)?)";

		# Here is the final breakdown, with capturing groups for each of these components
		# The variants, extensions, legacy, and private-use may have interior '-'

		$root = "^(?:$langtag|$privateUse|$legacy)$";

		return preg_match( "/$root/i", $code );
	}
}

/** @deprecated class alias since 1.43 */
class_alias( LanguageCode::class, 'LanguageCode' );
