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
 * You can contact Alexander Sigachov (alexander.sigachov at Googgle Mail)
 *
 * @ingroup Language
 */
class LanguageRu extends Language {

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
		if ( isset( $wgGrammarForms['ru'][$case][$word] ) ) {
			return $wgGrammarForms['ru'][$case][$word];
		}

		# These rules are not perfect, but they are currently only used for Wikimedia
		# site names so it doesn't matter if they are wrong sometimes.
		# Just add a special case for your site name if necessary.

		# substr doesn't support Unicode and mb_substr has issues,
		# so break it to characters using preg_match_all and then use array_slice and join
		$chars = array();
		preg_match_all( '/./us', $word, $chars );
		if ( !preg_match( "/[a-zA-Z_]/us", $word ) ) {
			switch ( $case ) {
				case 'genitive': # родительный падеж
					if ( join( '', array_slice( $chars[0], -1 ) ) === 'ь' ) {
						$word = join( '', array_slice( $chars[0], 0, -1 ) ) . 'я';
					} elseif ( join( '', array_slice( $chars[0], -2 ) ) === 'ия' ) {
						$word = join( '', array_slice( $chars[0], 0, -2 ) ) . 'ии';
					} elseif ( join( '', array_slice( $chars[0], -2 ) ) === 'ка' ) {
						$word = join( '', array_slice( $chars[0], 0, -2 ) ) . 'ки';
					} elseif ( join( '', array_slice( $chars[0], -2 ) ) === 'ти' ) {
						$word = join( '', array_slice( $chars[0], 0, -2 ) ) . 'тей';
					} elseif ( join( '', array_slice( $chars[0], -2 ) ) === 'ды' ) {
						$word = join( '', array_slice( $chars[0], 0, -2 ) ) . 'дов';
					} elseif ( join( '', array_slice( $chars[0], -3 ) ) === 'ник' ) {
						$word = join( '', array_slice( $chars[0], 0, -3 ) ) . 'ника';
					} elseif ( join( '', array_slice( $chars[0], -3 ) ) === 'ные' ) {
						$word = join( '', array_slice( $chars[0], 0, -3 ) ) . 'ных';
					}
					break;
				case 'dative': # дательный падеж
					# stub
					break;
				case 'accusative': # винительный падеж
					# stub
					break;
				case 'instrumental': # творительный падеж
					# stub
					break;
				case 'prepositional': # предложный падеж
					if ( join( '', array_slice( $chars[0], -1 ) ) === 'ь' ) {
						$word = join( '', array_slice( $chars[0], 0, -1 ) ) . 'е';
					} elseif ( join( '', array_slice( $chars[0], -2 ) ) === 'ия' ) {
						$word = join( '', array_slice( $chars[0], 0, -2 ) ) . 'ии';
					} elseif ( join( '', array_slice( $chars[0], -2 ) ) === 'ка' ) {
						$word = join( '', array_slice( $chars[0], 0, -2 ) ) . 'ке';
					} elseif ( join( '', array_slice( $chars[0], -2 ) ) === 'ти' ) {
						$word = join( '', array_slice( $chars[0], 0, -2 ) ) . 'тях';
					} elseif ( join( '', array_slice( $chars[0], -2 ) ) === 'ды' ) {
						$word = join( '', array_slice( $chars[0], 0, -2 ) ) . 'дах';
					} elseif ( join( '', array_slice( $chars[0], -3 ) ) === 'ник' ) {
						$word = join( '', array_slice( $chars[0], 0, -3 ) ) . 'нике';
					} elseif ( join( '', array_slice( $chars[0], -3 ) ) === 'ные' ) {
						$word = join( '', array_slice( $chars[0], 0, -3 ) ) . 'ных';
					}
					break;
			}
		}

		return $word;
	}

	/**
	 * Four-digit number should be without group commas (spaces)
	 * See manual of style at http://ru.wikipedia.org/wiki/Википедия:Оформление_статей
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
