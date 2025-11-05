<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;

/**
 * Hungarian localisation for MediaWiki
 *
 * @ingroup Languages
 */
class LanguageHu extends Language {

	/** @inheritDoc */
	public function convertGrammar( $word, $case ) {
		$grammarForms = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::GrammarForms );
		if ( isset( $grammarForms[$this->getCode()][$case][$word] ) ) {
			return $grammarForms[$this->getCode()][$case][$word];
		}

		switch ( $case ) {
			case 'rol':
				return $word . 'ról';
			case 'ba':
				return $word . 'ba';
			case 'k':
				return $word . 'k';
		}
		return '';
	}
}
