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

// phpcs:ignoreFile Squiz.Classes.ValidClassName.NotCamelCaps
namespace Wikimedia\Leximorph\Handler\Overrides\Grammar;

use Wikimedia\Leximorph\Handler\Overrides\IGrammarTransformer;

/**
 * GrammarKk_cyrl
 *
 * Implements grammar transformations for Kazakh using the Cyrillic script.
 *
 * These rules don't cover the whole grammar of the language.
 * This logic was originally taken from MediaWiki Core.
 * Thanks to all contributors.
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 */
class GrammarKk_cyrl implements IGrammarTransformer {
	/**
	 * Applies Cyrillic Kazakh-specific grammatical transformations.
	 *
	 * Convert from the nominative form of a noun to some other case
	 * Invoked with {{GRAMMAR:case|word}}
	 *
	 * Cases: genitive, dative, accusative, locative, ablative, comitative + possessive forms
	 *
	 * @param string $word The word to process.
	 * @param string $case The grammatical case.
	 *
	 * @since 1.45
	 * @return string The processed word.
	 */
	public function process( string $word, string $case ): string {
		// Set up some constants...
		// Vowels in last syllable
		$frontVowels = [ "е", "ө", "ү", "і", "ә", "э", "я", "ё", "и", ];
		$backVowels = [ "а", "о", "ұ", "ы", ];
		$allVowels = [ "е", "ө", "ү", "і", "ә", "э", "а", "о", "ұ", "ы", "я", "ё", "и", ];
		// Preceding letters
		$Nasals = [ "м", "н", "ң", ];
		$Sonants = [ "и", "й", "л", "р", "у", "ю", ];
		$Consonants = [ "п", "ф", "к", "қ", "т", "ш", "с", "х", "ц", "ч", "щ", "б", "в", "г", "д", ];
		$Sibilants = [ "ж", "з", ];
		$Sonorants = [ "и", "й", "л", "р", "у", "ю", "м", "н", "ң", "ж", "з", ];

		// Possessives
		// 1st singular, 2nd informal
		$firstPerson = [ "м", "ң", ];
		// 1st plural, 2nd formal
		$secondPerson = [ "з" ];
		// 3rd
		$thirdPerson = [ "ы", "і", ];

		[
			$wordEnding,
			$wordLastVowel,
		] = $this->lastLetter( $word, $allVowels );

		// Now convert the word
		switch ( $case ) {
			case "dc1":
				# ilik
			case "genitive":
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
				# barıs
			case "dative":
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
				# täweldık + barıs
			case "possessive dative":
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
				# tabıs
			case "accusative":
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
				# täweldık + tabıs
			case "possessive accusative":
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
				# jatıs
			case "locative":
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
				# täweldık + jatıs
			case "possessive locative":
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
				# şığıs
			case "ablative":
				if ( in_array( $wordEnding, $Consonants ) ) {
					if ( in_array( $wordLastVowel, $frontVowels ) ) {
						$word .= "тен";
					} elseif ( in_array( $wordLastVowel, $backVowels ) ) {
						$word .= "тан";
					}
				} elseif (
					in_array( $wordEnding, $allVowels ) ||
					in_array( $wordEnding, $Sonants ) ||
					in_array( $wordEnding, $Sibilants )
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
				# täweldık + şığıs
			case "possessive ablative":
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
				# kömektes
			case "comitative":
				if ( in_array( $wordEnding, $Consonants ) ) {
					$word .= "пен";
				} elseif (
					in_array( $wordEnding, $allVowels ) ||
					in_array( $wordEnding, $Nasals ) ||
					in_array( $wordEnding, $Sonants )
				) {
					$word .= "мен";
				} elseif ( in_array( $wordEnding, $Sibilants ) ) {
					$word .= "бен";
				}
				break;
			case "dc61":
				# täweldık + kömektes
			case "possessive comitative":
				if ( in_array( $wordEnding, $Consonants ) ) {
					$word .= "пенен";
				} elseif (
					in_array( $wordEnding, $allVowels ) ||
					in_array( $wordEnding, $Nasals ) ||
					in_array( $wordEnding, $Sonants )
				) {
					$word .= "менен";
				} elseif ( in_array( $wordEnding, $Sibilants ) ) {
					$word .= "бенен";
				}
				break;

			# dc0 #nominative #ataw
			default:
				break;
		}

		return $word;
	}

	/**
	 * @param string $word
	 * @param string[] $allVowels
	 *
	 * @return array{0: string, 1: string|null}
	 */
	private function lastLetter( string $word, array $allVowels ): array {
		// Convert the word to lowercase safely for UTF-8 handling
		$lowered = $this->lc( $word );
		$ar = mb_str_split( $lowered, 1 );

		// Get the last letter using array_key_last to ensure a string
		$lastKey = array_key_last( $ar );
		$lastLetter = $ar[$lastKey] ?? '';

		// Find the last vowel in the word
		for ( $i = count( $ar ) - 1; $i >= 0; $i-- ) {
			$letter = $ar[$i];
			if ( in_array( $letter, $allVowels, true ) ) {
				return [
					$lastLetter,
					$letter,
				];
			}
		}

		return [
			$lastLetter,
			null,
		];
	}

	/**
	 * @param string $str
	 * @param bool $first Whether to lowercase only the first character
	 *
	 * @return string The string with lowercase conversion applied
	 */
	public function lc( string $str, bool $first = false ): string {
		if ( $first ) {
			return $this->lcfirst( $str );
		} else {
			return $this->isMultibyte( $str ) ? mb_strtolower( $str ) : strtolower( $str );
		}
	}

	/**
	 * @param string $str
	 *
	 * @return string The string with lowercase conversion applied to the first character
	 */
	public function lcfirst( string $str ): string {
		$octetCode = ord( $str );
		// See https://en.wikipedia.org/wiki/ASCII#Printable_characters
		if ( $octetCode < 96 ) {
			// Assume this is an uppercase/uncased ASCII character
			return lcfirst( $str );
		} elseif ( $octetCode < 128 ) {
			// Assume this is a lowercase/uncased ASCII character
			return $str;
		}

		return $this->isMultibyte( $str )
			// Assume this is a multibyte character and mb_internal_encoding() is appropriate
			? mb_strtolower( mb_substr( $str, 0, 1 ) ) . mb_substr( $str, 1 )
			// Assume this is a non-multibyte character and LC_CASE is appropriate
			: lcfirst( $str );
	}

	/**
	 * @param string $str
	 *
	 * @return bool
	 */
	private function isMultibyte( string $str ): bool {
		return strlen( $str ) !== mb_strlen( $str );
	}
}
