<?php

/** Ossetian (Ирон)
 *
 * @author Soslan Khubulov
 *
 * @ingroup Language
 */
class LanguageOs extends Language {

	/**
	 * Convert from the nominative form of a noun to other cases
	 * Invoked with {{grammar:case|word}}
	 *
	 * Depending on word there are four different ways of converting to other cases.
	 * 1) Word consist of not cyrillic letters or is an abbreviation.
	 * 		Then result word is: word + hyphen + case ending.
	 *
	 * 2) Word consist of cyrillic letters.
	 * 2.1) Word is in plural.
	 * 		Then result word is: word - last letter + case ending. Ending of allative case here is 'æм'.
	 *
	 * 2.2) Word is in singular.
	 * 2.2.1) Word ends on consonant.
	 * 		Then result word is: word + case ending.
	 *
	 * 2.2.2) Word ends on vowel.
	 * 		Then result word is: word + 'й' + case ending for cases != allative or comitative
	 * 		and word + case ending for allative or comitative. Ending of allative case here is 'æ'.
	 *
	 * @param $word string
	 * @param $case string
	 * @return string
	 */
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset( $wgGrammarForms['os'][$case][$word] ) ) {
			return $wgGrammarForms['os'][$case][$word];
		}
		# Ending for allative case
		$end_allative = 'мæ';
		# Variable for 'j' beetwen vowels
		$jot = '';
		# Variable for "-" for not Ossetic words
		$hyphen = '';
		# Variable for ending
		$ending = '';


		# CHecking if the $word is in plural form
		if ( preg_match( '/тæ$/u', $word ) ) {
			$word = mb_substr( $word, 0, -1 );
			$end_allative = 'æм';
		}
		# Works if $word is in singular form.
		# Checking if $word ends on one of the vowels: е, ё, и, о, ы, э, ю, я.
		elseif ( preg_match( "/[аæеёиоыэюя]$/u", $word ) ) {
			$jot = 'й';
		}
		# Checking if $word ends on 'у'. 'У' can be either consonant 'W' or vowel 'U' in cyrillic Ossetic.
		# Examples: {{grammar:genitive|аунеу}} = аунеуы, {{grammar:genitive|лæппу}} = лæппуйы.
		elseif ( preg_match( "/у$/u", $word ) ) {
			if ( !preg_match( "/[аæеёиоыэюя]$/u", mb_substr( $word, -2, 1 ) ) )
				$jot = 'й';
		} elseif ( !preg_match( "/[бвгджзйклмнопрстфхцчшщьъ]$/u", $word ) ) {
			$hyphen = '-';
		}

		switch ( $case ) {
			case 'genitive': $ending = $hyphen . $jot . 'ы'; break;
			case 'dative': $ending = $hyphen . $jot . 'æн'; break;
			case 'allative': $ending = $hyphen . $end_allative; break;
			case 'ablative':
				if ( $jot == 'й' ) {
					$ending = $hyphen . $jot . 'æ'; break;
				}
				else {
					$ending = $hyphen . $jot . 'æй'; break;
				}
			case 'inessive': break;
			case 'superessive': $ending = $hyphen . $jot . 'ыл'; break;
			case 'equative': $ending = $hyphen . $jot . 'ау'; break;
			case 'comitative': $ending = $hyphen . 'имæ'; break;
		}
		return $word . $ending;
	}
}
