<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;

/**
 * Bosnian (bosanski)
 *
 * @ingroup Languages
 */
class LanguageBs extends Language {
	/**
	 * Cases: genitiv, dativ, akuzativ, vokativ, instrumental, lokativ
	 *
	 * @inheritDoc
	 */
	public function convertGrammar( $word, $case ) {
		$grammarForms =
			MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::GrammarForms );
		if ( isset( $grammarForms['bs'][$case][$word] ) ) {
			return $grammarForms['bs'][$case][$word];
		}
		switch ( $case ) {
			case 'instrumental': # instrumental
				$word = 's ' . $word;
				break;
			case 'lokativ': # locative
				$word = 'o ' . $word;
				break;
		}

		# this will return the original value for 'nominativ' (nominative)
		# and all undefined case values.
		return $word;
	}
}
