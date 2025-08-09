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

namespace Wikimedia\Leximorph\Handler\Overrides\Grammar;

use Wikimedia\Leximorph\Handler\Overrides\IGrammarTransformer;

/**
 * GrammarOs
 *
 * Implements grammar transformations for Ossetic (os).
 *
 * These rules don't cover the whole grammar of the language.
 * This logic was originally taken from MediaWiki Core.
 * Thanks to all contributors.
 *
 * @since     1.45
 * @author    Soslan Khubulov
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 */
class GrammarOs implements IGrammarTransformer {
	/**
	 * Applies Ossetic-specific grammatical transformations.
	 *
	 * Convert from the nominative form of a noun to other cases
	 * Invoked with {{grammar:case|word}}
	 *
	 * Depending on the word, there are four different ways of converting to other cases.
	 * 1) Words consist of not Cyrillic letters or is an abbreviation.
	 *        Then result word is: word + hyphen + case ending.
	 *
	 * 2) Word consist of Cyrillic letters.
	 * 2.1) Word is in plural.
	 *        Then result word is: word - last letter + case ending. Ending of the allative case here is 'æм'.
	 *
	 * 2.2) Word is in singular form.
	 * 2.2.1) Word ends on consonant.
	 *        Then result word is: word + case ending.
	 *
	 * 2.2.2) Word ends on vowel.
	 *        The resultant word is: word + 'й' + case ending for cases != allative or comitative
	 *        and word + case ending for allative or comitative. Ending of the allative case here is 'æ'.
	 *
	 * @param string $word The word to process.
	 * @param string $case The grammatical case.
	 *
	 * @since 1.45
	 * @return string The processed word.
	 */
	public function process( string $word, string $case ): string {
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

			case 'superessive':
				$ending = $hyphen . $jot . 'ыл';
				break;

			case 'equative':
				$ending = $hyphen . $jot . 'ау';
				break;

			case 'comitative':
				$ending = $hyphen . 'имæ';
				break;

			default:
				break;
		}

		return $word . $ending;
	}
}
