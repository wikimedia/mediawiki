<?php
/**
 * Scots Gaelic (Gàidhlig) specific code.
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
 * @author Raimond Spekking
 * @author Niklas Laxström
 * @ingroup Language
 */

/**
 * Scots Gaelic (Gàidhlig)
 *
 * @ingroup Language
 */
class LanguageGd extends Language {

	/**
	 * Plural form transformations
	 * Based on this discussion: http://translatewiki.net/wiki/Thread:Support/New_plural_rules_for_Scots_Gaelic_(gd)
	 *
	 * $forms[0] - 1
	 * $forms[1] - 2
	 * $forms[2] - 11
	 * $forms[3] - 12
	 * $forms[4] - 3-10, 13-19
	 * $forms[5] - 0, 20, rest
	 *
	 * @param $count int
	 * @param $forms array
	 *
	 * @return string
	 */
	function convertPlural( $count, $forms ) {
		if ( !count( $forms ) ) { return ''; }
		$forms = $this->preConvertPlural( $forms, 6 );

		$count = abs( $count );
		if ( $count == 1 ) {
			return $forms[0];
		} elseif ( $count == 2 ) {
			return $forms[1];
		} elseif ( $count == 11 ) {
			return $forms[2];
		} elseif ( $count == 12 ) {
			return $forms[3];
		} elseif ( ($count >= 3 && $count <= 10) || ($count >= 13 && $count <= 19) ) {
			return $forms[4];
		} else {
			return $forms[5];
		}
	}
}
