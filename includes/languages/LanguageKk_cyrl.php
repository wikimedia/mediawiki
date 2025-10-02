<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
// phpcs:ignoreFile Squiz.Classes.ValidClassName.NotCamelCaps

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Language\Language;

/**
 * Kazakh (Қазақша)
 *
 * @ingroup Languages
 */
class LanguageKk_cyrl extends Language {
	/**
	 * Convert from the nominative form of a noun to some other case
	 * Invoked with {{GRAMMAR:case|word}}
	 *
	 * Cases: genitive, dative, accusative, locative, ablative, comitative + possessive forms
	 *
	 * @param string $word
	 * @param string $case
	 *
	 * @return string
	 */
	protected function convertGrammarKk_cyrl( $word, $case ) {
		$grammarForms =
			MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::GrammarForms );
		if ( isset( $grammarForms['kk-kz'][$case][$word] ) ) {
			return $grammarForms['kk-kz'][$case][$word];
		}
		if ( isset( $grammarForms['kk-cyrl'][$case][$word] ) ) {
			return $grammarForms['kk-cyrl'][$case][$word];
		}
		// Set up some constants...
		// Vowels in last syllable
		$frontVowels = [ "е", "ө", "ү", "і", "ә", "э", "я", "ё", "и" ];
		$backVowels = [ "а", "о", "ұ", "ы" ];
		$allVowels = [ "е", "ө", "ү", "і", "ә", "э", "а", "о", "ұ", "ы", "я", "ё", "и" ];
		// Preceding letters
		$Nasals = [ "м", "н", "ң" ];
		$Sonants = [ "и", "й", "л", "р", "у", "ю" ];
		$Consonants = [ "п", "ф", "к", "қ", "т", "ш", "с", "х", "ц", "ч", "щ", "б", "в", "г", "д" ];
		$Sibilants = [ "ж", "з" ];
		$Sonorants = [ "и", "й", "л", "р", "у", "ю", "м", "н", "ң", "ж", "з" ];

		// Possessives
		$firstPerson = [ "м", "ң" ]; // 1st singular, 2nd informal
		$secondPerson = [ "з" ]; // 1st plural, 2nd formal
		$thirdPerson = [ "ы", "і" ]; // 3rd

		[ $wordEnding, $wordLastVowel ] = $this->lastLetter( $word, $allVowels );

		// Now convert the word
		switch ( $case ) {
			case "dc1":
			case "genitive": # ilik
				if ( in_array( $wordEnding, $Consonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "тің";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "тың";
					}
				} elseif ( in_array( $wordEnding, $allVowels ) || in_array( $wordEnding, $Nasals ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "нің";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "ның";
					}
				} elseif ( in_array( $wordEnding, $Sonants ) || in_array( $wordEnding, $Sibilants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "дің";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "дың";
					}
				}
				break;

			case "dc2":
			case "dative": # barıs
				if ( in_array( $wordEnding, $Consonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "ке";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "қа";
					}
				} elseif ( in_array( $wordEnding, $allVowels ) || in_array( $wordEnding, $Sonorants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "ге";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "ға";
					}
				}
				break;

			case "dc21":
			case "possessive dative": # täweldık + barıs
				if ( in_array( $wordEnding, $firstPerson ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "е";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "а";
					}
				} elseif ( in_array( $wordEnding, $secondPerson ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "ге";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "ға";
					}
				} elseif ( in_array( $wordEnding, $thirdPerson ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "не";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "на";
					}
				}
				break;

			case "dc3":
			case "accusative": # tabıs
				if ( in_array( $wordEnding, $Consonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "ті";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "ты";
					}
				} elseif ( in_array( $wordEnding, $allVowels ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "ні";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "ны";
					}
				} elseif ( in_array( $wordEnding, $Sonorants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "ді";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "ды";
					}
				}
				break;

			case "dc31":
			case "possessive accusative": # täweldık + tabıs
				if ( in_array( $wordEnding, $firstPerson ) || in_array( $wordEnding, $secondPerson ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "ді";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "ды";
					}
				} elseif ( in_array( $wordEnding, $thirdPerson ) ) {
					$word .= "н";
				}
				break;

			case "dc4":
			case "locative": # jatıs
				if ( in_array( $wordEnding, $Consonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "те";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "та";
					}
				} elseif ( in_array( $wordEnding, $allVowels ) || in_array( $wordEnding, $Sonorants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "де";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "да";
					}
				}
				break;

			case "dc41":
			case "possessive locative": # täweldık + jatıs
				if ( in_array( $wordEnding, $firstPerson ) || in_array( $wordEnding, $secondPerson ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "де";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "да";
					}
				} elseif ( in_array( $wordEnding, $thirdPerson ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "нде";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "нда";
					}
				}
				break;

			case "dc5":
			case "ablative": # şığıs
				if ( in_array( $wordEnding, $Consonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "тен";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "тан";
					}
				} elseif ( in_array( $wordEnding, $allVowels )
					|| in_array( $wordEnding, $Sonants )
					|| in_array( $wordEnding, $Sibilants )
				) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "ден";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "дан";
					}
				} elseif ( in_array( $wordEnding, $Nasals ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "нен";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "нан";
					}
				}
				break;

			case "dc51":
			case "possessive ablative": # täweldık + şığıs
				if ( in_array( $wordEnding, $firstPerson ) || in_array( $wordEnding, $thirdPerson ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "нен";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "нан";
					}
				} elseif ( in_array( $wordEnding, $secondPerson ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "ден";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "дан";
					}
				}
				break;

			case "dc6":
			case "comitative": # kömektes
				if ( in_array( $wordEnding, $Consonants ) ) {
						$word .= "пен";
				} elseif ( in_array( $wordEnding, $allVowels )
					|| in_array( $wordEnding, $Nasals )
					|| in_array( $wordEnding, $Sonants )
				) {
						$word .= "мен";
				} elseif ( in_array( $wordEnding, $Sibilants ) ) {
						$word .= "бен";
				}
				break;
			case "dc61":
			case "possessive comitative": # täweldık + kömektes
				if ( in_array( $wordEnding, $Consonants ) ) {
						$word .= "пенен";
				} elseif ( in_array( $wordEnding, $allVowels )
					|| in_array( $wordEnding, $Nasals )
					|| in_array( $wordEnding, $Sonants )
				) {
						$word .= "менен";
				} elseif ( in_array( $wordEnding, $Sibilants ) ) {
						$word .= "бенен";
				}
				break;

			default: # dc0 #nominative #ataw
				break;
		}
		return $word;
	}

	/**
	 * @param string $word
	 * @param string[] $allVowels
	 * @return array
	 */
	private function lastLetter( $word, $allVowels ) {
		// Put the word in a form we can play with since we're using UTF-8
		$ar = mb_str_split( $this->lc( $word ), 1 );

		// Here's the last letter in the word
		$lastLetter = end( $ar );

		// Find the last vowel in the word
		for ( $i = count( $ar ); $i--; ) {
			$lastVowel = $ar[$i];
			if ( in_array( $lastVowel, $allVowels, true ) ) {
				return [ $lastLetter, $lastVowel ];
			}
		}

		return [ $lastLetter, null ];
	}
}
