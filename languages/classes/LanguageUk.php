<?php
/**
 * Ukrainian (українська мова) specific code.
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
 * Ukrainian (українська мова)
 *
 * @ingroup Language
 */
class LanguageUk extends Language {

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
		if ( isset( $wgGrammarForms['uk'][$case][$word] ) ) {
			return $wgGrammarForms['uk'][$case][$word];
		}

		# These rules don't cover the whole language.
		# They are used only for site names.

		# join and array_slice instead mb_substr
		$ar = [];
		preg_match_all( '/./us', $word, $ar );
		if ( !preg_match( "/[a-zA-Z_]/us", $word ) ) {
			switch ( $case ) {
				case 'genitive': # родовий відмінок
					if ( implode( '', array_slice( $ar[0], -2 ) ) === 'ія' ) {
						$word = implode( '', array_slice( $ar[0], 0, -2 ) ) . 'ії';
					} elseif ( implode( '', array_slice( $ar[0], -2 ) ) === 'ти' ) {
						$word = implode( '', array_slice( $ar[0], 0, -2 ) ) . 'т';
					} elseif ( implode( '', array_slice( $ar[0], -2 ) ) === 'ди' ) {
						$word = implode( '', array_slice( $ar[0], 0, -2 ) ) . 'дів';
					} elseif ( implode( '', array_slice( $ar[0], -3 ) ) === 'ник' ) {
						$word = implode( '', array_slice( $ar[0], 0, -3 ) ) . 'ника';
					}
					break;
				case 'accusative': # знахідний відмінок
					if ( implode( '', array_slice( $ar[0], -2 ) ) === 'ія' ) {
						$word = implode( '', array_slice( $ar[0], 0, -2 ) ) . 'ію';
					}
					break;
			}
		}
		return $word;
	}

	/**
	 * Ukrainian numeric format is "12 345,67" but "1234,56"
	 *
	 * @param string $_
	 *
	 * @return string
	 */
	function commafy( $_ ) {
		if ( !preg_match( '/^\-?\d{1,4}(\.\d+)?$/', $_ ) ) {
			return strrev( (string)preg_replace(
				'/(\d{3})(?=\d)(?!\d*\.)/',
				'$1,',
				strrev( $_ )
			) );
		} else {
			return $_;
		}
	}
}
