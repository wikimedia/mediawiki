<?php
/** Slovenian (Slovenščina)
 *
 * @addtogroup Language
 *
 */
class LanguageSl extends Language {
	# Convert from the nominative form of a noun to some other case
	# Invoked with {{GRAMMAR:case|word}}
	/**
	 * Cases: rodilnik, dajalnik, tožilnik, mestnik, orodnik
	 */
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset($wgGrammarForms['sl'][$case][$word]) ) {
			return $wgGrammarForms['sl'][$case][$word];
		}

		switch ( $case ) {
			case 'rodilnik': # genitive
				if ( $word == 'Wikipedija' ) {
					$word = 'Wikipedije';
				} elseif ( $word == 'Wikiknjige' ) {
					$word = 'Wikiknjig';
				} elseif ( $word == 'Wikinovice' ) {
					$word = 'Wikinovic';
				} elseif ( $word == 'Wikinavedek' ) {
					$word = 'Wikinavedka';
				} elseif ( $word == 'Wikivir' ) {
					$word = 'Wikivira';
				} elseif ( $word == 'Wikislovar' ) {
					$word = 'Wikislovarja';
				}
			break;
			case 'dajalnik': # dativ
				if ( $word == 'Wikipedija' ) {
					$word = 'Wikipediji';
				} elseif ( $word == 'Wikiknjige' ) {
					$word = 'Wikiknjigam';
				} elseif ( $word == 'Wikinovice' ) {
					$word = 'Wikinovicam';
				} elseif ( $word == 'Wikinavedek' ) {
					$word = 'Wikinavedku';
				} elseif ( $word == 'Wikivir' ) {
					$word = 'Wikiviru';
				} elseif ( $word == 'Wikislovar' ) {
					$word = 'Wikislovarju';
				}
			break;
			case 'tožilnik': # akuzatív
				if ( $word == 'Wikipedija' ) {
					$word = 'Wikipedijo';
				} elseif ( $word == 'Wikiknjige' ) {
					$word = 'Wikiknjige';
				} elseif ( $word == 'Wikinovice' ) {
					$word = 'Wikinovice';
				} elseif ( $word == 'Wikinavedek' ) {
					$word = 'Wikinavedek';
				} elseif ( $word == 'Wikivir' ) {
					$word = 'Wikivir';
				} elseif ( $word == 'Wikislovar' ) {
					$word = 'Wikislovar';
				}
			break;
			case 'mestnik': # locative
				if ( $word == 'Wikipedija' ) {
					$word = 'o Wikipediji';
				} elseif ( $word == 'Wikiknjige' ) {
					$word = 'o Wikiknjigah';
				} elseif ( $word == 'Wikinovice' ) {
					$word = 'o Wikinovicah';
				} elseif ( $word == 'Wikinavedek' ) {
					$word = 'o Wikinavedku';
				} elseif ( $word == 'Wikivir' ) {
					$word = 'o Wikiviru';
				} elseif ( $word == 'Wikislovar' ) {
					$word = 'o Wikislovarju';
				} else {
					$word = 'o ' . $word;
				}
			break;
			case 'orodnik': # instrumental
				if ( $word == 'Wikipedija' ) {
					$word = 'z Wikipedijo';
				} elseif ( $word == 'Wikiknjige' ) {
					$word = 'z Wikiknjigami';
				} elseif ( $word == 'Wikinovice' ) {
					$word = 'z Wikinovicami';
				} elseif ( $word == 'Wikinavedek' ) {
					$word = 'z Wikinavedkom';
				} elseif ( $word == 'Wikivir' ) {
					$word = 'z Wikivirom';
				} elseif ( $word == 'Wikislovar' ) {
					$word = 'z Wikislovarjem';
				} else {
					$word = 'z ' . $word;
				}
			break;
		}

		return $word; # this will return the original value for 'imenovalnik' (nominativ) and all undefined case values
	}

	function convertPlural( $count, $w1, $w2, $w3, $w4, $w5) {
		$count = str_replace ('.', '', $count);
		$forms = array( $w1, $w2, $w3, $w4, $w5 );
		if ( $count % 100 == 1 ) {
			$index = 0;
		} elseif ( $count % 100 == 2 ) {
			$index = 1;
		} elseif ( $count % 100 == 3 || $count % 100 == 4 ) {
			$index = 2;
		} elseif ( $count != 0 ) {
			$index = 3;
		} else {
			$index = 4;
		}
		return $forms[$index];
	}


}
?>
