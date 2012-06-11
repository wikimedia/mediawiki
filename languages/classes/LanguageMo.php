<?php
/**
 * Moldavian (Молдовеняскэ) specific code.
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
 * Moldavian (Молдовеняскэ)
 *
 * @ingroup Language
 */
class LanguageMo extends Language {

	/**
	 * @param $count int
	 * @param $forms array
	 * @return string
	 */
	function convertPlural( $count, $forms ) {
		// Plural rules per
		// http://unicode.org/repos/cldr-tmp/trunk/diff/supplemental/language_plural_rules.html#mo
		// Identical to Romanian (ro).
		if ( !count( $forms ) ) { return ''; }

		$forms = $this->preConvertPlural( $forms, 3 );

		if ( $count == 1 ) {
			$index = 0;
		} elseif ( $count == 0 || ( $count % 100 > 0 && $count % 100 < 20 ) ) {
			$index = 1;
		} else {
			$index = 2;
		}
		return $forms[$index];
	}
}
