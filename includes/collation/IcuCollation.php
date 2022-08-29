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

use MediaWiki\Languages\LanguageFactory;

/**
 * @since 1.16.3
 */
class IcuCollation extends Collation {
	private const FIRST_LETTER_VERSION = 4;

	/** @var Collator */
	private $primaryCollator;

	/** @var Collator */
	private $mainCollator;

	/** @var string */
	private $locale;

	/** @var Language */
	protected $digitTransformLanguage;

	/** @var bool */
	private $useNumericCollation = false;

	/** @var array */
	private $firstLetterData;

	/**
	 * Unified CJK blocks.
	 *
	 * The same definition of a CJK block must be used for both Collation and
	 * generateCollationData.php. These blocks are omitted from the first
	 * letter data, as an optimisation measure and because the default UCA table
	 * is pretty useless for sorting Chinese text anyway. Japanese and Korean
	 * blocks are not included here, because they are smaller and more useful.
	 */
	private const CJK_BLOCKS = [
		[ 0x2E80, 0x2EFF ], // CJK Radicals Supplement
		[ 0x2F00, 0x2FDF ], // Kangxi Radicals
		[ 0x2FF0, 0x2FFF ], // Ideographic Description Characters
		[ 0x3000, 0x303F ], // CJK Symbols and Punctuation
		[ 0x31C0, 0x31EF ], // CJK Strokes
		[ 0x3200, 0x32FF ], // Enclosed CJK Letters and Months
		[ 0x3300, 0x33FF ], // CJK Compatibility
		[ 0x3400, 0x4DBF ], // CJK Unified Ideographs Extension A
		[ 0x4E00, 0x9FFF ], // CJK Unified Ideographs
		[ 0xF900, 0xFAFF ], // CJK Compatibility Ideographs
		[ 0xFE30, 0xFE4F ], // CJK Compatibility Forms
		[ 0x20000, 0x2A6DF ], // CJK Unified Ideographs Extension B
		[ 0x2A700, 0x2B73F ], // CJK Unified Ideographs Extension C
		[ 0x2B740, 0x2B81F ], // CJK Unified Ideographs Extension D
		[ 0x2F800, 0x2FA1F ], // CJK Compatibility Ideographs Supplement
	];

	/**
	 * Additional characters (or character groups) to be considered separate
	 * letters for given languages, or to be removed from the list of such
	 * letters (denoted by keys starting with '-').
	 *
	 * These are additions to (or subtractions from) the data stored in the
	 * first-letters-root.php data file (which among others includes full basic
	 * Latin, Cyrillic and Greek alphabets).
	 *
	 * "Separate letter" is a letter that would have a separate heading/section
	 * for it in a dictionary or a phone book in this language. This data isn't
	 * used for sorting (the ICU library handles that), only for deciding which
	 * characters (or character groups) to use as headings.
	 *
	 * Initially generated based on the primary level of Unicode collation
	 * tailorings available at http://developer.mimer.com/charts/tailorings.htm ,
	 * later modified.
	 *
	 * Empty arrays are intended; this signifies that the data for the language is
	 * available and that there are, in fact, no additional letters to consider.
	 */
	private const TAILORING_FIRST_LETTERS = [
		'af' => [],
		'am' => [],
		'ar' => [],
		'as' => [ "\u{0982}", "\u{0981}", "\u{0983}", "\u{09CE}", "ক্ষ " ],
		'ast' => [ "Ch", "Ll", "Ñ" ], // not in libicu
		'az' => [ "Ç", "Ə", "Ğ", "İ", "Ö", "Ş", "Ü" ],
		'be' => [ "Ё" ],
		'be-tarask' => [ "Ё" ],
		'bg' => [],
		'bn' => [ 'ং', 'ঃ', 'ঁ' ],
		'bn@collation=traditional' => [
			'ং', 'ঃ', 'ঁ', 'ক্', 'খ্', 'গ্', 'ঘ্', 'ঙ্', 'চ্', 'ছ্', 'জ্', 'ঝ্',
			'ঞ্', 'ট্', 'ঠ্', 'ড্', 'ঢ্', 'ণ্', 'ৎ', 'থ্', 'দ্', 'ধ্', 'ন্', 'প্',
			'ফ্', 'ব্', 'ভ্', 'ম্', 'য্', 'র্', 'ৰ্', 'ল্', 'ৱ্', 'শ্', 'ষ্', 'স্', 'হ্'
		],
		'bo' => [],
		'br' => [ "Ch", "C'h" ],
		'bs' => [ "Č", "Ć", "Dž", "Đ", "Lj", "Nj", "Š", "Ž" ],
		'bs-Cyrl' => [],
		'ca' => [],
		'chr' => [],
		'co' => [], // not in libicu
		'cs' => [ "Č", "Ch", "Ř", "Š", "Ž" ],
		'cy' => [ "Ch", "Dd", "Ff", "Ng", "Ll", "Ph", "Rh", "Th" ],
		'da' => [ "Æ", "Ø", "Å" ],
		'de' => [],
		'de-AT@collation=phonebook' => [ 'ä', 'ö', 'ü', 'ß' ],
		'dsb' => [ "Č", "Ć", "Dź", "Ě", "Ch", "Ł", "Ń", "Ŕ", "Š", "Ś", "Ž", "Ź" ],
		'ee' => [ "Dz", "Ɖ", "Ɛ", "Ƒ", "Gb", "Ɣ", "Kp", "Ny", "Ŋ", "Ɔ", "Ts", "Ʋ" ],
		'el' => [],
		'en' => [],
		'eo' => [ "Ĉ", "Ĝ", "Ĥ", "Ĵ", "Ŝ", "Ŭ" ],
		'es' => [ "Ñ" ],
		'et' => [ "Š", "Ž", "Õ", "Ä", "Ö", "Ü" ],
		'eu' => [ "Ñ" ], // not in libicu
		'fa' => [
			// RTL, let's put each letter on a new line
			"آ",
			"ء",
			"ه",
			"ا",
			"و"
		],
		'fi' => [ "Å", "Ä", "Ö" ],
		'fil' => [ "Ñ", "Ng" ],
		'fo' => [ "Á", "Ð", "Í", "Ó", "Ú", "Ý", "Æ", "Ø", "Å" ],
		'fr' => [],
		'fr-CA' => [], // fr-CA sorts accents slightly different from fr.
		'fur' => [ "À", "Á", "Â", "È", "Ì", "Ò", "Ù" ], // not in libicu
		'fy' => [], // not in libicu
		'ga' => [],
		'gd' => [], // not in libicu
		'gl' => [ "Ch", "Ll", "Ñ" ],
		'gu' => [ "\u{0A82}", "\u{0A83}", "\u{0A81}", "\u{0AB3}" ],
		'ha' => [ 'Ɓ', 'Ɗ', 'Ƙ', 'Sh', 'Ts', 'Ƴ' ],
		'haw' => [ 'ʻ' ],
		'he' => [],
		'hi' => [ "\u{0902}", "\u{0903}" ],
		'hr' => [ "Č", "Ć", "Dž", "Đ", "Lj", "Nj", "Š", "Ž" ],
		'hsb' => [ "Č", "Dź", "Ě", "Ch", "Ł", "Ń", "Ř", "Š", "Ć", "Ž" ],
		'hu' => [ "Cs", "Dz", "Dzs", "Gy", "Ly", "Ny", "Ö", "Sz", "Ty", "Ü", "Zs" ],
		'hy' => [ "և" ],
		'id' => [],
		'ig' => [ "Ch", "Gb", "Gh", "Gw", "Ị", "Kp", "Kw", "Ṅ", "Nw", "Ny", "Ọ", "Sh", "Ụ" ],
		'is' => [ "Á", "Ð", "É", "Í", "Ó", "Ú", "Ý", "Þ", "Æ", "Ö", "Å" ],
		'it' => [],
		'ka' => [],
		'kk' => [ "Ү", "І" ],
		'kl' => [ "Æ", "Ø", "Å" ],
		'km' => [
			"រ", "ឫ", "ឬ", "ល", "ឭ", "ឮ", "\u{17BB}\u{17C6}",
			"\u{17C6}", "\u{17B6}\u{17C6}", "\u{17C7}",
			"\u{17B7}\u{17C7}", "\u{17BB}\u{17C7}",
			"\u{17C1}\u{17C7}", "\u{17C4}\u{17C7}",
		],
		'kn' => [ "\u{0C81}", "\u{0C83}", "\u{0CF1}", "\u{0CF2}" ],
		'kok' => [ "\u{0902}", "\u{0903}", "ळ", "क्ष" ],
		'ku' => [ "Ç", "Ê", "Î", "Ş", "Û" ], // not in libicu
		'ky' => [ "Ё" ],
		'la' => [], // not in libicu
		'lb' => [],
		'lkt' => [ 'Č', 'Ǧ', 'Ȟ', 'Š', 'Ž' ],
		'ln' => [ 'Ɛ' ],
		'lo' => [],
		'lt' => [ "Č", "Š", "Ž" ],
		'lv' => [ "Č", "Ģ", "Ķ", "Ļ", "Ņ", "Š", "Ž" ],
		'mk' => [ "Ѓ", "Ќ" ],
		'ml' => [],
		'mn' => [],
		'mo' => [ "Ă", "Â", "Î", "Ș", "Ț" ], // not in libicu
		'mr' => [ "\u{0902}", "\u{0903}", "ळ", "क्ष", "ज्ञ" ],
		'ms' => [],
		'mt' => [ "Ċ", "Ġ", "Għ", "Ħ", "Ż" ],
		'nb' => [ "Æ", "Ø", "Å" ],
		'ne' => [],
		'nl' => [],
		'nn' => [ "Æ", "Ø", "Å" ],
		'no' => [ "Æ", "Ø", "Å" ], // not in libicu. You should probably use nb or nn instead.
		'oc' => [], // not in libicu
		'om' => [ 'Ch', 'Dh', 'Kh', 'Ny', 'Ph', 'Sh' ],
		'or' => [ "\u{0B01}", "\u{0B02}", "\u{0B03}", "କ୍ଷ" ],
		'pa' => [ "\u{0A4D}" ],
		'pl' => [ "Ą", "Ć", "Ę", "Ł", "Ń", "Ó", "Ś", "Ź", "Ż" ],
		'pt' => [],
		'rm' => [], // not in libicu
		'ro' => [ "Ă", "Â", "Î", "Ș", "Ț" ],
		'ru' => [],
		'rup' => [ "Ă", "Â", "Î", "Ľ", "Ń", "Ș", "Ț" ], // not in libicu
		'sco' => [],
		'se' => [
			'Á', 'Č', 'Ʒ', 'Ǯ', 'Đ', 'Ǧ', 'Ǥ', 'Ǩ', 'Ŋ',
			'Š', 'Ŧ', 'Ž', 'Ø', 'Æ', 'Ȧ', 'Ä', 'Ö'
		],
		'si' => [ "\u{0D82}", "\u{0D83}", "\u{0DA4}" ],
		'sk' => [ "Ä", "Č", "Ch", "Ô", "Š", "Ž" ],
		'sl' => [ "Č", "Š", "Ž" ],
		'smn' => [ "Á", "Č", "Đ", "Ŋ", "Š", "Ŧ", "Ž", "Æ", "Ø", "Å", "Ä", "Ö" ],
		'sq' => [ "Ç", "Dh", "Ë", "Gj", "Ll", "Nj", "Rr", "Sh", "Th", "Xh", "Zh" ],
		'sr' => [],
		'sr-Latn' => [ "Č", "Ć", "Dž", "Đ", "Lj", "Nj", "Š", "Ž" ],
		'sv' => [ "Å", "Ä", "Ö" ],
		'sv@collation=standard' => [ "Å", "Ä", "Ö" ],
		'sw' => [],
		'ta' => [
			"\u{0B82}", "ஃ", "க்ஷ", "க்", "ங்", "ச்", "ஞ்", "ட்", "ண்", "த்", "ந்",
			"ப்", "ம்", "ய்", "ர்", "ல்", "வ்", "ழ்", "ள்", "ற்", "ன்", "ஜ்", "ஶ்", "ஷ்",
			"ஸ்", "ஹ்", "க்ஷ்"
		],
		'te' => [ "\u{0C01}", "\u{0C02}", "\u{0C03}" ],
		'th' => [ "ฯ", "\u{0E46}", "\u{0E4D}", "\u{0E3A}" ],
		'tk' => [ "Ç", "Ä", "Ž", "Ň", "Ö", "Ş", "Ü", "Ý" ],
		'tl' => [ "Ñ", "Ng" ], // not in libicu
		'to' => [ "Ng", "ʻ" ],
		'tr' => [ "Ç", "Ğ", "İ", "Ö", "Ş", "Ü" ],
		'-tr' => [ "ı" ],
		'tt' => [ "Ә", "Ө", "Ү", "Җ", "Ң", "Һ" ], // not in libicu
		'uk' => [ "Ґ", "Ь" ],
		'uz' => [ "Ch", "G'", "Ng", "O'", "Sh" ], // not in libicu
		'vi' => [ "Ă", "Â", "Đ", "Ê", "Ô", "Ơ", "Ư" ],
		'vo' => [ "Ä", "Ö", "Ü" ],
		'yi' => [
			"\u{05D1}\u{05BF}", "\u{05DB}\u{05BC}", "\u{05E4}\u{05BC}",
			"\u{05E9}\u{05C2}", "\u{05EA}\u{05BC}"
		],
		'yo' => [ "Ẹ", "Gb", "Ọ", "Ṣ" ],
		'zu' => [],
	];

	/**
	 * @param LanguageFactory $languageFactory
	 * @param string $locale
	 */
	public function __construct(
		LanguageFactory $languageFactory,
		$locale
	) {
		$this->locale = $locale;
		// Drop everything after the '@' in locale's name
		$localeParts = explode( '@', $locale );
		$this->digitTransformLanguage = $languageFactory->getLanguage( $locale === 'root' ? 'en' : $localeParts[0] );

		$mainCollator = Collator::create( $locale );
		if ( !$mainCollator ) {
			throw new MWException( "Invalid ICU locale specified for collation: $locale" );
		}
		$this->mainCollator = $mainCollator;

		// @phan-suppress-next-line PhanPossiblyNullTypeMismatchProperty successed before, no null check needed
		$this->primaryCollator = Collator::create( $locale );
		$this->primaryCollator->setStrength( Collator::PRIMARY );

		// If the special suffix for numeric collation is present, turn on numeric collation.
		if ( substr( $locale, -5, 5 ) === '-u-kn' ) {
			$this->useNumericCollation = true;
			// Strip off the special suffix so it doesn't trip up fetchFirstLetterData().
			$this->locale = substr( $this->locale, 0, -5 );
			$this->mainCollator->setAttribute( Collator::NUMERIC_COLLATION, Collator::ON );
			$this->primaryCollator->setAttribute( Collator::NUMERIC_COLLATION, Collator::ON );
		}
	}

	public function getSortKey( $string ) {
		return $this->mainCollator->getSortKey( $string );
	}

	public function getFirstLetter( $string ) {
		$string = strval( $string );
		if ( $string === '' ) {
			return '';
		}

		$firstChar = mb_substr( $string, 0, 1, 'UTF-8' );

		// If the first character is a CJK character, just return that character.
		if ( ord( $firstChar ) > 0x7f && self::isCjk( mb_ord( $firstChar ) ) ) {
			return $firstChar;
		}

		$sortKey = $this->getPrimarySortKey( $string );
		$data = $this->getFirstLetterData();
		$keys = $data['keys'];
		$letters = $data['chars'];

		// Do a binary search to find the correct letter to sort under
		$min = ArrayUtils::findLowerBound(
			static function ( $index ) use ( $keys ) {
				return $keys[$index];
			},
			count( $keys ),
			'strcmp',
			$sortKey );

		if ( $min === false ) {
			// Before the first letter
			return '';
		}

		$sortLetter = $letters[$min];

		if ( $this->useNumericCollation ) {
			// If the sort letter is a number, return '0–9' (or localized equivalent).
			// ASCII value of 0 is 48. ASCII value of 9 is 57.
			// Note that this also applies to non-Arabic numerals since they are
			// mapped to Arabic numeral sort letters. For example, ২ sorts as 2.
			if ( ord( $sortLetter ) >= 48 && ord( $sortLetter ) <= 57 ) {
				$sortLetter = wfMessage( 'category-header-numerals' )->numParams( 0, 9 )->text();
			}
		}
		return $sortLetter;
	}

	private function getPrimarySortKey( $string ) {
		return $this->primaryCollator->getSortKey( $string );
	}

	/**
	 * @since 1.16.3
	 * @return array
	 */
	private function getFirstLetterData() {
		if ( $this->firstLetterData === null ) {
			$cache = ObjectCache::getLocalServerInstance( CACHE_ANYTHING );
			$cacheKey = $cache->makeKey(
				'first-letters',
				static::class,
				$this->locale,
				$this->digitTransformLanguage->getCode(),
				INTL_ICU_VERSION,
				self::FIRST_LETTER_VERSION
			);
			$this->firstLetterData = $cache->getWithSetCallback( $cacheKey, $cache::TTL_WEEK, function () {
				return $this->fetchFirstLetterData();
			} );
		}
		return $this->firstLetterData;
	}

	/**
	 * @return array
	 * @throws MWException
	 */
	private function fetchFirstLetterData() {
		// Generate data from serialized data file
		if ( isset( self::TAILORING_FIRST_LETTERS[$this->locale] ) ) {
			$letters = require __DIR__ . "/data/first-letters-root.php";
			// Append additional characters
			$letters = array_merge( $letters, self::TAILORING_FIRST_LETTERS[$this->locale] );
			// Remove unnecessary ones, if any
			if ( isset( self::TAILORING_FIRST_LETTERS['-' . $this->locale] ) ) {
				$letters = array_diff( $letters, self::TAILORING_FIRST_LETTERS['-' . $this->locale] );
			}
			// Apply digit transforms
			$digits = [ '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' ];
			$letters = array_diff( $letters, $digits );
			foreach ( $digits as $digit ) {
				$letters[] = $this->digitTransformLanguage->formatNumNoSeparators( $digit );
			}
		} elseif ( $this->locale === 'root' ) {
			$letters = require __DIR__ . "/data/first-letters-root.php";
		} else {
			throw new MWException( "MediaWiki does not support ICU locale " .
				"\"{$this->locale}\"" );
		}

		/* Sort the letters.
		 *
		 * It's impossible to have the precompiled data file properly sorted,
		 * because the sort order changes depending on ICU version. If the
		 * array is not properly sorted, the binary search will return random
		 * results.
		 *
		 * We also take this opportunity to remove primary collisions.
		 */
		$letterMap = [];
		foreach ( $letters as $letter ) {
			$key = $this->getPrimarySortKey( $letter );
			if ( isset( $letterMap[$key] ) ) {
				// Primary collision (two characters with the same sort position).
				// Keep whichever one sorts first in the main collator.
				$comp = $this->mainCollator->compare( $letter, $letterMap[$key] );
				wfDebug( "Primary collision '$letter' '{$letterMap[$key]}' (comparison: $comp)" );
				// If that also has a collision, use codepoint as a tiebreaker.
				if ( $comp === 0 ) {
					$comp = mb_ord( $letter ) <=> mb_ord( $letterMap[$key] );
				}
				if ( $comp < 0 ) {
					$letterMap[$key] = $letter;
				}
			} else {
				$letterMap[$key] = $letter;
			}
		}
		ksort( $letterMap, SORT_STRING );

		/* Remove duplicate prefixes. Basically, if something has a sortkey
		 * which is a prefix of some other sortkey, then it is an
		 * expansion and probably should not be considered a section
		 * header.
		 *
		 * For example, 'þ' is sometimes sorted as if it is the letters
		 * 'th'. Other times it is its own primary element. Another
		 * example is '₨'. Sometimes it's a currency symbol. Sometimes it
		 * is an 'R' followed by an 's'.
		 *
		 * Additionally, an expanded element should always sort directly
		 * after its first element due to the way sortkeys work.
		 *
		 * UCA sortkey elements are of variable length, but no collation
		 * element should be a prefix of some other element, so I think
		 * this is safe. See:
		 * - https://unicode-org.github.io/icu-docs/design/collation/ICU_collation_design.htm
		 * - https://icu.unicode.org/design/collation/uca-weight-allocation
		 *
		 * Additionally, there is something called primary compression to
		 * worry about. Basically, if you have two primary elements that
		 * are more than one byte and both start with the same byte, then
		 * the first byte is dropped on the second primary. Additionally,
		 * either \x03 or \xFF may be added to mean that the next primary
		 * does not start with the first byte of the first primary.
		 *
		 * This shouldn't matter much, as the first primary is not
		 * changed, and that is what we are comparing against.
		 *
		 * tl;dr: This makes some assumptions about how ICU implements
		 * collations. It seems incredibly unlikely these assumptions
		 * will change, but nonetheless they are assumptions.
		 */

		$prev = '';
		$duplicatePrefixes = [];
		foreach ( $letterMap as $key => $value ) {
			// Due to the fact the array is sorted, we only have
			// to compare with the element directly previous
			// to the current element (skipping expansions).
			// An element "X" will always sort directly
			// before "XZ" (Unless we have "XY", but we
			// do not update $prev in that case).
			if ( $prev !== '' && str_starts_with( $key, $prev ) ) {
				$duplicatePrefixes[] = $key;
				// If this is an expansion, we don't want to
				// compare the next element to this element,
				// but to what is currently $prev
				continue;
			}
			$prev = $key;
		}
		foreach ( $duplicatePrefixes as $badKey ) {
			wfDebug( "Removing '{$letterMap[$badKey]}' from first letters." );
			unset( $letterMap[$badKey] );
			// This code assumes that unsetting does not change sort order.
		}
		return [
			'chars' => array_values( $letterMap ),
			'keys' => array_keys( $letterMap ),
		];
	}

	/**
	 * Test if a code point is a CJK (Chinese, Japanese, Korean) character
	 * @param int $codepoint
	 * @return bool
	 * @since 1.16.3
	 */
	public static function isCjk( $codepoint ) {
		foreach ( self::CJK_BLOCKS as $block ) {
			if ( $codepoint >= $block[0] && $codepoint <= $block[1] ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Return the version of Unicode appropriate for the version of ICU library
	 * currently in use, or false when it can't be determined.
	 *
	 * @since 1.21
	 * @return string|bool
	 */
	public static function getUnicodeVersionForICU() {
		$icuVersion = INTL_ICU_VERSION;
		if ( !$icuVersion ) {
			return false;
		}

		$versionPrefix = substr( $icuVersion, 0, 3 );
		// Source: https://icu.unicode.org/download
		$map = [
			'71.' => '14.0',
			'70.' => '14.0',
			'69.' => '13.0',
			'68.' => '13.0',
			'67.' => '13.0',
			'66.' => '13.0',
			'65.' => '12.0',
			'64.' => '12.0',
			'63.' => '11.0',
			'62.' => '11.0',
			'61.' => '10.0',
			'60.' => '10.0',
			'59.' => '9.0',
			'58.' => '9.0',
			'57.' => '8.0',
			'56.' => '8.0',
			'55.' => '7.0',
			'54.' => '7.0',
			'53.' => '6.3',
			'52.' => '6.3',
			'51.' => '6.2',
			'50.' => '6.2',
			'49.' => '6.1',
			'4.8' => '6.0',
			'4.6' => '6.0',
			'4.4' => '5.2',
			'4.2' => '5.1',
			'4.0' => '5.1',
			'3.8' => '5.0',
			'3.6' => '5.0',
			'3.4' => '4.1',
		];

		return $map[$versionPrefix] ?? false;
	}
}
