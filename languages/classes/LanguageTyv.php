<?php
/**
 * Tyvan (Тыва дыл) specific code.
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
 * Tyvan localization (Тыва дыл)
 *
 * From friends at tyvawiki.org
 *
 * @ingroup Language
 */
class LanguageTyv extends Language {
	/**
	 * Grammatical transformations, needed for inflected languages
	 * Invoked by putting {{grammar:case|word}} in a message
	 *
	 * @param string $word
	 * @param string $case
	 * @return string
	 */
	public function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset( $wgGrammarForms['tyv'][$case][$word] ) ) {
			return $wgGrammarForms['tyv'][$case][$word];
		}

		// Set up some constants...
		$allVowels = [ "е", "и", "э", "ө", "ү", "а", "ё", "о", "у", "ы", "ю", "я" ];
		$frontVowels = [ "е", "и", "э", "ө", "ү" ];
		$backVowels = [ "а", "ё", "о", "у", "ы", "ю", "я" ];
		$unroundFrontVowels = [ "е", "и", "э" ];
		$roundFrontVowels = [ "ө", "ү" ];
		$unroundBackVowels = [ "а", "ы", "я" ];
		$roundBackVowels = [ "ё", "о", "у", "ю" ];
		$unvoicedPhonemes = [ "т", "п", "с", "ш", "к", "ч", "х" ];
		$directiveUnvoicedStems = [ "т", "п", "с", "ш", "к", "ч", "х", "л", "м", "н", "ң" ];
		$directiveVoicedStems = [ "д", "б", "з", "ж", "г", "р", "й" ];

		// Put the word in a form we can play with since we're using UTF-8
		preg_match_all( '/./us', $word, $ar );

		// Here's the last letter in the word
		$wordEnding = $ar[0][count( $ar[0] ) - 1];

		// Here's an array with the order of the letters in the word reversed so
		// we can find a match quicker. *shrug*
		$wordReversed = array_reverse( $ar[0] );

		// Find the last vowel in the word
		$wordLastVowel = null;
		foreach ( $wordReversed as $xvalue ) {
			foreach ( $allVowels as $yvalue ) {
				if ( strcmp( $xvalue, $yvalue ) == 0 ) {
					$wordLastVowel = $xvalue;
					break;
				}
			}

			if ( $wordLastVowel !== null ) {
				break;
			}
		}

		// Now convert the word
		switch ( $case ) {
			case "genitive":
				if ( in_array( $wordEnding, $unvoicedPhonemes ) ) {
					if ( in_array( $wordLastVowel, $roundFrontVowels ) ) {
						$word = implode( "", $ar[0] ) . "түң";
					} elseif ( in_array( $wordLastVowel, $unroundFrontVowels ) ) {
						$word = implode( "", $ar[0] ) . "тиң";
					} elseif ( in_array( $wordLastVowel, $roundBackVowels ) ) {
						$word = implode( "", $ar[0] ) . "туң";
					} elseif ( in_array( $wordLastVowel, $unroundBackVowels ) ) {
						$word = implode( "", $ar[0] ) . "тың";
					} else {
					}
				} elseif ( $wordEnding === "л" ) {
					if ( in_array( $wordLastVowel, $roundFrontVowels ) ) {
						$word = implode( "", $ar[0] ) . "дүң";
					} elseif ( in_array( $wordLastVowel, $unroundFrontVowels ) ) {
						$word = implode( "", $ar[0] ) . "диң";
					} elseif ( in_array( $wordLastVowel, $roundBackVowels ) ) {
						$word = implode( "", $ar[0] ) . "дуң";
					} elseif ( in_array( $wordLastVowel, $unroundBackVowels ) ) {
						$word = implode( "", $ar[0] ) . "дың";
					} else {
					}
				} else {
					if ( in_array( $wordLastVowel, $roundFrontVowels ) ) {
						$word = implode( "", $ar[0] ) . "нүң";
					} elseif ( in_array( $wordLastVowel, $unroundFrontVowels ) ) {
						$word = implode( "", $ar[0] ) . "ниң";
					} elseif ( in_array( $wordLastVowel, $roundBackVowels ) ) {
						$word = implode( "", $ar[0] ) . "нуң";
					} elseif ( in_array( $wordLastVowel, $unroundBackVowels ) ) {
						$word = implode( "", $ar[0] ) . "ның";
					} else {
					}
				}
				break;
			case "dative":
				if ( in_array( $wordEnding, $unvoicedPhonemes ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar[0] ) . "ке";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar[0] ) . "ка";
					} else {
					}
				} else {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar[0] ) . "ге";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar[0] ) . "га";
					} else {
					}
				}
				break;
			case "accusative":
				if ( in_array( $wordEnding, $unvoicedPhonemes ) ) {
					if ( in_array( $wordLastVowel, $roundFrontVowels ) ) {
						$word = implode( "", $ar[0] ) . "тү";
					} elseif ( in_array( $wordLastVowel, $unroundFrontVowels ) ) {
						$word = implode( "", $ar[0] ) . "ти";
					} elseif ( in_array( $wordLastVowel, $roundBackVowels ) ) {
						$word = implode( "", $ar[0] ) . "ту";
					} elseif ( in_array( $wordLastVowel, $unroundBackVowels ) ) {
						$word = implode( "", $ar[0] ) . "ты";
					} else {
					}
				} elseif ( $wordEnding === "л" ) {
					if ( in_array( $wordLastVowel, $roundFrontVowels ) ) {
						$word = implode( "", $ar[0] ) . "дү";
					} elseif ( in_array( $wordLastVowel, $unroundFrontVowels ) ) {
						$word = implode( "", $ar[0] ) . "ди";
					} elseif ( in_array( $wordLastVowel, $roundBackVowels ) ) {
						$word = implode( "", $ar[0] ) . "ду";
					} elseif ( in_array( $wordLastVowel, $unroundBackVowels ) ) {
						$word = implode( "", $ar[0] ) . "ды";
					} else {
					}
				} else {
					if ( in_array( $wordLastVowel, $roundFrontVowels ) ) {
						$word = implode( "", $ar[0] ) . "нү";
					} elseif ( in_array( $wordLastVowel, $unroundFrontVowels ) ) {
						$word = implode( "", $ar[0] ) . "ни";
					} elseif ( in_array( $wordLastVowel, $roundBackVowels ) ) {
						$word = implode( "", $ar[0] ) . "ну";
					} elseif ( in_array( $wordLastVowel, $unroundBackVowels ) ) {
						$word = implode( "", $ar[0] ) . "ны";
					} else {
					}
				}
				break;
			case "locative":
				if ( in_array( $wordEnding, $unvoicedPhonemes ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar[0] ) . "те";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar[0] ) . "та";
					} else {
					}
				} else {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar[0] ) . "де";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar[0] ) . "да";
					} else {
					}
				}
				break;
			case "ablative":
				if ( in_array( $wordEnding, $unvoicedPhonemes ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar[0] ) . "тен";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar[0] ) . "тан";
					} else {
					}
				} else {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = implode( "", $ar[0] ) . "ден";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = implode( "", $ar[0] ) . "дан";
					} else {
					}
				}
				break;
			case "directive1":
				if ( in_array( $wordEnding, $directiveVoicedStems ) ) {
					$word = implode( "", $ar[0] ) . "же";
				} elseif ( in_array( $wordEnding, $directiveUnvoicedStems ) ) {
					$word = implode( "", $ar[0] ) . "че";
				} else {
				}
				break;
			case "directive2":
				if ( in_array( $wordEnding, $unvoicedPhonemes ) ) {
					if ( in_array( $wordLastVowel, $roundFrontVowels ) ) {
						$word = implode( "", $ar[0] ) . "түве";
					} elseif ( in_array( $wordLastVowel, $unroundFrontVowels ) ) {
						$word = implode( "", $ar[0] ) . "тиве";
					} elseif ( in_array( $wordLastVowel, $roundBackVowels ) ) {
						$word = implode( "", $ar[0] ) . "туве";
					} elseif ( in_array( $wordLastVowel, $unroundBackVowels ) ) {
						$word = implode( "", $ar[0] ) . "тыве";
					} else {
					}
				} else {
					if ( in_array( $wordLastVowel, $roundFrontVowels ) ) {
						$word = implode( "", $ar[0] ) . "дүве";
					} elseif ( in_array( $wordLastVowel, $unroundFrontVowels ) ) {
						$word = implode( "", $ar[0] ) . "диве";
					} elseif ( in_array( $wordLastVowel, $roundBackVowels ) ) {
						$word = implode( "", $ar[0] ) . "дуве";
					} elseif ( in_array( $wordLastVowel, $unroundBackVowels ) ) {
						$word = implode( "", $ar[0] ) . "дыве";
					} else {
					}
				}
				break;
			default:
				break;
		}

		return $word;
	}
}
