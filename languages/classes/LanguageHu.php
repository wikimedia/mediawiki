<?php
/**
 * Hungarian (magyar) specific code.
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
 * Hungarian localisation for MediaWiki
 *
 * @ingroup Language
 */
class LanguageHu extends Language {
	protected static $vowelsBack = array( 'a', 'á', 'o', 'ó', 'u', 'ú' );
	protected static $vowelsFrontIllabial = array( 'e', 'é', 'i', 'í' );
	protected static $vowelsFrontLabial = array( 'ö', 'ő', 'ü', 'ű' );
	protected static $digraphs = array( 'cs', 'dz', 'gy', 'ly', 'ny', 'sz', 'zs' );

	/**
	 * Callback for {{GRAMMAR:<type>|<param>|...}}
	 * See other functions for documentation of each type:
	 *   - suffix: {@link addSuffix() addSuffix} 
	 *   - article: {@link getArticle() getArticle}
	 *   - rol/ba/k: {@link addSuffixBC() addSuffixBC}
	 *
	 * @param string $type
	 * @param string $param1
	 * @param string $param2
	 * @param string $param3
	 * @param string $param4
	 * @return string
	 */
	public function convertGrammar( $type, $param1, $param2 = null, $param3 = null, $param4 = null ) {
		switch ( $type ) {
			case 'suffix':
				return $this->addSuffix( $param1, $param2, $param3, $param4 );
			case 'article':
				return $this->getArticle( $param1 );
			default:
				return $this->addSuffixBC( $type, $param1 );
		}
	}

	/**
	 * Combine word (presumably a noun) with suffix according to Hungarian grammar.
	 * Takes vowel harmony and assimilation into account - for details see:
	 *   - https://en.wikipedia.org/wiki/Vowel_harmony#Hungarian
	 *   - https://en.wikipedia.org/wiki/Hungarian_phonology#Vowel_harmony
	 *   - https://en.wikipedia.org/wiki/Hungarian_noun_phrase#Case_endings
	 *
	 * This is far from perfect (and some of the rules are not algorithmizable at all)
	 * but this function should work with the suffixes following {{SITENAME}} in the
	 * interface messages, unless sitename is some tricky compound or foreign word.
	 * 
	 * The function does three things:
	 * 1) select the suffix with matching vowel harmony (first parameter should be back,
	 *    second front, third rounded (labial); second and third might be omitted if the
	 *    suffix has less forms).
	 * 2) if the last letter of the word is 'a', 'e' or 'o', change it to 'á', 'é' or 'ó'
	 *    respectively.
	 * 3) if the first letter of the suffix is 'v', change it according to assimilation
	 *    rules. (This can get complicated if the last letter of the word is a
	 *    digraph/trigraph or a double consonant.)
	 * 
	 * @param string $word
	 * @param string $backSuffix The variant with back vowel (or the suffix, if it has
	 *   no variants)
	 * @param string $frontSuffix The variant with front vowel (illabial front vowel if
	 *   there are three forms).
	 * @param string $labialSuffix The variant with labial front vowel
	 * @return string
	 */
	protected function addSuffix( $word, $backSuffix, $frontSuffix = null, $labialSuffix = null ) {
		$word = trim($word);
		$backSuffix = trim( $backSuffix );
		$frontSuffix = trim( $frontSuffix );
		$labialSuffix = trim( $labialSuffix );

		$vowels = array_merge( static::$vowelsBack, static::$vowelsFrontIllabial,
			static::$vowelsFrontLabial );

		// calculate vowel harmony + get last vowel
		if ( strtolower( mb_substr( $word, -4 ) ) === 'wiki' ) {
			// there is no way to handle compund words in general, but
			// special-case "somethingwiki" as it's a frequent sitename
			$lastVowel = 'i';
			$vowelHarmony = 'front';
		} else {
			$hasBackVowel = $hasFrontVowel = $lastVowel = false;
			foreach ( preg_split( '//u', $word, -1, PREG_SPLIT_NO_EMPTY ) as $char ) {
				if ( $char === ' ' || $char === '-' || $char === '–' ) {
					// poor man's word split
					$hasBackVowel = $hasFrontVowel = $lastVowel = false;
					continue;
				} elseif ( !in_array( $char, $vowels, true ) ) {
					continue;
				}
				$lastVowel = $char;
				if ( in_array( $char, self::$vowelsBack, true ) ) {
					$hasBackVowel = true;
				} else {
					$hasFrontVowel = true;
				}
			}

			if ( !$lastVowel ) {
				// Hungarian has no vowelless words; this is some kind of mistake
				return '';
			}

			if ( $hasBackVowel && $hasFrontVowel ) {
				$vowelHarmony = 'mixed';
			} elseif ( $hasBackVowel ) {
				$vowelHarmony = 'back';
			} else {
				$vowelHarmony = 'front';
			}
		}

		// select suffix that matches vowel harmony
		if ( !$frontSuffix ) {
			$suffix = $backSuffix;
		} elseif ( $vowelHarmony === 'back' ) {
			$suffix = $backSuffix;
		} elseif ( $vowelHarmony === 'front' ) {
			if ( $labialSuffix && in_array( $lastVowel, static::$vowelsFrontLabial, true ) ) {
				$suffix = $labialSuffix;
			} else {
				$suffix = $frontSuffix;
			}
		} else { // $vowelHarmony === 'mixed'
			if ( in_array( $lastVowel, static::$vowelsBack, true ) ) {
				$suffix = $backSuffix;
			} elseif ( in_array( $lastVowel, static::$vowelsFrontIllabial, true ) ) {
				$suffix = $backSuffix;
			} else { // $lastVowel in $vowelsFrontLabial
				$suffix = $labialSuffix ?: $frontSuffix;
			}
		}

		// change word-ending vowel
		$lastCharacter = mb_substr( $word, -1 );
		$wordEndVowelReplacements = array( 'a' => 'á', 'e' => 'é', 'o' => 'ó' );
		if ( array_key_exists( $lastCharacter, $wordEndVowelReplacements ) ) {
			$word = mb_substr( $word, 0, -1 ) . $wordEndVowelReplacements[$lastCharacter];
		}

		$lastCharacter = mb_substr( $word, -1 );
		$lastTwoCharacters = mb_substr( $word, -2 );

		// change start of suffix: v assimilates if the word ends with a consonant
		if ( mb_substr( $suffix, 0, 1 ) === 'v' && !in_array( $lastCharacter, $vowels, true ) ) {
			if ( $lastTwoCharacters === $lastCharacter . $lastCharacter ) {
				// long consonant, does not get any longer
				$suffix = mb_substr( $suffix, 1 );
			} elseif ( in_array( $lastTwoCharacters, static::$digraphs, true ) ) {
				if ( mb_substr( $word, -2, 1 ) === mb_substr( $word, -3, 1 ) ) {
					// long digraph, does not get longer
					$suffix = mb_substr( $suffix, 1 );
				} else {
					// single digraph, will become long now
					$digraph = mb_substr( $word, -2, 1 ) . mb_substr( $word, -2 );
					$suffix = mb_substr( $suffix, 1 );
					$word = mb_substr( $word, 0, -2 ) . $digraph;
				}
			} else {
				// single character, will become double now
				// (no trigraph check needed since no word ends with the trigraph)
				$suffix = mb_substr( $word, -1 ) . mb_substr( $suffix, 1 );
			}
		// leave out first character of the suffix if its a vowel and the word also ends with a vowel
		} elseif (
			in_array( $lastCharacter, $vowels, true )
			&& in_array( mb_substr( $suffix, 0, 1), $vowels, true )
		) {
			$suffix = mb_substr( $suffix, 1 );
		}

		return $word . $suffix;
	}

	/**
	 * B/C wrapper for the old suffix syntax. Unlike the old logic, this actually works.
	 */
	protected function addSuffixBC( $type, $word ) {
		global $wgGrammarForms;
		if ( isset( $wgGrammarForms[$this->getCode()][$type][$word] ) ) {
			return $wgGrammarForms[$this->getCode()][$type][$word];
		}

		switch ( $type ) {
			case 'rol':
				return $this->addSuffix( $word, 'ról', 'ről' );
			case 'ba':
				return $this->addSuffix( $word, 'ba', 'be' );
			case 'k':
				return $this->addSuffix( $word, 'k' );
		}
		return $word;
	}

	/**
	 * Returns the definite article "a"/"az" in the form that's appropriate for this word.
	 * @param string $word
	 */
	protected function getArticle( $word ) {
		$word = trim( $word );
		$vowels = array_merge( static::$vowelsBack, static::$vowelsFrontIllabial,
			static::$vowelsFrontLabial );

		if ( !strlen( $word ) ) {
			return '';
		}

		return in_array( $word[0], $vowels, true ) ? 'az' : 'a';
	}
}

