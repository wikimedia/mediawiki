<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;

/**
 * Serbo-Croatian (srpskohrvatski / српскохрватски)
 *
 * @ingroup Languages
 */
class LanguageSh extends Language {
	/**
	 * Cases: genitiv, dativ, akuzativ, vokativ, instrumental, lokativ
	 *
	 * @inheritDoc
	 */
	public function convertGrammar( $word, $case ) {
		$grammarForms =
			MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::GrammarForms );

		if ( !isset( $grammarForms['sh'] ) ) {
			return $word;
		}

		if ( isset( $grammarForms['sh'][$case][$word] ) ) {
			return $grammarForms['sh'][$case][$word];
		}

		# if the word is not supported (i.e. there's no entry for any case),
		# use a descriptive declension for it
		$isWordSupported = false;
		foreach ( $grammarForms['sh'] as $caseForms ) {
			if ( isset( $caseForms[$word] ) ) {
				$isWordSupported = true;
				break;
			}
		}

		# descriptive declension for unknown projects
		if ( !$isWordSupported && isset( $grammarForms['sh'][$case]['projekt'] ) ) {
			return $grammarForms['sh'][$case]['projekt'] . ' ' . $word;
		}

		# this will return the original value for 'nominativ' (nominative)
		return $word;
	}
}
