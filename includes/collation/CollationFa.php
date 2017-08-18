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
 * Temporary workaround for incorrect collation of Persian language ('fa') in ICU 52 (bug T139110).
 *
 * Replace with other letters that appear in an okish spot in the alphabet
 *
 *  - Characters 'و' 'ا' (often appear at the beginning of words)
 *  - Characters 'ٲ' 'ٳ' (may appear at the beginning of words in loanwords)
 *
 * @since 1.29
 */
class CollationFa extends IcuCollation {

	// Really hacky - replace with stuff from other blocks.
	private $override = [
		// U+0627 ARABIC LETTER ALEF => U+0623 ARABIC LETTER ALEF WITH HAMZA ABOVE
		"\xd8\xa7" => "\xd8\xa3",
		// U+0648 ARABIC LETTER WAW => U+0649 ARABIC LETTER ALEF MAKSURA
		"\xd9\x88" => "\xd9\x89",
		// U+0672 ARABIC LETTER ALEF WITH WAVY HAMZA ABOVE => U+F3001 (private use area)
		"\xd9\xb2" => "\xF3\xB3\x80\x81",
		// U+0673 ARABIC LETTER ALEF WITH WAVY HAMZA BELOW => U+F3002 (private use area)
		"\xd9\xb3" => "\xF3\xB3\x80\x82",
	];

	public function __construct() {
		parent::__construct( 'fa' );
	}

	public function getSortKey( $string ) {
		$modified = strtr( $string, $this->override );
		return parent::getSortKey( $modified );
	}

	public function getFirstLetter( $string ) {
		if ( isset( $this->override[substr( $string, 0, 2 )] ) ) {
			return substr( $string, 0, 2 );
		}
		return parent::getFirstLetter( $string );
	}
}
