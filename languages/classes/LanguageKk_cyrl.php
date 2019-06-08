<?php
/**
 * Kazakh (Қазақша) specific code.
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
 * Kazakh (Қазақша)
 *
 * @ingroup Language
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class LanguageKk_cyrl extends Language {
	# Convert from the nominative form of a noun to some other case
	# Invoked with {{GRAMMAR:case|word}}
	/**
	 * Cases: genitive, dative, accusative, locative, ablative, comitative + possessive forms
	 *
	 * @param string $word
	 * @param string $case
	 *
	 * @return string
	 */
	function convertGrammarKk_cyrl( $word, $case ) {
		global $wgGrammarForms;
		if ( isset( $wgGrammarForms['kk-kz'][$case][$word] ) ) {
			return $wgGrammarForms['kk-kz'][$case][$word];
		}
		if ( isset( $wgGrammarForms['kk-cyrl'][$case][$word] ) ) {
			return $wgGrammarForms['kk-cyrl'][$case][$word];
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
		$firstPerson = [ "м", "ң" ]; // 1st singular, 2nd unformal
		$secondPerson = [ "з" ]; // 1st plural, 2nd formal
		$thirdPerson = [ "ы", "і" ]; // 3rd

		list( $wordEnding, $wordLastVowel ) = $this->lastLetter( $word, $allVowels );

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
		}
		return $word;
	}

	/**
	 * @param string $word
	 * @param string $case
	 * @return string
	 */
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
		$frontVowels = [ "e", "ö", "ü", "i", "ä", "é" ];
		$backVowels = [ "a", "o", "u", "ı" ];
		$allVowels = [ "e", "ö", "ü", "i", "ä", "é", "a", "o", "u", "ı" ];
		// Preceding letters
		$Nasals = [ "m", "n", "ñ" ];
		$Sonants = [ "ï", "y", "ý", "l", "r", "w" ];
		$Consonants = [ "p", "f", "k", "q", "t", "ş", "s", "x", "c", "ç", "b", "v", "g", "d" ];
		$Sibilants = [ "j", "z" ];
		$Sonorants = [ "ï", "y", "ý", "l", "r", "w", "m", "n", "ñ", "j", "z" ];

		// Possessives
		$firstPerson = [ "m", "ñ" ]; // 1st singular, 2nd unformal
		$secondPerson = [ "z" ]; // 1st plural, 2nd formal
		$thirdPerson = [ "ı", "i" ]; // 3rd

		list( $wordEnding, $wordLastVowel ) = $this->lastLetter( $word, $allVowels );

		// Now convert the word
		switch ( $case ) {
			case "dc1":
			case "genitive": # ilik
				if ( in_array( $wordEnding, $Consonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "tiñ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "tıñ";
					}
				} elseif ( in_array( $wordEnding, $allVowels ) || in_array( $wordEnding, $Nasals ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "niñ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "nıñ";
					}
				} elseif ( in_array( $wordEnding, $Sonants ) || in_array( $wordEnding, $Sibilants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "diñ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "dıñ";
					}
				}
				break;
			case "dc2":
			case "dative": # barıs
				if ( in_array( $wordEnding, $Consonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "ke";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "qa";
					}
				} elseif ( in_array( $wordEnding, $allVowels ) || in_array( $wordEnding, $Sonorants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "ge";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "ğa";
					}
				}
				break;
			case "dc21":
			case "possessive dative": # täweldık + barıs
				if ( in_array( $wordEnding, $firstPerson ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "e";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "a";
					}
				} elseif ( in_array( $wordEnding, $secondPerson ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "ge";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "ğa";
					}
				} elseif ( in_array( $wordEnding, $thirdPerson ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "ne";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "na";
					}
				}
				break;
			case "dc3":
			case "accusative": # tabıs
				if ( in_array( $wordEnding, $Consonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "ti";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "tı";
					}
				} elseif ( in_array( $wordEnding, $allVowels ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "ni";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "nı";
					}
				} elseif ( in_array( $wordEnding, $Sonorants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "di";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "dı";
					}
				}
				break;
			case "dc31":
			case "possessive accusative": # täweldık + tabıs
				if ( in_array( $wordEnding, $firstPerson ) || in_array( $wordEnding, $secondPerson ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "di";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "dı";
					}
				} elseif ( in_array( $wordEnding, $thirdPerson ) ) {
						$word .= "n";
				}
				break;
			case "dc4":
			case "locative": # jatıs
				if ( in_array( $wordEnding, $Consonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "te";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "ta";
					}
				} elseif ( in_array( $wordEnding, $allVowels ) || in_array( $wordEnding, $Sonorants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "de";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "da";
					}
				}
				break;
			case "dc41":
			case "possessive locative": # täweldık + jatıs
				if ( in_array( $wordEnding, $firstPerson ) || in_array( $wordEnding, $secondPerson ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "de";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "da";
					}
				} elseif ( in_array( $wordEnding, $thirdPerson ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "nde";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "nda";
					}
				}
				break;
			case "dc5":
			case "ablative": # şığıs
				if ( in_array( $wordEnding, $Consonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "ten";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "tan";
					}
				} elseif ( in_array( $wordEnding, $allVowels )
					|| in_array( $wordEnding, $Sonants )
					|| in_array( $wordEnding, $Sibilants )
				) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "den";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "dan";
					}
				} elseif ( in_array( $wordEnding, $Nasals ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "nen";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "nan";
					}
				}
				break;
			case "dc51":
			case "possessive ablative": # täweldık + şığıs
				if ( in_array( $wordEnding, $firstPerson ) || in_array( $wordEnding, $thirdPerson ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "nen";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "nan";
					}
				} elseif ( in_array( $wordEnding, $secondPerson ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "den";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "dan";
					}
				}
				break;
			case "dc6":
			case "comitative": # kömektes
				if ( in_array( $wordEnding, $Consonants ) ) {
						$word .= "pen";
				} elseif ( in_array( $wordEnding, $allVowels )
					|| in_array( $wordEnding, $Nasals )
					|| in_array( $wordEnding, $Sonants )
				) {
						$word .= "men";
				} elseif ( in_array( $wordEnding, $Sibilants ) ) {
						$word .= "ben";
				}
				break;
			case "dc61":
			case "possessive comitative": # täweldık + kömektes
				if ( in_array( $wordEnding, $Consonants ) ) {
						$word .= "penen";
				} elseif ( in_array( $wordEnding, $allVowels )
					|| in_array( $wordEnding, $Nasals )
					|| in_array( $wordEnding, $Sonants )
				) {
						$word .= "menen";
				} elseif ( in_array( $wordEnding, $Sibilants ) ) {
						$word .= "benen";
				}
				break;
			default: # dc0 #nominative #ataw
		}
		return $word;
	}

	/**
	 * @param string $word
	 * @param string $case
	 * @return string
	 */
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
		$frontVowels = [ "ە", "ٶ", "ٷ", "ٸ", "ٵ", "ە" ];
		$backVowels = [ "ا", "و", "ۇ", "ى" ];
		$allVowels = [ "ە", "ٶ", "ٷ", "ٸ", "ٵ", "ە", "ا", "و", "ۇ", "ى" ];
		// Preceding letters
		$Nasals = [ "م", "ن", "ڭ" ];
		$Sonants = [ "ي", "ي", "ل", "ر", "ۋ" ];
		$Consonants = [ "پ", "ف", "ك", "ق", "ت", "ش", "س", "ح", "تس", "چ", "ب", "ۆ", "گ", "د" ];
		$Sibilants = [ "ج", "ز" ];
		$Sonorants = [ "ي", "ي", "ل", "ر", "ۋ", "م", "ن", "ڭ", "ج", "ز" ];

		// Possessives
		$firstPerson = [ "م", "ڭ" ]; // 1st singular, 2nd unformal
		$secondPerson = [ "ز" ]; // 1st plural, 2nd formal
		$thirdPerson = [ "ى", "ٸ" ]; // 3rd

		list( $wordEnding, $wordLastVowel ) = $this->lastLetter( $word, $allVowels );

		// Now convert the word
		switch ( $case ) {
			case "dc1":
			case "genitive": # ilik
				if ( in_array( $wordEnding, $Consonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "تٸڭ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "تىڭ";
					}
				} elseif ( in_array( $wordEnding, $allVowels ) || in_array( $wordEnding, $Nasals ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "نٸڭ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "نىڭ";
					}
				} elseif ( in_array( $wordEnding, $Sonants ) || in_array( $wordEnding, $Sibilants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "دٸڭ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "دىڭ";
					}
				}
				break;
			case "dc2":
			case "dative": # barıs
				if ( in_array( $wordEnding, $Consonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "كە";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "قا";
					}
				} elseif ( in_array( $wordEnding, $allVowels ) || in_array( $wordEnding, $Sonorants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "گە";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "عا";
					}
				}
				break;
			case "dc21":
			case "possessive dative": # täweldık + barıs
				if ( in_array( $wordEnding, $firstPerson ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "ە";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "ا";
					}
				} elseif ( in_array( $wordEnding, $secondPerson ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "گە";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "عا";
					}
				} elseif ( in_array( $wordEnding, $thirdPerson ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "نە";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "نا";
					}
				}
				break;
			case "dc3":
			case "accusative": # tabıs
				if ( in_array( $wordEnding, $Consonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "تٸ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "تى";
					}
				} elseif ( in_array( $wordEnding, $allVowels ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "نٸ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "نى";
					}
				} elseif ( in_array( $wordEnding, $Sonorants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "دٸ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "دى";
					}
				}
				break;
			case "dc31":
			case "possessive accusative": # täweldık + tabıs
				if ( in_array( $wordEnding, $firstPerson ) || in_array( $wordEnding, $secondPerson ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "دٸ";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "دى";
					}
				} elseif ( in_array( $wordEnding, $thirdPerson ) ) {
						$word .= "ن";
				}
				break;
			case "dc4":
			case "locative": # jatıs
				if ( in_array( $wordEnding, $Consonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "تە";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "تا";
					}
				} elseif ( in_array( $wordEnding, $allVowels ) || in_array( $wordEnding, $Sonorants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "دە";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "دا";
					}
				}
				break;
			case "dc41":
			case "possessive locative": # täweldık + jatıs
				if ( in_array( $wordEnding, $firstPerson ) || in_array( $wordEnding, $secondPerson ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "دە";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "دا";
					}
				} elseif ( in_array( $wordEnding, $thirdPerson ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "ندە";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "ندا";
					}
				}
				break;
			case "dc5":
			case "ablative": # şığıs
				if ( in_array( $wordEnding, $Consonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "تەن";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "تان";
					}
				} elseif ( in_array( $wordEnding, $allVowels )
					|| in_array( $wordEnding, $Sonants )
					|| in_array( $wordEnding, $Sibilants )
				) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "دەن";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "دان";
					}
				} elseif ( in_array( $wordEnding, $Nasals ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "نەن";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "نان";
					}
				}
				break;
			case "dc51":
			case "possessive ablative": # täweldık + şığıs
				if ( in_array( $wordEnding, $firstPerson ) || in_array( $wordEnding, $thirdPerson ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "نەن";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "نان";
					}
				} elseif ( in_array( $wordEnding, $secondPerson ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "دەن";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "دان";
					}
				}
				break;
			case "dc6":
			case "comitative": # kömektes
				if ( in_array( $wordEnding, $Consonants ) ) {
						$word .= "پەن";
				} elseif ( in_array( $wordEnding, $allVowels )
					|| in_array( $wordEnding, $Nasals )
					|| in_array( $wordEnding, $Sonants )
				) {
						$word .= "مەن";
				} elseif ( in_array( $wordEnding, $Sibilants ) ) {
						$word .= "بەن";
				}
				break;
			case "dc61":
			case "possessive comitative": # täweldık + kömektes
				if ( in_array( $wordEnding, $Consonants ) ) {
						$word .= "پەنەن";
				} elseif ( in_array( $wordEnding, $allVowels )
					|| in_array( $wordEnding, $Nasals )
					|| in_array( $wordEnding, $Sonants )
				) {
						$word .= "مەنەن";
				} elseif ( in_array( $wordEnding, $Sibilants ) ) {
						$word .= "بەنەن";
				}
				break;
			default: # dc0 #nominative #ataw
		}
		return $word;
	}

	/**
	 * @param string $word
	 * @param string[] $allVowels
	 * @return array
	 */
	function lastLetter( $word, $allVowels ) {
		$lastLetter = [];

		// Put the word in a form we can play with since we're using UTF-8
		$ar = preg_split( '//u', parent::lc( $word ), -1, PREG_SPLIT_NO_EMPTY );

		// Here's an array with the order of the letters in the word reversed
		// so we can find a match quicker *shrug*
		$wordReversed = array_reverse( $ar );

		// Here's the last letter in the word
		$lastLetter[0] = $ar[count( $ar ) - 1];

		// Find the last vowel in the word
		$lastLetter[1] = null;
		foreach ( $wordReversed as $xvalue ) {
			foreach ( $allVowels as $yvalue ) {
				if ( strcmp( $xvalue, $yvalue ) == 0 ) {
					$lastLetter[1] = $xvalue;
					break;
				}
			}
			if ( $lastLetter[1] !== null ) {
				break;
			}
		}

		return $lastLetter;
	}
}
