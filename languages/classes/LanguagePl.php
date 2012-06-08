<?php
/**
 * Polish (polski) specific code.
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
 * @ingroup Language
 */

/**
 * Polish (polski)
 *
 * @ingroup Language
 */
class LanguagePl extends Language {

	/**
	 * @param $count string
	 * @param $forms array
	 * @return string
	 */
	function convertPlural( $count, $forms ) {
		if ( !count( $forms ) ) { return ''; }
		$forms = $this->preConvertPlural( $forms, 3 );
		$count = abs( $count );
		if ( $count == 1 ) {
			return $forms[0]; // singular
		}
		switch ( $count % 10 ) {
			case 2:
			case 3:
			case 4:
				if ( $count / 10 % 10 != 1 ) {
					return $forms[1]; // plural
				}
			default:
				return $forms[2];   // plural genitive
		}
	}

	/**
	 * @param $_ string
	 * @return string
	 */
	function commafy( $_ ) {
		if ( !preg_match( '/^\-?\d{1,4}(\.\d+)?$/', $_ ) ) {
			return strrev( (string)preg_replace( '/(\d{3})(?=\d)(?!\d*\.)/', '$1,', strrev( $_ ) ) );
		} else {
			return $_;
		}
	}
}
