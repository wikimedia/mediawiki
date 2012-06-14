<?php
/**
 * Belarusian in Taraškievica orthography (Беларуская тарашкевіца) specific code.
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
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @license http://www.gnu.org/copyleft/fdl.html GNU Free Documentation License
 * @ingroup Language
 */

/**
 * Belarusian in Taraškievica orthography (Беларуская тарашкевіца)
 *
 * @ingroup Language
 * @see http://be-x-old.wikipedia.org/wiki/Project_talk:LanguageBe_tarask.php
 */
class LanguageBe_tarask extends Language {
	/**
	 * Plural form transformations
	 *
	 * $wordform1 - singular form (for 1, 21, 31, 41...)
	 * $wordform2 - plural form (for 2, 3, 4, 22, 23, 24, 32, 33, 34...)
	 * $wordform3 - plural form (for 0, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 25, 26...)
	 */

	/**
	 * @param $count int
	 * @param $forms array
	 *
	 * @return string
	 */
	function convertPlural( $count, $forms ) {
		if ( !count( $forms ) ) { return ''; }

		// If the actual number is not mentioned in the expression, then just two forms are enough:
		// singular for $count == 1
		// plural   for $count != 1
		// For example, "This user belongs to {{PLURAL:$1|one group|several groups}}."
		if ( count( $forms ) === 2 ) return $count == 1 ? $forms[0] : $forms[1];

		// @todo FIXME: CLDR defines 4 plural forms instead of 3
		//        http://unicode.org/repos/cldr-tmp/trunk/diff/supplemental/language_plural_rules.html
		$forms = $this->preConvertPlural( $forms, 3 );

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
	 * The Belarusian language uses apostrophe sign,
	 * but the characters used for this could be both U+0027 and U+2019.
	 * This function unifies apostrophe sign in search index values
	 * to enable seach using both apostrophe signs.
	 *
	 * @param $string string
	 *
	 * @return string
	 */
	function normalizeForSearch( $string ) {
		wfProfileIn( __METHOD__ );

		# MySQL fulltext index doesn't grok utf-8, so we
		# need to fold cases and convert to hex

		# Replacing apostrophe sign U+2019 with U+0027
		$s = preg_replace( '/\xe2\x80\x99/', '\'', $string );

		$s = parent::normalizeForSearch( $s );

		wfProfileOut( __METHOD__ );
		return $s;
	}

	/**
	 * Four-digit number should be without group commas (spaces)
	 * So "1 234 567", "12 345" but "1234"
	 *
	 * @param $_ string
	 *
	 * @return string
	 */
	function commafy( $_ ) {
		if ( preg_match( '/^-?\d{1,4}(\.\d*)?$/', $_ ) ) {
			return $_;
		} else {
			return strrev( (string)preg_replace( '/(\d{3})(?=\d)(?!\d*\.)/', '$1,', strrev( $_ ) ) );
		}
	}
}
