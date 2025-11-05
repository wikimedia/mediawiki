<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;

/**
 * Slovenian (Slovenščina)
 *
 * @ingroup Languages
 */
class LanguageSl extends Language {

	/**
	 * Cases: rodilnik, dajalnik, tožilnik, mestnik, orodnik
	 *
	 * @inheritDoc
	 */
	public function convertGrammar( $word, $case ) {
		$grammarForms =
			MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::GrammarForms );
		if ( isset( $grammarForms['sl'][$case][$word] ) ) {
			return $grammarForms['sl'][$case][$word];
		}

		switch ( $case ) {
			case 'mestnik': # locative
				$word = 'o ' . $word;
				break;

			case 'orodnik': # instrumental
				$word = 'z ' . $word;
				break;
		}

		# this will return the original value for 'imenovalnik' (nominativ) and
		# all undefined case values.
		return $word;
	}
}
