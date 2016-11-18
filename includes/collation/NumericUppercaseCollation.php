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
 * Collation that orders text with numbers "naturally", so that 'Foo 1' < 'Foo 2' < 'Foo 12'.
 *
 * Note that this only works in terms of sequences of digits, and the behavior for decimal fractions
 * or pretty-formatted numbers may be unexpected.
 *
 * Digits will be based on the wiki's content language settings. If
 * you change the content langauge of a wiki you will need to run
 * updateCollation.php --force. Only English (ASCII 0-9) and the
 * localized version will be counted. Localized digits from other languages
 * or weird unicode digit equivalents (e.g. ４, 𝟜, ⓸ , ⁴, etc) will not count.
 *
 * @since 1.28
 */
class NumericUppercaseCollation extends UppercaseCollation {

	/**
	 * @var $digitTransformLang Language How to convert digits (usually $wgContLang)
	 */
	private $digitTransformLang;

	/**
	 * Constructor
	 *
	 * @param $lang Language How to convert digits.
	 *  For example, if given language "my" than ၇ is treated like 7.
	 *
	 * It is expected that usually this is given $wgContLang.
	 */
	public function __construct( Language $lang ) {
		$this->digitTransformLang = $lang;
		parent::__construct();
	}

	public function getSortKey( $string ) {
		$sortkey = parent::getSortKey( $string );
		$sortkey = $this->convertDigits( $sortkey );
		// For each sequence of digits, insert the digit '0' and then the length of the sequence
		// (encoded in two bytes) before it. That's all folks, it sorts correctly now! The '0' ensures
		// correct position (where digits would normally sort), then the length will be compared putting
		// shorter numbers before longer ones; if identical, then the characters will be compared, which
		// generates the correct results for numbers of equal length.
		$sortkey = preg_replace_callback( '/\d+/', function ( $matches ) {
			// Strip any leading zeros
			$number = ltrim( $matches[0], '0' );
			$len = strlen( $number );
			// This allows sequences of up to 65536 numeric characters to be handled correctly. One byte
			// would allow only for 256, which doesn't feel future-proof.
			$prefix = chr( floor( $len / 256 ) ) . chr( $len % 256 );
			return '0' . $prefix . $number;
		}, $sortkey );

		return $sortkey;
	}

	/**
	 * Convert localized digits to english digits.
	 *
	 * based on Language::parseFormattedNumber but without commas.
	 *
	 * @param $string String sortkey to unlocalize digits of
	 * @return String Sortkey with all localized digits replaced with ASCII digits.
	 */
	private function convertDigits( $string ) {
		$table = $this->digitTransformLang->digitTransformTable();
		if ( $table ) {
			$table = array_filter( $table );
			$flipped = array_flip( $table );
			// Some languages seem to also have commas in this table.
			$flipped = array_filter( $flipped, 'is_numeric' );
			$string = strtr( $string, $flipped );
		}
		return $string;
	}

	public function getFirstLetter( $string ) {
		$convertedString = $this->convertDigits( $string );

		if ( preg_match( '/^\d/', $convertedString ) ) {
			return wfMessage( 'category-header-numerals' )
				->numParams( 0, 9 )
				->text();
		} else {
			return parent::getFirstLetter( $string );
		}
	}
}
