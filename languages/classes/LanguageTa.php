<?php
/**
 * Tamil (தமிழ்) specific code.
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
 * @author Srikanth L
 * @ingroup Language
 */

/**
 * Tamil (தமிழ்)
 *
 * @ingroup Language
 */
class LanguageTa extends Language {

	/**
	 * Convert grammar forms of words.
	 *
	 * Available cases:
	 * "suffix" (or "விகுதி") - when the word has a suffix
	 *
	 * @param $word String: the word to convert
	 * @param $case String: the case
	 *
	 * @return string
	 */
	public function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset( $wgGrammarForms['ta'][$case][$word] ) ) {
			return $wgGrammarForms['ta'][$case][$word];
		}

		switch ( $case ) {
			case 'ISuffix':
			case 'ஐவிகுதி':
				# Get last character
				echo substr( $word, -1 )
				switch (substr( $word, -1 )) {
					case 'இ':
					case 'ஈ':
					case 'ஐ':
					case 'ி':
					case 'ீ':
					case 'ை':
						$word = $word . "யை";
					default:
						$word = $word . "வை";
				}
		}

		return $word;
	}

}
