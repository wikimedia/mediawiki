<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @author Niklas Laxström
 */

use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;

/**
 * Lower Sorbian (Dolnoserbski) specific code.
 *
 * @ingroup Languages
 */
class LanguageDsb extends Language {
	/** @inheritDoc */
	public function convertGrammar( $word, $case ) {
		$grammarForms =
			MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::GrammarForms );
		if ( isset( $grammarForms['dsb'][$case][$word] ) ) {
			return $grammarForms['dsb'][$case][$word];
		}

		switch ( $case ) {
			case 'instrumental': # instrumental
				$word = 'z ' . $word;
				// fall-through
			case 'lokatiw': # lokatiw
				$word = 'wo ' . $word;
				break;
		}

		# this will return the original value for 'nominatiw' (nominativ) and
		# all undefined case values.
		return $word;
	}
}
