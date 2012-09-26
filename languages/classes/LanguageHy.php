<?php
/**
 * Armenian (Հայերեն) specific code.
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
 * @author Ruben Vardanyan (Me@RubenVardanyan.com)
 * @ingroup Language
 */

/**
 * Armenian (Հայերեն)
 *
 * @ingroup Language
 */
class LanguageHy extends Language {

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
		if ( isset( $wgGrammarForms['hy'][$case][$word] ) ) {
			return $wgGrammarForms['hy'][$case][$word];
		}

		# These rules are not perfect, but they are currently only used for site names so it doesn't
		# matter if they are wrong sometimes. Just add a special case for your site name if necessary.

		# join and array_slice instead mb_substr
		$ar = array();
		preg_match_all( '/./us', $word, $ar );
		if ( !preg_match( "/[a-zA-Z_]/us", $word ) ) {
			switch ( $case ) {
				case 'genitive': # սեռական հոլով
					if ( join( '', array_slice( $ar[0], -1 ) ) == 'ա' ) {
						$word = join( '', array_slice( $ar[0], 0, -1 ) ) . 'այի';
					} elseif ( join( '', array_slice( $ar[0], -1 ) ) == 'ո' ) {
						$word = join( '', array_slice( $ar[0], 0, -1 ) ) . 'ոյի';
					} elseif ( join( '', array_slice( $ar[0], -4 ) ) == 'գիրք' ) {
						$word = join( '', array_slice( $ar[0], 0, -4 ) ) . 'գրքի';
					} else {
						$word .= 'ի';
					}
					break;
				case 'dative':  # Տրական հոլով
					# stub
					break;
				case 'accusative': # Հայցական հոլով
					# stub
					break;
				case 'instrumental':  #
					# stub
					break;
				case 'prepositional': #
					# stub
					break;
			}
		}
		return $word;
	}

	/**
	 * Armenian numeric format is "12 345,67" but "1234,56"
	 *
	 * @param $_ string
	 *
	 * @return string
	 */
	function commafy( $_ ) {
		if ( !preg_match( '/^\d{1,4}$/', $_ ) ) {
			return strrev( (string)preg_replace( '/(\d{3})(?=\d)(?!\d*\.)/', '$1,', strrev( $_ ) ) );
		} else {
			return $_;
		}
	}
}
