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
	static $instance;

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
	 * @param $collationName string
	 * @return Collation
	 */
	static function factory( $collationName ) {
		switch( $collationName ) {
			case 'uppercase':
				return new UppercaseCollation;
			case 'uppercase-sv':
				return new UppercaseSvCollation;
			case 'identity':
				return new IdentityCollation;
			case 'uca-default':
				return new IcuCollation( 'root' );
			default:
				$match = array();
				if ( preg_match( '/^uca-([a-z-]+)$/', $collationName, $match ) ) {
					return new IcuCollation( $match[1] );
				}

				# Provide a mechanism for extensions to hook in.
				$collationObject = null;
				wfRunHooks( 'Collation::factory', array( $collationName, &$collationObject ) );

				if ( $collationObject instanceof Collation ) {
					return $collationObject;
				}

				// If all else fails...
				throw new MWException( __METHOD__.": unknown collation type \"$collationName\"" );
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
	var $lang;
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
 * Like UppercaseCollation but swaps Ä and Æ.
 *
 * This provides an ordering suitable for Swedish.
 * @author Lejonel
 */
class UppercaseSvCollation extends UppercaseCollation {

	/* Unicode code point order is ÄÅÆÖ, Swedish order is ÅÄÖ and Æ is often sorted as Ä.
	 * Replacing Ä for Æ should give a better collation. */
	function getSortKey( $string ) {
		$uppercase = $this->lang->uc( $string );
		return strtr( $uppercase, array( 'Ä' => 'Æ', 'Æ' => 'Ä' ) );
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
	var $primaryCollator, $mainCollator, $locale;
	var $firstLetterData;

	/**
	 * Unified CJK blocks.
	 *
	 * The same definition of a CJK block must be used for both Collation and
	 * generateCollationData.php. These blocks are omitted from the first
	 * letter data, as an optimisation measure and because the default UCA table
	 * is pretty useless for sorting Chinese text anyway. Japanese and Korean
	 * blocks are not included here, because they are smaller and more useful.
	 */
	static $cjkBlocks = array(
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
	 * Additional characters (or character groups) to be considered first-letters
	 *
	 * Generated based on the primary level of Unicode collation tailorings
	 * available at http://developer.mimer.com/charts/tailorings.htm .
	 *
	 * Empty arrays are intended; this signifies that the data for the language is
	 * available and that there are, in fact, no additional letters to consider.
	 */
	static $tailoringFirstLetters = array(
		// Verified by native speakers
		'pl' => array( "Ą", "Ć", "Ę", "Ł", "Ń", "Ó", "Ś", "Ź", "Ż" ),
		'fi' => array( "Å", "Ä", "Ö" ),
		// Not verified, but likely correct
		'af' => array(),
		'ast' => array( "CH", "LL", "Ñ" ),
		'az' => array( "Ç", "Ə", "Ğ", "İ", "Ö", "Ş", "Ü" ),
		'be' => array( "Ё" ),
		'bg' => array(),
		'br' => array( "CH", "C'H" ),
		'bs' => array( "Č", "Ć", "DŽ", "Đ", "LJ", "NJ", "Š", "Ž" ),
		'ca' => array(),
		'co' => array(),
		'cs' => array( "Č", "CH", "Ř", "Š", "Ž" ),
		'cy' => array( "CH", "DD", "FF", "NG", "LL", "PH", "RH", "TH" ),
		'da' => array( "Æ", "Ø", "Å" ),
		'de' => array(),
		'dsb' => array( "Č", "Ć", "DŹ", "Ě", "CH", "Ł", "Ń", "Ŕ", "Š", "Ś", "Ž", "Ź" ),
		'el' => array(),
		'en' => array(),
		'eo' => array( "Ĉ", "Ĝ", "Ĥ", "Ĵ", "Ŝ", "Ŭ" ),
		'es' => array( "Ñ" ),
		'et' => array( "Š", "Ž", "Õ", "Ä", "Ö", "Ü" ),
		'eu' => array( "Ñ" ),
		'fo' => array( "Á", "Ð", "Í", "Ó", "Ú", "Ý", "Æ", "Ø", "Å" ),
		'fr' => array(),
		'fur' => array( "À", "Á", "Â", "È", "Ì", "Ò", "Ù" ),
		'fy' => array(),
		'ga' => array(),
		'gd' => array(),
		'gl' => array( "CH", "LL", "Ñ" ),
		'hr' => array( "Č", "Ć", "DŽ", "Đ", "LJ", "NJ", "Š", "Ž" ),
		'hsb' => array( "Č", "DŹ", "Ě", "CH", "Ł", "Ń", "Ř", "Š", "Ć", "Ž" ),
		'hu' => array( "CS", "DZ", "DZS", "GY", "LY", "NY", "Ö", "SZ", "TY", "Ü", "ZS" ),
		'is' => array( "Á", "Ð", "É", "Í", "Ó", "Ú", "Ý", "Þ", "Æ", "Ö", "Å" ),
		'it' => array(),
		'kk' => array( "Ү", "І" ),
		'kl' => array( "Æ", "Ø", "Å" ),
		'ku' => array( "Ç", "Ê", "Î", "Ş", "Û" ),
		'ky' => array( "Ё" ),
		'la' => array(),
		'lb' => array(),
		'lt' => array( "Č", "Š", "Ž" ),
		'lv' => array( "Č", "Ģ", "Ķ", "Ļ", "Ņ", "Š", "Ž" ),
		'mk' => array(),
		'mo' => array( "Ă", "Â", "Î", "Ş", "Ţ" ),
		'mt' => array( "Ċ", "Ġ", "GĦ", "Ħ", "Ż" ),
		'nl' => array(),
		'no' => array( "Æ", "Ø", "Å" ),
		'oc' => array(),
		'pt' => array(),
		'rm' => array(),
		'ro' => array( "Ă", "Â", "Î", "Ş", "Ţ" ),
		'ru' => array(),
		'rup' => array( "Ă", "Â", "Î", "Ľ", "Ń", "Ş", "Ţ" ),
		'sco' => array(),
		'sk' => array( "Ä", "Č", "CH", "Ô", "Š", "Ž" ),
		'sl' => array( "Č", "Š", "Ž" ),
		'smn' => array( "Á", "Č", "Đ", "Ŋ", "Š", "Ŧ", "Ž", "Æ", "Ø", "Å", "Ä", "Ö" ),
		'sq' => array( "Ç", "DH", "Ë", "GJ", "LL", "NJ", "RR", "SH", "TH", "XH", "ZH" ),
		'sr' => array(),
		'sv' => array( "Å", "Ä", "Ö" ),
		'tk' => array( "Ç", "Ä", "Ž", "Ň", "Ö", "Ş", "Ü", "Ý" ),
		'tl' => array( "Ñ", "NG" ), /* 'fil' in the data source */
		'tr' => array( "Ç", "Ğ", "İ", "Ö", "Ş", "Ü" ),
		'tt' => array( "Ә", "Ө", "Ү", "Җ", "Ң", "Һ" ),
		'uk' => array( "Ґ", "Ь" ),
		'uz' => array( "CH", "G'", "NG", "O'", "SH" ),
		'vi' => array( "Ă", "Â", "Đ", "Ê", "Ô", "Ơ", "Ư" ),
	);

	const RECORD_LENGTH = 14;

	function __construct( $locale ) {
		if ( !extension_loaded( 'intl' ) ) {
			throw new MWException( 'An ICU collation was requested, ' .
				'but the intl extension is not available.' );
		}
		$this->locale = $locale;
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
		if ( ord( $firstChar ) > 0x7f
			&& self::isCjk( utf8ToCodepoint( $firstChar ) ) )
		{
			return $firstChar;
		}

		$sortKey = $this->getPrimarySortKey( $string );

		// Do a binary search to find the correct letter to sort under
		$min = $this->findLowerBound(
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
		$cacheKey = wfMemcKey( 'first-letters', $this->locale );
		$cacheEntry = $cache->get( $cacheKey );

		if ( $cacheEntry ) {
			$this->firstLetterData = $cacheEntry;
			return $this->firstLetterData;
		}

		// Generate data from serialized data file

		if ( isset ( self::$tailoringFirstLetters[$this->locale] ) ) {
			$letters = wfGetPrecompiledData( "first-letters-root.ser" );
			$letters = array_merge( $letters, self::$tailoringFirstLetters[$this->locale] );
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
		$data = array(
			'chars' => array_values( $letterMap ),
			'keys' => array_keys( $letterMap )
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

	/**
	 * Do a binary search, and return the index of the largest item that sorts
	 * less than or equal to the target value.
	 *
	 * @param $valueCallback array A function to call to get the value with
	 *     a given array index.
	 * @param $valueCount int The number of items accessible via $valueCallback,
	 *     indexed from 0 to $valueCount - 1
	 * @param $comparisonCallback array A callback to compare two values, returning
	 *     -1, 0 or 1 in the style of strcmp().
	 * @param $target string The target value to find.
	 *
	 * @return int|bool The item index of the lower bound, or false if the target value
	 *     sorts before all items.
	 */
	function findLowerBound( $valueCallback, $valueCount, $comparisonCallback, $target ) {
		if ( $valueCount === 0 ) {
			return false;
		}

		$min = 0;
		$max = $valueCount;
		do {
			$mid = $min + ( ( $max - $min ) >> 1 );
			$item = call_user_func( $valueCallback, $mid );
			$comparison = call_user_func( $comparisonCallback, $target, $item );
			if ( $comparison > 0 ) {
				$min = $mid;
			} elseif ( $comparison == 0 ) {
				$min = $mid;
				break;
			} else {
				$max = $mid;
			}
		} while ( $min < $max - 1 );

		if ( $min == 0 ) {
			$item = call_user_func( $valueCallback, $min );
			$comparison = call_user_func( $comparisonCallback, $target, $item );
			if ( $comparison < 0 ) {
				// Before the first item
				return false;
			}
		}
		return $min;
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
	 * @return string|false
	 */
	static function getICUVersion() {
		return defined( 'INTL_ICU_VERSION' ) ? INTL_ICU_VERSION : false;
	}

	/**
	 * Return the version of Unicode appropriate for the version of ICU library
	 * currently in use, or false when it can't be determined.
	 *
	 * @since 1.21
	 * @return string|false
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
