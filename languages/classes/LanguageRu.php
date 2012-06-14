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

		# These rules are not perfect, but they are currently only used for site names so it doesn't
		# matter if they are wrong sometimes. Just add a special case for your site name if necessary.

		# join and array_slice instead mb_substr
		$ar = array();
		preg_match_all( '/./us', $word, $ar );
		if ( !preg_match( "/[a-zA-Z_]/us", $word ) )
			switch ( $case ) {
				case 'genitive': # родительный падеж
					if ( ( join( '', array_slice( $ar[0], -4 ) ) == 'вики' ) || ( join( '', array_slice( $ar[0], -4 ) ) == 'Вики' ) )
						{ }
					elseif ( join( '', array_slice( $ar[0], -1 ) ) == 'ь' )
						$word = join( '', array_slice( $ar[0], 0, -1 ) ) . 'я';
					elseif ( join( '', array_slice( $ar[0], -2 ) ) == 'ия' )
						$word = join( '', array_slice( $ar[0], 0, -2 ) ) . 'ии';
					elseif ( join( '', array_slice( $ar[0], -2 ) ) == 'ка' )
						$word = join( '', array_slice( $ar[0], 0, -2 ) ) . 'ки';
					elseif ( join( '', array_slice( $ar[0], -2 ) ) == 'ти' )
						$word = join( '', array_slice( $ar[0], 0, -2 ) ) . 'тей';
					elseif ( join( '', array_slice( $ar[0], -2 ) ) == 'ды' )
						$word = join( '', array_slice( $ar[0], 0, -2 ) ) . 'дов';
					elseif ( join( '', array_slice( $ar[0], -3 ) ) == 'ник' )
						$word = join( '', array_slice( $ar[0], 0, -3 ) ) . 'ника';
					break;
				case 'dative':  # дательный падеж
					# stub
					break;
				case 'accusative': # винительный падеж
					# stub
					break;
				case 'instrumental':  # творительный падеж
					# stub
					break;
				case 'prepositional': # предложный падеж
					# stub
					break;
			}
		return $word;
	}

	/**
	 * Plural form transformations
	 *
	 * $forms[0] - singular form (for 1, 21, 31, 41...)
	 * $forms[1] - paucal form (for 2, 3, 4, 22, 23, 24, 32, 33, 34...)
	 * $forms[2] - plural form (for 0, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 25, 26...)
	 *
	 * Examples:
	 *   message with number
	 *     "Сделано $1 {{PLURAL:$1|изменение|изменения|изменений}}"
	 *     ("$1 change[s] were made)
	 *   message without number
	 *     "Действие не может быть выполнено по {{PLURAL:$1|следующей причине|следующим причинам}}:"
	 *     ("The action cannot be performed for the following reason[s]")
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

		// @todo FIXME: CLDR defines 4 plural forms. Form with decimals missing.
		// See http://unicode.org/repos/cldr-tmp/trunk/diff/supplemental/language_plural_rules.html#ru
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
