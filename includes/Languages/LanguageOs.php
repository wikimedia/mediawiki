<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @author Soslan Khubulov
 */

use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;

/**
 * Ossetian (Ирон)
 *
 * @ingroup Languages
 */
class LanguageOs extends Language {

	/**
	 * Convert from the nominative form of a noun to other cases
	 * Invoked with {{grammar:case|word}}
	 *
	 * Depending on the word, there are four different ways of converting to other cases.
	 * 1) Words consist of not Cyrillic letters or is an abbreviation.
	 * 		Then result word is: word + hyphen + case ending.
	 *
	 * 2) Word consist of Cyrillic letters.
	 * 2.1) Word is in plural.
	 * 		Then result word is: word - last letter + case ending. Ending of the allative case here is 'æм'.
	 *
	 * 2.2) Word is in singular form.
	 * 2.2.1) Word ends on consonant.
	 * 		Then result word is: word + case ending.
	 *
	 * 2.2.2) Word ends on vowel.
	 * 		The resultant word is: word + 'й' + case ending for cases != allative or comitative
	 * 		and word + case ending for allative or comitative. Ending of the allative case here is 'æ'.
	 *
	 * @param string $word
	 * @param string $case
	 * @return string
	 */
	public function convertGrammar( $word, $case ) {
		$grammarForms =
			MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::GrammarForms );
		if ( isset( $grammarForms['os'][$case][$word] ) ) {
			return $grammarForms['os'][$case][$word];
		}
		# Ending for the allative case
		$end_allative = 'мæ';
		# Variable for 'j' between vowels
		$jot = '';
		# Variable for "-" for not Ossetic words
		$hyphen = '';
		# Variable for ending
		$ending = '';

		# Checking if the $word is in plural form
		if ( preg_match( '/тæ$/u', $word ) ) {
			$word = mb_substr( $word, 0, -1 );
			$end_allative = 'æм';
		} elseif ( preg_match( "/[аæеёиоыэюя]$/u", $word ) ) {
			# Works if $word is in singular form.
			# Checking if $word ends on one of the vowels: е, ё, и, о, ы, э, ю, я.
			$jot = 'й';
		} elseif ( preg_match( "/у$/u", $word ) ) {
			# Checking if $word ends on 'у'. 'У'
			# can be either consonant 'W' or vowel 'U' in Cyrillic Ossetic.
			# Examples: {{grammar:genitive|аунеу}} = аунеуы, {{grammar:genitive|лæппу}} = лæппуйы.
			if ( !preg_match( "/[аæеёиоыэюя]$/u", mb_substr( $word, -2, 1 ) ) ) {
				$jot = 'й';
			}
		} elseif ( !preg_match( "/[бвгджзйклмнопрстфхцчшщьъ]$/u", $word ) ) {
			$hyphen = '-';
		}

		switch ( $case ) {
			case 'genitive':
				$ending = $hyphen . $jot . 'ы';
				break;

			case 'dative':
				$ending = $hyphen . $jot . 'æн';
				break;

			case 'allative':
				$ending = $hyphen . $end_allative;
				break;

			case 'ablative':
				if ( $jot == 'й' ) {
					$ending = $hyphen . $jot . 'æ';
				} else {
					$ending = $hyphen . $jot . 'æй';
				}
				break;

			case 'inessive':
				break;

			case 'superessive':
				$ending = $hyphen . $jot . 'ыл';
				break;

			case 'equative':
				$ending = $hyphen . $jot . 'ау';
				break;

			case 'comitative':
				$ending = $hyphen . 'имæ';
				break;
		}
		return $word . $ending;
	}
}
