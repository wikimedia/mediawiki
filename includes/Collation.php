<?php
/**
 * Database row sorting.
 *
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

abstract class Collation {
	private static $instance;

	/**
	 * @return Collation
	 */
	static function singleton() {
		if ( !self::$instance ) {
			global $wgCategoryCollation;
			self::$instance = self::factory( $wgCategoryCollation );
		}
		return self::$instance;
	}

	/**
	 * @throws MWException
	 * @param string $collationName
	 * @return Collation
	 */
	static function factory( $collationName ) {
		switch ( $collationName ) {
			case 'uppercase':
				return new UppercaseCollation;
			case 'identity':
				return new IdentityCollation;
			case 'uca-default':
				return new IcuCollation( 'root' );
			case 'xx-uca-ckb':
				return new CollationCkb;
			case 'xx-uca-et':
				return new CollationEt;
			default:
				$match = array();
				if ( preg_match( '/^uca-([a-z@=-]+)$/', $collationName, $match ) ) {
					return new IcuCollation( $match[1] );
				}

				# Provide a mechanism for extensions to hook in.
				$collationObject = null;
				Hooks::run( 'Collation::factory', array( $collationName, &$collationObject ) );

				if ( $collationObject instanceof Collation ) {
					return $collationObject;
				}

				// If all else fails...
				throw new MWException( __METHOD__ . ": unknown collation type \"$collationName\"" );
		}
	}

	/**
	 * Given a string, convert it to a (hopefully short) key that can be used
	 * for efficient sorting.  A binary sort according to the sortkeys
	 * corresponds to a logical sort of the corresponding strings.  Current
	 * code expects that a line feed character should sort before all others, but
	 * has no other particular expectations (and that one can be changed if
	 * necessary).
	 *
	 * @param string $string UTF-8 string
	 * @return string Binary sortkey
	 */
	abstract function getSortKey( $string );

	/**
	 * Given a string, return the logical "first letter" to be used for
	 * grouping on category pages and so on.  This has to be coordinated
	 * carefully with convertToSortkey(), or else the sorted list might jump
	 * back and forth between the same "initial letters" or other pathological
	 * behavior.  For instance, if you just return the first character, but "a"
	 * sorts the same as "A" based on getSortKey(), then you might get a
	 * list like
	 *
	 * == A ==
	 * * [[Aardvark]]
	 *
	 * == a ==
	 * * [[antelope]]
	 *
	 * == A ==
	 * * [[Ape]]
	 *
	 * etc., assuming for the sake of argument that $wgCapitalLinks is false.
	 *
	 * @param string $string UTF-8 string
	 * @return string UTF-8 string corresponding to the first letter of input
	 */
	abstract function getFirstLetter( $string );
}

class UppercaseCollation extends Collation {
	private $lang;

	function __construct() {
		// Get a language object so that we can use the generic UTF-8 uppercase
		// function there
		$this->lang = Language::factory( 'en' );
	}

	function getSortKey( $string ) {
		return $this->lang->uc( $string );
	}

	function getFirstLetter( $string ) {
		if ( $string[0] == "\0" ) {
			$string = substr( $string, 1 );
		}
		return $this->lang->ucfirst( $this->lang->firstChar( $string ) );
	}
}

/**
 * Collation class that's essentially a no-op.
 *
 * Does sorting based on binary value of the string.
 * Like how things were pre 1.17.
 */
class IdentityCollation extends Collation {

	function getSortKey( $string ) {
		return $string;
	}

	function getFirstLetter( $string ) {
		global $wgContLang;
		// Copied from UppercaseCollation.
		// I'm kind of unclear on when this could happen...
		if ( $string[0] == "\0" ) {
			$string = substr( $string, 1 );
		}
		return $wgContLang->firstChar( $string );
	}
}

class IcuCollation extends Collation {
	const FIRST_LETTER_VERSION = 2;

	/** @var Collator */
	private $primaryCollator;

	/** @var Collator */
	private $mainCollator;

	/** @var string */
	private $locale;

	/** @var Language */
	protected $digitTransformLanguage;

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
	private static $cjkBlocks = array(
		array( 0x2E80, 0x2EFF ), // CJK Radicals Supplement
		array( 0x2F00, 0x2FDF ), // Kangxi Radicals
		array( 0x2FF0, 0x2FFF ), // Ideographic Description Characters
		array( 0x3000, 0x303F ), // CJK Symbols and Punctuation
		array( 0x31C0, 0x31EF ), // CJK Strokes
		array( 0x3200, 0x32FF ), // Enclosed CJK Letters and Months
		array( 0x3300, 0x33FF ), // CJK Compatibility
		array( 0x3400, 0x4DBF ), // CJK Unified Ideographs Extension A
		array( 0x4E00, 0x9FFF ), // CJK Unified Ideographs
		array( 0xF900, 0xFAFF ), // CJK Compatibility Ideographs
		array( 0xFE30, 0xFE4F ), // CJK Compatibility Forms
		array( 0x20000, 0x2A6DF ), // CJK Unified Ideographs Extension B
		array( 0x2A700, 0x2B73F ), // CJK Unified Ideographs Extension C
		array( 0x2B740, 0x2B81F ), // CJK Unified Ideographs Extension D
		array( 0x2F800, 0x2FA1F ), // CJK Compatibility Ideographs Supplement
	);

	/**
	 * Additional characters (or character groups) to be considered separate
	 * letters for given languages, or to be removed from the list of such
	 * letters (denoted by keys starting with '-').
	 *
	 * These are additions to (or subtractions from) the data stored in the
	 * first-letters-root.ser file (which among others includes full basic latin,
	 * cyrillic and greek alphabets).
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
	private static $tailoringFirstLetters = array(
		// Verified by native speakers
		'be' => array( "Ё" ),
		'be-tarask' => array( "Ё" ),
		'cy' => array( "Ch", "Dd", "Ff", "Ng", "Ll", "Ph", "Rh", "Th" ),
		'en' => array(),
		'fa' => array( "آ", "ء", "ه" ),
		'fi' => array( "Å", "Ä", "Ö" ),
		'fr' => array(),
		'hu' => array( "Cs", "Dz", "Dzs", "Gy", "Ly", "Ny", "Ö", "Sz", "Ty", "Ü", "Zs" ),
		'is' => array( "Á", "Ð", "É", "Í", "Ó", "Ú", "Ý", "Þ", "Æ", "Ö", "Å" ),
		'it' => array(),
		'lv' => array( "Č", "Ģ", "Ķ", "Ļ", "Ņ", "Š", "Ž" ),
		'pl' => array( "Ą", "Ć", "Ę", "Ł", "Ń", "Ó", "Ś", "Ź", "Ż" ),
		'pt' => array(),
		'ru' => array(),
		'sv' => array( "Å", "Ä", "Ö" ),
		'sv@collation=standard' => array( "Å", "Ä", "Ö" ),
		'uk' => array( "Ґ", "Ь" ),
		'vi' => array( "Ă", "Â", "Đ", "Ê", "Ô", "Ơ", "Ư" ),
		// Not verified, but likely correct
		'af' => array(),
		'ast' => array( "Ch", "Ll", "Ñ" ),
		'az' => array( "Ç", "Ə", "Ğ", "İ", "Ö", "Ş", "Ü" ),
		'bg' => array(),
		'br' => array( "Ch", "C'h" ),
		'bs' => array( "Č", "Ć", "Dž", "Đ", "Lj", "Nj", "Š", "Ž" ),
		'ca' => array(),
		'co' => array(),
		'cs' => array( "Č", "Ch", "Ř", "Š", "Ž" ),
		'da' => array( "Æ", "Ø", "Å" ),
		'de' => array(),
		'dsb' => array( "Č", "Ć", "Dź", "Ě", "Ch", "Ł", "Ń", "Ŕ", "Š", "Ś", "Ž", "Ź" ),
		'el' => array(),
		'eo' => array( "Ĉ", "Ĝ", "Ĥ", "Ĵ", "Ŝ", "Ŭ" ),
		'es' => array( "Ñ" ),
		'et' => array( "Š", "Ž", "Õ", "Ä", "Ö", "Ü", "W" ), // added W for CollationEt (xx-uca-et)
		'eu' => array( "Ñ" ),
		'fo' => array( "Á", "Ð", "Í", "Ó", "Ú", "Ý", "Æ", "Ø", "Å" ),
		'fur' => array( "À", "Á", "Â", "È", "Ì", "Ò", "Ù" ),
		'fy' => array(),
		'ga' => array(),
		'gd' => array(),
		'gl' => array( "Ch", "Ll", "Ñ" ),
		'hr' => array( "Č", "Ć", "Dž", "Đ", "Lj", "Nj", "Š", "Ž" ),
		'hsb' => array( "Č", "Dź", "Ě", "Ch", "Ł", "Ń", "Ř", "Š", "Ć", "Ž" ),
		'kk' => array( "Ү", "І" ),
		'kl' => array( "Æ", "Ø", "Å" ),
		'ku' => array( "Ç", "Ê", "Î", "Ş", "Û" ),
		'ky' => array( "Ё" ),
		'la' => array(),
		'lb' => array(),
		'lt' => array( "Č", "Š", "Ž" ),
		'mk' => array(),
		'mo' => array( "Ă", "Â", "Î", "Ş", "Ţ" ),
		'mt' => array( "Ċ", "Ġ", "Għ", "Ħ", "Ż" ),
		'nl' => array(),
		'no' => array( "Æ", "Ø", "Å" ),
		'oc' => array(),
		'rm' => array(),
		'ro' => array( "Ă", "Â", "Î", "Ş", "Ţ" ),
		'rup' => array( "Ă", "Â", "Î", "Ľ", "Ń", "Ş", "Ţ" ),
		'sco' => array(),
		'sk' => array( "Ä", "Č", "Ch", "Ô", "Š", "Ž" ),
		'sl' => array( "Č", "Š", "Ž" ),
		'smn' => array( "Á", "Č", "Đ", "Ŋ", "Š", "Ŧ", "Ž", "Æ", "Ø", "Å", "Ä", "Ö" ),
		'sq' => array( "Ç", "Dh", "Ë", "Gj", "Ll", "Nj", "Rr", "Sh", "Th", "Xh", "Zh" ),
		'sr' => array(),
		'tk' => array( "Ç", "Ä", "Ž", "Ň", "Ö", "Ş", "Ü", "Ý" ),
		'tl' => array( "Ñ", "Ng" ),
		'tr' => array( "Ç", "Ğ", "İ", "Ö", "Ş", "Ü" ),
		'tt' => array( "Ә", "Ө", "Ү", "Җ", "Ң", "Һ" ),
		'uz' => array( "Ch", "G'", "Ng", "O'", "Sh" ),
	);

	const RECORD_LENGTH = 14;

	function __construct( $locale ) {
		if ( !extension_loaded( 'intl' ) ) {
			throw new MWException( 'An ICU collation was requested, ' .
				'but the intl extension is not available.' );
		}

		$this->locale = $locale;
		// Drop everything after the '@' in locale's name
		$localeParts = explode( '@', $locale );
		$this->digitTransformLanguage = Language::factory( $locale === 'root' ? 'en' : $localeParts[0] );

		$this->mainCollator = Collator::create( $locale );
		if ( !$this->mainCollator ) {
			throw new MWException( "Invalid ICU locale specified for collation: $locale" );
		}

		$this->primaryCollator = Collator::create( $locale );
		$this->primaryCollator->setStrength( Collator::PRIMARY );
	}

	function getSortKey( $string ) {
		// intl extension produces non null-terminated
		// strings. Appending '' fixes it so that it doesn't generate
		// a warning on each access in debug php.
		wfSuppressWarnings();
		$key = $this->mainCollator->getSortKey( $string ) . '';
		wfRestoreWarnings();
		return $key;
	}

	function getPrimarySortKey( $string ) {
		wfSuppressWarnings();
		$key = $this->primaryCollator->getSortKey( $string ) . '';
		wfRestoreWarnings();
		return $key;
	}

	function getFirstLetter( $string ) {
		$string = strval( $string );
		if ( $string === '' ) {
			return '';
		}

		// Check for CJK
		$firstChar = mb_substr( $string, 0, 1, 'UTF-8' );
		if ( ord( $firstChar ) > 0x7f && self::isCjk( UtfNormal\Utils::utf8ToCodepoint( $firstChar ) ) ) {
			return $firstChar;
		}

		$sortKey = $this->getPrimarySortKey( $string );

		// Do a binary search to find the correct letter to sort under
		$min = ArrayUtils::findLowerBound(
			array( $this, 'getSortKeyByLetterIndex' ),
			$this->getFirstLetterCount(),
			'strcmp',
			$sortKey );

		if ( $min === false ) {
			// Before the first letter
			return '';
		}
		return $this->getLetterByIndex( $min );
	}

	function getFirstLetterData() {
		if ( $this->firstLetterData !== null ) {
			return $this->firstLetterData;
		}

		$cache = wfGetCache( CACHE_ANYTHING );
		$cacheKey = wfMemcKey(
			'first-letters',
			$this->locale,
			$this->digitTransformLanguage->getCode(),
			self::getICUVersion()
		);
		$cacheEntry = $cache->get( $cacheKey );

		if ( $cacheEntry && isset( $cacheEntry['version'] )
			&& $cacheEntry['version'] == self::FIRST_LETTER_VERSION
		) {
			$this->firstLetterData = $cacheEntry;
			return $this->firstLetterData;
		}

		// Generate data from serialized data file

		if ( isset( self::$tailoringFirstLetters[$this->locale] ) ) {
			$letters = wfGetPrecompiledData( "first-letters-root.ser" );
			// Append additional characters
			$letters = array_merge( $letters, self::$tailoringFirstLetters[$this->locale] );
			// Remove unnecessary ones, if any
			if ( isset( self::$tailoringFirstLetters['-' . $this->locale] ) ) {
				$letters = array_diff( $letters, self::$tailoringFirstLetters['-' . $this->locale] );
			}
			// Apply digit transforms
			$digits = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' );
			$letters = array_diff( $letters, $digits );
			foreach ( $digits as $digit ) {
				$letters[] = $this->digitTransformLanguage->formatNum( $digit, true );
			}
		} else {
			$letters = wfGetPrecompiledData( "first-letters-{$this->locale}.ser" );
			if ( $letters === false ) {
				throw new MWException( "MediaWiki does not support ICU locale " .
					"\"{$this->locale}\"" );
			}
		}

		// Sort the letters.
		//
		// It's impossible to have the precompiled data file properly sorted,
		// because the sort order changes depending on ICU version. If the
		// array is not properly sorted, the binary search will return random
		// results.
		//
		// We also take this opportunity to remove primary collisions.
		$letterMap = array();
		foreach ( $letters as $letter ) {
			$key = $this->getPrimarySortKey( $letter );
			if ( isset( $letterMap[$key] ) ) {
				// Primary collision
				// Keep whichever one sorts first in the main collator
				if ( $this->mainCollator->compare( $letter, $letterMap[$key] ) < 0 ) {
					$letterMap[$key] = $letter;
				}
			} else {
				$letterMap[$key] = $letter;
			}
		}
		ksort( $letterMap, SORT_STRING );
		// Remove duplicate prefixes. Basically if something has a sortkey
		// which is a prefix of some other sortkey, then it is an
		// expansion and probably should not be considered a section
		// header.
		//
		// For example 'þ' is sometimes sorted as if it is the letters
		// 'th'. Other times it is its own primary element. Another
		// example is '₨'. Sometimes its a currency symbol. Sometimes it
		// is an 'R' followed by an 's'.
		//
		// Additionally an expanded element should always sort directly
		// after its first element due to they way sortkeys work.
		//
		// UCA sortkey elements are of variable length but no collation
		// element should be a prefix of some other element, so I think
		// this is safe. See:
		// * https://ssl.icu-project.org/repos/icu/icuhtml/trunk/design/collation/ICU_collation_design.htm
		// * http://site.icu-project.org/design/collation/uca-weight-allocation
		//
		// Additionally, there is something called primary compression to
		// worry about. Basically, if you have two primary elements that
		// are more than one byte and both start with the same byte then
		// the first byte is dropped on the second primary. Additionally
		// either \x03 or \xFF may be added to mean that the next primary
		// does not start with the first byte of the first primary.
		//
		// This shouldn't matter much, as the first primary is not
		// changed, and that is what we are comparing against.
		//
		// tl;dr: This makes some assumptions about how icu implements
		// collations. It seems incredibly unlikely these assumptions
		// will change, but nonetheless they are assumptions.

		$prev = false;
		$duplicatePrefixes = array();
		foreach ( $letterMap as $key => $value ) {
			// Remove terminator byte. Otherwise the prefix
			// comparison will get hung up on that.
			$trimmedKey = rtrim( $key, "\0" );
			if ( $prev === false || $prev === '' ) {
				$prev = $trimmedKey;
				// We don't yet have a collation element
				// to compare against, so continue.
				continue;
			}

			// Due to the fact the array is sorted, we only have
			// to compare with the element directly previous
			// to the current element (skipping expansions).
			// An element "X" will always sort directly
			// before "XZ" (Unless we have "XY", but we
			// do not update $prev in that case).
			if ( substr( $trimmedKey, 0, strlen( $prev ) ) === $prev ) {
				$duplicatePrefixes[] = $key;
				// If this is an expansion, we don't want to
				// compare the next element to this element,
				// but to what is currently $prev
				continue;
			}
			$prev = $trimmedKey;
		}
		foreach ( $duplicatePrefixes as $badKey ) {
			wfDebug( "Removing '{$letterMap[$badKey]}' from first letters.\n" );
			unset( $letterMap[$badKey] );
			// This code assumes that unsetting does not change sort order.
		}
		$data = array(
			'chars' => array_values( $letterMap ),
			'keys' => array_keys( $letterMap ),
			'version' => self::FIRST_LETTER_VERSION,
		);

		// Reduce memory usage before caching
		unset( $letterMap );

		// Save to cache
		$this->firstLetterData = $data;
		$cache->set( $cacheKey, $data, 86400 * 7 /* 1 week */ );
		return $data;
	}

	function getLetterByIndex( $index ) {
		if ( $this->firstLetterData === null ) {
			$this->getFirstLetterData();
		}
		return $this->firstLetterData['chars'][$index];
	}

	function getSortKeyByLetterIndex( $index ) {
		if ( $this->firstLetterData === null ) {
			$this->getFirstLetterData();
		}
		return $this->firstLetterData['keys'][$index];
	}

	function getFirstLetterCount() {
		if ( $this->firstLetterData === null ) {
			$this->getFirstLetterData();
		}
		return count( $this->firstLetterData['chars'] );
	}

	static function isCjk( $codepoint ) {
		foreach ( self::$cjkBlocks as $block ) {
			if ( $codepoint >= $block[0] && $codepoint <= $block[1] ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Return the version of ICU library used by PHP's intl extension,
	 * or false when the extension is not installed of the version
	 * can't be determined.
	 *
	 * The constant INTL_ICU_VERSION this function refers to isn't really
	 * documented. It is available since PHP 5.3.7 (see PHP bug 54561).
	 * This function will return false on older PHPs.
	 *
	 * @since 1.21
	 * @return string|bool
	 */
	static function getICUVersion() {
		return defined( 'INTL_ICU_VERSION' ) ? INTL_ICU_VERSION : false;
	}

	/**
	 * Return the version of Unicode appropriate for the version of ICU library
	 * currently in use, or false when it can't be determined.
	 *
	 * @since 1.21
	 * @return string|bool
	 */
	static function getUnicodeVersionForICU() {
		$icuVersion = IcuCollation::getICUVersion();
		if ( !$icuVersion ) {
			return false;
		}

		$versionPrefix = substr( $icuVersion, 0, 3 );
		// Source: http://site.icu-project.org/download
		$map = array(
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
		);

		if ( isset( $map[$versionPrefix] ) ) {
			return $map[$versionPrefix];
		} else {
			return false;
		}
	}
}

/**
 * Workaround for the lack of support of Sorani Kurdish / Central Kurdish language ('ckb') in ICU.
 *
 * Uses the same collation rules as Persian / Farsi ('fa'), but different characters for digits.
 */
class CollationCkb extends IcuCollation {
	function __construct() {
		// This will set $locale and collators, which affect the actual sorting order
		parent::__construct( 'fa' );
		// Override the 'fa' language set by parent constructor, which affects #getFirstLetterData()
		$this->digitTransformLanguage = Language::factory( 'ckb' );
	}
}

/**
 * Workaround for incorrect collation of Estonian language ('et') in ICU (bug 54168).
 *
 * 'W' and 'V' should not be considered the same letter for the purposes of collation in modern
 * Estonian. We work around this by replacing 'W' and 'w' with 'ᴡ' U+1D21 'LATIN LETTER SMALL
 * CAPITAL W' for sortkey generation, which is collated like 'W' and is not tailored to have the
 * same primary weight as 'V' in Estonian.
 */
class CollationEt extends IcuCollation {
	function __construct() {
		parent::__construct( 'et' );
	}

	private static function mangle( $string ) {
		return str_replace(
			array( 'w', 'W' ),
			'ᴡ', // U+1D21 'LATIN LETTER SMALL CAPITAL W'
			$string
		);
	}

	private static function unmangle( $string ) {
		// Casing data is lost…
		return str_replace(
			'ᴡ', // U+1D21 'LATIN LETTER SMALL CAPITAL W'
			'W',
			$string
		);
	}

	function getSortKey( $string ) {
		return parent::getSortKey( self::mangle( $string ) );
	}

	function getFirstLetter( $string ) {
		return self::unmangle( parent::getFirstLetter( self::mangle( $string ) ) );
	}
}
