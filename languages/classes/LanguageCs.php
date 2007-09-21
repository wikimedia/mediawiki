<?php
/** Czech (česky)
 *
 * @addtogroup Language
 */

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class LanguageCs extends Language {
	# Grammatical transformations, needed for inflected languages
	# Invoked by putting {{grammar:case|word}} in a message
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset($wgGrammarForms['cs'][$case][$word]) ) {
			return $wgGrammarForms['cs'][$case][$word];
		}
		# allowed values for $case:
		#	1sg, 2sg, ..., 7sg -- nominative, genitive, ... (in singular)
		switch ( $word ) {
			case 'Wikibooks':
			case 'Wikiknihy':
				switch ( $case ) {
					case '2sg':
						return 'Wikiknih';
					case '3sg':
						return 'Wikiknihám';
					case '6sg';
						return 'Wikiknihách';
					case '7sg':
						return 'Wikiknihami';
					default:
						return 'Wikiknihy';
				}
			case 'Wikipedia':
			case 'Wikipedie':
				switch ( $case ) {
					case '3sg':
					case '4sg':
					case '6sg':
						return 'Wikipedii';
					case '7sg':
						return 'Wikipedií';
					default:
						return 'Wikipedie';
				}

			case 'Wiktionary':
			case 'Wikcionář':
			case 'Wikislovník':
				switch ( $case ) {
					case '2sg':
					case '3sg':
					case '5sg';
					case '6sg';
						return 'Wikislovníku';
					case '7sg':
						return 'Wikislovníkem';
					default:
						return 'Wikislovník';
				}

			case 'Wikiquote':
			case 'Wikicitáty':
				switch ( $case ) {
					case '2sg':
						return 'Wikicitátů';
					case '3sg':
						return 'Wikicitátům';
					case '6sg';
						return 'Wikicitátech';
					default:
						return 'Wikicitáty';
				}
		}
		# unknown
		return $word;
	}

  # Plural form transformations, needed for some languages.
  # Invoked by {{plural:count|wordform1|wordform2|wordform3}}
  function convertPlural( $count, $wordform1, $wordform2, $wordform3, $w4, $w5) {
	$count = str_replace( '\xc2\xa0', '', $count );
	switch ( $count ) {
		case 1:
			return $wordform1;

		case 2:
		case 3:
		case 4:
			return $wordform2;

		default:
			return $wordform3;
	};
  }
}
