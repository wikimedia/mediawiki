<?php

/** Russian (русский язык)
  *
  * You can contact Alexander Sigachov (alexander.sigachov at Googgle Mail)
  *
  * @ingroup Language
  */
class LanguageRu extends Language {
	# Convert from the nominative form of a noun to some other case
	# Invoked with {{grammar:case|word}}
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
	 *   message without number
	 *     "Действие не может быть выполнено по {{PLURAL:$1|следующей причине|следующим причинам}}:"
	 *
	 */

	function convertPlural( $count, $forms ) {
		if ( !count( $forms ) ) { return ''; }

		// if no number with word, then use $form[0] for singular and $form[1] for plural or zero
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
	 */
	function commafy( $_ ) {
		if ( preg_match( '/^-?\d{1,4}(\.\d*)?$/', $_ ) ) {
			return $_;
		} else {
			return strrev( (string)preg_replace( '/(\d{3})(?=\d)(?!\d*\.)/', '$1,', strrev( $_ ) ) );
		}
	}
}
