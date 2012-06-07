<?php
/**
 * Ripuarian (Ripoarėsh) specific code.
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
 * @author Purodha Blissenbach
 * @ingroup Language
 */

/**
 * Ripuarian (Ripoarėsh)
 *
 * @ingroup Language
 */
class LanguageKsh extends Language {
	static $familygender = array(
		// Do not add male wiki families, since that's the default.
		// No need add neuter wikis having names ending in -wiki.
			'wikipedia' => 'f',
			'wikiversity' => 'f',
			'wiktionary' => 'n',
			'wikibooks' => 'n',
			'wikiquote' => 'n',
			'wikisource' => 'n',
			'wikitravel' => 'n',
			'wikia' => 'f',
			'translatewiki.net' => 'n',
		);

	/**
	 * Convert from the nominative form of a noun to other cases.
	 * Invoked with {{GRAMMAR:case|word}} inside messages.
	 *
	 * case is a sequence of words, each of which is case insensitive.
	 * Between words, there must be at least one space character.
	 * Only the 1st character of each word is considered.
	 * Word order is irrelevant.
	 *
	 * Possible values specifying the grammatical case are:
	 *	1, Nominative
	 *	2, Genitive
	 *	3, Dative
	 *	4, Accusative, -omitted-
	 *
	 * Possible values specifying the article type are:
	 *	Betoont               focussed or stressed article
	 *	-omitted-             unstressed or unfocussed article
	 *
	 * Possible values for the type of genitive are:
	 *	Sing, Iehr            prepositioned genitive = possessive dative
	 *	Vun, Fon, -omitted-   postpositioned genitive
	 *	                               = preposition "vun" with dative
	 *
	 * Values of case overrides & prepositions, in the order of preceedence:
	 *	Sing, Iehr            possessive dative = prepositioned genitive
	 *	Vun, Fon              preposition "vun" with dative
	 *	                                     = postpositioned genitive
	 *	En, em                preposition "en" with dative
	 *
	 * Values for object gender specifiers of the possessive dative, or
	 * prepositioned genitive, evaluated with "Sing, Iehr" of above only:
	 *	Male                  a singular male object follows
	 *	-omitted-             a non-male or plural object follows
	 *
	 * We currently handle definite articles of the singular only.
	 * There is a full set of test cases at:
	 * http://translatewiki.net/wiki/Portal:Ksh#GRAMMAR_Pr%C3%B6%C3%B6fe
	 * Contents of the leftmost table column can be copied and pasted as
	 * "case" values.
	 *
	 * @param $word String
	 * @param $case String
	 *
	 * @return string
	 */
	function convertGrammar( $word, $case ) {
		$lord = strtolower( $word );
		$gender = 'm'; // Nuutnaarel // default
		if ( preg_match ( '/wiki$/', $lord ) ) {
			$gender = 'n';	// Dat xyz-wiki
		}
		if ( isset( self::$familygender[$lord] ) ) {
			$gender = self::$familygender[$lord];
		}
		$case = ' ' . strtolower( $case );
		if ( preg_match( '/ [is]/', $case ) ) {
			# däm WikiMaatplaz singe, dä Wikipeedija iere, däm Wikiwööterbooch singe
			# dem/em WikiMaatplaz singe, de Wikipeedija iere, dem/em Wikiwööterbooch singe
			# däm WikiMaatplaz sing, dä Wikipeedija ier, däm Wikiwööterbooch sing
			# dem/em WikiMaatplaz sing, de Wikipeedija ier, dem/em Wikiwööterbooch sing
			$word = ( preg_match( '/ b/', $case )
						? ( $gender=='f' ? 'dä' : 'däm' )
						: ( $gender=='f' ? 'de' : 'dem' )
					) . ' ' . $word . ' ' .
					( $gender=='f' ? 'ier' : 'sing' ) .
					( preg_match( '/ m/', $case ) ? 'e' : ''
				);
		} elseif ( preg_match( '/ e/', $case ) ) {
			# en dämm WikiMaatPlaz, en dä Wikipeedija, en dämm Wikiwööterbooch
			# em WikiMaatplaz, en de Wikipeedija, em Wikiwööterbooch
			if ( preg_match( '/ b/', $case ) ) {
				$word = 'en '.( $gender == 'f' ? 'dä' : 'däm' ) . ' ' . $word;
			} else {
				$word = ( $gender == 'f' ? 'en de' : 'em' ) . ' ' . $word;
			}
		} elseif ( preg_match( '/ [fv]/', $case ) || preg_match( '/ [2jg]/', $case ) ) {
			# vun däm WikiMaatplaz, vun dä Wikipeedija, vun däm Wikiwööterbooch
			# vum WikiMaatplaz, vun de Wikipeedija, vum Wikiwööterbooch
			if ( preg_match( '/ b/', $case ) ) {
				$word = 'vun ' . ( $gender == 'f' ? 'dä' : 'däm' ) . ' ' . $word;
			} else {
				$word = ( $gender== 'f' ? 'vun de' : 'vum' ) . ' ' . $word;
			}
		} elseif ( preg_match( '/ [3d]/', $case ) ) {
			# dämm WikiMaatPlaz, dä Wikipeedija, dämm Wikiwööterbooch
			# dem/em WikiMaatplaz, de Wikipeedija, dem/em Wikiwööterbooch
			if ( preg_match( '/ b/', $case ) ) {
				$word = ( $gender == 'f' ? 'dää' : 'dämm' ) .' ' . $word;
			} else {
				$word = ( $gender == 'f' ? 'de' : 'dem' ) . ' ' . $word;
			}
		} else {
			# dä WikiMaatPlaz, di Wikipeedija, dat Wikiwööterbooch
			# der WikiMaatplaz, de Wikipeedija, et Wikiwööterbooch
			if ( preg_match( '/ b/', $case ) ) {
				switch ( $gender ) {
					case 'm':
						$lord = 'dä';
						break ;
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
			$word = $lord.' '.$word;
		}
		return $word;
	}

	/**
	 * Avoid grouping whole numbers between 0 to 9999
	 *
	 * @param $_ string
	 *
	 * @return string
	 */
	public function commafy( $_ ) {
		if ( !preg_match( '/^\d{1,4}$/', $_ ) ) {
			return strrev( (string)preg_replace( '/(\d{3})(?=\d)(?!\d*\.)/', '$1,', strrev( $_ ) ) );
		} else {
			return $_;
		}
	}

	/**
	 * Handle cases of (1, other, 0) or (1, other)
	 *
	 * @param $count int
	 * @param $forms array
	 *
	 * @return string
	 */
	function convertPlural( $count, $forms ) {
		if ( !count( $forms ) ) { return ''; }
		$forms = $this->preConvertPlural( $forms, 3 );

		if ( $count == 1 ) {
			return $forms[0];
		} elseif ( $count == 0 ) {
			return $forms[2];
		} else {
			return $forms[1];
		}
	}
}
