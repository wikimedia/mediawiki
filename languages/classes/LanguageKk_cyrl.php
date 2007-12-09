<?php
/** Kazakh (Қазақша)
  *
  *
  * @addtogroup Language
  */


class LanguageKk_cyrl extends Language {

	# Convert from the nominative form of a noun to some other case
	# Invoked with {{GRAMMAR:case|word}}
	/**
	 * Cases: genitive, dative, accusative, locative, ablative, comitative + possessive forms
	 */
	function convertGrammar( $word, $case, $variant ) {
		global $wgGrammarForms;

		if ($variant='kk-cyrl') { $word = self::convertGrammarKk_cyrl( $word, $case ); }
		if ($variant='kk-latn') { $word = self::convertGrammarKk_latn( $word, $case ); }
		if ($variant='kk-arab') { $word = self::convertGrammarKk_arab( $word, $case ); }
		return $word;
	}

	function convertGrammarKk_cyrl( $word, $case ) {

		if ( isset( $wgGrammarForms['kk-kz'][$case][$word] ) ) {
			return $wgGrammarForms['kk-kz'][$case][$word];
		}
		if ( isset( $wgGrammarForms['kk-cyrl'][$case][$word] ) ) {
			return $wgGrammarForms['kk-cyrl'][$case][$word];
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
		$seconds = array( "з" );	 // 1st plural, 2nd formal
		$thirds = array( "ы", "і" ); // 3rd
		// Put the word in a form we can play with since we're using UTF-8
		$ar = array();
		$ar = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);
		$wordEnding = $ar[count( $ar ) - 1]; //Here's the last letter in the word
		$wordReversed = array_reverse( $ar ); //Here's an array with the order of the letters in the word reversed so we can find a match quicker *shrug*

		$wordLastVowel = self::lastVowel( $wordReversed, $allVowels );
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

	function convertGrammarKk_latn( $word, $case ) {
		global $wgGrammarForms;
		if ( isset( $wgGrammarForms['kk-tr'][$case][$word] ) ) {
			return $wgGrammarForms['kk-tr'][$case][$word];
		}
		if ( isset( $wgGrammarForms['kk-latn'][$case][$word] ) ) {
			return $wgGrammarForms['kk-latn'][$case][$word];
		}
		// Set up some constants...
		// Vowels in last syllable
		$frontVowels = array( "e", "ö", "ü", "i", "ä", "é" );
		$backVowels = array( "a", "o", "u", "ı" );
		$allVowels = array( "e", "ö", "ü", "i", "ä", "é", "a", "o", "u", "ı" );
		// Preceding letters
		$preVowels = $allVowels;
		$preNasals = array( "m", "n", "ñ" );
		$preSonants = array( "ï", "ý", "l", "r", "w");
		# $preVoiceds = array( "b", "v", "g", "ğ", "d", "j", "z", "h" );
		# $preVoicelesses = array( "p", "f", "k", "q", "t", "ş", "s", "x", "c", "ç"  );
		$preConsonants = array( "p", "f", "k", "q", "t", "ş", "s", "x", "c", "ç", "b", "v", "g", "d" );
		$preEzhZet = array( "j", "z" );
		$preSonorants = array( "ï", "ý", "l", "r", "w", "m", "n", "ñ", "j", "z");

		// Possessives
		$firsts = array( "m", "ñ" ); // 1st singular, 2nd unformal
		$seconds = array( "z" );	 // 1st plural, 2nd formal
		$thirds = array( "ı", "i" ); // 3rd
		// Put the word in a form we can play with since we're using UTF-8
		$ar = array();
		$ar = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);
		$wordEnding = $ar[count( $ar ) - 1]; //Here's the last letter in the word
		$wordReversed = array_reverse( $ar ); //Here's an array with the order of the letters in the word reversed so we can find a match quicker *shrug*

		$wordLastVowel = self::lastVowel( $wordReversed, $allVowels );
		// Now convert the word
		switch ( $case ) {
			case "dc1":
			case "genitive": #ilik
				if ( in_array( $wordEnding, $preConsonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "tiñ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "tıñ";
					}
				} elseif ( in_array( $wordEnding, $preVowels ) || in_array( $wordEnding, $preNasals ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "niñ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "nıñ";
					}
				} elseif ( in_array( $wordEnding, $preSonants ) || in_array( $wordEnding, $preEzhZet )) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "diñ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "dıñ";
					}
				}
				break;
			case "dc2":
			case "dative": #barıs
				if ( in_array( $wordEnding, $preConsonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "ke";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "qa";
					}
				} elseif ( in_array( $wordEnding, $preVowels ) || in_array( $wordEnding, $preSonorants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "ge";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "ğa";
					}
				}
				break;
			case "dc21":
			case "possessive dative": #täweldık + barıs
				if ( in_array( $wordEnding, $firsts ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "e";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "a";
					}
				} elseif ( in_array( $wordEnding, $seconds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "ge";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "ğa";
					}
				} elseif ( in_array( $wordEnding, $thirds ) ) {
				  if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "ne";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "na";
					}
				}
				break;
			case "dc3":
			case "accusative": #tabıs
				if ( in_array( $wordEnding, $preConsonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "ti";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "tı";
					}
				} elseif ( in_array( $wordEnding, $preVowels ) ) {
					if ( in_array($wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "ni";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "nı";
					}
				} elseif ( in_array( $wordEnding, $preSonorants) ) {
					if ( in_array( $wordLastVowel, $frontVowels) ) {
						$word = implode( "", $ar ) . "di";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "dı";
					}
				}
				break;
			case "dc31":
			case "possessive accusative": #täweldık + tabıs
				if ( in_array( $wordEnding, $firsts ) || in_array( $wordEnding, $seconds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "di";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "dı";
					}
				} elseif ( in_array( $wordEnding, $thirds ) ) {
						$word = implode( "", $ar ) . "n";
				}
				break;
			case "dc4":
			case "locative": #jatıs
				if ( in_array( $wordEnding, $preConsonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "te";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "ta";
					}
				} elseif ( in_array( $wordEnding, $preVowels ) || in_array( $wordEnding, $preSonorants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels) ) {
						$word = implode( "", $ar ) . "de";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "",$ar ) . "da";
					}
				} 
				break;
			case "dc41":
			case "possessive locative": #täweldık + jatıs
				if ( in_array( $wordEnding, $firsts ) || in_array( $wordEnding, $seconds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "de";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "da";
					}
				} elseif ( in_array( $wordEnding, $thirds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels) ) {
						$word = implode( "", $ar ) . "nde";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "",$ar ) . "nda";
					}
				} 
				break;
			case "dc5":
			case "ablative": #şığıs
				if ( in_array( $wordEnding, $preConsonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "ten";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "tan";
					}
				} elseif ( in_array($wordEnding, $preVowels ) || in_array($wordEnding, $preSonants ) || in_array($wordEnding, $preEzhZet ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "den";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "dan";
					}
				}  elseif ( in_array($wordEnding, $preNasals ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "nen";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "nan";
					}
				}
				break;
			case "dc51":
			case "possessive ablative": #täweldık + şığıs
				if ( in_array( $wordEnding, $firsts ) || in_array( $wordEnding, $thirds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "nen";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "nan";
					}
				} elseif ( in_array($wordEnding, $seconds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "den";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "dan";
					}
				}
				break;
			case "dc6":
			case "comitative": #kömektes
				if ( in_array( $wordEnding, $preConsonants ) ) {
						$word = implode( "", $ar ) . "pen";
				} elseif ( in_array( $wordEnding, $preVowels ) || in_array( $wordEnding, $preNasals ) || in_array( $wordEnding, $preSonants ) ) {
						$word = implode( "", $ar ) . "men";
				} elseif ( in_array( $wordEnding, $preEzhZet ) ) {
						$word = implode( "", $ar ) . "ben";
				}
				break;
			case "dc61":
			case "possessive comitative": #täweldık + kömektes
				if ( in_array( $wordEnding, $preConsonants ) ) {
						$word = implode( "", $ar ) . "penen";
				} elseif ( in_array( $wordEnding, $preVowels ) || in_array( $wordEnding, $preNasals ) || in_array( $wordEnding, $preSonants ) ) {
						$word = implode( "", $ar ) . "menen";
				} elseif ( in_array( $wordEnding, $preEzhZet ) ) {
						$word = implode( "", $ar ) . "benen";
				}
				break;
			default: #dc0 #nominative #ataw
		}
		return $word;
	}

	function convertGrammarKk_arab( $word, $case ) {
		global $wgGrammarForms;
		if ( isset( $wgGrammarForms['kk-cn'][$case][$word] ) ) {
			return $wgGrammarForms['kk-cn'][$case][$word];
		}
		if ( isset( $wgGrammarForms['kk-arab'][$case][$word] ) ) {
			return $wgGrammarForms['kk-arab'][$case][$word];
		}
		// Set up some constants...
		// Vowels in last syllable
		$frontVowels = array( "ە", "ٶ", "ٷ", "ٸ", "ٵ", "ە" );
		$backVowels = array( "ا", "و", "ۇ", "ى"  );
		$allVowels = array( "ە", "ٶ", "ٷ", "ٸ", "ٵ", "ە", "ا", "و", "ۇ", "ى" );
		// Preceding letters
		$preVowels = $allVowels;
		$preNasals = array( "م", "ن", "ڭ" );
		$preSonants = array( "ي", "ي", "ل", "ر", "ۋ");
		# $preVoiceds = array( "ب", "ۆ", "گ", "ع", "د", "ج", "ز", "ھ" );
		# $preVoicelesses = array( "پ", "ف", "ك", "ق", "ت", "ش", "س", "ح", "تس", "چ" );
		$preConsonants = array( "پ", "ف", "ك", "ق", "ت", "ش", "س", "ح", "تس", "چ", "ب", "ۆ", "گ", "د" );
		$preEzhZet = array( "ج", "ز" );
		$preSonorants = array( "ي", "ي", "ل", "ر", "ۋ", "م", "ن", "ڭ", "ج", "ز");

		// Possessives
		$firsts = array( "م", "ڭ" ); // 1st singular, 2nd unformal
		$seconds = array( "ز" );	 // 1st plural, 2nd formal
		$thirds = array( "ى", "ٸ" ); // 3rd
		// Put the word in a form we can play with since we're using UTF-8
		$ar = array();
		$ar = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);
		$wordEnding = $ar[count( $ar ) - 1]; //Here's the last letter in the word
		$wordReversed = array_reverse( $ar ); //Here's an array with the order of the letters in the word reversed so we can find a match quicker *shrug*
		$wordLastVowel = self::lastVowel( $wordReversed, $allVowels );
		// Now convert the word
		switch ( $case ) {
			case "dc1":
			case "genitive": #ilik
				if ( in_array( $wordEnding, $preConsonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "تٸڭ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "تىڭ";
					}
				} elseif ( in_array( $wordEnding, $preVowels ) || in_array( $wordEnding, $preNasals ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "نٸڭ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "نىڭ";
					}
				} elseif ( in_array( $wordEnding, $preSonants ) || in_array( $wordEnding, $preEzhZet )) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "دٸڭ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "دىڭ";
					}
				}
				break;
			case "dc2":
			case "dative": #barıs
				if ( in_array( $wordEnding, $preConsonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "كە";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "قا";
					}
				} elseif ( in_array( $wordEnding, $preVowels ) || in_array( $wordEnding, $preSonorants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "گە";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "عا";
					}
				}
				break;
			case "dc21":
			case "possessive dative": #täweldık + barıs
				if ( in_array( $wordEnding, $firsts ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "ە";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "ا";
					}
				} elseif ( in_array( $wordEnding, $seconds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "گە";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "عا";
					}
				} elseif ( in_array( $wordEnding, $thirds ) ) {
				  if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "نە";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "نا";
					}
				}
				break;
			case "dc3":
			case "accusative": #tabıs
				if ( in_array( $wordEnding, $preConsonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "تٸ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "تى";
					}
				} elseif ( in_array( $wordEnding, $preVowels ) ) {
					if ( in_array($wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "نٸ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "نى";
					}
				} elseif ( in_array( $wordEnding, $preSonorants) ) {
					if ( in_array( $wordLastVowel, $frontVowels) ) {
						$word = implode( "", $ar ) . "دٸ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "دى";
					}
				}
				break;
			case "dc31":
			case "possessive accusative": #täweldık + tabıs
				if ( in_array( $wordEnding, $firsts ) || in_array( $wordEnding, $seconds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "دٸ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "دى";
					}
				} elseif ( in_array( $wordEnding, $thirds ) ) {
						$word = implode( "", $ar ) . "ن";
				}
				break;
			case "dc4":
			case "locative": #jatıs
				if ( in_array( $wordEnding, $preConsonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "تە";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "تا";
					}
				} elseif ( in_array( $wordEnding, $preVowels ) || in_array( $wordEnding, $preSonorants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels) ) {
						$word = implode( "", $ar ) . "دە";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "",$ar ) . "دا";
					}
				} 
				break;
			case "dc41":
			case "possessive locative": #täweldık + jatıs
				if ( in_array( $wordEnding, $firsts ) || in_array( $wordEnding, $seconds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "دە";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "دا";
					}
				} elseif ( in_array( $wordEnding, $thirds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels) ) {
						$word = implode( "", $ar ) . "ندە";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "",$ar ) . "ندا";
					}
				} 
				break;
			case "dc5":
			case "ablative": #şığıs
				if ( in_array( $wordEnding, $preConsonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "تەن";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "تان";
					}
				} elseif ( in_array($wordEnding, $preVowels ) || in_array($wordEnding, $preSonants ) || in_array($wordEnding, $preEzhZet ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "دەن";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "دان";
					}
				}  elseif ( in_array($wordEnding, $preNasals ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "نەن";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "نان";
					}
				}
				break;
			case "dc51":
			case "possessive ablative": #täweldık + şığıs
				if ( in_array( $wordEnding, $firsts ) || in_array( $wordEnding, $thirds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "نەن";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "نان";
					}
				} elseif ( in_array($wordEnding, $seconds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "دەن";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "دان";
					}
				}
				break;
			case "dc6":
			case "comitative": #kömektes
				if ( in_array( $wordEnding, $preConsonants ) ) {
						$word = implode( "", $ar ) . "پەن";
				} elseif ( in_array( $wordEnding, $preVowels ) || in_array( $wordEnding, $preNasals ) || in_array( $wordEnding, $preSonants ) ) {
						$word = implode( "", $ar ) . "مەن";
				} elseif ( in_array( $wordEnding, $preEzhZet ) ) {
						$word = implode( "", $ar ) . "بەن";
				}
				break;
			case "dc61":
			case "possessive comitative": #täweldık + kömektes
				if ( in_array( $wordEnding, $preConsonants ) ) {
						$word = implode( "", $ar ) . "پەنەن";
				} elseif ( in_array( $wordEnding, $preVowels ) || in_array( $wordEnding, $preNasals ) || in_array( $wordEnding, $preSonants ) ) {
						$word = implode( "", $ar ) . "مەنەن";
				} elseif ( in_array( $wordEnding, $preEzhZet ) ) {
						$word = implode( "", $ar ) . "بەنەن";
				}
				break;
			default: #dc0 #nominative #ataw
		}
		return $word;
	}

	function lastVowel( $wordReversed, $allVowels ) {

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

		return $wordLastVowel; $wordEnding;
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
	function convertGrammar( $word, $case, $variant ) {
		global $wgGrammarForms;

		if ($variant='kk-kz') { $word = LanguageKk_kz::convertGrammarKk_kz( $word, $case ); }
		if ($variant='kk-tr') { $word = LanguageKk_kz::convertGrammarKk_tr( $word, $case ); }
		if ($variant='kk-cn') { $word = LanguageKk_kz::convertGrammarKk_cn( $word, $case ); }
		return $word;
	}

	function convertGrammarKk_kz( $word, $case ) {

		if ( isset( $wgGrammarForms['kk-kz'][$case][$word] ) ) {
			return $wgGrammarForms['kk-kz'][$case][$word];
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
		$seconds = array( "з" );	 // 1st plural, 2nd formal
		$thirds = array( "ы", "і" ); // 3rd
		// Put the word in a form we can play with since we're using UTF-8
		$ar = array();
		$ar = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);
		$wordEnding = $ar[count( $ar ) - 1]; //Here's the last letter in the word
		$wordReversed = array_reverse( $ar ); //Here's an array with the order of the letters in the word reversed so we can find a match quicker *shrug*

		$wordLastVowel = LanguageKk_kz::lastVowel( $wordReversed, $allVowels );
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

	function convertGrammarKk_tr( $word, $case ) {
		global $wgGrammarForms;
		if ( isset( $wgGrammarForms['kk-tr'][$case][$word] ) ) {
			return $wgGrammarForms['kk-tr'][$case][$word];
		}
		// Set up some constants...
		// Vowels in last syllable
		$frontVowels = array( "e", "ö", "ü", "i", "ä", "é" );
		$backVowels = array( "a", "o", "u", "ı" );
		$allVowels = array( "e", "ö", "ü", "i", "ä", "é", "a", "o", "u", "ı" );
		// Preceding letters
		$preVowels = $allVowels;
		$preNasals = array( "m", "n", "ñ" );
		$preSonants = array( "ï", "ý", "l", "r", "w");
		# $preVoiceds = array( "b", "v", "g", "ğ", "d", "j", "z", "h" );
		# $preVoicelesses = array( "p", "f", "k", "q", "t", "ş", "s", "x", "c", "ç"  );
		$preConsonants = array( "p", "f", "k", "q", "t", "ş", "s", "x", "c", "ç", "b", "v", "g", "d" );
		$preEzhZet = array( "j", "z" );
		$preSonorants = array( "ï", "ý", "l", "r", "w", "m", "n", "ñ", "j", "z");

		// Possessives
		$firsts = array( "m", "ñ" ); // 1st singular, 2nd unformal
		$seconds = array( "z" );	 // 1st plural, 2nd formal
		$thirds = array( "ı", "i" ); // 3rd
		// Put the word in a form we can play with since we're using UTF-8
		$ar = array();
		$ar = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);
		$wordEnding = $ar[count( $ar ) - 1]; //Here's the last letter in the word
		$wordReversed = array_reverse( $ar ); //Here's an array with the order of the letters in the word reversed so we can find a match quicker *shrug*

		$wordLastVowel = LanguageKk_kz::lastVowel( $wordReversed, $allVowels );
		// Now convert the word
		switch ( $case ) {
			case "dc1":
			case "genitive": #ilik
				if ( in_array( $wordEnding, $preConsonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "tiñ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "tıñ";
					}
				} elseif ( in_array( $wordEnding, $preVowels ) || in_array( $wordEnding, $preNasals ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "niñ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "nıñ";
					}
				} elseif ( in_array( $wordEnding, $preSonants ) || in_array( $wordEnding, $preEzhZet )) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "diñ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "dıñ";
					}
				}
				break;
			case "dc2":
			case "dative": #barıs
				if ( in_array( $wordEnding, $preConsonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "ke";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "qa";
					}
				} elseif ( in_array( $wordEnding, $preVowels ) || in_array( $wordEnding, $preSonorants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "ge";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "ğa";
					}
				}
				break;
			case "dc21":
			case "possessive dative": #täweldık + barıs
				if ( in_array( $wordEnding, $firsts ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "e";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "a";
					}
				} elseif ( in_array( $wordEnding, $seconds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "ge";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "ğa";
					}
				} elseif ( in_array( $wordEnding, $thirds ) ) {
				  if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "ne";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "na";
					}
				}
				break;
			case "dc3":
			case "accusative": #tabıs
				if ( in_array( $wordEnding, $preConsonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "ti";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "tı";
					}
				} elseif ( in_array( $wordEnding, $preVowels ) ) {
					if ( in_array($wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "ni";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "nı";
					}
				} elseif ( in_array( $wordEnding, $preSonorants) ) {
					if ( in_array( $wordLastVowel, $frontVowels) ) {
						$word = implode( "", $ar ) . "di";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "dı";
					}
				}
				break;
			case "dc31":
			case "possessive accusative": #täweldık + tabıs
				if ( in_array( $wordEnding, $firsts ) || in_array( $wordEnding, $seconds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "di";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "dı";
					}
				} elseif ( in_array( $wordEnding, $thirds ) ) {
						$word = implode( "", $ar ) . "n";
				}
				break;
			case "dc4":
			case "locative": #jatıs
				if ( in_array( $wordEnding, $preConsonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "te";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "ta";
					}
				} elseif ( in_array( $wordEnding, $preVowels ) || in_array( $wordEnding, $preSonorants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels) ) {
						$word = implode( "", $ar ) . "de";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "",$ar ) . "da";
					}
				} 
				break;
			case "dc41":
			case "possessive locative": #täweldık + jatıs
				if ( in_array( $wordEnding, $firsts ) || in_array( $wordEnding, $seconds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "de";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "da";
					}
				} elseif ( in_array( $wordEnding, $thirds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels) ) {
						$word = implode( "", $ar ) . "nde";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "",$ar ) . "nda";
					}
				} 
				break;
			case "dc5":
			case "ablative": #şığıs
				if ( in_array( $wordEnding, $preConsonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "ten";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "tan";
					}
				} elseif ( in_array($wordEnding, $preVowels ) || in_array($wordEnding, $preSonants ) || in_array($wordEnding, $preEzhZet ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "den";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "dan";
					}
				}  elseif ( in_array($wordEnding, $preNasals ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "nen";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "nan";
					}
				}
				break;
			case "dc51":
			case "possessive ablative": #täweldık + şığıs
				if ( in_array( $wordEnding, $firsts ) || in_array( $wordEnding, $thirds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "nen";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "nan";
					}
				} elseif ( in_array($wordEnding, $seconds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "den";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "dan";
					}
				}
				break;
			case "dc6":
			case "comitative": #kömektes
				if ( in_array( $wordEnding, $preConsonants ) ) {
						$word = implode( "", $ar ) . "pen";
				} elseif ( in_array( $wordEnding, $preVowels ) || in_array( $wordEnding, $preNasals ) || in_array( $wordEnding, $preSonants ) ) {
						$word = implode( "", $ar ) . "men";
				} elseif ( in_array( $wordEnding, $preEzhZet ) ) {
						$word = implode( "", $ar ) . "ben";
				}
				break;
			case "dc61":
			case "possessive comitative": #täweldık + kömektes
				if ( in_array( $wordEnding, $preConsonants ) ) {
						$word = implode( "", $ar ) . "penen";
				} elseif ( in_array( $wordEnding, $preVowels ) || in_array( $wordEnding, $preNasals ) || in_array( $wordEnding, $preSonants ) ) {
						$word = implode( "", $ar ) . "menen";
				} elseif ( in_array( $wordEnding, $preEzhZet ) ) {
						$word = implode( "", $ar ) . "benen";
				}
				break;
			default: #dc0 #nominative #ataw
		}
		return $word;
	}

	function convertGrammarKk_cn( $word, $case ) {
		global $wgGrammarForms;
		if ( isset( $wgGrammarForms['kk-cn'][$case][$word] ) ) {
			return $wgGrammarForms['kk-cn'][$case][$word];
		}
		// Set up some constants...
		// Vowels in last syllable
		$frontVowels = array( "ە", "ٶ", "ٷ", "ٸ", "ٵ", "ە" );
		$backVowels = array( "ا", "و", "ۇ", "ى"  );
		$allVowels = array( "ە", "ٶ", "ٷ", "ٸ", "ٵ", "ە", "ا", "و", "ۇ", "ى" );
		// Preceding letters
		$preVowels = $allVowels;
		$preNasals = array( "م", "ن", "ڭ" );
		$preSonants = array( "ي", "ي", "ل", "ر", "ۋ");
		# $preVoiceds = array( "ب", "ۆ", "گ", "ع", "د", "ج", "ز", "ھ" );
		# $preVoicelesses = array( "پ", "ف", "ك", "ق", "ت", "ش", "س", "ح", "تس", "چ" );
		$preConsonants = array( "پ", "ف", "ك", "ق", "ت", "ش", "س", "ح", "تس", "چ", "ب", "ۆ", "گ", "د" );
		$preEzhZet = array( "ج", "ز" );
		$preSonorants = array( "ي", "ي", "ل", "ر", "ۋ", "م", "ن", "ڭ", "ج", "ز");

		// Possessives
		$firsts = array( "م", "ڭ" ); // 1st singular, 2nd unformal
		$seconds = array( "ز" );	 // 1st plural, 2nd formal
		$thirds = array( "ى", "ٸ" ); // 3rd
		// Put the word in a form we can play with since we're using UTF-8
		$ar = array();
		$ar = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);
		$wordEnding = $ar[count( $ar ) - 1]; //Here's the last letter in the word
		$wordReversed = array_reverse( $ar ); //Here's an array with the order of the letters in the word reversed so we can find a match quicker *shrug*
		$wordLastVowel = LanguageKk_kz::lastVowel( $wordReversed, $allVowels );
		// Now convert the word
		switch ( $case ) {
			case "dc1":
			case "genitive": #ilik
				if ( in_array( $wordEnding, $preConsonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "تٸڭ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "تىڭ";
					}
				} elseif ( in_array( $wordEnding, $preVowels ) || in_array( $wordEnding, $preNasals ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "نٸڭ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "نىڭ";
					}
				} elseif ( in_array( $wordEnding, $preSonants ) || in_array( $wordEnding, $preEzhZet )) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "دٸڭ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "دىڭ";
					}
				}
				break;
			case "dc2":
			case "dative": #barıs
				if ( in_array( $wordEnding, $preConsonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "كە";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "قا";
					}
				} elseif ( in_array( $wordEnding, $preVowels ) || in_array( $wordEnding, $preSonorants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "گە";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "عا";
					}
				}
				break;
			case "dc21":
			case "possessive dative": #täweldık + barıs
				if ( in_array( $wordEnding, $firsts ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "ە";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "ا";
					}
				} elseif ( in_array( $wordEnding, $seconds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "گە";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "عا";
					}
				} elseif ( in_array( $wordEnding, $thirds ) ) {
				  if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "نە";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "نا";
					}
				}
				break;
			case "dc3":
			case "accusative": #tabıs
				if ( in_array( $wordEnding, $preConsonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "تٸ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "تى";
					}
				} elseif ( in_array( $wordEnding, $preVowels ) ) {
					if ( in_array($wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "نٸ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "نى";
					}
				} elseif ( in_array( $wordEnding, $preSonorants) ) {
					if ( in_array( $wordLastVowel, $frontVowels) ) {
						$word = implode( "", $ar ) . "دٸ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "دى";
					}
				}
				break;
			case "dc31":
			case "possessive accusative": #täweldık + tabıs
				if ( in_array( $wordEnding, $firsts ) || in_array( $wordEnding, $seconds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "دٸ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "دى";
					}
				} elseif ( in_array( $wordEnding, $thirds ) ) {
						$word = implode( "", $ar ) . "ن";
				}
				break;
			case "dc4":
			case "locative": #jatıs
				if ( in_array( $wordEnding, $preConsonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "تە";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "تا";
					}
				} elseif ( in_array( $wordEnding, $preVowels ) || in_array( $wordEnding, $preSonorants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels) ) {
						$word = implode( "", $ar ) . "دە";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "",$ar ) . "دا";
					}
				} 
				break;
			case "dc41":
			case "possessive locative": #täweldık + jatıs
				if ( in_array( $wordEnding, $firsts ) || in_array( $wordEnding, $seconds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "دە";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "دا";
					}
				} elseif ( in_array( $wordEnding, $thirds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels) ) {
						$word = implode( "", $ar ) . "ندە";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "",$ar ) . "ندا";
					}
				} 
				break;
			case "dc5":
			case "ablative": #şığıs
				if ( in_array( $wordEnding, $preConsonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "تەن";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "تان";
					}
				} elseif ( in_array($wordEnding, $preVowels ) || in_array($wordEnding, $preSonants ) || in_array($wordEnding, $preEzhZet ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "دەن";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "دان";
					}
				}  elseif ( in_array($wordEnding, $preNasals ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "نەن";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "نان";
					}
				}
				break;
			case "dc51":
			case "possessive ablative": #täweldık + şığıs
				if ( in_array( $wordEnding, $firsts ) || in_array( $wordEnding, $thirds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "نەن";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "نان";
					}
				} elseif ( in_array($wordEnding, $seconds ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar ) . "دەن";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar ) . "دان";
					}
				}
				break;
			case "dc6":
			case "comitative": #kömektes
				if ( in_array( $wordEnding, $preConsonants ) ) {
						$word = implode( "", $ar ) . "پەن";
				} elseif ( in_array( $wordEnding, $preVowels ) || in_array( $wordEnding, $preNasals ) || in_array( $wordEnding, $preSonants ) ) {
						$word = implode( "", $ar ) . "مەن";
				} elseif ( in_array( $wordEnding, $preEzhZet ) ) {
						$word = implode( "", $ar ) . "بەن";
				}
				break;
			case "dc61":
			case "possessive comitative": #täweldık + kömektes
				if ( in_array( $wordEnding, $preConsonants ) ) {
						$word = implode( "", $ar ) . "پەنەن";
				} elseif ( in_array( $wordEnding, $preVowels ) || in_array( $wordEnding, $preNasals ) || in_array( $wordEnding, $preSonants ) ) {
						$word = implode( "", $ar ) . "مەنەن";
				} elseif ( in_array( $wordEnding, $preEzhZet ) ) {
						$word = implode( "", $ar ) . "بەنەن";
				}
				break;
			default: #dc0 #nominative #ataw
		}
		return $word;
	}

	function lastVowel( $wordReversed, $allVowels ) {

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

		return $wordLastVowel; $wordEnding;
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


