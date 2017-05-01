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
 * Workaround for incorrect collation of Estonian language ('et') in ICU (T56168).
 *
 * 'W' and 'V' should not be considered the same letter for the purposes of collation in modern
 * Estonian. We work around this by replacing 'W' and 'w' with 'ᴡ' U+1D21 'LATIN LETTER SMALL
 * CAPITAL W' for sortkey generation, which is collated like 'W' and is not tailored to have the
 * same primary weight as 'V' in Estonian.
 *
 * @since 1.24
 */
class CollationEt extends IcuCollation {
	public function __construct() {
		parent::__construct( 'et' );
	}

	private static function mangle( $string ) {
		return str_replace(
			[ 'w', 'W' ],
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

	public function getSortKey( $string ) {
		return parent::getSortKey( self::mangle( $string ) );
	}

	public function getFirstLetter( $string ) {
		return self::unmangle( parent::getFirstLetter( self::mangle( $string ) ) );
	}
}
