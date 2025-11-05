<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;

/**
 * Latin (lingua Latina)
 *
 * @ingroup Languages
 */
class LanguageLa extends Language {
	/**
	 * Convert from the nominative form of a noun to some other case
	 *
	 * Just used in a couple places for sitenames; special-case as necessary.
	 * The rules are far from complete.
	 *
	 * Cases: genitive, accusative, ablative
	 *
	 * @param string $word
	 * @param string $case
	 *
	 * @return string
	 */
	public function convertGrammar( $word, $case ) {
		$grammarForms =
			MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::GrammarForms );
		if ( isset( $grammarForms['la'][$case][$word] ) ) {
			return $grammarForms['la'][$case][$word];
		}

		switch ( $case ) {
			case 'genitive':
				// only a few declensions, and even for those mostly the singular only
				$in = [
					'/u[ms]$/', # 2nd declension singular
					'/ommunia$/', # 3rd declension neuter plural (partly)
					'/a$/', # 1st declension singular
					'/libri$/', '/nuntii$/', '/datae$/', # 2nd declension plural (partly)
					'/tio$/', '/ns$/', '/as$/', # 3rd declension singular (partly)
					'/es$/'                              # 5th declension singular
				];
				$out = [
					'i',
					'ommunium',
					'ae',
					'librorum', 'nuntiorum', 'datorum',
					'tionis', 'ntis', 'atis',
					'ei'
				];
				return preg_replace( $in, $out, $word );

			case 'accusative':
				// only a few declensions, and even for those mostly the singular only
				$in = [
					'/u[ms]$/', # 2nd declension singular
					'/a$/', # 1st declension singular
					'/ommuniam$/', # 3rd declension neuter plural (partly)
					'/libri$/', '/nuntii$/', '/datam$/', # 2nd declension plural (partly)
					'/tio$/', '/ns$/', '/as$/', # 3rd declension singular (partly)
					'/es$/'                              # 5th declension singular
				];
				$out = [
					'um',
					'am',
					'ommunia',
					'libros', 'nuntios', 'data',
					'tionem', 'ntem', 'atem',
					'em'
				];
				return preg_replace( $in, $out, $word );

			case 'ablative':
				// only a few declensions, and even for those mostly the singular only
				$in = [
					'/u[ms]$/', # 2nd declension singular
					'/ommunia$/', # 3rd declension neuter plural (partly)
					'/a$/', # 1st declension singular
					'/libri$/', '/nuntii$/', '/data$/', # 2nd declension plural (partly)
					'/tio$/', '/ns$/', '/as$/', # 3rd declension singular (partly)
					'/es$/'                              # 5th declension singular
				];
				$out = [
					'o',
					'ommunibus',
					'a',
					'libris', 'nuntiis', 'datis',
					'tione', 'nte', 'ate',
					'e'
				];
				return preg_replace( $in, $out, $word );

			default:
				return $word;
		}
	}
}
