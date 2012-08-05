<?php
/**
 * Latvian (Latviešu) specific code.
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
 * @copyright Copyright © 2006, Niklas Laxström
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 * @ingroup Language
 */

/**
 * Latvian (Latviešu)
 *
 * @ingroup Language
 */
class LanguageLv extends Language {
	/**
	 * Plural form transformations. Using the first form for words with the last digit 1, but not for words with the last digits 11, and the second form for all the others.
	 *
	 * Example: {{plural:{{NUMBEROFARTICLES}}|article|articles}}
	 *
	 * @param $count Integer
	 * @param $forms Array
	 * @return String
	 */
	function convertPlural( $count, $forms ) {
		if ( !count( $forms ) ) { return ''; }

		// @todo FIXME: CLDR defines 3 plural forms instead of 2.  Form for 0 is missing.
		//        http://unicode.org/repos/cldr-tmp/trunk/diff/supplemental/language_plural_rules.html#lv
		$forms = $this->preConvertPlural( $forms, 2 );

		return ( ( $count % 10 == 1 ) && ( $count % 100 != 11 ) ) ? $forms[0] : $forms[1];
	}
}
