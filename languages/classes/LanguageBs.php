<?php
/**
 * Bosnian (bosanski) specific code.
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
 * Bosnian (bosanski)
 *
 * @ingroup Language
 */
class LanguageBs extends Language {

	/**
	 * @param $count int
	 * @param $forms array
	 * @return string
	 */
	function convertPlural( $count, $forms ) {
		if ( !count( $forms ) ) { return ''; }
		$forms = $this->preConvertPlural( $forms, 3 );

		// @todo FIXME: CLDR defines 4 plural forms instead of 3. Plural for decimals is missing.
		//        http://unicode.org/repos/cldr-tmp/trunk/diff/supplemental/language_plural_rules.html
		if ( $count > 10 && floor( ( $count % 100 ) / 10 ) == 1 ) {
			return $forms[2];
		} else {
			switch ( $count % 10 ) {
				case 1:  return $forms[0];
				case 2:
				case 3:
				case 4:  return $forms[1];
				default: return $forms[2];
			}
		}
	}

	/**
	 * Convert from the nominative form of a noun to some other case
	 * Invoked with {{GRAMMAR:case|word}}
	 *
	 * Cases: genitiv, dativ, akuzativ, vokativ, instrumental, lokativ
	 *
	 * @param $word string
	 * @param $case string
	 *
	 * @return string
	 */
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset( $wgGrammarForms['bs'][$case][$word] ) ) {
			return $wgGrammarForms['bs'][$case][$word];
		}
		switch ( $case ) {
			case 'instrumental': # instrumental
				$word = 's ' . $word;
			break;
			case 'lokativ': # locative
				$word = 'o ' . $word;
			break;
		}

		return $word; # this will return the original value for 'nominativ' (nominative) and all undefined case values
	}
}
