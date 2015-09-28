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
	 * @param string $word The word to convert
	 * @param string $case The case
	 *
	 * @return string
	 */
	public function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset( $wgGrammarForms['he'][$case][$word] ) ) {
			return $wgGrammarForms['he'][$case][$word];
		}

		$grammarTransformations = $this->getGrammarTransformations();

		if ( array_key_exists( $case, $grammarTransformations ) ) {
			foreach ( array_values( $grammarTransformations[$case] ) as $rule ) {
				$form = $rule[0];

				if ( $form === '@metadata' ) {
					continue;
				}

				$replacement = $rule[1];

				$regex = "/$form/";

				wfDebug( "Chapa! replacement: $replacement; regex: $regex; word: $word\n" );

				if ( preg_match( $regex, $word ) ) {
					wfDebug( "match\n" );
					$word = preg_replace( $regex, $replacement, $word );

					break;
				} else {
					wfDebug( "no match\n" );
				}
			}
		}

		return $word;
	}
}
