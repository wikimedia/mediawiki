<?php
/**
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
 */

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;

/**
 * Tyvan localization (Тыва дыл)
 *
 * From friends at tyvawiki.org
 *
 * @ingroup Languages
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
		$grammarForms =
			MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::GrammarForms );
		if ( isset( $grammarForms['tyv'][$case][$word] ) ) {
			return $grammarForms['tyv'][$case][$word];
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
		$ar = mb_str_split( $word, 1 );

		// Here's the last letter in the word
		$wordEnding = end( $ar );

		// Find the last vowel in the word
		$wordLastVowel = null;
		for ( $i = count( $ar ); $i--; ) {
			if ( in_array( $ar[$i], $allVowels, true ) ) {
				$wordLastVowel = $ar[$i];
				break;
			}
		}

		// Now convert the word
		switch ( $case ) {
			case "genitive":
				if ( in_array( $wordEnding, $unvoicedPhonemes ) ) {
					if ( in_array( $wordLastVowel, $roundFrontVowels ) ) {
						$word = $word . "түң";
					} elseif ( in_array( $wordLastVowel, $unroundFrontVowels ) ) {
						$word = $word . "тиң";
					} elseif ( in_array( $wordLastVowel, $roundBackVowels ) ) {
						$word = $word . "туң";
					} elseif ( in_array( $wordLastVowel, $unroundBackVowels ) ) {
						$word = $word . "тың";
					}
				} elseif ( $wordEnding === "л" ) {
					if ( in_array( $wordLastVowel, $roundFrontVowels ) ) {
						$word = $word . "дүң";
					} elseif ( in_array( $wordLastVowel, $unroundFrontVowels ) ) {
						$word = $word . "диң";
					} elseif ( in_array( $wordLastVowel, $roundBackVowels ) ) {
						$word = $word . "дуң";
					} elseif ( in_array( $wordLastVowel, $unroundBackVowels ) ) {
						$word = $word . "дың";
					}
				} else {
					if ( in_array( $wordLastVowel, $roundFrontVowels ) ) {
						$word = $word . "нүң";
					} elseif ( in_array( $wordLastVowel, $unroundFrontVowels ) ) {
						$word = $word . "ниң";
					} elseif ( in_array( $wordLastVowel, $roundBackVowels ) ) {
						$word = $word . "нуң";
					} elseif ( in_array( $wordLastVowel, $unroundBackVowels ) ) {
						$word = $word . "ның";
					}
				}
				break;
			case "dative":
				if ( in_array( $wordEnding, $unvoicedPhonemes ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = $word . "ке";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = $word . "ка";
					}
				} else {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = $word . "ге";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = $word . "га";
					}
				}
				break;
			case "accusative":
				if ( in_array( $wordEnding, $unvoicedPhonemes ) ) {
					if ( in_array( $wordLastVowel, $roundFrontVowels ) ) {
						$word = $word . "тү";
					} elseif ( in_array( $wordLastVowel, $unroundFrontVowels ) ) {
						$word = $word . "ти";
					} elseif ( in_array( $wordLastVowel, $roundBackVowels ) ) {
						$word = $word . "ту";
					} elseif ( in_array( $wordLastVowel, $unroundBackVowels ) ) {
						$word = $word . "ты";
					}
				} elseif ( $wordEnding === "л" ) {
					if ( in_array( $wordLastVowel, $roundFrontVowels ) ) {
						$word = $word . "дү";
					} elseif ( in_array( $wordLastVowel, $unroundFrontVowels ) ) {
						$word = $word . "ди";
					} elseif ( in_array( $wordLastVowel, $roundBackVowels ) ) {
						$word = $word . "ду";
					} elseif ( in_array( $wordLastVowel, $unroundBackVowels ) ) {
						$word = $word . "ды";
					}
				} else {
					if ( in_array( $wordLastVowel, $roundFrontVowels ) ) {
						$word = $word . "нү";
					} elseif ( in_array( $wordLastVowel, $unroundFrontVowels ) ) {
						$word = $word . "ни";
					} elseif ( in_array( $wordLastVowel, $roundBackVowels ) ) {
						$word = $word . "ну";
					} elseif ( in_array( $wordLastVowel, $unroundBackVowels ) ) {
						$word = $word . "ны";
					}
				}
				break;
			case "locative":
				if ( in_array( $wordEnding, $unvoicedPhonemes ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = $word . "те";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = $word . "та";
					}
				} else {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = $word . "де";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = $word . "да";
					}
				}
				break;
			case "ablative":
				if ( in_array( $wordEnding, $unvoicedPhonemes ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = $word . "тен";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = $word . "тан";
					}
				} else {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word = $word . "ден";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word = $word . "дан";
					}
				}
				break;
			case "directive1":
				if ( in_array( $wordEnding, $directiveVoicedStems ) ) {
					$word = $word . "же";
				} elseif ( in_array( $wordEnding, $directiveUnvoicedStems ) ) {
					$word = $word . "че";
				}
				break;
			case "directive2":
				if ( in_array( $wordEnding, $unvoicedPhonemes ) ) {
					if ( in_array( $wordLastVowel, $roundFrontVowels ) ) {
						$word = $word . "түве";
					} elseif ( in_array( $wordLastVowel, $unroundFrontVowels ) ) {
						$word = $word . "тиве";
					} elseif ( in_array( $wordLastVowel, $roundBackVowels ) ) {
						$word = $word . "туве";
					} elseif ( in_array( $wordLastVowel, $unroundBackVowels ) ) {
						$word = $word . "тыве";
					}
				} else {
					if ( in_array( $wordLastVowel, $roundFrontVowels ) ) {
						$word = $word . "дүве";
					} elseif ( in_array( $wordLastVowel, $unroundFrontVowels ) ) {
						$word = $word . "диве";
					} elseif ( in_array( $wordLastVowel, $roundBackVowels ) ) {
						$word = $word . "дуве";
					} elseif ( in_array( $wordLastVowel, $unroundBackVowels ) ) {
						$word = $word . "дыве";
					}
				}
				break;
			default:
				break;
		}

		return $word;
	}
}
