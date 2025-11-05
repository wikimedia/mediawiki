<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;

/**
 * Karakalpak (Qaraqalpaqsha)
 *
 * @ingroup Languages
 */
class LanguageKaa extends Language {

	/**
	 * Cases: genitive, dative, accusative, locative, ablative, comitative + possessive forms
	 *
	 * @inheritDoc
	 */
	public function convertGrammar( $word, $case ) {
		$grammarForms =
			MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::GrammarForms );
		if ( isset( $grammarForms['kaa'][$case][$word] ) ) {
			return $grammarForms['kaa'][$case][$word];
		}
		/* Full code of function convertGrammar() is in development. Updates coming soon. */
		return $word;
	}

	/**
	 * Fixes an issue with ucfirst for transforming 'ı' to 'Í'
	 *
	 * @inheritDoc
	 */
	public function ucfirst( $str ) {
		if ( str_starts_with( $str, 'ı' ) ) {
			return 'Í' . mb_substr( $str, 1 );
		}
		return parent::ucfirst( $str );
	}

	/**
	 * Fixes an issue with lcfirst for transforming 'Í' to 'ı'
	 *
	 * @inheritDoc
	 */
	public function lcfirst( $str ) {
		if ( str_starts_with( $str, 'Í' ) ) {
			return 'ı' . mb_substr( $str, 1 );
		}
		return parent::lcfirst( $str );
	}

}
