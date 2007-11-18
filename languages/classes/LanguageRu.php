<?php
/** Russian (русский язык)
  *
  * You can contact Alexander Sigachov (alexander.sigachov at Googgle Mail)
  *
  * @addtogroup Language
  */

/* Please, see Language.php for general function comments */
class LanguageRu extends Language {
	# Convert from the nominative form of a noun to some other case
	# Invoked with {{grammar:case|word}}
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset($wgGrammarForms['ru'][$case][$word]) ) {
			return $wgGrammarForms['ru'][$case][$word];
		}

		# These rules are not perfect, but they are currently only used for site names so it doesn't
		# matter if they are wrong sometimes. Just add a special case for your site name if necessary.

		#join and array_slice instead mb_substr
		$ar = array();
		preg_match_all( '/./us', $word, $ar );
		if (!preg_match("/[a-zA-Z_]/us", $word))
			switch ( $case ) {
				case 'genitive': #родительный падеж
					if ((join('',array_slice($ar[0],-4))=='вики') || (join('',array_slice($ar[0],-4))=='Вики'))
						{}
					elseif (join('',array_slice($ar[0],-1))=='ь')
						$word = join('',array_slice($ar[0],0,-1)).'я';
					elseif (join('',array_slice($ar[0],-2))=='ия')
						$word=join('',array_slice($ar[0],0,-2)).'ии';
					elseif (join('',array_slice($ar[0],-2))=='ка')
						$word=join('',array_slice($ar[0],0,-2)).'ки';
					elseif (join('',array_slice($ar[0],-2))=='ти')
						$word=join('',array_slice($ar[0],0,-2)).'тей';
					elseif (join('',array_slice($ar[0],-2))=='ды')
						$word=join('',array_slice($ar[0],0,-2)).'дов';
					elseif (join('',array_slice($ar[0],-3))=='ник')
						$word=join('',array_slice($ar[0],0,-3)).'ника';
					break;
				case 'dative':  #дательный падеж
					#stub
					break;
				case 'accusative': #винительный падеж
					#stub
					break;
				case 'instrumental':  #творительный падеж
					#stub
					break;
				case 'prepositional': #предложный падеж
					#stub
					break;
			}
		return $word;
	}

	/**
	 * Plural form transformations
         *
         * $wordform1 - singular form (for 1, 21, 31, 41...)
         * $wordform2 - paucal form (for 2, 3, 4, 22, 23, 24, 32, 33, 34...)
	 * $wordform3 - plural form (for 0, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 25, 26...)
         * $wordform4 - plural form for messages without number
         * $wordform5 - not used
         *
	 * Examples:
         *   message with number
         *     "Сделано $1 {{PLURAL:$1|изменение|изменения|изменений}}"
         *   message without number
	 *     "Действие не может быть выполнено по {{PLURAL:$1|следующей причине|||следующим причинам}}:"
         *
	 */

	function convertPlural( $count, $forms ) {
		if ( !count($forms) ) { return ''; }
		$forms = $this->preConvertPlural( $forms, 3 );

		$count = abs( $count );
		if ( isset($forms[3]) && $count != 1 )
			return $forms[3];

		if ($count > 10 && floor(($count % 100) / 10) == 1) {
			return $forms[2];
		} else {
			switch ($count % 10) {
				case 1:  return $forms[0];
				case 2:
				case 3:
				case 4:  return $forms[1];
				default: return $forms[2];
			}
		}
	}

	/*
	 * Russian numeric format is "12 345,67" but "1234,56"
	 */

	function commafy($_) {
		if (!preg_match('/^\d{1,4}$/',$_)) {
			return strrev((string)preg_replace('/(\d{3})(?=\d)(?!\d*\.)/','$1,',strrev($_)));
		} else {
			return $_;
		}
	}
}


