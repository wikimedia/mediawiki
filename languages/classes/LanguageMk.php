<?php
/**
 * Macedonian (Македонски) specific code.
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
 * Macedonian (Македонски)
 *
 * @ingroup Language
 */
class LanguageMk extends Language {
	/**
	 * Plural forms per
	 * http://unicode.org/repos/cldr-tmp/trunk/diff/supplemental/language_plural_rules.html#mk
	 *
	 * @param $count int
	 * @param $forms array
	 *
	 * @return string
	 */
	function convertPlural( $count, $forms ) {
		if ( !count( $forms ) ) { return ''; }
		$forms = $this->preConvertPlural( $forms, 2 );
		// TODO CLDR defines forms[0] for n != 11 and not for n%100 !== 11
		if ( $count % 10 === 1 && $count % 100 !== 11 ) {
			return $forms[0];
		} else {
			return $forms[1];
		}
	}
}
