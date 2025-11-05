<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;

/**
 * Irish (Gaeilge)
 *
 * @ingroup Languages
 */
class LanguageGa extends Language {
	/** @inheritDoc */
	public function convertGrammar( $word, $case ) {
		$grammarForms =
			MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::GrammarForms );
		if ( isset( $grammarForms['ga'][$case][$word] ) ) {
			return $grammarForms['ga'][$case][$word];
		}

		switch ( $case ) {
			case 'ainmlae':
				switch ( $word ) {
					case 'an Domhnach':
						$word = 'Dé Domhnaigh';
						break;
					case 'an Luan':
						$word = 'Dé Luain';
						break;
					case 'an Mháirt':
						$word = 'Dé Mháirt';
						break;
					case 'an Chéadaoin':
						$word = 'Dé Chéadaoin';
						break;
					case 'an Déardaoin':
						$word = 'Déardaoin';
						break;
					case 'an Aoine':
						$word = 'Dé hAoine';
						break;
					case 'an Satharn':
						$word = 'Dé Sathairn';
						break;
				}
		}
		return $word;
	}

}
