<?php
/**
 * Russian (русский язык) specific code.
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
 * Russian (русский язык)
 *
 * You can contact:
 * Alexander Sigachov (alexander.sigachov at Googgle Mail)
 * Amir E. Aharoni (amir.aharoni@mail.huji.ac.il)
 *
 * @ingroup Language
 */
class LanguageRu extends Language {

	/**
	 * Convert from the nominative form of a noun to some other case
	 * Invoked with {{grammar:case|word}}
	 *
	 * @param string $word
	 * @param string $case
	 * @return string
	 */
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset( $wgGrammarForms['ru'][$case][$word] ) ) {
			return $wgGrammarForms['ru'][$case][$word];
		}

		$grammarDataFile = __DIR__ . '/data/grammar.ru.json';
		$grammarData = FormatJson::decode( file_get_contents( $grammarDataFile ), true );

		if ( array_key_exists( $case, $grammarData ) ) {
			foreach ( array_keys( $grammarData[$case] ) as $form ) {
				if ( $form === '@metadata' ) {
					continue;
				}

				$regex = "/$form/";

				if ( preg_match( $regex, $word ) ) {
					$word = preg_replace( $regex, $grammarData[$case][$form], $word );

					break;
				}
			}
		}

		return $word;
	}

	/**
	 * Four-digit number should be without group commas (spaces)
	 * See manual of style at http://ru.wikipedia.org/wiki/Википедия:Оформление_статей
	 * So "1 234 567", "12 345" but "1234"
	 *
	 * @param string $_
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
