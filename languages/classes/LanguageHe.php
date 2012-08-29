<?php
/**
 * Hebrew (עברית) specific code.
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
 * @author Rotem Liss
 * @ingroup Language
 */

/**
 * Hebrew (עברית)
 *
 * @ingroup Language
 */
class LanguageHe extends Language {

	/**
	 * Convert grammar forms of words.
	 *
	 * Available cases:
	 * "prefixed" (or "תחילית") - when the word has a prefix
	 *
	 * @param $word String: the word to convert
	 * @param $case String: the case
	 *
	 * @return string
	 */
	public function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset( $wgGrammarForms['he'][$case][$word] ) ) {
			return $wgGrammarForms['he'][$case][$word];
		}

		switch ( $case ) {
			case 'prefixed':
			case 'תחילית':
				# Duplicate the "Waw" if prefixed
				if ( substr( $word, 0, 2 ) == "ו" && substr( $word, 0, 4 ) != "וו" ) {
					$word = "ו" . $word;
				}

				# Remove the "He" if prefixed
				if ( substr( $word, 0, 2 ) == "ה" ) {
					$word = substr( $word, 2 );
				}

				# Add a hyphen (maqaf) if non-Hebrew letters
				if ( substr( $word, 0, 2 ) < "א" || substr( $word, 0, 2 ) > "ת" ) {
					$word = "־" . $word;
				}
		}

		return $word;
	}

}
