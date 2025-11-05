<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Language\Language;
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

	/** @inheritDoc */
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
						$word .= "түң";
					} elseif ( in_array( $wordLastVowel, $unroundFrontVowels ) ) {
						$word .= "тиң";
					} elseif ( in_array( $wordLastVowel, $roundBackVowels ) ) {
						$word .= "туң";
					} elseif ( in_array( $wordLastVowel, $unroundBackVowels ) ) {
						$word .= "тың";
					}
				} elseif ( $wordEnding === "л" ) {
					if ( in_array( $wordLastVowel, $roundFrontVowels ) ) {
						$word .= "дүң";
					} elseif ( in_array( $wordLastVowel, $unroundFrontVowels ) ) {
						$word .= "диң";
					} elseif ( in_array( $wordLastVowel, $roundBackVowels ) ) {
						$word .= "дуң";
					} elseif ( in_array( $wordLastVowel, $unroundBackVowels ) ) {
						$word .= "дың";
					}
				} else {
					if ( in_array( $wordLastVowel, $roundFrontVowels ) ) {
						$word .= "нүң";
					} elseif ( in_array( $wordLastVowel, $unroundFrontVowels ) ) {
						$word .= "ниң";
					} elseif ( in_array( $wordLastVowel, $roundBackVowels ) ) {
						$word .= "нуң";
					} elseif ( in_array( $wordLastVowel, $unroundBackVowels ) ) {
						$word .= "ның";
					}
				}
				break;

			case "dative":
				if ( in_array( $wordEnding, $unvoicedPhonemes ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "ке";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "ка";
					}
				} else {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "ге";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "га";
					}
				}
				break;

			case "accusative":
				if ( in_array( $wordEnding, $unvoicedPhonemes ) ) {
					if ( in_array( $wordLastVowel, $roundFrontVowels ) ) {
						$word .= "тү";
					} elseif ( in_array( $wordLastVowel, $unroundFrontVowels ) ) {
						$word .= "ти";
					} elseif ( in_array( $wordLastVowel, $roundBackVowels ) ) {
						$word .= "ту";
					} elseif ( in_array( $wordLastVowel, $unroundBackVowels ) ) {
						$word .= "ты";
					}
				} elseif ( $wordEnding === "л" ) {
					if ( in_array( $wordLastVowel, $roundFrontVowels ) ) {
						$word .= "дү";
					} elseif ( in_array( $wordLastVowel, $unroundFrontVowels ) ) {
						$word .= "ди";
					} elseif ( in_array( $wordLastVowel, $roundBackVowels ) ) {
						$word .= "ду";
					} elseif ( in_array( $wordLastVowel, $unroundBackVowels ) ) {
						$word .= "ды";
					}
				} else {
					if ( in_array( $wordLastVowel, $roundFrontVowels ) ) {
						$word .= "нү";
					} elseif ( in_array( $wordLastVowel, $unroundFrontVowels ) ) {
						$word .= "ни";
					} elseif ( in_array( $wordLastVowel, $roundBackVowels ) ) {
						$word .= "ну";
					} elseif ( in_array( $wordLastVowel, $unroundBackVowels ) ) {
						$word .= "ны";
					}
				}
				break;

			case "locative":
				if ( in_array( $wordEnding, $unvoicedPhonemes ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "те";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "та";
					}
				} else {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "де";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "да";
					}
				}
				break;

			case "ablative":
				if ( in_array( $wordEnding, $unvoicedPhonemes ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "тен";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "тан";
					}
				} else {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "ден";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "дан";
					}
				}
				break;

			case "directive1":
				if ( in_array( $wordEnding, $directiveVoicedStems ) ) {
					$word .= "же";
				} elseif ( in_array( $wordEnding, $directiveUnvoicedStems ) ) {
					$word .= "че";
				}
				break;

			case "directive2":
				if ( in_array( $wordEnding, $unvoicedPhonemes ) ) {
					if ( in_array( $wordLastVowel, $roundFrontVowels ) ) {
						$word .= "түве";
					} elseif ( in_array( $wordLastVowel, $unroundFrontVowels ) ) {
						$word .= "тиве";
					} elseif ( in_array( $wordLastVowel, $roundBackVowels ) ) {
						$word .= "туве";
					} elseif ( in_array( $wordLastVowel, $unroundBackVowels ) ) {
						$word .= "тыве";
					}
				} else {
					if ( in_array( $wordLastVowel, $roundFrontVowels ) ) {
						$word .= "дүве";
					} elseif ( in_array( $wordLastVowel, $unroundFrontVowels ) ) {
						$word .= "диве";
					} elseif ( in_array( $wordLastVowel, $roundBackVowels ) ) {
						$word .= "дуве";
					} elseif ( in_array( $wordLastVowel, $unroundBackVowels ) ) {
						$word .= "дыве";
					}
				}
				break;

			default:
				break;
		}

		return $word;
	}
}
