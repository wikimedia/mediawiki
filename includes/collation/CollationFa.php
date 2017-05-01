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
 * All of the following will be considered separate letters for category headings in Persian:
 *  - Characters 'و' 'ا' (often appear at the beginning of words)
 *  - Characters 'ٲ' 'ٳ' (may appear at the beginning of words in loanwords)
 *  - Characters 'ء' 'ئ' (don't appear at the beginning of words, but it's easier to implement)
 *
 * @since 1.29
 */
class CollationFa extends IcuCollation {
	private $tertiaryCollator;

	public function __construct() {
		parent::__construct( 'fa' );
		$this->tertiaryCollator = Collator::create( 'fa' );
	}

	public function getPrimarySortKey( $string ) {
		$primary = parent::getPrimarySortKey( $string );
		// We have to use a tertiary sortkey for everything with the primary sortkey of 2627.
		// Otherwise, the "Remove duplicate prefixes" logic in IcuCollation would remove them.
		// This matches sortkeys for the following characters: ء ئ ا و ٲ ٳ
		if ( substr( $primary, 0, 2 ) === "\x26\x27" ) {
			wfDebug( "Using tertiary sortkey for '$string'\n" );
			return $this->tertiaryCollator->getSortKey( $string );
		}
		return $primary;
	}
}
