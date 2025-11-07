<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @author Purodha Blissenbach
 */

use MediaWiki\Language\Language;

/**
 * Ripuarian (Ripoarėsh)
 *
 * @ingroup Languages
 */
class LanguageKsh extends Language {
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
	 * @inheritDoc
	 *
	 * $case is a sequence of words, each of which is case insensitive.
	 * There must be at least one space character between words.
	 * Only the 1st character of each word is considered.
	 * Word order is irrelevant.
	 *
	 * Possible values specifying the grammatical case are:
	 * 	1, Nominative
	 * 	2, Genitive
	 * 	3, Dative
	 * 	4, Accusative, -omitted-
	 *
	 * Possible values specifying the article type are:
	 * 	Betoont               focussed or stressed article
	 * 	-omitted-             unstressed or unfocused article
	 *
	 * Possible values for the type of genitive are:
	 * 	Sing, Iehr            prepositioned genitive = possessive dative
	 * 	Vun, Fon, -omitted-   postpositioned genitive = preposition "vun" with dative
	 *
	 * Values of case overrides & prepositions, in the order of precedence:
	 * 	Sing, Iehr            possessive dative = prepositioned genitive
	 * 	Vun, Fon              preposition "vun" with dative = post positioned genitive
	 * 	En, em                preposition "en" with dative
	 *
	 * Values for object gender specifiers of the possessive dative, or
	 * prepositioned genitive, evaluated with "Sing, Iehr" of above only:
	 * 	Male                  a singular male object follows
	 * 	-omitted-             a non-male or plural object follows
	 *
	 * We currently handle definite articles of the singular only.
	 * There is a full set of test cases at:
	 * http://translatewiki.net/wiki/Portal:Ksh#GRAMMAR_Pr%C3%B6%C3%B6fe
	 * Contents of the leftmost table column can be copied and pasted as
	 * "case" values.
	 */
	public function convertGrammar( $word, $case ) {
		$lord = strtolower( $word );
		$gender = 'm'; // Nuutnaarel // default
		if ( str_ends_with( $lord, 'wiki' ) ) {
			$gender = 'n'; // Dat xyz-wiki
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
			$word = ( str_contains( $case, ' b' )
					? ( $isGenderFemale ? 'dä' : 'däm' )
					: ( $isGenderFemale ? 'de' : 'dem' )
				) . ' ' . $word . ' ' .
				( $isGenderFemale ? 'ier' : 'sing' ) .
				( str_contains( $case, ' m' ) ? 'e' : '' );
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
				$lord = match ( $gender ) {
					'm' => 'dä',
					'f' => 'di',
					default => 'dat'
				};
			} else {
				$lord = match ( $gender ) {
					'm' => 'der',
					'f' => 'de',
					default => 'et'
				};
			}
			$word = $lord . ' ' . $word;
		}
		return $word;
	}

	/**
	 * Handle cases of (1, other, 0) or (1, other)
	 *
	 * @param int $count
	 * @param array $forms
	 *
	 * @return string
	 */
	public function convertPlural( $count, $forms ) {
		$forms = $this->handleExplicitPluralForms( $count, $forms );
		if ( is_string( $forms ) ) {
			return $forms;
		}
		if ( !$forms ) {
			return '';
		}
		$forms = $this->preConvertPlural( $forms, 3 );

		return match ( $count ) {
			1 => $forms[0],
			0 => $forms[2],
			default => $forms[1]
		};
	}
}
