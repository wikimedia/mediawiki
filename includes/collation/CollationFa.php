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
		"\xd8\xa7" => "\u{0621}",
		"\xd9\x88" => "\u{0649}",
		"\xd9\xb2" => "\xF3\xB3\x80\x81",
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
