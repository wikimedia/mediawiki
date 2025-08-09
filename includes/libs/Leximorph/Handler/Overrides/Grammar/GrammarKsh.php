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
 * GrammarKsh
 *
 * Implements grammar transformations for Colognian (ksh).
 *
 * These rules don't cover the whole grammar of the language.
 * This logic was originally taken from MediaWiki Core.
 * Thanks to all contributors.
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 */
class GrammarKsh implements IGrammarTransformer {

	/**
	 * Do not add male wiki families, since that's the default.
	 * No need to add neuter to wikis having names ending in "-wiki".
	 */
	private const FAMILYGENDER = [
		'wikipedia' => 'f',
		'wikiversity' => 'f',
		'wiktionary' => 'n',
		'wikibooks' => 'n',
		'wikiquote' => 'n',
		'wikisource' => 'n',
		'wikitravel' => 'n',
		'wikia' => 'f',
		'translatewiki.net' => 'n',
	];

	/**
	 * Applies Colognian-specific grammatical transformations.
	 *
	 * $case is a sequence of words, each of which is case insensitive.
	 * There must be at least one space character between words.
	 * Only the 1st character of each word is considered.
	 * Word order is irrelevant.
	 *
	 * Possible values specifying the grammatical case are:
	 *    1, Nominative
	 *    2, Genitive
	 *    3, Dative
	 *    4, Accusative, -omitted-
	 *
	 * Possible values specifying the article type are:
	 *    Betoont               focussed or stressed article
	 *    -omitted-             unstressed or unfocused article
	 *
	 * Possible values for the type of genitive are:
	 *    Sing, Iehr            prepositioned genitive = possessive dative
	 *    Vun, Fon, -omitted-   postpositioned genitive = preposition "vun" with dative
	 *
	 * Values of case overrides & prepositions, in the order of precedence:
	 *    Sing, Iehr            possessive dative = prepositioned genitive
	 *    Vun, Fon              preposition "vun" with dative = post positioned genitive
	 *    En, em                preposition "en" with dative
	 *
	 * Values for object gender specifiers of the possessive dative, or
	 * prepositioned genitive, evaluated with "Sing, Iehr" of above only:
	 *    Male                  a singular male object follows
	 *    -omitted-             a non-male or plural object follows
	 *
	 * We currently handle definite articles of the singular only.
	 * There is a full set of test cases at:
	 * http://translatewiki.net/wiki/Portal:Ksh#GRAMMAR_Pr%C3%B6%C3%B6fe
	 * Contents of the leftmost table column can be copied and pasted as
	 * "case" values.
	 *
	 * @param string $word The word to process.
	 * @param string $case The grammatical case.
	 *
	 * @since 1.45
	 * @return string The processed word.
	 */
	public function process( string $word, string $case ): string {
		$lord = strtolower( $word );
		// Nuutnaarel // default
		$gender = 'm';
		if ( str_ends_with( $lord, 'wiki' ) ) {
			// Dat xyz-wiki
			$gender = 'n';
		}
		if ( isset( self::FAMILYGENDER[$lord] ) ) {
			$gender = self::FAMILYGENDER[$lord];
		}

		$isGenderFemale = $gender === 'f';

		$case = ' ' . strtolower( $case );
		if ( preg_match( '/ [is]/', $case ) ) {
			# däm WikiMaatplaz singe, dä Wikipeedija iere, däm Wikiwööterbooch singe
			# dem/em WikiMaatplaz singe, de Wikipeedija iere, dem/em Wikiwööterbooch singe
			# däm WikiMaatplaz sing, dä Wikipeedija ier, däm Wikiwööterbooch sing
			# dem/em WikiMaatplaz sing, de Wikipeedija ier, dem/em Wikiwööterbooch sing
			if ( str_contains( $case, ' b' ) ) {
				if ( $isGenderFemale ) {
					$prefix = 'dä';
				} else {
					$prefix = 'däm';
				}
			} elseif ( $isGenderFemale ) {
				$prefix = 'de';
			} else {
				$prefix = 'dem';
			}

			$possessive = $isGenderFemale ? 'ier' : 'sing';
			$suffix = str_contains( $case, ' m' ) ? 'e' : '';

			$word = $prefix . ' ' . $word . ' ' . $possessive . $suffix . ( str_contains( $case, ' m' ) ? 'e' : '' );
		} elseif ( str_contains( $case, ' e' ) ) {
			# en dämm WikiMaatPlaz, en dä Wikipeedija, en dämm Wikiwööterbooch
			# em WikiMaatplaz, en de Wikipeedija, em Wikiwööterbooch
			if ( str_contains( $case, ' b' ) ) {
				$word = 'en ' . ( $isGenderFemale ? 'dä' : 'däm' ) . ' ' . $word;
			} else {
				$word = ( $isGenderFemale ? 'en de' : 'em' ) . ' ' . $word;
			}
		} elseif ( preg_match( '/ [fv]/', $case ) || preg_match( '/ [2jg]/', $case ) ) {
			# vun däm WikiMaatplaz, vun dä Wikipeedija, vun däm Wikiwööterbooch
			# vum WikiMaatplaz, vun de Wikipeedija, vum Wikiwööterbooch
			if ( str_contains( $case, ' b' ) ) {
				$word = 'vun ' . ( $isGenderFemale ? 'dä' : 'däm' ) . ' ' . $word;
			} else {
				$word = ( $isGenderFemale ? 'vun de' : 'vum' ) . ' ' . $word;
			}
		} elseif ( preg_match( '/ [3d]/', $case ) ) {
			# dämm WikiMaatPlaz, dä Wikipeedija, dämm Wikiwööterbooch
			# dem/em WikiMaatplaz, de Wikipeedija, dem/em Wikiwööterbooch
			if ( str_contains( $case, ' b' ) ) {
				$word = ( $isGenderFemale ? 'dää' : 'dämm' ) . ' ' . $word;
			} else {
				$word = ( $isGenderFemale ? 'de' : 'dem' ) . ' ' . $word;
			}
		} else {
			# dä WikiMaatPlaz, di Wikipeedija, dat Wikiwööterbooch
			# der WikiMaatplaz, de Wikipeedija, et Wikiwööterbooch
			if ( str_contains( $case, ' b' ) ) {
				switch ( $gender ) {
					case 'm':
						$lord = 'dä';
						break;
					case 'f':
						$lord = 'di';
						break;
					default:
						$lord = 'dat';
				}
			} else {
				switch ( $gender ) {
					case 'm':
						$lord = 'der';
						break;
					case 'f':
						$lord = 'de';
						break;
					default:
						$lord = 'et';
				}
			}
			$word = $lord . ' ' . $word;
		}

		return $word;
	}
}
