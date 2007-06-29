<?php
/** Old Church Slavonic (Ѩзыкъ словѣньскъ)
  *
  * @addtogroup Language
  */

/* Please, see Language.php for general function comments */
class LanguageCu extends Language {
	# Convert from the nominative form of a noun to some other case
	# Invoked with {{grammar:case|word}}
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset($wgGrammarForms['сu'][$case][$word]) ) {
			return $wgGrammarForms['сu'][$case][$word];
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
					elseif (join('',array_slice($ar[0],-2))=='ї')
						$word = join('',array_slice($ar[0],0,-2)).'їѩ';
					break;
				case 'accusative': #винительный падеж
					#stub
					break;
			}
		return $word;
	}

	function convertPlural( $count, $wordform1, $wordform2, $wordform3, $wordform4, $w5) {
		switch ($count % 10) {
			case 1: return $wordform1;
			case 2: return $wordform2;
			case 3: return $wordform3;
			case 4: return $wordform3;
			default: return $wordform4;
		}
	}

}

