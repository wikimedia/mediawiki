<?php
/**
 * Old Church Slavonic (Ѩзыкъ словѣньскъ) specific code.
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
 * Old Church Slavonic (Ѩзыкъ словѣньскъ)
 *
 * @ingroup Language
 */
class LanguageCu extends Language {

	/**
	 * Convert from the nominative form of a noun to some other case
	 * Invoked with {{grammar:case|word}}
	 *
	 * @param $word string
	 * @param $case string
	 * @return string
	 */
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset( $wgGrammarForms['сu'][$case][$word] ) ) {
			return $wgGrammarForms['сu'][$case][$word];
		}

		# These rules are not perfect, but they are currently only used for site names so it doesn't
		# matter if they are wrong sometimes. Just add a special case for your site name if necessary.

		# join and array_slice instead mb_substr
		$ar = array();
		preg_match_all( '/./us', $word, $ar );
		if ( !preg_match( "/[a-zA-Z_]/us", $word ) ) {
			switch ( $case ) {
				case 'genitive': # родительный падеж
					if ( ( join( '', array_slice( $ar[0], -4 ) ) == 'вики' ) || ( join( '', array_slice( $ar[0], -4 ) ) == 'Вики' ) ) {
					} elseif ( join( '', array_slice( $ar[0], -2 ) ) == 'ї' ) {
						$word = join( '', array_slice( $ar[0], 0, -2 ) ) . 'їѩ';
					}
					break;
				case 'accusative': # винительный падеж
					# stub
					break;
			}
		}
		return $word;
	}
}
