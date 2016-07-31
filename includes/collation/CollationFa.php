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
 * Temporary workaround for incorrect collation of Persian language ('fa') in ICU (bug T139110).
 * This only works when these letters show up at the beginning, it won't fix the sorting otherwise.
 *
 * 'ا' and 'و' should not be considered the same letter for the purposes of collation in Persian.
 *
 * @since 1.28
 */
class CollationFa extends IcuCollation {
	public function __construct() {
		parent::__construct( 'fa' );
	}

	public function getPrimarySortKey( $string ) {
		if ( mb_substr( $string, 0, 1 ) === 'و' ) {
			return hex2bin( "26b0" );
		} elseif ( mb_substr( $string, 0, 1 ) === 'ا' ) {
			return hex2bin( "261a" );
		}
		return parent::getPrimarySortKey( $string );
	}
}
