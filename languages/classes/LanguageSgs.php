<?php
/**
 * Samogitian (Žemaitėška) specific code.
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
 * @author Niklas Laxström
 * @ingroup Language
 */

/**
 * Samogitian (Žemaitėška)
 *
 * @ingroup Language
 */
class LanguageSgs extends Language {

	/**
	 * @param $count int
	 * @param $forms array
	 * @return string
	 */
	function convertPlural( $count, $forms ) {
		if ( !count( $forms ) ) { return ''; }
		$forms = $this->preConvertPlural( $forms, 4 );

		$count = abs( $count );
		if ( $count == 0 || ( $count % 100 === 0 || ( $count % 100 >= 10 && $count % 100 < 20 ) ) ) {
			return $forms[2];
		} elseif ( $count % 10 === 1 ) {
			return $forms[0];
		} elseif ( $count % 10 === 2 ) {
			return $forms[1];
		} else {
			return $forms[3];
		}
	}
}
