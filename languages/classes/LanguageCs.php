<?php
/**
 * Czech (čeština [subst.], český [adj.], česky [adv.]) specific code.
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
 * Czech (čeština [subst.], český [adj.], česky [adv.])
 *
 * @ingroup Language
 */
class LanguageCs extends Language {

	/**
	 * Plural transformations
	 * Invoked by putting
	 * {{plural:count|form1|form2-4|form0,5+}} for two forms plurals
	 * {{plural:count|form1|form0,2+}} for single form plurals
	 * in a message
	 * @param $count int
	 * @param $forms array
	 * @return string
	 */
	function convertPlural( $count, $forms ) {
		if ( !count( $forms ) ) { return ''; }
		$forms = $this->preConvertPlural( $forms, 3 );

		switch ( $count ) {
			case 1:
				return $forms[0];
			case 2:
			case 3:
			case 4:
				return $forms[1];
			default:
				return $forms[2];
		}
	}
}
