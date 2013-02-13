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
	static $instances = array();

	/**
	 * @deprecated in 1.21; use Collation::getInstance() instead
	 * @return Collation
	 */
	static function singleton() {
		global $wgCategoryCollations;
		wfDeprecated( __METHOD__, '1.21' );

		return self::getInstance( $wgCategoryCollations[0] );
	}

	/**
	 * @since 1.21
	 * @return Collation
	 */
	static function getInstance( $name ) {
		global $wgCategoryCollations;

		if ( !isset( self::$instances[$name] ) ) {
			if ( in_array( $name, $wgCategoryCollations ) ) {
				self::$instances[$name] = self::factory( $name );
			} else {
				throw new MWException( __METHOD__.": invalid collation type \"$name\"" );
			}
		}

		return self::$instances[$name];
	}

	/**
	 * @since 1.21
	 * @return Array
	 */
	static function getInstanceByContext( $name = null, $title = null, $context = null ) {
		global $wgCategoryCollations;

		if ( in_array( $name, $wgCategoryCollations ) ) {
		} elseif ( ( $defaultcollation = wfGetDB( DB_SLAVE )->selectField(
			'page_props', 'pp_value',
			array( 'pp_page' => $title->getArticleId(), 'pp_propname' => 'defaultcollation' ),
			__METHOD__
		) ) !== false && in_array( $defaultcollation, $wgCategoryCollations ) ) {
			$name = $defaultcollation;
		} elseif ( in_array( $context->getUser()->getOption( 'collation' ), $wgCategoryCollations ) ) {
			$name = $context->getUser()->getOption( 'collation' );
		} else {
			$name = $wgCategoryCollations[0];
		}

		return array( $name, self::getInstance( $name ) );
	}

	/**
	 * @since 1.21
	 * @return Array
	 */
	static function getInstances() {
		global $wgCategoryCollations;

		foreach ( $wgCategoryCollations as $name ) {
			if ( !isset( self::$instances[$name] ) ) {
				self::$instances[$name] = self::factory( $name );
			}
		}
		return self::$instances;
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
			case 'identity':
				return new IdentityCollation;
			case 'uca-default':
				return new IcuCollation( 'root' );
			case 'zh-pinyin':
				return new IcuCollation( 'zh@collation=pinyin' );
			case 'zh-stroke':
				return new IcuCollation( 'zh@collation=stroke' );
			case 'zh-stroke-hans':
				return new ConvertedIcuCollation( 'zh@collation=stroke', 'zh', 'zh-hans' );
			case 'zh-stroke-hant':
				return new ConvertedIcuCollation( 'zh@collation=stroke', 'zh', 'zh-hant' );
			case 'zh-zhuyin':
				return new IcuCollation( 'zh@collation=zhuyin' );
			default:
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
	 * Get a valid collation name from user input (matched using magic words)
	 *
	 * @since 1.21
	 * @param $text String: User input (localized collation name)
	 * @return String|bool: A valid collation name, or false for invalid input
	 */
	static function getNameFromText( $text ) {
		global $wgCategoryCollations;

		static $mwArray = null;
		static $collationMap = array();
		if ( !$mwArray ) {
			foreach ( $wgCategoryCollations as $collationName ) {
				// Unluckily magic word names can't contain hyphens.
				// Magic word names used in core, for easier grepping:
				// * collation_uppercase
				// * collation_identity
				// * collation_uca_default
				// * collation_zh_pinyin
				// * collation_zh_stroke
				// * collation_zh_stroke_hans
				// * collation_zh_stroke_hant
				// * collation_zh_zhuyin
				$magicName = 'collation_' . str_replace( '-', '_', $collationName );
				$collationMap[$magicName] = $collationName;
			}
			$mwArray = new MagicWordArray( array_keys( $collationMap ) );
		}

		$magicName = $mwArray->matchStartToEnd( $text );
		if ( $magicName === false ) {
			return false;
		} else {
			return $collationMap[$magicName];
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
		// Always sort Chinese if this is using a Chinese locale.
		// self::isCjk() checks Chinese only though it's called 'CJK'.
		$firstChar = mb_substr( $string, 0, 1, 'UTF-8' );
		$localePieces = explode( '@', $this->locale );
		$localePieces = explode( '-', $localePieces[0] );
		if ( ord( $firstChar ) > 0x7f
			&& $localePieces[0] !== 'zh'
			&& self::isCjk( utf8ToCodepoint( $firstChar ) ) ) 
		{
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
		$cacheKey = wfMemcKey( 'first-letters', $this->locale );
		$cacheEntry = $cache->get( $cacheKey );

		if ( $cacheEntry ) {
			$this->firstLetterData = $cacheEntry;
			return $this->firstLetterData;
		}

		// Generate data from serialized data file

		$letters = wfGetPrecompiledData( "first-letters-{$this->locale}.ser" );
		if ( $letters === false ) {
			throw new MWException( "MediaWiki does not support ICU locale " .
				"\"{$this->locale}\"" );
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
			// Chinese collations don't display real first letters.
			if ( !is_array( $letter ) ) {
				// array( $letterSort, $letterDisplay )
				$letter = array( $letter, $letter );
			}
			$key = $this->getPrimarySortKey( $letter[0] );
			if ( isset( $letterMap[$key] ) ) {
				// Primary collision
				// Keep whichever one sorts first in the main collator
				if ( $this->mainCollator->compare( $letter[0], $letterMap[$key][0] ) < 0 ) {
					$letterMap[$key] = $letter;
				}
			} else {
				$letterMap[$key] = $letter;
			}
		}
		ksort( $letterMap, SORT_STRING );
		$data = array(
			'chars' => array_map( function( $letter ) {
				return $letter[1];
			}, array_values( $letterMap ) ),
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
	 * @deprecated in 1.21; use ArrayUtils::findLowerBound() instead
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
		wfDeprecated( __METHOD__, '1.21' );
		return ArrayUtils::findLowerBound( $valueCallback, $valueCount, $comparisonCallback, $target );
	}

	static function isCjk( $codepoint ) {
		foreach ( self::$cjkBlocks as $block ) {
			if ( $codepoint >= $block[0] && $codepoint <= $block[1] ) {
				return true;
			}
		}
		return false;
	}
}

class ConvertedIcuCollation extends IcuCollation {
	function __construct( $locale, $language, $variant ) {
		parent::__construct( $locale );

		$this->converter = Language::factory( $language )->getConverter();
		$this->variant = $variant;
	}

	function convert( $string ) {
		return $this->converter->translate( $string, $this->variant );
	}

	function getSortKey( $string ) {
		return parent::getSortKey( $this->convert( $string ) );
	}

	function getFirstLetter( $string ) {
		return parent::getFirstLetter( $this->convert( $string ) );
	}
}
