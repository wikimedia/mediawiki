<?php
/**
 * Ossetian (Ирон) specific code.
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
 * @author Soslan Khubulov
 * @ingroup Language
 */

/**
 * Ossetian (Ирон)
 *
 * @ingroup Language
 */
class LanguageOs extends Language {

	/**
	 * Convert from the nominative form of a noun to other cases
	 * Invoked with {{grammar:case|word}}
	 *
	 * Depending on word there are four different ways of converting to other cases.
	 * 1) Word consist of not Cyrillic letters or is an abbreviation.
	 * 		Then result word is: word + hyphen + case ending.
	 *
	 * 2) Word consist of Cyrillic letters.
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
	 * @param string $word
	 * @param string $case
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
