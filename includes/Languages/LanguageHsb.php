<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;

/**
 * Upper Sorbian (Hornjoserbsce)
 *
 * @ingroup Languages
 */
class LanguageHsb extends Language {

	/** @inheritDoc */
	public function convertGrammar( $word, $case ) {
		$grammarForms =
			MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::GrammarForms );
		if ( isset( $grammarForms['hsb'][$case][$word] ) ) {
			return $grammarForms['hsb'][$case][$word];
		}

		switch ( $case ) {
			case 'instrumental': # instrumental
				$word = 'z ' . $word;
				break;
			case 'lokatiw': # lokatiw
				$word = 'wo ' . $word;
				break;
		}

		# this will return the original value for 'nominatiw' (nominativ) and
		# all undefined case values.
		return $word;
	}
}
