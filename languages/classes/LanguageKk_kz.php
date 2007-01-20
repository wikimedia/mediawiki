<?php
/** Kazakh (Қазақша)
  *
  *
  * @addtogroup Language
  */


class LanguageKk_kz extends Language {

 	# Convert from the nominative form of a noun to some other case
	# Invoked with {{GRAMMAR:case|word}}
	/**
	 * Cases: genitive, dative, accusative, locative, ablative, comitative + possessive forms
	 */
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset( $wgGrammarForms['kk'][$case][$word] ) ) {
			return $wgGrammarForms['kk'][$case][$word];
		}
		// Set up some constants...
    // Vowels in last syllable
		$frontVowels = array( "е", "ө", "ү", "і", "ә", "э" );
		$backVowels = array( "а", "о", "ұ", "ы", "я", "ё" );
    $allVowels = array( "е", "ө", "ү", "і", "ә", "э", "а", "о", "ұ", "ы", "я", "ё" );
    // Preceding letters
		$preVowels = $allVowels;
		$preNasals = array( "м", "н", "ң" );
    $preSonants = array( "и", "й", "л", "р", "у", "ю");
		# $preVoiceds = array( "б", "в", "г", "ғ", "д", "ж", "з", "һ" );
		# $preVoicelesses = array( "п", "ф", "к", "қ", "т", "ш", "с", "х", "ц", "ч", "щ" );
		$preConsonants = array( "п", "ф", "к", "қ", "т", "ш", "с", "х", "ц", "ч", "щ", "б", "в", "г", "д" );
		$preEzhZet = array( "ж", "з" );
    $preSonorants = array( "и", "й", "л", "р", "у", "ю", "м", "н", "ң", "ж", "з");

    // Possessives
    $firsts = array( "м", "ң" ); // 1st singular, 2nd unformal
    $seconds = array( "з" );     // 1st plural, 2nd formal
    $thirds = array( "ы", "і" ); // 3rd

		// Put the word in a form we can play with since we're using UTF-8
    $ar = array();
    $ar = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);
		$wordEnding = $ar[count( $ar ) - 1]; //Here's the last letter in the word
		$wordReversed = array_reverse( $ar ); //Here's an array with the order of the letters in the word reversed so we can find a match quicker *shrug*

		// Find the last vowel in the word
		$wordLastVowel = NULL;
		foreach ( $wordReversed as $xvalue ) {
			foreach ( $allVowels as $yvalue ) {
				if ( strcmp( $xvalue, $yvalue ) == 0 ) {
					$wordLastVowel = $xvalue;
					break;
				} else {
					continue;
				}
			}
			if ( $wordLastVowel !== NULL ) {
				break;
			} else {
				continue;
			}
		}

		// Now convert the word
		switch ( $case ) {
			case "dc1":
			case "genitive": #ilik
				if ( in_array( $wordEnding, $preConsonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "тің";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "тың";
					}
				} elseif ( in_array( $wordEnding, $preVowels ) || in_array( $wordEnding, $preNasals ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "нің";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "ның";
					}
				} elseif ( in_array( $wordEnding, $preSonants ) || in_array( $wordEnding, $preEzhZet )) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "дің";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "дың";
					}
				}
				break;
			case "dc2":
			case "dative": #barıs
				if ( in_array( $wordEnding, $preConsonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "ке";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "қа";
					}
				} elseif ( in_array( $wordEnding, $preVowels ) || in_array( $wordEnding, $preSonorants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "ге";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "ға";
					}
        }
				break;
			case "dc21":
			case "possessive dative": #täweldık + barıs
				if ( in_array( $wordEnding, $firsts ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "е";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "а";
					}
				} elseif ( in_array( $wordEnding, $seconds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "ге";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "ға";
					}
    		} elseif ( in_array( $wordEnding, $thirds ) ) {
				  if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "не";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "на";
					}
				}
				break;
			case "dc3":
			case "accusative": #tabıs
				if ( in_array( $wordEnding, $preConsonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "ті";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "ты";
					}
				} elseif ( in_array( $wordEnding, $preVowels ) ) {
					if ( in_array($wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "ні";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "ны";
					}
				} elseif ( in_array( $wordEnding, $preSonorants) ) {
					if ( in_array( $wordLastVowel, $frontVowels) ) {
						$word = implode( "", $ar ) . "ді";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "ды";
					}
				}
				break;
			case "dc31":
			case "possessive accusative": #täweldık + tabıs
				if ( in_array( $wordEnding, $firsts ) || in_array( $wordEnding, $seconds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "ді";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "ды";
					}
				} elseif ( in_array( $wordEnding, $thirds ) ) {
						$word = implode( "", $ar ) . "н";
				}
				break;
			case "dc4":
			case "locative": #jatıs
				if ( in_array( $wordEnding, $preConsonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "те";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "та";
					}
				} elseif ( in_array( $wordEnding, $preVowels ) || in_array( $wordEnding, $preSonorants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels) ) {
						$word = implode( "", $ar ) . "де";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "",$ar ) . "да";
					}
				} 
				break;
			case "dc41":
			case "possessive locative": #täweldık + jatıs
				if ( in_array( $wordEnding, $firsts ) || in_array( $wordEnding, $seconds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "де";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "да";
					}
				} elseif ( in_array( $wordEnding, $thirds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels) ) {
						$word = implode( "", $ar ) . "нде";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "",$ar ) . "нда";
					}
				} 
				break;
			case "dc5":
			case "ablative": #şığıs
				if ( in_array( $wordEnding, $preConsonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "тен";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "тан";
					}
				} elseif ( in_array($wordEnding, $preVowels ) || in_array($wordEnding, $preSonants ) || in_array($wordEnding, $preEzhZet ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "ден";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "дан";
					}
				}  elseif ( in_array($wordEnding, $preNasals ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "нен";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "нан";
					}
				}
				break;
			case "dc51":
			case "possessive ablative": #täweldık + şığıs
				if ( in_array( $wordEnding, $firsts ) || in_array( $wordEnding, $thirds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "нен";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "нан";
					}
				} elseif ( in_array($wordEnding, $seconds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "ден";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "дан";
					}
				}
				break;
			case "dc6":
			case "comitative": #kömektes
				if ( in_array( $wordEnding, $preConsonants ) ) {
						$word = implode( "", $ar ) . "пен";
				} elseif ( in_array( $wordEnding, $preVowels ) || in_array( $wordEnding, $preNasals ) || in_array( $wordEnding, $preSonants ) ) {
						$word = implode( "", $ar ) . "мен";
				} elseif ( in_array( $wordEnding, $preEzhZet ) ) {
						$word = implode( "", $ar ) . "бен";
				}
				break;
			case "dc61":
			case "possessive comitative": #täweldık + kömektes
				if ( in_array( $wordEnding, $preConsonants ) ) {
						$word = implode( "", $ar ) . "пенен";
				} elseif ( in_array( $wordEnding, $preVowels ) || in_array( $wordEnding, $preNasals ) || in_array( $wordEnding, $preSonants ) ) {
						$word = implode( "", $ar ) . "менен";
				} elseif ( in_array( $wordEnding, $preEzhZet ) ) {
						$word = implode( "", $ar ) . "бенен";
				}
				break;
			default: #dc0 #nominative #ataw
		}
		return $word;
	}

	/**
	 * Avoid grouping whole numbers between 0 to 9999
	 */
	function commafy( $_ ) {
		if ( !preg_match( '/^\d{1,4}$/', $_ ) ) {
			return strrev( (string)preg_replace( '/(\d{3})(?=\d)(?!\d*\.)/', '$1,', strrev($_) ) );
		} else {
			return $_;
		}
	}
}

?>
